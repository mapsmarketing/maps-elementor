<?php

namespace MAPSElementor\Modules\Slider_Tabs\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Slider_Tabs extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-slider-tabs';
  }

  public function get_title()
  {
    return __('MAPS Slider Tabs', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-stream';
  }

  public function get_categories()
  {
    return ['maps-marketing'];
  }

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    // wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    // wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend', 'vendor-js'], uniqid(), true);
  
    add_action('elementor/frontend/after_enqueue_scripts', [ $this, '_enqueue_scripts' ]);
  }

  public function _enqueue_scripts() {
    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend','vendor-js'], uniqid(), true);
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
    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->start_controls_tabs('list_tabs');

    $repeater->start_controls_tab('list_tab_content', [
      'label' => esc_html__('Content', 'maps-marketing')
    ]);

    $repeater->add_control('list_title', [
      'label' => __('Title', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => __('List Title', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_content', [
      'label' => __('Content', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::WYSIWYG,
      'default' => __('List Content', 'maps-marketing'),
      'show_label' => false
    ]);

    $repeater->add_control('list_button_text', [
      'label' => __('Button Text', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => __('Link Here', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_button_link', [
      'label' => __('Button Link', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::URL,
      'placeholder' => __('https://your-link.com', 'maps-marketing'),
      'show_external' => true,
      'default' => [
        'url' => '',
        'is_external' => false,
        'nofollow' => false
      ]
    ]);

    $repeater->end_controls_tab();

    $repeater->start_controls_tab('list_tab_style', [
      'label' => esc_html__('Style', 'maps-marketing')
    ]);

    $repeater->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'list_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}'
    ]);

    $repeater->end_controls_tab();

    $repeater->end_controls_tabs();

    $this->add_control('list', [
      'label' => __('Slides', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'default' => [
        [
          'list_title' => __('Title #1', 'maps-marketing'),
          'list_content' => __('Item content. Click the edit button to change this text.', 'maps-marketing'),
          'list_button_text' => __('Link Here', 'maps-marketing'),
          'list_button_link' => '#'
        ],
        [
          'list_title' => __('Title #2', 'maps-marketing'),
          'list_content' => __('Item content. Click the edit button to change this text.', 'maps-marketing'),
          'list_button_text' => __('Link Here', 'maps-marketing'),
          'list_button_link' => '#'
        ]
      ],
      'title_field' => '{{{ list_title }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_settings', [
      'label' => __('Settings', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_responsive_control('slider_height', [
      'label' => __('Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 700
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__images__slides__item' => 'height: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__image' => 'height: {{SIZE}}{{UNIT}}'
      ],
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_slidestoshow', [
      'label' => __('Slides to Show', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'min' => 1,
      'default' => 4,
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_slidestoscroll', [
      'label' => __('Slides to Scroll', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'min' => 1,
      'default' => 4,
      'frontend_available' => true
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

    $this->add_control('slider_autoplay', [
      'label' => esc_html__('Autoplay', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'yes',
      'default' => 'no',
      'frontend_available' => true
    ]);

    $this->add_control('slider_interval', [
      'label' => esc_html__('Autoplay Interval (ms)', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'default' => 3000,
      'frontend_available' => true
    ]);

    $this->add_control('slider_loop', [
      'label' => esc_html__('Loop', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Yes', 'maps-marketing'),
      'label_off' => esc_html__('No', 'maps-marketing'),
      'return_value' => 'yes',
      'default' => 'no',
      'frontend_available' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_tabs', [
      'label' => __('Tabs', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('tabs_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'tab_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides'
    ]);

    $this->start_controls_tabs('title_tabs');

    $this->start_controls_tab('title_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title'
    ]);

    $this->add_control('title_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'title_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('title_active_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title_active',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title:hover'
    ]);

    $this->add_control('title_colour_active', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__title:hover' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'title_background_active',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '
				    {{WRAPPER}} .maps-slider-tabs__nav__slides__item__title:hover,
				    {{WRAPPER}} .maps-slider-tabs__nav__slides__item.swiper-slide-thumb-active .maps-slider-tabs__nav__slides__item__title
				'
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'content_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab'
    ]);

    $this->add_responsive_control('content_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('content_spacing', [
      'label' => __('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'em', 'rem'],
      'default' => [
        'unit' => 'px',
        'size' => 20
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab' => 'gap: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('content_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_control('content_title', [
      'label' => esc_html__('Title', 'textdomain'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'content_title',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__title'
    ]);

    $this->add_control('content_title_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__title' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_control('content_description', [
      'label' => esc_html__('Description', 'textdomain'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'content',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__content'
    ]);

    $this->add_control('content_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__content' => 'color: {{VALUE}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_button', [
      'label' => __('Button', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'button_text',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn'
    ]);

    $this->start_controls_tabs('tabs_button_style');

    $this->start_controls_tab('style_button_state_normal', [
      'label' => __('Normal', 'maps-marketing')
    ]);

    $this->add_control('button_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn'
    ]);

    $this->add_responsive_control('button_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('style_button_state_hover', [
      'label' => __('Hover', 'maps-marketing')
    ]);

    $this->add_control('button_colour_hover', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background_hover',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border_hover',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn:hover'
    ]);

    $this->add_control('button_border_radius_hover', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__slides__item__tab__btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $autoplay = $settings['slider_autoplay'] ? $settings['slider_autoplay'] : 'false';

    $this->add_render_attribute([
      'maps-slider-tabs-images' => [
        'class' => ['maps-slider-tabs__images', 'swiper-container']
      ],
      'maps-slider-tabs-images-slides' => [
        'class' => ['maps-slider-tabs__images__slides', 'swiper-wrapper']
      ],
      'maps-slider-tabs-nav' => [
        'class' => ['maps-slider-tabs__nav', 'swiper-container']
      ],
      'maps-slider-tabs-nav-slides' => [
        'class' => ['maps-slider-tabs__nav__slides', 'swiper-wrapper']
      ]
    ]);

    if ($settings['list']): ?>
      <div class="maps-slider-tabs">
        <div <?php $this->print_render_attribute_string('maps-slider-tabs-images'); ?>>
          <div <?php $this->print_render_attribute_string('maps-slider-tabs-images-slides'); ?>>

            <?php foreach ($settings['list'] as $item): ?>
              <div class="maps-slider-tabs__images__slides__item swiper-slide elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>"></div>
            <?php endforeach; ?>

          </div>
        </div>

        <div <?php $this->print_render_attribute_string('maps-slider-tabs-nav'); ?>>
          <div <?php $this->print_render_attribute_string('maps-slider-tabs-nav-slides'); ?>>

            <?php foreach ($settings['list'] as $index => $item):

              $list_title_key = $this->get_repeater_setting_key('list_title', 'list', $index);
              $list_content_key = $this->get_repeater_setting_key('list_content', 'list', $index);
              $list_button_text_key = $this->get_repeater_setting_key('list_button_text', 'list', $index);

              $this->add_render_attribute([
                $list_title_key => [
                  'class' => ['maps-slider-tabs__nav__slides__item__tab__title']
                ],
                $list_content_key => [
                  'class' => ['maps-slider-tabs__nav__slides__item__tab__content']
                ],
                $list_button_text_key => [
                  'href' => $item['list_button_link']['url'],
                  'class' => ['maps-slider-tabs__nav__slides__item__tab__btn', 'elementor-button'],
                  'target' => $item['list_button_link']['is_external'] ? '_blank' : '',
                  'nofollow' => $item['list_button_link']['nofollow'] ? 'nofollow' : ''
                ]
              ]);

              $this->add_inline_editing_attributes($list_title_key, 'none');
              $this->add_inline_editing_attributes($list_content_key, 'advanced');
              $this->add_inline_editing_attributes($list_button_text_key, 'none');
              ?>
              <div class="maps-slider-tabs__nav__slides__item swiper-slide">
                <div class="maps-slider-tabs__nav__slides__item__title"><?php echo $item['list_title']; ?></div>
                <div class="maps-slider-tabs__nav__slides__item__tab">
                  <h3 <?php $this->print_render_attribute_string($list_title_key); ?>>
                    <?php echo $item['list_title']; ?>
                  </h3>
                  <div <?php $this->print_render_attribute_string($list_content_key); ?>>
                    <?php echo $item['list_content']; ?>
                  </div>

                  <?php if ($item['list_button_text'] and $item['list_button_link']): ?>
                    <a <?php $this->print_render_attribute_string($list_button_text_key); ?>>
                      <?php echo $item['list_button_text']; ?>
                    </a>
                  <?php endif; ?>

                  <div class="maps-slider-tabs__nav__slides__item__tab__image elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>"></div>
                </div>
              </div>
            <?php
            endforeach; ?>

          </div>
        </div>
      </div>
    <?php endif;
  }

  /*protected function content_template()
  {
    ?>
    <# if ( settings.list ) { #>
      <div class="maps-slider-tabs">
        <div class="maps-slider-tabs__images swiper-container">
          <div class="maps-slider-tabs__images__slides swiper-wrapper">
            <# _.each( settings.list, function( item, index ) { #>
              <div class="maps-slider-tabs__images__slides__item swiper-slide elementor-repeater-item-{{ index }}"></div>
            <# } ); #>
          </div>
        </div>

        <div class="maps-slider-tabs__nav swiper-container">
          <div class="maps-slider-tabs__nav__slides swiper-wrapper">
            <# _.each( settings.list, function( item, index ) {
                var listTitleKey = view.getRepeaterSettingKey( 'list_title', 'list', index );
                var listContentKey = view.getRepeaterSettingKey( 'list_content', 'list', index );
                var listButtonTextKey = view.getRepeaterSettingKey( 'list_button_text', 'list', index );

                view.addRenderAttribute( listTitleKey, {
                    'class': [ 'maps-slider-tabs__nav__slides__item__tab__title' ],
                } );
                view.addRenderAttribute( listContentKey, {
                    'class': [ 'maps-slider-tabs__nav__slides__item__tab__content' ],
                } );
                view.addRenderAttribute( listButtonTextKey, {
                    'href': item.list_button_link.url,
                    'class': [ 'maps-slider-tabs__nav__slides__item__tab__btn', 'elementor-button' ],
                    'target': item.list_button_link.is_external ? '_blank' : '',
                    'nofollow': item.list_button_link.nofollow ? 'nofollow' : '',
                } );

                view.addInlineEditingAttributes( listTitleKey, 'none' );
                view.addInlineEditingAttributes( listContentKey, 'advanced' );
                view.addInlineEditingAttributes( listButtonTextKey, 'none' );
            #>
              <div class="maps-slider-tabs__nav__slides__item swiper-slide">
                <div class="maps-slider-tabs__nav__slides__item__title" {{{ view.getRenderAttributeString( listTitleKey ) }}}>
                  {{{ item.list_title }}}
                </div>
                <div class="maps-slider-tabs__nav__slides__item__tab">
                  <h3 {{{ view.getRenderAttributeString( listTitleKey ) }}}>
                    {{{ item.list_title }}}
                  </h3>
                  <div {{{ view.getRenderAttributeString( listContentKey ) }}}>
                    {{{ item.list_content }}}
                  </div>

                  <# if ( item.list_button_text && item.list_button_link ) { #>
                    <a {{{ view.getRenderAttributeString( listButtonTextKey ) }}}>
                      {{{ item.list_button_text }}}
                    </a>
                  <# } #>

                  <div class="maps-slider-tabs__nav__slides__item__tab__image elementor-repeater-item-{{ index }}"></div>
                </div>
              </div>
            <# } ); #>
          </div>
        </div>
      </div>
    <# } #>
    <?php
  }*/
}
