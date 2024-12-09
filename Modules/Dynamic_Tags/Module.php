<?php

namespace MAPSElementor\Modules\Dynamic_Tags;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \Elementor\Core\Base\Module
{
  public function __construct()
  {
    $this->register_groups();

    add_action('elementor/dynamic_tags/register', [$this, 'register_tags']);
  }

  public function get_name()
  {
    return 'maps-dynamic-tags';
  }

  public function get_tag_classes_names()
  {
    return ['Post_Content', 'Post_Meta_Image', 'Documents'];
  }

  public function get_groups()
  {
    return [
      'maps-dynamic-tags' => [
        'title' => __('MAPS Marketing', 'elementor-pro')
      ]
    ];
  }

  private function register_groups()
  {
    foreach ($this->get_groups() as $group_name => $group_settings) {
      \Elementor\Plugin::$instance->dynamic_tags->register_group($group_name, $group_settings);
    }
  }

  public function register_tags($dynamic_tags)
  {
    foreach ($this->get_tag_classes_names() as $tag_class) {
      $class_name = $this->get_reflection()->getNamespaceName() . '\Tags\\' . $tag_class;

      if (class_exists($class_name)) {
        $dynamic_tags->register(new $class_name());
      }
    }
  }
}
