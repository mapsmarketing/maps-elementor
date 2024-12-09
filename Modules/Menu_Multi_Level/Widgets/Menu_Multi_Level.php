<?php

namespace MAPSElementor\Modules\Menu_Multi_Level\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Menu_Multi_Level extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-menu-multi-level';
  }

  public function get_title()
  {
    return __('MAPS Menu Multi Level', 'maps-menu-multi-level');
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

    // wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    // wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);
  
    add_action('elementor/frontend/after_enqueue_scripts', [ $this, '_enqueue_scripts' ]);
  }

  public function _enqueue_scripts() {
    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);
  }

  public function get_script_depends()
  {
    return [$this->get_name() . '-js'];
  }

  public function get_style_depends()
  {
    return [$this->get_name() . '-css'];
  }

  protected function _register_controls()
  {
    $menus = wp_get_nav_menus();
    $menu_options = [];
    foreach ($menus as $menu) {
      $menu_options[$menu->term_id] = $menu->name;
    }

    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $list_tabs = new \Elementor\Repeater();
    $list_tabs->add_control('list_title', [
      'label' => esc_html__('Title', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('List Title', 'maps-menu-multi-level'),
      'label_block' => true
    ]);
    $list_tabs->add_control('list_tabs', [
      'label' => esc_html__('Secondary', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => [
        [
          'name' => 'list_tabs_title',
          'label' => esc_html__('Title', 'maps-menu-multi-level'),
          'type' => \Elementor\Controls_Manager::TEXT,
          'default' => esc_html__('List Title', 'maps-menu-multi-level'),
          'label_block' => true
        ],
        [
          'name' => 'list_tabs_menu',
          'label' => __('Menu', 'maps-menu-multi-level'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'options' => $menu_options,
          'default' => array_keys($menu_options)[0],
          'save_default' => true,
          'separator' => 'after',
          'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'maps-menu-multi-level'), admin_url('nav-menus.php'))
        ],
        [
          'name' => 'list_tabs_menu_secondary',
          'label' => __('Secondary Menu', 'maps-menu-multi-level'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'options' => $menu_options,
          'default' => array_keys($menu_options)[0],
          'save_default' => true,
          'separator' => 'after',
          'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'maps-menu-multi-level'), admin_url('nav-menus.php'))
        ]
      ],
      'title_field' => '{{{ list_tabs_title }}}'
    ]);

    $this->add_control('list', [
      'label' => esc_html__('Primary', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $list_tabs->get_controls(),
      'title_field' => '{{{ list_title }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_main_menu', [
      'label' => __('Main Menu', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('main_menu_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('main_menu_tabs');

    $this->start_controls_tab('main_menu_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'main_menu_typography',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'main_menu_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->add_control('main_menu_color', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'main_menu_border',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->end_controls_tab(); // normal_tab

    $this->start_controls_tab('main_menu_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'main_menu_typography_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'main_menu_background_hover',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item:hover .maps-menu-multi-level__tabs__list__item__toggle, {{WRAPPER}} .maps-menu-multi-level__tabs__list__item.on .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->add_control('main_menu_color_hover', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item:hover .maps-menu-multi-level__tabs__list__item__toggle' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item.on .maps-menu-multi-level__tabs__list__item__toggle' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'main_menu_border_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item:hover .maps-menu-multi-level__tabs__list__item__toggle, {{WRAPPER}} .maps-menu-multi-level__tabs__list__item.on .maps-menu-multi-level__tabs__list__item__toggle'
    ]);

    $this->end_controls_tab(); // main_menu_hover_tab
    $this->end_controls_tabs();

    $this->end_controls_section(); // style_main_menu

    $this->start_controls_section('style_mega', [
      'label' => __('Mega', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('mega_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus' => 'padding: 0 0 0 {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'mega_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
      'name' => 'mega_box_shadow',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_mega_tabs', [
      'label' => __('Mega - Tabs', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('mega_tabs_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('mega_tabs');

    $this->start_controls_tab('mega_tabs_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_tabs_typography',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'mega_tabs_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->add_control('mega_tabs_color', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'mega_tabs_border',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->end_controls_tab(); // mega_tabs_normal_tab

    $this->start_controls_tab('mega_tabs_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_tabs_typography_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'mega_tabs_background_hover',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item:hover .maps-menu-multi-level__tabs__mega__item__toggle, {{WRAPPER}} .maps-menu-multi-level__tabs__mega__item.on .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->add_control('mega_tabs_color_hover', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item:hover .maps-menu-multi-level__tabs__mega__item__toggle' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__item.on .maps-menu-multi-level__tabs__mega__item__toggle' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'mega_tabs_border_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__list__item:hover .maps-menu-multi-level__tabs__mega__item__toggle, {{WRAPPER}} .maps-menu-multi-level__tabs__mega__item.on .maps-menu-multi-level__tabs__mega__item__toggle'
    ]);

    $this->end_controls_tab(); // mega_tabs_hover_tab
    $this->end_controls_tabs();

    $this->end_controls_section(); // style_mega_tabs

    $this->start_controls_section('style_mega_menu', [
      'label' => __('Mega - Primary Menu', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('mega_menu_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'mega_menu_border',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary'
    ]);

    $this->start_controls_tabs('mega_menu_tabs');

    $this->start_controls_tab('mega_menu_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_menu_typography',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary ul li a'
    ]);

    $this->add_control('mega_menu_color', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary ul li a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // mega_menu_normal_tab

    $this->start_controls_tab('mega_menu_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_menu_typography_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary ul li a:hover'
    ]);

    $this->add_control('mega_menu_color_hover', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary ul li a:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // mega_menu_hover_tab
    $this->end_controls_tabs();

    $this->add_responsive_control('mega_menu_link_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('mega_menu_link_spacing', [
      'label' => esc_html__('Spacing', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'custom'],
      'range' => [
        'px' => [
          'min' => 0,
          'step' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 20
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary > ul > li' => 'margin-bottom: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'mega_menu_link_border',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__primary > ul > .menu-item-has-children > a, .maps-menu-multi-level__tabs__mega__menus__secondary.on h3 a'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_mega_menu_secondary', [
      'label' => __('Mega - Secondary Menu', 'maps-menu-multi-level'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('mega_menu_secondary_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'mega_menu_secondary_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary'
    ]);

    $this->start_controls_tabs('mega_menu_secondary_tabs');

    $this->start_controls_tab('mega_menu_secondary_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_menu_secondary_typography',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary ul li a'
    ]);

    $this->add_control('mega_menu_secondary_color', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary ul li a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // mega_menu_normal_tab

    $this->start_controls_tab('mega_menu_secondary_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'mega_menu_secondary_typography_hover',
      'selector' => '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary ul li a:hover'
    ]);

    $this->add_control('mega_menu_secondary_color_hover', [
      'label' => esc_html__('Color', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary ul li a:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // mega_menu_hover_tab
    $this->end_controls_tabs();

    $this->add_responsive_control('mega_menu_secondary_link_padding', [
      'label' => esc_html__('Padding', 'maps-menu-multi-level'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'selectors' => [
        '{{WRAPPER}} .maps-menu-multi-level__tabs__mega__menus__secondary ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display(); ?>

    <div class="maps-menu-multi-level">
      <a href="#" class="maps-menu-multi-level__hamburger" role="button">
        <i class="fas fa-bars"></i>
      </a>
      <div class="maps-menu-multi-level__tabs">
        <ul class="maps-menu-multi-level__tabs__list">

          <?php foreach ($settings['list'] as $item): ?>
            <li class="maps-menu-multi-level__tabs__list__item">
              <a href="#maps-menu-multi-level-<?php echo $item['_id']; ?>" class="maps-menu-multi-level__tabs__list__item__toggle" role="button">
                <?php echo $item['list_title']; ?>
              </a>
              <ul id="maps-menu-multi-level-<?php echo $item['_id']; ?>" class="maps-menu-multi-level__tabs__mega">

                <?php foreach ($item['list_tabs'] as $tab): ?>
                  <li class="maps-menu-multi-level__tabs__mega__item">
                    <a href="#" class="maps-menu-multi-level__tabs__mega__item__toggle" role="button">
                      <?php echo $tab['list_tabs_title']; ?>
                    </a>

                    <div class="maps-menu-multi-level__tabs__mega__menus">
                      <?php if (isset($tab['list_tabs_menu'])): ?>
                        <div class="maps-menu-multi-level__tabs__mega__menus__primary">

                          <?php wp_nav_menu([
                            'menu' => $tab['list_tabs_menu'],
                            'container' => ''
                          ]); ?>

                        </div>
                      <?php endif; ?>

                      <?php if (isset($tab['list_tabs_menu_secondary'])): ?>
                        <div class="maps-menu-multi-level__tabs__mega__menus__secondary">
                          <h3>
                            <a href="#" role="button"><?php _e('I want to', 'maps-menu-multi-level'); ?></a>
                          </h3>

                          <?php wp_nav_menu([
                            'menu' => $tab['list_tabs_menu_secondary'],
                            'container' => ''
                          ]); ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </li>
                <?php endforeach; ?>

              </ul>
            </li>
          <?php endforeach; ?>

        </ul>
      </div>
    </div>

<?php
  }
}
