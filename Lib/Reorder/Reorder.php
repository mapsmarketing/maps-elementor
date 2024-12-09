<?php

namespace MAPSElementor\Lib\Reorder;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Reorder {
  private static $instance;

  public static function instance() {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function __construct() {
    // add_options_page(
    //   'Reorder Posts',
    //   'Reorder Posts',
    //   'manage_options',
    //   'maps-reorder',
    //   'maps_reorder_settings_page_callback_wrapper'
    // );

    // add_action('admin_menu', [$this, 'add_reorder_page']);
    // add_action('wp_ajax_maps_save_order', [$this, 'save_order']);

    // add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
  }

  public function enqueue_scripts($hook) {
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

    // Adjust to match the correct page slug
    if ($page !== "{$post_type}-reorder") {
      return;
    }

    // Enqueue the scripts and styles
    wp_enqueue_script(
      'maps-reorder-js',
      MAPS_ELEMENTOR_ASSETS_URL . 'js/reorder.js',
      ['jquery', 'jquery-ui-sortable'],
      '1.0',
      true
    );

    wp_enqueue_style(
      'maps-reorder-css',
      MAPS_ELEMENTOR_ASSETS_URL . 'css/reorder.css',
      [],
      '1.0'
    );

    wp_localize_script('maps-reorder-js', 'mapsReorder', [
      'ajaxUrl' => admin_url('admin-ajax.php'),
    ]);
  }

  public function reorder_settings_page_callback() {
    // Handle form submission
    if (isset($_POST['maps_reorder_save_settings'])) {
      check_admin_referer('maps_reorder_settings_nonce');
      $settings = $_POST['maps_reorder_settings'] ?? [];
      update_option('maps_reorder_settings', $settings);
      echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    // Get current settings
    $settings = get_option('maps_reorder_settings', []);
    $post_types = get_post_types(['public' => true], 'objects');
?>
    <div class="wrap">
      <h1>Reorder Posts Settings</h1>
      <form method="post">
        <?php wp_nonce_field('maps_reorder_settings_nonce'); ?>
        <table class="form-table">
          <tr>
            <th>Enable Reordering</th>
            <td>
              <?php foreach ($post_types as $post_type) : ?>
                <label>
                  <input type="checkbox" name="maps_reorder_settings[]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, $settings)); ?>>
                  <?php echo esc_html($post_type->label); ?>
                </label><br>
              <?php endforeach; ?>
            </td>
          </tr>
        </table>
        <p class="submit">
          <button type="submit" name="maps_reorder_save_settings" class="button button-primary">Save Changes</button>
        </p>
      </form>
    </div>
  <?php
  }

  public function add_reorder_page() {
    $settings = get_option('maps_reorder_settings', []);

    foreach ($settings as $post_type) {
      add_submenu_page(
        "edit.php?post_type=$post_type",
        'Reorder Posts',
        'Reorder',
        'manage_options',
        "$post_type-reorder",
        [$this, 'reorder_page_callback']
      );
    }
  }

  public function reorder_page_callback() {
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
    $taxonomy = isset($_GET['taxonomy']) ? sanitize_text_field($_GET['taxonomy']) : '';
    $term_id = isset($_GET['term']) ? intval($_GET['term']) : 0;

    if (! $post_type) {
      echo '<p>No post type provided.</p>';
      return;
    }

    // Get taxonomies associated with the post type
    $taxonomies = get_object_taxonomies($post_type, 'objects');

    // If no taxonomy is selected, show a dropdown to choose one
    if (! $taxonomy || ! array_key_exists($taxonomy, $taxonomies)) {
      echo '<h1>Select Taxonomy</h1>';
      echo '<form method="get">';
      echo '<input type="hidden" name="post_type" value="' . esc_attr($post_type) . '">';
      echo '<input type="hidden" name="page" value="' . esc_attr($_GET['page']) . '">';
      echo '<select name="taxonomy">';
      foreach ($taxonomies as $tax) {
        echo '<option value="' . esc_attr($tax->name) . '">' . esc_html($tax->label) . '</option>';
      }
      echo '</select>';
      echo '<button type="submit" class="button button-primary">Select</button>';
      echo '</form>';
      return;
    }

    // If taxonomy is selected, show terms dropdown
    $terms = get_terms([
      'taxonomy' => $taxonomy,
      'hide_empty' => false,
    ]);

    if (! $term_id || ! term_exists($term_id, $taxonomy)) {
      echo '<h1>Select Term for ' . esc_html($taxonomies[$taxonomy]->label) . '</h1>';
      echo '<form method="get">';
      echo '<input type="hidden" name="post_type" value="' . esc_attr($post_type) . '">';
      echo '<input type="hidden" name="page" value="' . esc_attr($_GET['page']) . '">';
      echo '<input type="hidden" name="taxonomy" value="' . esc_attr($taxonomy) . '">';
      echo '<select name="term">';
      foreach ($terms as $term) {
        echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
      }
      echo '</select>';
      echo '<button type="submit" class="button button-primary">Select</button>';
      echo '</form>';
      return;
    }

    // Fetch posts for the selected term
    $posts = get_posts([
      'post_type' => $post_type,
      'posts_per_page' => -1,
      'tax_query' => [
        [
          'taxonomy' => $taxonomy,
          'terms' => $term_id,
        ]
      ],
      'orderby' => 'menu_order',
      'order' => 'ASC',
    ]);

  ?>
    <div class="wrap">
      <h1>Reorder Posts in Term "<?php echo esc_html(get_term($term_id)->name); ?>"</h1>
      <form id="reorder-form">
        <ul id="sortable">
          <?php foreach ($posts as $post) : ?>
            <li id="post-<?php echo esc_attr($post->ID); ?>">
              <?php echo esc_html($post->post_title); ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <button type="submit" class="button button-primary">Save Order</button>
      </form>
    </div>
<?php
  }

  function save_order() {
    if (! isset($_POST['order'])) {
      wp_send_json_error('Invalid request.');
    }

    $order = $_POST['order'];
    foreach ($order as $index => $post_id) {
      wp_update_post([
        'ID' => intval($post_id),
        'menu_order' => $index,
      ]);
    }

    wp_send_json_success();
  }
}

function maps_reorder_settings_page_callback_wrapper() {
  // $instance = \MAPSElementor\Plugin::instance();
  // $instance->libs['\MAPSElementor\Lib\Reorder\Reorder']->reorder_settings_page_callback();
  $instance = \MAPSElementor\Lib\Reorder\Reorder::instance();
  $instance->reorder_settings_page_callback();
}
