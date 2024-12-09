<?php

namespace MAPSElementor\Modules\Toggle_Text\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Toggle_Text extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-toggle-text';
  }

  public function get_title()
  {
    return __('MAPS Toggle Text', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-toggle-on';
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

  protected function register_skins()
  {
    parent::register_skins();

    $this->add_skin(new \MAPSElementor\Modules\Toggle_Text\Skins\Slide($this));
  }

  protected function register_controls()
  {
    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_control('text', [
      'label' => esc_html__('Text', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::WYSIWYG,
      'default' => esc_html__('Type your description here', 'maps-marketing'),
      'placeholder' => esc_html__('Type your description here', 'maps-marketing')
    ]);

    $this->add_control('button', [
      'label' => esc_html__('Button', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('Read More', 'maps-marketing'),
      'placeholder' => esc_html__('Read More', 'maps-marketing')
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_settings', [
      'label' => __('Settings', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_responsive_control('height', [
      'label' => __('Max Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['vh', 'px'],
      'default' => [
        'unit' => 'vh',
        'size' => 15
      ],
      'range' => [
        'vh' => [
          'max' => 100
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__content' => 'max-height: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('columns', [
      'label' => __('Columns', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 1
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__content' => 'column-count: {{SIZE}}'
      ]
    ]);

    $this->add_responsive_control('columns_gap', [
      'label' => __('Columns Gap', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'em', 'vw'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__content' => 'column-gap: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('content_spacing', [
      'label' => __('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'default' => [
        'unit' => 'px',
        'size' => 45
      ],
      'range' => [
        'px' => [
          'max' => 100
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text' => 'gap: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('content_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__content' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_button', [
      'label' => __('Button', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'button_typography',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT
      ],
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Text_Shadow::get_type(), [
      'name' => 'button_text_shadow',
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn'
    ]);

    $this->start_controls_tabs('tabs_button_style');

    $this->start_controls_tab('tab_button_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('button_text_color', [
      'label' => esc_html__('Text Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'default' => '',
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__btn' => 'fill: {{VALUE}}; color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn',
      'fields_options' => [
        'background' => [
          'default' => 'classic'
        ],
        'color' => [
          'global' => [
            'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT
          ]
        ]
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('tab_button_hover', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('button_hover_color', [
      'label' => esc_html__('Text Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__btn:hover, {{WRAPPER}} .maps-toggle-text__btn:focus' => 'color: {{VALUE}};',
        '{{WRAPPER}} .maps-toggle-text__btn:hover svg, {{WRAPPER}} .maps-toggle-text__btn:focus svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background_hover',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn:hover, {{WRAPPER}} .maps-toggle-text__btn:focus',
      'fields_options' => [
        'background' => [
          'default' => 'classic'
        ]
      ]
    ]);

    $this->add_control('button_hover_border_color', [
      'label' => esc_html__('Border Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'condition' => [
        'border_border!' => ''
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__btn:hover, {{WRAPPER}} .maps-toggle-text__btn:focus' => 'border-color: {{VALUE}};'
      ]
    ]);

    $this->add_control('hover_animation', [
      'label' => esc_html__('Hover Animation', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HOVER_ANIMATION
    ]);

    $this->end_controls_tab();
    $this->end_controls_tabs();

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border',
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn',
      'separator' => 'before'
    ]);

    $this->add_control('button_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
      'name' => 'button_box_shadow',
      'selector' => '{{WRAPPER}} .maps-toggle-text__btn'
    ]);

    $this->add_responsive_control('button_text_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em', '%'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-text__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ],
      'separator' => 'before'
    ]);

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $this->add_inline_editing_attributes('text', 'advanced');
    $this->add_inline_editing_attributes('button', 'none');
    ?>

    <div class="maps-toggle-text">
      <div class="maps-toggle-text__content">
        <div <?php $this->print_render_attribute_string('text'); ?>>
          <?php echo $settings['text']; ?>
        </div>
      </div>
      <div class="maps-toggle-text__footer">
        <button type="button" class="maps-toggle-text__btn elementor-button">
          <span <?php $this->print_render_attribute_string('button'); ?>>
            <?php echo $settings['button']; ?>
          </span>
        </button>
      </div>
    </div>
  <?php
  }

  protected function content_template()
  {
    ?>
    <# view.addInlineEditingAttributes( 'text' , 'advanced' ); #>
    <# view.addInlineEditingAttributes( 'button' , 'none' ); #>
      <div class="maps-toggle-text">
        <div class="maps-toggle-text__content">
          <div {{{ view.getRenderAttributeString( 'text' ) }}}>
            {{{ settings.text }}}
          </div>
        </div>
        <div class="maps-toggle-text__footer">
          <button type="button" class="maps-toggle-text__btn elementor-button">
            <span {{{ view.getRenderAttributeString( 'button' ) }}}>
              {{{ settings.button }}}
            </span>
          </button>
        </div>
      </div>
  <?php
  }
}
