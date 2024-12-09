<?php

namespace MAPSElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Carousel extends \ElementorPro\Modules\Posts\Skins\Skin_Base
{
  public function get_id()
  {
    return 'carousel';
  }

  public function get_title()
  {
    return esc_html__('Carousel', 'maps-marketing');
  }

  protected function _register_controls_actions()
  {
    parent::_register_controls_actions();

    add_action('elementor/element/posts/section_layout/before_section_end', [$this, 'register_controls_layout']);
    add_action('elementor/element/posts/section_pagination/before_section_end', [$this, 'register_controls_pagination']);
    add_action('elementor/element/posts/section_pagination/after_section_end', [$this, 'register_controls_carousel']);
    add_action('elementor/element/posts/carousel_section_design_layout/after_section_end', [$this, 'register_additional_design_controls']);
    add_action('elementor/element/posts/carousel_section_design_layout/before_section_end', [$this, 'register_controls_design_layout']);
  }

  public function start_controls_tab($id, $args)
  {
    $args['condition']['_skin'] = $this->get_id();
    $this->parent->start_controls_tab($this->get_control_id($id), $args);
  }

  public function end_controls_tab()
  {
    $this->parent->end_controls_tab();
  }

  public function start_controls_tabs($id)
  {
    $args['condition']['_skin'] = $this->get_id();
    $this->parent->start_controls_tabs($this->get_control_id($id));
  }

  public function end_controls_tabs()
  {
    $this->parent->end_controls_tabs();
  }

  public function register_controls_layout($widget)
  {
    $this->parent = $widget;
    $this->parent->remove_control('carousel_columns');
  }

  public function register_controls_pagination($widget)
  {
    $this->parent = $widget;
    $this->parent->remove_control('section_pagination');
  }

  public function register_controls_design_layout($widget)
  {
    $this->parent = $widget;
    $this->parent->remove_control('carousel_section_design_layout');
  }

  public function register_controls_carousel()
  {
    $this->start_controls_section('section_carousel', [
      'label' => esc_html__('Carousel', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('slider_effect', [
      'label' => esc_html__('Effect', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => 'slide',
      'options' => [
        'slide' => esc_html__('slide', 'maps-marketing'),
        // 	'fade' => esc_html__( 'fade', 'maps-marketing' ),
        'cube' => esc_html__('cube', 'maps-marketing'),
        'coverflow' => esc_html__('coverflow', 'maps-marketing')
        // 	'flip' => esc_html__( 'flip', 'maps-marketing' ),
        // 	'creative' => esc_html__( 'creative', 'maps-marketing' ),
        // 	'cards' => esc_html__( 'cards', 'maps-marketing' ),
      ],
      'frontend_available' => true
    ]);

    $this->add_control('slider_speed', [
      'label' => esc_html__('Effect Speed (ms)', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'default' => 300,
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_slidestoshow', [
      'label' => __('Slides to Show', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'min' => 1,
      'default' => 1,
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_slidestoscroll', [
      'label' => __('Slides to Scroll', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'min' => 1,
      'default' => 1,
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_space', [
      'label' => __('Slides Gap', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'default' => [
        'unit' => 'px',
        'size' => 0
      ],
      'frontend_available' => true
    ]);

    $this->add_control('slider_loop', [
      'label' => esc_html__('Loop', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
      'frontend_available' => true
    ]);

    $this->add_control('slider_autoplay', [
      'label' => esc_html__('Autoplay', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
      'frontend_available' => true
    ]);

    $this->add_control('slider_interval', [
      'label' => esc_html__('Autoplay Interval (ms)', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'default' => 3000,
      'condition' => [
        'carousel_slider_autoplay' => 'true'
      ],
      'frontend_available' => true
    ]);

    $this->add_control('slider_center_mode', [
      'label' => esc_html__('Center Mode', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
      'frontend_available' => true
    ]);

    $this->add_control('slider_arrows', [
      'label' => esc_html__('Arrows', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
      'frontend_available' => true
    ]);

    $this->add_control('slider_pagination', [
      'label' => esc_html__('Pagination', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
      'frontend_available' => true
    ]);

    $this->add_control('slider_pagination_type', [
      'label' => esc_html__('Pagination Type', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => 'bullets',
      'options' => [
        'bullets' => esc_html__('bullets', 'maps-marketing'),
        'fraction' => esc_html__('fraction', 'maps-marketing'),
        'progressbar' => esc_html__('progressbar', 'maps-marketing')
      ],
      'condition' => [
        'carousel_slider_pagination' => 'true'
      ],
      'frontend_available' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_arrows', [
      'label' => esc_html__('Arrows', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('arrows_prev', [
      'label' => esc_html__('Prev', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-chevron-left',
        'library' => 'fa-solid'
      ]
    ]);

    $this->add_control('arrows_next', [
      'label' => esc_html__('Next', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-chevron-right',
        'library' => 'fa-solid'
      ]
    ]);

    $this->end_controls_section();
  }

  public function register_additional_design_controls()
  {
    $this->start_controls_section('section_design_box', [
      'label' => esc_html__('Box', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('box_border_width', [
      'label' => esc_html__('Border Width', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 50
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-post' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('box_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', '%'],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 200
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-post' => 'border-radius: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('box_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 50
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('content_padding', [
      'label' => esc_html__('Content Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 50
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-post__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
      ],
      'separator' => 'after'
    ]);

    $this->start_controls_tabs('box_tabs');

    $this->start_controls_tab('box_tab_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'box_shadow',
      'selector' => '{{WRAPPER}} .elementor-post'
    ]);

    $this->add_control('box_bg', [
      'label' => esc_html__('Background Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-post' => 'background-color: {{VALUE}}'
      ]
    ]);

    $this->add_control('box_border_color', [
      'label' => esc_html__('Border Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-post' => 'border-color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('box_tab_hover', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'box_shadow_hover',
      'selector' => '{{WRAPPER}} .elementor-post:hover'
    ]);

    $this->add_control('box_bg_hover', [
      'label' => esc_html__('Background Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-post:hover' => 'background-color: {{VALUE}}'
      ]
    ]);

    $this->add_control('box_border_color_hover', [
      'label' => esc_html__('Border Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-post:hover' => 'border-color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('section_design_arrows', [
      'label' => esc_html__('Arrows', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('arrows_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'font-size: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->start_controls_tabs('arrows_tabs');

    $this->start_controls_tab('arrows_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('arrows_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('arrows_hover', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('arrows_color_hover', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-button:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_pagination', [
      'label' => esc_html__('Pagination', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('pagination_bullet', [
      'label' => esc_html__('Normal', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}}'
      ]
    ]);

    $this->add_control('pagination_bullet_active', [
      'label' => esc_html__('Active', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}}'
      ]
    ]);

    $this->end_controls_section();
  }

  protected function render_post_header()
  {
    ?>
    <article <?php post_class(['swiper-slide', 'elementor-post']); ?>>
    <?php
  }

  protected function render_loop_header()
  {
    $settings = $this->parent->get_settings_for_display();

    $this->parent->add_render_attribute([
      'container' => [
        'class' => ['elementor-posts-container', 'elementor-posts', 'swiper-container', $this->get_container_class()]
      ],
      'wrapper' => [
        'class' => ['elementor-posts-wrapper', 'swiper-wrapper']
      ]
    ]);
    ?>

      <div <?php $this->parent->print_render_attribute_string('container'); ?>>
        <div <?php $this->parent->print_render_attribute_string('wrapper'); ?>>
        <?php
  }

  protected function render_loop_footer()
  {
    $settings = $this->parent->get_settings_for_display(); ?>
        </div>

        <?php if ($settings['carousel_slider_pagination']): ?>
          <div class="swiper-pagination"></div>
        <?php endif; ?>

        <?php if ($settings['carousel_slider_arrows']): ?>
          <div class="swiper-button swiper-button-prev">
            <?php \Elementor\Icons_Manager::render_icon($settings['carousel_arrows_prev'], [
              'aria-hidden' => 'true'
            ]); ?>
          </div>
          <div class="swiper-button swiper-button-next">
            <?php \Elementor\Icons_Manager::render_icon($settings['carousel_arrows_next'], [
              'aria-hidden' => 'true'
            ]); ?>
          </div>
        <?php endif; ?>

        <!--<div class="swiper-scrollbar"></div>-->
      </div>
  <?php
  }
}
