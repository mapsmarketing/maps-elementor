<?php

namespace MAPSElementor\Modules\Dynamic_Tags\Tags;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Documents extends \Elementor\Core\DynamicTags\Tag
{
  public function get_name()
  {
    return 'maps-document-tag';
  }

  public function get_title()
  {
    return __('Documents', 'maps-marketing');
  }

  public function get_group()
  {
    return 'maps-dynamic-tags';
  }

  public function get_categories()
  {
    return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
  }

  public function render()
  {
    global $post;

    if (!$post) {
      echo false;
    } else {
      $documents = get_posts([
        'post_type' => 'maps_document',
        'posts_per_page' => -1,
        'meta_query' => [
          [
            'key' => 'page_link',
            'value' => serialize(strval(get_the_id())),
            'compare' => 'LIKE'
          ]
        ]
      ]);

      echo $documents ? true : false;
    }
  }
}
