<?php

namespace MAPSElementor\Modules\CTA_Accordion\Skins;

use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Horizontal extends Skin_Base
{
  protected function _register_controls_actions()
  {
    parent::_register_controls_actions();

    $widget_name = $this->parent->get_name();

    add_action('elementor/element/' . $widget_name . '/style_heading/after_section_start', [$this, 'style_heading_after_section_start']);
    add_action('elementor/element/' . $widget_name . '/style_heading/before_section_end', [$this, 'style_heading_before_section_end']);
    add_action('elementor/element/' . $widget_name . '/style_content/before_section_end', [$this, 'register_controls_style_content']);
  }

  public function get_id()
  {
    return 'maps-cta-accordion-horizontal-skin';
  }

  public function get_title()
  {
    return __('Horizontal', 'maps-marketing');
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

  public function style_heading_after_section_start($widget)
  {
    $this->parent = $widget;

    $this->add_control('heading_title', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'type' => Controls_Manager::HEADING
    ]);
  }

  public function style_heading_before_section_end(Widget_Base $widget)
  {
    $this->parent = $widget;

    $this->add_control('heading_subtitle', [
      'label' => esc_html__('Sub title', 'maps-marketing'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'subtitle',
      'global' => [
        'default' => Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__title__text > span'
    ]);

    $this->add_control('subtitle_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__title__text > span' => 'color: {{VALUE}}'
      ]
    ]);
  }

  public function register_controls_style_content(Widget_Base $widget)
  {
    $this->parent = $widget;

    $this->add_responsive_control('width', [
      'label' => __('Width', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'vw', 'custom'],
      'range' => [
        'px' => [
          'max' => 1000
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 600
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content' => 'width: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('heading_content_title', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'content_title',
      'global' => [
        'default' => Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__title'
    ]);
  }

  public function render()
  {
    $settings = $this->parent->get_settings_for_display();

    if ($settings['list']): ?>
      <div class="maps-cta-accordions maps-cta-accordions--horizontal">

        <?php foreach ($settings['list'] as $index => $item):

          $list_website_key = "list.{$index}.list_website";

          if (!empty($item['list_website']['url'])) {
            $this->parent->add_link_attributes($list_website_key, $item['list_website']);
          }

          $this->parent->add_render_attribute([
            $list_website_key => [
              'class' => 'maps-cta-accordions__slide__content__link'
            ]
          ]);
          ?>
          <div class="maps-cta-accordions__slide elementor-repeater-item-<?php echo $item['_id']; ?>">
            <div class="maps-cta-accordions__slide__title">
              <div class="maps-cta-accordions__slide__title__text">
                <?php echo $item['list_title']; ?>
                <span><?php echo $item['list_sub_title']; ?></span>
              </div>
              <div class="maps-cta-accordions__slide__title__icon">
                <?php Icons_Manager::render_icon($item['list_icon'], [
                  'aria-hidden' => 'true'
                ]); ?>
              </div>
            </div>
            <div class="maps-cta-accordions__slide__content">
              <div class="maps-cta-accordions__slide__content__icon">
                <?php Icons_Manager::render_icon($item['list_icon'], [
                  'aria-hidden' => 'true'
                ]); ?>
              </div>
              <h3 class="maps-cta-accordions__slide__content__title">
                <?php echo $item['list_title']; ?>
              </h3>
              <div class="maps-cta-accordions__slide__content__text">
                <?php echo $item['list_content']; ?>
              </div>

              <?php if (!empty($item['list_website']['url'])): ?>
                <a <?php $this->parent->print_render_attribute_string($list_website_key); ?>>
                  <?php _e('Learn More', 'maps-marketing'); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php
        endforeach; ?>

      </div>
    <?php endif;
  }
}
