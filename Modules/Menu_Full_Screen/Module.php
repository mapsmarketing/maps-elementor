<?php

namespace MAPSElementor\Modules\Menu_Full_Screen;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Menu_Full_Screen'];
  }

  public function get_name()
  {
    return 'maps-menu-full-screen';
  }

  public function __construct()
  {
    parent::__construct();

    $this->hooks();
  }

  /**
   * `add_action` and `add_filter` hooks added here for the widget.
   */
  protected function hooks()
  {
    // Ajax search action
    add_action('wp_ajax_maps_menu_full_screen_search', [$this, 'search']);
    add_action('wp_ajax_nopriv_maps_menu_full_screen_search', [$this, 'search']);
  }

  /**
   * Allows the user to search for pages, posts, map_document and smartnews.
   *
   * @return json Contains a formatted array.
   */
  public function search()
  {
    global $post;

    $query = esc_html($_REQUEST['s']);

    $posts = get_posts([
      'post_type' => ['post', 'page', 'maps_document', 'smartnews'],
      'posts_per_page' => 12,
      's' => $query
    ]);

    $results = [];
    foreach ($posts as $post) {
      setup_postdata($post);

      $post_type = get_post_type_object($post->post_type);

      $results[$post_type->labels->menu_name][] = [
        'thumbnail' => get_the_post_thumbnail_url($post, [80, 80]),
        'title' => get_the_title(),
        'url' => get_the_permalink()
      ];
    }
    wp_reset_postdata();

    wp_send_json($results);
  }
}
