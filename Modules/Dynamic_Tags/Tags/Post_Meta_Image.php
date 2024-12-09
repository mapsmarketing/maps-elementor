<?php

namespace MAPSElementor\Modules\Dynamic_Tags\Tags;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Post_Meta_Image extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag
{
  public function get_name()
  {
    return 'maps-post-meta-image';
  }

  public function get_title()
  {
    return __('Post Meta Image', 'maps-marketing');
  }

  public function get_group()
  {
    return 'maps-dynamic-tags';
  }

  public function get_categories()
  {
    return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY];
  }

  protected function register_controls()
  {
    global $post;

    $keys = array_keys(get_post_meta($post->ID));

    $fields = [];

    foreach ($keys as $key) {
      $fields[$key] = $key;
    }

    $this->add_control('field', [
      'label' => __('Field', 'elementor-pro'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'options' => $fields
    ]);
  }

  public function get_value(array $options = [])
  {
    global $post;

    $field = $this->get_settings('field');

    $meta = get_post_meta($post->ID, $field, true);

    if (!$post || !$field) {
      return;
    }

    $url = false;
    $attachment_id = false;

    if (strpos($meta, 'http') !== false) {
      $url = $meta;
      $attachment_id = attachment_url_to_postid($meta);
    } else {
      $attachment_id = $meta;
      $url = wp_get_attachment_image_url($meta);
    }

    return [
      'id' => $attachment_id,
      'url' => $url
    ];
  }
}
