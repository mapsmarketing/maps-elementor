<?php

namespace MAPSElementor\Modules\Menu_Full_Screen\Widgets;

use MAPSElementor\Modules\Menu_Full_Screen\Inc\Walker_Menu;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Menu_Full_Screen extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-menu-full-screen';
  }

  public function get_title()
  {
    return __('MAPS Menu Full Screen', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-bars';
  }

  public function get_categories()
  {
    return ['maps-marketing'];
  }

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    // wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    // wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);

    // wp_localize_script($this->get_name() . '-js', 'mapsObject', [
    //   'ajax_url' => admin_url('admin-ajax.php')
    // ]);

    // add_filter('nav_menu_link_attributes', [$this, 'nav_menu_link_attributes'], 10, 4);

    add_action('elementor/frontend/after_enqueue_scripts', [ $this, '_enqueue_scripts' ]);
  }

  public function _enqueue_scripts() {
    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);

    wp_localize_script($this->get_name() . '-js', 'mapsObject', [
      'ajax_url' => admin_url('admin-ajax.php')
    ]);
  }

  public function get_script_depends()
  {
    return [$this->get_name() . '-js'];
  }

  public function get_style_depends()
  {
    return [$this->get_name() . '-css'];
  }

  public function register_controls()
  {
    $this->start_controls_section('section_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $menus = get_terms('nav_menu');
    $menu_options = [
      0 => __('None', 'maps-marketing')
    ];
    foreach ($menus as $menu) {
      $menu_options[$menu->term_id] = $menu->name;
    }

    if (!empty($menu_options)) {
      $this->add_control('menu', [
        'label' => __('Menu', 'maps-marketing'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $menu_options,
        'default' => array_keys($menu_options)[0],
        'save_default' => true,
        'separator' => 'after',
        'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'elementor-pro'), admin_url('nav-menus.php'))
      ]);

      $this->add_control('menu_secondary', [
        'label' => __('Menu Secondary', 'maps-marketing'),
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

    $this->start_controls_section('section_portals', [
      'label' => __('Portals', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('list_text', [
      'label' => esc_html__('Text', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('Text', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_link', [
      'label' => esc_html__('Link', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::URL,
      'placeholder' => esc_html__('https://your-link.com', 'maps-marketing'),
      'options' => ['url', 'is_external', 'nofollow'],
      'label_block' => true
    ]);

    $this->add_control('list_portals', [
      'label' => esc_html__('Portals', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'default' => [
        [
          'list_text' => esc_html__('Portal #1', 'maps-marketing'),
          'list_link' => [
            'url' => '#'
          ]
        ],
        [
          'list_text' => esc_html__('Portal #2', 'maps-marketing'),
          'list_link' => [
            'url' => '#'
          ]
        ]
      ],
      'title_field' => '{{{ list_text }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_buttons', [
      'label' => __('Buttons', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('list_text', [
      'label' => esc_html__('Text', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('Button', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_link', [
      'label' => esc_html__('Link', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::URL,
      'placeholder' => esc_html__('https://your-link.com', 'maps-marketing'),
      'options' => ['url', 'is_external', 'nofollow'],
      'label_block' => true
    ]);

    $this->add_control('list_buttons', [
      'label' => esc_html__('Buttons', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'default' => [
        [
          'list_text' => esc_html__('Button #1', 'maps-marketing'),
          'list_link' => [
            'url' => '#'
          ]
        ],
        [
          'list_text' => esc_html__('Button #2', 'maps-marketing'),
          'list_link' => [
            'url' => '#'
          ]
        ]
      ],
      'title_field' => '{{{ list_text }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_icons', [
      'label' => __('Icons', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('logo', [
      'label' => esc_html__('Logo', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::ICONS
    ]);

    $this->add_control('search', [
      'label' => esc_html__('Search', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-search',
        'library' => 'solid'
      ]
    ]);

    $this->add_control('hamburger', [
      'label' => esc_html__('Hamburger', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-menu',
        'library' => 'solid'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_settings', [
      'label' => __('Settings', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('transparent_header', [
      'label' => esc_html__('Transparent Header', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'yes',
      'default' => 'no',
      'frontend_available' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_header', [
      'label' => __('Header', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('header_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('header_tabs');

    $this->start_controls_tab('header_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'header_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__main'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('header_scroll_tab', [
      'label' => esc_html__('Scroll', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'header_background_scroll',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__main--scroll-up'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('header_active_tab', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'header_background_active',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__main.on'
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_control('heading_icons', [
      'label' => esc_html__('Icons', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_responsive_control('logo_size', [
      'label' => esc_html__('Logo size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__main__left__logo svg' => 'width: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-menu-full-screen__main__left__logo' => 'font-size: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('search_size', [
      'label' => esc_html__('Search size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__main__right__search svg' => 'width: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-menu-full-screen__main__right__search' => 'font-size: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('hamburger_size', [
      'label' => esc_html__('Hamburger size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__main__right__toggle svg' => 'width: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-menu-full-screen__main__right__toggle' => 'font-size: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_main_menu', [
      'label' => __('Main Menu', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'main_menu_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > a'
    ]);

    $this->start_controls_tabs('main_menu_tabs');

    $this->start_controls_tab('main_menu_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('main_menu_color_normal', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('main_menu_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('main_menu_color_hover', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > a:hover' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > a:focus' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_responsive_control('main_menu_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_secondary_menu', [
      'label' => __('Secondary Menu', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('secondary_menu_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__secondary > .menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'secondary_menu_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__secondary .menu > li > a'
    ]);

    $this->start_controls_tabs('secondary_menu_tabs');

    $this->start_controls_tab('secondary_menu_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('secondary_menu_color_normal', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__secondary > .menu > li > a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('secondary_menu_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('secondary_menu_color_hover', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__secondary > .menu > li > a:hover' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-menu-full-screen__menu__secondary > .menu > li > a:focus' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_responsive_control('secondary_menu_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__secondary' => 'margin-top: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_offcanvas', [
      'label' => __('Offcanvas', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('offcanvas_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'offcanvas_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_sub_menu', [
      'label' => __('Sub Menu', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'sub_menu_background',
      'label' => __('Sub Menu Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__primary > .menu > li > .sub-menu'
    ]);

    $this->add_control('heading_sub_menu_link', [
      'label' => esc_html__('Links', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_responsive_control('sub_menu_link_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'sub_menu_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu li a'
    ]);

    $this->start_controls_tabs('sub_menu_tabs');

    $this->start_controls_tab('sub_menu_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('sub_menu_color_normal', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu li a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('sub_menu_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('sub_menu_color_hover', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu li a:hover' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu li a:focus' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_control('heading_sub_menu_back', [
      'label' => esc_html__('Back', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'sub_menu_back_background',
      'label' => __('Sub Menu Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu .menu-item-back a'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'sub_menu_back_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__primary .sub-menu .menu-item-back a'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_campuses', [
      'label' => __('Campus', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'campus_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__campuses'
    ]);

    $this->add_responsive_control('campus_padding', [
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'label' => esc_html__('Padding', 'maps-marketing'),
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('campus_gap', [
      'label' => esc_html__('Gap', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses' => 'gap: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('campus_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item' => 'gap: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('campus_heading', [
      'label' => esc_html__('Heading', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'campus_title_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item h4'
    ]);

    $this->add_control('campus_heading_colour', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item h4' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_control('campus_address', [
      'label' => esc_html__('Address', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'campus_address_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item address'
    ]);

    $this->add_control('campus_address_colour', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item address' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_control('campus_phone', [
      'label' => esc_html__('Phone', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'campus_phone_typography',
      'selector' => '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item a'
    ]);

    $this->add_control('campus_phone_colour', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-full-screen__menu__campuses__item a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_section();
  }

  public function nav_menu_link_attributes($atts, $menu_item, $args, $depth)
  {
    if ($args->menu === 2 && $depth === 0) {
      $atts['data-featured-image'] = get_the_post_thumbnail_url($menu_item->object_id) ? get_the_post_thumbnail_url($menu_item->object_id, 'full') : '';
    }

    return $atts;
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

    // $content = $settings['content'];
    // $content_html = sprintf( '<%1$s>%2$s</%1$s>', \Elementor\Utils::validate_html_tag( $settings['content_html'] ), $content );
    $logo = isset($settings['logo']['value']) ? $settings['logo'] : false;
    $logo = isset($settings['logo_mobile']['value']) ? $settings['logo_mobile'] : $logo;

    $campuses = get_field('campuses', 'options');

    $this->add_render_attribute('widget', [
      'class' => $settings['transparent_header'] == 'yes' ? ['maps-menu-full-screen', $this->get_name() . '--sticky'] : ['maps-menu-full-screen']
    ]);
    // $menu_items = wp_get_nav_menu_items( $settings['menu'] );

    //         $this->add_render_attribute( 'classes', array(
    // 			'class' => 'elementor-maps-nav-menu',
    // 		) );
    ?>

    <div <?php $this->print_render_attribute_string('widget'); ?>>
      <div class="maps-menu-full-screen__main">
        <div class="maps-menu-full-screen__main__left">
          <a href="<?php echo get_home_url(); ?>" class="maps-menu-full-screen__main__left__logo">
            <?php \Elementor\Icons_Manager::render_icon($settings['logo'], [
              'aria-hidden' => 'true'
            ]); ?>
          </a>
        </div>
        <div class="maps-menu-full-screen__main__right">
          <div class="maps-menu-full-screen__main__right__portals">
            <?php foreach ($settings['list_portals'] as $button):

              if (empty($button['list_link']['url'])) {
                break;
              }

              $this->add_link_attributes('list_link_' . $button['_id'], $button['list_link']);
              ?>
              <a <?php echo $this->get_render_attribute_string('list_link_' . $button['_id']); ?>><?php echo $button['list_text']; ?></a>
            <?php
            endforeach; ?>
          </div>
          <div class="maps-menu-full-screen__main__right__actions">
            <?php foreach ($settings['list_buttons'] as $button):

              if (empty($button['list_link']['url'])) {
                break;
              }

              $this->add_link_attributes('list_link_' . $button['_id'], $button['list_link']);
              ?>
              <a class="elementor-button" <?php echo $this->get_render_attribute_string('list_link_' . $button['_id']); ?>><?php echo $button['list_text']; ?></a>
            <?php
            endforeach; ?>

            <a href="#" class="maps-menu-full-screen__main__right__search" role="button">
              <?php \Elementor\Icons_Manager::render_icon($settings['search'], [
                'aria-hidden' => 'true'
              ]); ?>
            </a>
            <a href="#" class="maps-menu-full-screen__main__right__toggle" role="button">
              <?php \Elementor\Icons_Manager::render_icon($settings['hamburger'], [
                'aria-hidden' => 'true'
              ]); ?>
            </a>
          </div>
        </div>
      </div>

      <div class="maps-menu-full-screen__menu">

        <div class="maps-menu-full-screen__menu__top">
          <nav class="maps-menu-full-screen__menu__primary">
            <?php wp_nav_menu([
              'menu' => $settings['menu'],
              'container' => 'ul',
              'walker' => new Walker_Menu
            ]); ?>
          </nav>

          <?php if ($settings['menu_secondary'] != 0): ?>
            <nav class="maps-menu-full-screen__menu__secondary">
              <?php wp_nav_menu([
                'menu' => $settings['menu_secondary'],
                'container' => 'ul',
                'walker' => new Walker_Menu
              ]); ?>
            </nav>
          <?php endif; ?>

          <div class="maps-menu-full-screen__menu__bg"></div>
        </div>

        <?php if ($campuses): ?>
          <div class="maps-menu-full-screen__menu__campuses">

            <?php foreach ($campuses as $campus): ?>
              <div class="maps-menu-full-screen__menu__campuses__item">
                <h4><?php echo $campus['name']; ?></h4>
                <address><?php echo $campus['address']; ?></address>
                <a href="tel:<?php echo $campus['phone']; ?>"><?php echo $campus['phone']; ?></a>
              </div>
            <?php endforeach; ?>

          </div>
        <?php endif; ?>

      </div>

      <div class="maps-menu-full-screen__search">
        <div class="maps-menu-full-screen__search__container">
          <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input type="text" name="s" placeholder="Enter Search" value="<?php the_search_query(); ?>" class="maps-menu-full-screen__search__text" />
          </form>

          <div class="maps-menu-full-screen__search__results"></div>
        </div>
      </div>
    </div>

<?php
  }
}
