<?php

namespace MAPSElementor\Modules\Carousel\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Carousel extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-carousel';
  }

  public function get_title()
  {
    return __('MAPS Carousel', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-images';
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
    return ['vendor-js', $this->get_name() . '-js'];
  }

  public function get_style_depends()
  {
    return [$this->get_name() . '-css'];
  }

  public function register_controls()
  {
    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->start_controls_tabs('list_tabs');

    $repeater->start_controls_tab('list_tab_content', [
      'label' => esc_html__('Content', 'maps-marketing')
    ]);

    $repeater->add_control('list_content', [
      'label' => esc_html__('Content', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::WYSIWYG,
      'default' => '',
      'placeholder' => esc_html__('Type your description here', 'maps-marketing')
    ]);

    $repeater->end_controls_tab();

    $repeater->start_controls_tab('list_tab_style', [
      'label' => esc_html__('Background', 'maps-marketing')
    ]);

    $repeater->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'list_image',
      'label' => __('Choose Image', 'maps-marketing'),
      'types' => ['classic'],
      'exclude' => ['gradient'],
      'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}'
    ]);

    $repeater->end_controls_tab();

    $repeater->end_controls_tabs();

    $this->add_control('list', [
      'label' => __('Slides', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls()
    ]);

    $this->add_control('hr', [
      'type' => \Elementor\Controls_Manager::DIVIDER
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

    $this->add_responsive_control('slider_height', [
      'label' => __('Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 500
      ],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 500
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-carousel__slides__item__image' => 'height: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('hr_3', [
      'type' => \Elementor\Controls_Manager::DIVIDER
    ]);

    $this->add_control('slider_first_wide', [
      'label' => esc_html__('Wider Active Slide?', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true'
    ]);

    $this->add_responsive_control('slider_first_slide_width', [
      'label' => __('Width', 'maps-marketing'),
      'description' => __('Sets a custom width for the active slide', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'vw'],
      'range' => [
        'vw' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'vw',
        'size' => 50
      ],
      'condition' => [
        'slider_first_wide' => 'true'
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-carousel__slides__item.swiper-slide-active' => 'min-width: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_navigation', [
      'label' => __('Navigation', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('navigation_next_icon', [
      'label' => esc_html__('Next Icon', 'plugin-name'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-arrow-right',
        'library' => 'fa-solid'
      ]
    ]);

    $this->add_control('navigation_prev_icon', [
      'label' => esc_html__('Prev Icon', 'plugin-name'),
      'type' => \Elementor\Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-arrow-left',
        'library' => 'fa-solid'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_settings', [
      'label' => __('Additional Options', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('slider_speed', [
      'label' => esc_html__('Effect Speed (ms)', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'default' => 300,
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
        'slider_autoplay' => 'true'
      ],
      'frontend_available' => true
    ]);

    $this->add_control('slider_loop', [
      'label' => esc_html__('Infinite Loop', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'true',
      'default' => 'true',
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

    $this->add_control('slider_direction', [
      'label' => esc_html__('Direction', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => 'horizontal',
      'options' => [
        'horizontal' => esc_html__('horizontal', 'maps-marketing'),
        'vertical' => esc_html__('vertical', 'maps-marketing')
      ],
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_container_height', [
      'label' => __('Container Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'vh',
        'size' => 100
      ],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 500
        ]
      ],
      'condition' => [
        'slider_direction' => 'vertical'
      ],
      'selectors' => [
        '{{WRAPPER}} .swiper-container' => 'height: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('hr_2', [
      'type' => \Elementor\Controls_Manager::DIVIDER
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
        'slider_pagination' => 'true'
      ],
      'frontend_available' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('style_content_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em'],
      'selectors' => [
        '{{WRAPPER}} .maps-carousel__slides__item__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'style_content_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-carousel__slides__item__content'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_navigation', [
      'label' => __('Navigation', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('style_navigation_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em'],
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('style_navigation_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 16
      ],
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'font-size: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->start_controls_tabs('style_navigation_tabs');

    $this->start_controls_tab('style_navigation_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('style_navigation_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'style_navigation_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .swiper-button'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'style_navigation_border',
      'label' => esc_html__('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .swiper-button'
    ]);

    $this->add_responsive_control('style_navigation_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em'],
      'selectors' => [
        '{{WRAPPER}} .swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('style_navigation_active_tab', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_control('style_navigation_color_active', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-button:hover' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'style_navigation_background_active',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .swiper-button:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'style_navigation_border_active',
      'label' => esc_html__('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .swiper-button:hover'
    ]);

    $this->add_responsive_control('style_navigation_border_radius_active', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em'],
      'selectors' => [
        '{{WRAPPER}} .swiper-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_pagination', [
      'label' => __('Pagination', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('style_pagination_space', [
      'label' => __('Space', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%'],
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->start_controls_tabs('style_pagination_tabs');

    $this->start_controls_tab('style_pagination_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('style_pagination_normal_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}}'
      ]
    ]);

    $this->add_responsive_control('style_pagination_normal_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'rem', 'em'],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('style_pagination_active_tab', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_control('style_pagination_active_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}}'
      ]
    ]);

    $this->add_control('style_pagination_active_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'rem', 'em'],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $this->add_render_attribute([
      'container' => [
        'class' => ['maps-carousel', 'swiper-container']
      ],
      'wrapper' => [
        'class' => ['maps-carousel__slides', 'swiper-wrapper']
      ]
    ]);
    ?>

    <div <?php $this->print_render_attribute_string('container'); ?>>
      <div <?php $this->print_render_attribute_string('wrapper'); ?>>

        <?php foreach ($settings['list'] as $index => $item):

          $content_setting_key = $this->get_repeater_setting_key('list_content', 'list', $index);

          // $this->add_render_attribute($content_setting_key, 'class', ['maps-carousel__slides__item__content']);
          $this->add_render_attribute([
            'slides_item' => [
              'class' => ['maps-carousel__slides__item', 'swiper-slide', "swiper-slide--{$item['_id']}"]
            ],
            'slides_item_image' => [
              'class' => ['maps-carousel__slides__item__image', "elementor-repeater-item-{$item['_id']}"]
            ],
            $content_setting_key => [
              'class' => ['maps-carousel__slides__item__content']
            ]
          ]);
          ?>

          <div <?php $this->print_render_attribute_string('slides_item'); ?>>
            <div <?php $this->print_render_attribute_string('slides_item_image'); ?>></div>

            <?php if (!empty($item['list_content'])): ?>
              <div <?php $this->print_render_attribute_string($content_setting_key); ?>>
                <?php echo $item['list_content']; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php
        endforeach; ?>

      </div>

      <?php if ($settings['slider_pagination']): ?>
        <div class="swiper-pagination"></div>
      <?php endif; ?>

      <?php if ($settings['slider_arrows']): ?>
        <div class="swiper-button swiper-button-prev">
          <?php \Elementor\Icons_Manager::render_icon($settings['navigation_prev_icon'], [
            'aria-hidden' => 'true'
          ]); ?>
        </div>
        <div class="swiper-button swiper-button-next">
          <?php \Elementor\Icons_Manager::render_icon($settings['navigation_next_icon'], [
            'aria-hidden' => 'true'
          ]); ?>
        </div>
      <?php endif; ?>
    </div>
    <?php
  }
}
