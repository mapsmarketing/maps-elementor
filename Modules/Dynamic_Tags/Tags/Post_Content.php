<?php

namespace MAPSElementor\Modules\Dynamic_Tags\Tags;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Post_Content extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag
{
  public function get_name()
  {
    return 'maps-post-content';
  }

  public function get_title()
  {
    return __('Post Content', 'maps-marketing');
  }

  public function get_group()
  {
    return 'maps-dynamic-tags';
  }

  public function get_categories()
  {
    return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
  }

  protected function register_controls()
  {
    global $post;
  }

  public function get_value(array $options = [])
  {
    global $post;

    if (!$post) {
      return;
    }

    return apply_filters('the_content', get_the_content());
  }
}
