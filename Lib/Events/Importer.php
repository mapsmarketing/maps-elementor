<?php

namespace MAPSElementor\Lib\Events;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

use ICal\ICal;

class Importer {
  public function __construct() {
    // Check if 'All-in-One Event Calendar' plugin is active
    if (!is_plugin_active('all-in-one-event-calendar/all-in-one-event-calendar.php')) {
      return;
    }

    // add_action('admin_menu', [$this, 'remove_menus'], 999);
    add_action('init', [$this, 'acf_options_page']);
    add_action('init', [$this, 'acf']);
    add_action('init', [$this, 'check_for_cron_trigger']);
    // add_action('wp_loaded', [$this, 'refresh_events']);
    add_action('admin_footer', [$this, 'update_submitdiv']);
    add_action('admin_post_refresh_calendar', [$this, 'handle_refresh_calendar']);
    add_action('admin_notices', [$this, 'display_refresh_notifications']);

    add_filter('cron_schedules', [$this, 'cron_schedules']);

    register_activation_hook(__FILE__, [$this, 'schedule_cron']);
    add_action('maps_refresh_events_cron', [$this, 'refresh_events']);
    register_deactivation_hook(__FILE__, [$this, 'clear_scheduled_cron']);
  }

  /**
   * The function `remove_menus` removes the original "Import Feeds" page.
   */
  function remove_menus() {
    remove_submenu_page('edit.php?post_type=ai1ec_event', 'all-in-one-event-calendar-feeds');
  }

  /**
   * The function `acf_options_page` adds an options page titled "Import Feeds" under the `ai1ec_event` post-type.
   */
  public function acf_options_page() {
    if (function_exists('acf_add_options_page')) {
      acf_add_options_page(array(
        'page_title'    => 'Import Feeds',
        'menu_title'    => 'Import Feeds',
        'menu_slug'     => 'import-feeds',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'edit.php?post_type=ai1ec_event',
        'redirect'      => false
      ));
    }
  }

  /**
   * The function `refresh_events` retrieves event feeds, processes them, and returns the total count
   * of events processed.
   * 
   * @return int returns the total count of events processed from the feeds.
   */
  public function refresh_events() {
    $feeds = get_field('feeds', 'option');
    $event_counts = 0;

    foreach ($feeds as $feed) {
      $event_counts += $this->process_ics_feed($feed['feed_url']);
    }

    return $event_counts;
  }

  /**
   * The function `process_ics_feed` parses an ICS feed from a given URL, creates or updates events
   * based on the feed data, and returns the count of events processed.
   * 
   * @param url represents the URL of an iCalendar feed (ICS feed) that contains event information.
   * 
   * @return int `process_ics_feed` function is returning the count of events parsed from the ICS feed.
   */
  private function process_ics_feed($url) {
    global $wpdb;

    try {
      $site_name = get_bloginfo('name');
      $ical = new ICal($url);
      $current_year = (int)date('Y');
      $next_year = $current_year + 1;
      $valid_event_uids = [];  // Array to store valid event UIDs

      if ($ical->events()) {
        $events = $ical->events(); // Get all events
        $unique_events = $this->filter_duplicate_events($events);

        foreach ($unique_events as $event) {
          $event_timestamp = strtotime($event->dtstart);

          if ($event_timestamp !== false) {
            $event_year = (int)date('Y', $event_timestamp);

            // Check if the event is recurring
            if ($event->rrule) {
              // For recurring events, we check all previous years, not just the current and next year
              $this->create_or_update_event($event, $url, $ical);
              $valid_event_uids[] = $event->uid;  // Add to valid UIDs
            } else {
              // For non-recurring events, we only check for the current year and next year
              if ($event_year >= $current_year && $event_year <= $next_year) {
                $this->create_or_update_event($event, $url, $ical);
                $valid_event_uids[] = $event->uid;  // Add to valid UIDs
              }
            }
          }
        }

        // // After processing all events, remove obsolete events
        $this->remove_obsolete_events($valid_event_uids);
      } else {
        wp_mail('serverlogs@mapsmarketing.com.au', $site_name . ' - Calendar Sync Error', 'Calendar has an error [' . $site_name . ']', ['Content-Type: text/plain; charset=UTF-8']);
      }

      return count($ical->events());
    } catch (\Exception $e) {
      error_log('Parsing ICS failed: ' . $e->getMessage());
      wp_mail('serverlogs@mapsmarketing.com.au', $site_name . ' - Calendar Sync Error', 'Calendar has an error [' . $site_name . ']', ['Content-Type: text/plain; charset=UTF-8']);
    }
  }

  /**
   * The function filters out duplicate events based on their unique identifiers.
   * 
   * @param events The `filter_duplicate_events` function takes an array of events as input. Each event
   * in the array is an object with a `uid` property. The function filters out duplicate events based
   * on the `uid` property and returns an array containing only unique events.
   * 
   * @return The function `filter_duplicate_events` returns an array of unique events after filtering
   * out any duplicate events based on the `uid` property of each event.
   */
  private function filter_duplicate_events($events) {
    $uids = [];
    $unique_events = [];

    foreach ($events as $event) {
      if (!in_array($event->uid, $uids)) {
        $uids[] = $event->uid;
        $unique_events[] = $event; // Only add unique events
      }
    }

    return $unique_events;
  }

  /**
   * The function `create_or_update_event` in handles the creation or update of event data.
   * 
   * @param event The `$event` object contains various properties like `summary`, `description`,
   * `dtstart`, `dtend`, `timezone`, `uid`, `rrule`, `exrule`, etc.
   * @param url The `url` parameter contains the calendar feed URL.
   * 
   * @return void
   */
  private function create_or_update_event($event, $url, $ical) {
    global $wpdb;

    $timezone = get_option('timezone_string');

    if (!$event->summary) {
      return;
    }

    // Parse start and end times based on format
    $start_time = $this->parse_event_time($event->dtstart, $timezone);
    $end_time = $this->parse_event_time($event->dtend, $timezone, $start_time);

    $post_id = false;
    $existing_id = $wpdb->get_var($wpdb->prepare(
      "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ical_uid' AND meta_value = %s",
      $event->uid
    ));

    // Check if event already exists, otherwise create a new post
    if ($existing_id) {
      $post_id = wp_update_post([
        'ID'           => $existing_id,
        'post_title'   => $event->summary,
        'post_content' => $event->description,
        'post_status'  => 'publish'
      ]);
    } else {
      $post_id = wp_insert_post([
        'post_type'    => 'ai1ec_event',
        'post_title'   => $event->summary,
        'post_content' => $event->description,
        'post_status'  => 'publish'
      ]);
      update_post_meta($post_id, '_ical_uid', $event->uid);
      update_post_meta($post_id, '_ai1ec_cost_type', "free");
    }

    if ($post_id !== false) {
      $data = [
        'post_id'            => $post_id,
        'start'              => $start_time->getTimestamp(),
        'end'                => $end_time ? $end_time->getTimestamp() : null,
        'timezone_name'      => $timezone,
        'allday'             => $this->is_all_day_event($event),
        'instant_event'      => 0,
        'recurrence_rules'   => $event->rrule ?? "",
        'exception_rules'    => $event->exrule ?? "",
        'recurrence_dates'   => $event->rdate ?? "",
        'exception_dates'    => $event->exdate ?? "",
        'venue'              => $event->location ?? "",
        'ical_feed_url'      => $event->ical_feed_url,
        'ical_uid'           => $event->uid,
      ];

      // Fix: Unset the end date for the parent event if it is recurring
      if (isset($event->additionalProperties['rrule'])) {
        $data['instant_event'] = 1;
        unset($data['end']);
      }

      // Insert or update the main event details
      if ($existing_id) {
        $wpdb->update("{$wpdb->prefix}ai1ec_events", $data, ['post_id' => $post_id]);
      } else {
        $wpdb->insert("{$wpdb->prefix}ai1ec_events", $data);
      }

      if (!isset($event->additionalProperties['rrule'])) {
        // Delete any existing recurring instances for this event
        $wpdb->delete("{$wpdb->prefix}ai1ec_event_instances", ['post_id' => $post_id]);

        $data_event_instances = [
          'post_id' => $post_id,
          'start'   => $start_time->getTimestamp(),
          'end'     => $end_time ? $end_time->getTimestamp() : null,
        ];

        $wpdb->insert("{$wpdb->prefix}ai1ec_event_instances", $data_event_instances);
      }

      // Handle Recurring Instances
      if (isset($event->additionalProperties['rrule'])) {
        $this->handle_recurring_event_instances($post_id, $event, $start_time);
      }
    }
  }

  /**
   * The function `parse_event_time` parses event datetime strings into DateTime objects,
   * handling different formats and timezones. It returns a `DateTime` object representing the parsed event
   * time in the specified timezone. If the parsing fails for any reason, it returns the default value
   * provided as the third argument.
   * 
   * @param datetime The `datetime` parameter in the `parse_event_time` function represents the date
   * and time of an event in various formats. It could be in UTC time with a 'Z' suffix, in the format
   * YYYYMMDDTHHmmss, or just YYYYMMDD for an all-day event. The
   * @param timezone The `timezone` parameter in the `parse_event_time` function is used to specify the
   * timezone to which the event time should be converted. This function takes a datetime string, along
   * with the timezone, and optionally a default value to return if the parsing fails.
   * @param default The `default` parameter in the `parse_event_time` function is used to specify a
   * default value that will be returned if the parsing of the event time fails for any reason. If an
   * error occurs during the parsing process, the function will catch the exception and log an error
   * message, then return the
   */
  private function parse_event_time($datetime, $timezone, $default = null) {
    try {
      if (substr($datetime, -1) === 'Z') {
        // Handle UTC times with 'Z' suffix
        $date = new \DateTime($datetime, new \DateTimeZone('UTC'));
        // Convert to the specified timezone
        $date->setTimezone(new \DateTimeZone($timezone));
        return $date;
      } elseif (strlen($datetime) === 15) { // Format: YYYYMMDDTHHmmss
        return new \DateTime($datetime, new \DateTimeZone($timezone));
      } elseif (strlen($datetime) === 8) { // Format: YYYYMMDD (all-day event)
        return new \DateTime($datetime, new \DateTimeZone($timezone));
      }
    } catch (\Exception $e) {
      error_log('Error parsing event time: ' . $e->getMessage());
    }

    return $default; // Return default if parsing fails
  }

  /**
   * The function is_all_day_event returns a boolean value by checking if the length of
   * the `dtstart` property of the event is equal to 8 characters or if the `dtend` property of the
   * event is empty. If either condition is true, the function will return `true`, indicating that the
   * event is an all-day event. Otherwise, it will return `false`.
   * 
   * @param event The event object created by iCal
   */
  private function is_all_day_event($event) {
    return (strlen($event->dtstart) === 8) || (!$event->dtend);
  }

  /**
   * The function `handle_recurring_event_instances` processes recurrence rules for events and inserts
   * instances into the database based on the rules and specified parameters. It generates occurrences based on the
   * recurrence rule provided, filtering them based on start and end dates, excluding specific dates,
   * deleting existing instances, and inserting new instances into the database. The function performs
   * these operations but does not return any specific value.
   * 
   * @param post_id The `post_id` is the parent event the event instances will be attached to.
   * @param event The event object created by iCal.
   * @param start_time The `start_time` is used as the reference point for creating the recurrence rule 
   * and filtering the occurrences.
   */
  private function handle_recurring_event_instances($post_id, $event, $start_time) {
    global $wpdb;

    try {
      // Use the recurrence rule and start time from the event
      $rrule = $event->additionalProperties['rrule'];
      $exdates = !empty($event->additionalProperties['exdate']) ? explode(',', $event->additionalProperties['exdate']) : [];

      // Define the date range for occurrences: from start_time until the end of the next year
      $current_year = (int) date('Y');
      $end_date = new \DateTime("$current_year-12-31"); // End of this year
      $end_date->modify('+1 year'); // Add one year for extended range

      // Create a recurrence rule object
      $rule = new \Recurr\Rule($rrule, $start_time);

      // Create a transformer to generate recurrence instances
      $transformer = new \Recurr\Transformer\ArrayTransformer();
      $occurrences = $transformer->transform($rule);

      // Convert RecurrenceCollection to an array
      $occurrences_array = $occurrences->toArray();

      // Filter occurrences based on start and end date and exclude specific dates
      $filtered_occurrences = array_filter($occurrences_array, function ($occurrence) use ($start_time, $end_date, $exdates) {
        $occurrence_start = $occurrence->getStart()->format('Ymd\THis');
        return $occurrence->getStart() >= $start_time
          && $occurrence->getStart() <= $end_date
          && !in_array($occurrence_start, $exdates);
      });

      // Delete any existing recurring instances for this event
      $wpdb->delete("{$wpdb->prefix}ai1ec_event_instances", ['post_id' => $post_id]);

      // Insert new recurring instances into the database
      foreach ($filtered_occurrences as $occurrence) {
        $instance_start = $occurrence->getStart();
        $instance_end = $occurrence->getEnd() ?: $instance_start;

        // Prepare data for insertion
        $data_event_instances = [
          'post_id' => $post_id,
          'start'   => $instance_start->getTimestamp(),
          'end'     => $instance_end->getTimestamp(),
        ];

        // Insert each instance
        $wpdb->insert("{$wpdb->prefix}ai1ec_event_instances", $data_event_instances);
      }
    } catch (\Exception $e) {
      // Log any errors that occur while processing recurrence rules
      error_log('Error processing recurring instances: ' . $e->getMessage());
    }
  }

  /**
   * This PHP function retrieves all existing events with their corresponding iCal UIDs from the
   * WordPress database.
   * 
   * @return The `get_all_existing_events()` function returns an array of objects containing the ID and
   * ical_uid (meta_value) of existing events from the WordPress database where the post type is
   * 'ai1ec_event' and the meta key is '_ical_uid'.
   */
  private function get_all_existing_events() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT p.ID, pm.meta_value as ical_uid
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'ai1ec_event'
        AND pm.meta_key = '_ical_uid'
    ");
    return $results;
  }

  /**
   * The function `remove_obsolete_events` removes events that are not in the list of valid event UIDs.
   * 
   * @param valid_event_uids The `valid_event_uids` parameter is an array containing the unique
   * identifiers of events that are considered valid and should not be removed. The
   * `remove_obsolete_events` function iterates through all existing events and deletes those that have
   * an `ical_uid` not present in the `valid_event_uids
   */
  private function remove_obsolete_events($valid_event_uids) {
    $existing_events = $this->get_all_existing_events();

    foreach ($existing_events as $event) {
      if (!in_array($event->ical_uid, $valid_event_uids)) {
        wp_delete_post($event->ID, true);
      }
    }
  }

  /**
   * The function `update_submitdiv` adds a "Refresh Calendar" button to the WordPress admin page for
   * importing feeds.
   * 
   * @return void `update_submitdiv` function is returning a script that appends a "Refresh Calendar"
   * button to the publishing action section on a specific admin screen
   * (`ai1ec_event_page_import-feeds`).
   */
  public function update_submitdiv() {
    $screen = get_current_screen();

    if ($screen->id !== 'ai1ec_event_page_import-feeds') {
      return;
    }

    $url = admin_url('admin-post.php');
    $link = add_query_arg([
      'action' => 'refresh_calendar',
      'post_type' => 'ai1ec_event',
      'page' => 'import-feeds'
    ], $url);

?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('<a href="<?php echo $link; ?>" class="button">Refresh Calendar</button>')
          .appendTo('#publishing-action');
      });
    </script>
<?php
  }

  /**
   * The function `handle_refresh_calendar` refreshes events, sets a transient message, and redirects
   * to a specific admin page.
   */
  public function handle_refresh_calendar() {
    $event_counts = $this->refresh_events();

    set_transient('refresh_calendar_success', "(" . $event_counts . ") Events have been successfully refreshed!", 60);

    wp_redirect(admin_url('edit.php?post_type=ai1ec_event&page=import-feeds'));
    exit;
  }

  /**
   * The function `display_refresh_notifications` checks for a transient message and displays it as a
   * success notice if it exists.
   */
  public function display_refresh_notifications() {
    if ($message = get_transient('refresh_calendar_success')) {
      echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
      delete_transient('refresh_calendar_success');
    }
  }

  /**
   * The function `check_for_cron_trigger` checks if a specific GET parameter is set to trigger a
   * refresh of events.
   */
  public function check_for_cron_trigger() {
    if (isset($_REQUEST['trigger_maps_cron']) && $_REQUEST['trigger_maps_cron'] == '1') {
      $this->refresh_events();
    }
  }

  /**
   * The function adds a custom cron schedule for running a task every fifteen minutes in PHP.
   * 
   * @param schedules contains an array of WordPress cron intervals.
   * 
   * @return array of cron schedules with a new schedule named 'every_fifteen_minutes' that runs
   * every 15 minutes.
   */
  public function cron_schedules($schedules) {
    $schedules['every_fifteen_minutes'] = array(
      'interval' => 15 * 60,
      'display'  => __('Every Fifteen Minutes')
    );

    return $schedules;
  }

  /**
   * The function `schedule_cron` schedules a recurring event named `maps_refresh_events_cron` to run
   * every fifteen minutes if it's not already scheduled.
   */
  public function schedule_cron() {
    if (!wp_next_scheduled('maps_refresh_events_cron')) {
      wp_schedule_event(time(), 'every_fifteen_minutes', 'maps_refresh_events_cron');
    }
  }

  /**
   * The function `clear_scheduled_cron` clears a scheduled cron event named
   * `maps_refresh_events_cron`.
   */
  public function clear_scheduled_cron() {
    $timestamp = wp_next_scheduled('maps_refresh_events_cron');
    if ($timestamp) {
      wp_unschedule_event($timestamp, 'maps_refresh_events_cron');
    }
  }

  /**
   * The function `acf()` is adding a local field group for MAPS Events Import Feeds using
   * Advanced Custom Fields (ACF) plugin used for importing MAPS Events Feeds with a repeater
   * field for feed URLs.
   * 
   * @return void
   */
  public function acf() {
    if (!function_exists('acf_add_local_field_group')) {
      return;
    }

    acf_add_local_field_group(array(
      'key' => 'group_662b2da74617b',
      'title' => 'MAPS Events Import Feeds',
      'fields' => array(
        array(
          'key' => 'field_662b2da8e2e6b',
          'label' => 'Feeds',
          'name' => 'feeds',
          'aria-label' => '',
          'type' => 'repeater',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'layout' => 'table',
          'pagination' => 0,
          'min' => 0,
          'max' => 0,
          'collapsed' => '',
          'button_label' => 'Add Row',
          'rows_per_page' => 20,
          'sub_fields' => array(
            array(
              'key' => 'field_662b2de0e2e6c',
              'label' => 'Feed URL',
              'name' => 'feed_url',
              'aria-label' => '',
              'type' => 'url',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'default_value' => '',
              'placeholder' => '',
              'parent_repeater' => 'field_662b2da8e2e6b',
            ),
          ),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'import-feeds',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
      'show_in_rest' => 0,
    ));
  }
}
