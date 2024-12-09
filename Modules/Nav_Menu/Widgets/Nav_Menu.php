<?php

namespace MAPSElementor\Modules\Nav_Menu\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Nav_Menu extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-nav-menu';
  }

  public function get_title()
  {
    return __('MAPS Nav Menu', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fa fa-bars';
  }

  public function get_categories()
  {
    return ['maps-marketing'];
  }

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());
  }

  public function get_script_depends()
  {
    return [];
  }

  public function get_style_depends()
  {
    return [$this->get_name() . '-css'];
  }

  protected function _register_controls()
  {
    $this->start_controls_section('content_section', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    // $menus = $this->get_available_menus();

    $menus = get_terms('nav_menu');
    $menu_options = [];
    foreach ($menus as $menu) {
      $menu_options[$menu->term_id] = $menu->name;
    }

    if (!empty($menu_options)) {
      $this->add_control('menu', [
        'label' => __('Menu', 'elementor-pro'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $menu_options,
        'default' => array_keys($menu_options)[0],
        'save_default' => true,
        'separator' => 'after',
        'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'elementor-pro'), admin_url('nav-menus.php'))
      ]);
    } else {
      $this->add_control('menu', [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => '<strong>' . __('There are no menus in your site.', 'elementor-pro') . '</strong><br>' . sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'elementor-pro'), admin_url('nav-menus.php?action=edit&menu=0')),
        'separator' => 'after',
        'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
      ]);
    }

    $this->end_controls_section();
  }

  protected function render()
  {
    //         $available_menus = $this->get_available_menus();

    // 		if ( ! $available_menus ) {
    // 			return;
    // 		}

    // $menus = get_terms('nav_menu');

    // $menu_arr = array_map(function($menu) {
    //     return array( $menu->name => $menu->term_id );
    // }, $menus);

    $settings = $this->get_settings_for_display();

    $menu_items = wp_get_nav_menu_items($settings['menu']);
    //         $this->add_render_attribute( 'classes', array(
    // 			'class' => 'elementor-maps-nav-menu',
    // 		) );
    ?>

    <?php foreach ($menu_items as $item): ?>

      <div class="elementor-widget-maps-nav-menu__post">
        <a href="<?php echo $item->url; ?>">
          <div class="elementor-widget-maps-nav-menu__post__thumbnail" style="background-image: url('<?php echo get_the_post_thumbnail_url($item->object_id, 'medium'); ?>');"></div>
          <div class="elementor-widget-maps-nav-menu__post__title"><?php echo $item->title; ?></div>
        </a>
      </div>

    <?php endforeach; ?>

<?php
  }
}
