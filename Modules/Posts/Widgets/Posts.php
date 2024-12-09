<?php

namespace MAPSElementor\Modules\Posts\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

/**
 * Class Posts
 */
class Posts extends \ElementorPro\Modules\Posts\Widgets\Posts
{
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    // wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    // wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);
  
    add_action('elementor/frontend/after_enqueue_scripts', [ $this, '_enqueue_scripts' ]);
  }

  public function _enqueue_scripts() {
    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);
  }

  public function get_script_depends(): array
  {
    $scripts = parent::get_script_depends();
    $scripts = array_merge($scripts, [$this->get_name() . '-js']);

    return $scripts;
  }

  public function get_style_depends(): array
  {
    $styles = parent::get_style_depends();
    $styles = array_merge($styles, [$this->get_name() . '-css']);

    return $styles;
  }

  protected function register_skins()
  {
    parent::register_skins();

    $this->add_skin(new \MAPSElementor\Modules\Posts\Skins\Carousel($this));
  }
}
