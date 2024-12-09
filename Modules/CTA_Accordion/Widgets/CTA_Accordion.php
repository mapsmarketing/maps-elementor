<?php

namespace MAPSElementor\Modules\CTA_Accordion\Widgets;

use MAPSElementor\Modules\CTA_Accordion\Skins;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class CTA_Accordion extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-cta-accordion';
  }

  public function get_title()
  {
    return __('MAPS CTA Accordion', 'maps-marketing');
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
    // parent::register_skins();

    $this->add_skin(new Skins\Horizontal($this));
  }

  protected function register_controls()
  {
    // parent::register_controls();

    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new Repeater();

    $repeater->start_controls_tabs('content_tabs');

    $repeater->start_controls_tab('content_tab', [
      'label' => esc_html__('Content', 'maps-marketing')
    ]);

    $repeater->add_control('list_icon', [
      'label' => __('Icon', 'text-domain'),
      'type' => Controls_Manager::ICONS,
      'default' => [
        'value' => 'fas fa-star',
        'library' => 'solid'
      ]
    ]);

    $repeater->add_control('list_title', [
      'label' => __('Title', 'maps-marketing'),
      'type' => Controls_Manager::TEXT,
      'default' => __('List Title', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_sub_title', [
      'label' => __('Sub Title', 'maps-marketing'),
      'type' => Controls_Manager::TEXT,
      'default' => __('List Sub Title', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_content', [
      'label' => __('Content', 'maps-marketing'),
      'type' => Controls_Manager::WYSIWYG,
      'default' => __('List Content', 'maps-marketing'),
      'show_label' => false
    ]);

    $repeater->add_control('list_website', [
      'label' => __('Link', 'maps-marketing'),
      'type' => Controls_Manager::URL,
      'placeholder' => __('https://your-link.com', 'maps-marketing'),
      'show_external' => true,
      'default' => [
        'url' => '',
        'is_external' => false,
        'nofollow' => false
      ]
    ]);

    $repeater->end_controls_tab();

    $repeater->start_controls_tab('background_tab', [
      'label' => esc_html__('Background', 'maps-marketing')
    ]);

    $repeater->add_control('heading_default', [
      'label' => esc_html__('Default', 'plugin-name'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $repeater->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'list_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}'
    ]);

    $repeater->add_control('heading_hover', [
      'label' => esc_html__('Alt', 'plugin-name'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $repeater->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'list_background_hover',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-cta-accordions--default {{CURRENT_ITEM}}:hover, {{WRAPPER}} .maps-cta-accordions--horizontal {{CURRENT_ITEM}}:not(.on)'
    ]);

    $repeater->end_controls_tab();

    $repeater->end_controls_tabs();

    $this->add_control('list', [
      'label' => __('Accordions', 'maps-marketing'),
      'type' => Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'default' => [
        [
          'list_title' => __('Title #1', 'maps-marketing'),
          'list_content' => __('Item content. Click the edit button to change this text.', 'maps-marketing')
        ],
        [
          'list_title' => __('Title #2', 'maps-marketing'),
          'list_content' => __('Item content. Click the edit button to change this text.', 'maps-marketing')
        ]
      ],
      'title_field' => '{{{ list_title }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_settings', [
      'label' => __('Settings', 'elementor'),
      'tab' => Controls_Manager::TAB_CONTENT
    ]);

    $this->add_responsive_control('height', [
      'label' => __('Height', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', '%'],
      'default' => [
        'unit' => 'px',
        'size' => 600
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions--default .maps-cta-accordions__slide' => 'height: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal' => 'height: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_responsive_control('display', [
      'label' => __('Display', 'maps-marketing'),
      'type' => Controls_Manager::CHOOSE,
      'options' => [
        'flex' => [
          'title' => __('Flex', 'maps-marketing'),
          'icon' => 'eicon-menu-bar'
        ],
        'grid' => [
          'title' => __('Grid', 'maps-marketing'),
          'icon' => 'eicon-apps'
        ]
      ],
      'default' => 'flex',
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions' => 'display: {{VALUE}};'
      ],
      // 'responsive' => true,
      'condition' => [
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ]
    ]);

    $start = is_rtl() ? 'right' : 'left';
    $end = is_rtl() ? 'left' : 'right';

    $this->add_responsive_control('direction', [
      'label' => __('Direction', 'maps-marketing'),
      'type' => Controls_Manager::CHOOSE,
      'options' => [
        'row' => [
          'title' => __('Row - horizontal', 'maps-marketing'),
          'icon' => 'eicon-arrow-' . $end
        ],
        'column' => [
          'title' => __('Column - vertical', 'maps-marketing'),
          'icon' => 'eicon-arrow-down'
        ]
      ],
      'default' => '',
      'selectors_dictionary' => [
        'row' => 'flex-direction: row;',
        'column' => 'flex-direction: column;'
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions' => '{{VALUE}};'
      ],
      'condition' => [
        'display' => 'flex',
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ]
    ]);

    $this->add_responsive_control('grid_width', [
      'label' => __('Width', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 1000
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 240
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions' => 'grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, 1fr));'
      ],
      'condition' => [
        'display' => 'grid',
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ]
    ]);

    $this->add_responsive_control('flex_width', [
      'label' => __('Columns', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'range' => [
        'px' => [
          'min' => 1,
          'max' => 12
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 1
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide' => 'width: calc(100% / {{SIZE}});'
      ],
      'condition' => [
        'display' => 'flex',
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ]
    ]);

    $this->add_responsive_control('gap', [
      'label' => __('Gaps', 'maps-marketing'),
      'type' => Controls_Manager::GAPS,
      'size_units' => ['px', '%', 'em', 'rem', 'vm', 'custom'],
      'default' => [
        'unit' => 'px',
        'size' => 15
      ],
      'separator' => 'before',
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions' => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}};'
      ],
      'condition' => [
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ]
    ]);

    // $this->add_responsive_control('gap', [
    //   'label' => __('Gap', 'maps-marketing'),
    //   'type' => Controls_Manager::SLIDER,
    //   'size_units' => ['px', '%'],
    //   'default' => [
    //     'unit' => 'px',
    //     'size' => 32
    //   ],
    //   'condition' => [
    //     '_skin!' => 'maps-cta-accordion-horizontal-skin'
    //   ],
    //   'selectors' => [
    //     '{{WRAPPER}} .maps-cta-accordions--default' => 'gap: {{SIZE}}{{UNIT}}',
    //     '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide' => 'gap: {{SIZE}}{{UNIT}}'
    //   ]
    // ]);

    $this->end_controls_section();

    $this->start_controls_section('style_icon', [
      'label' => __('Icon', 'elementor'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('icon_size', [
      'label' => __('Size', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['em', 'rem', 'px', '%'],
      'default' => [
        'unit' => 'px',
        'size' => 32
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__icon' => 'font-size: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__title__icon' => 'font-size: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__title__icon svg' => 'width: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon' => 'font-size: {{SIZE}}{{UNIT}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon svg' => 'width: {{SIZE}}{{UNIT}}'
      ]
    ]);

    $this->add_control('icon_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__icon i' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__title__icon i' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__icon svg' => 'fill: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__title__icon svg' => 'fill: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon i' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon i' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon svg' => 'fill: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions__slide__content__icon svg' => 'fill: {{VALUE}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_heading', [
      'label' => __('Headings', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} h3.maps-cta-accordions__slide__title, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__title__text'
    ]);

    $this->add_control('title_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} h3.maps-cta-accordions__slide__title' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__title__text' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__title' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_control('title_underline_colour', [
      'label' => __('Underline Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'condition' => [
        '_skin!' => 'maps-cta-accordion-horizontal-skin'
      ],
      'selectors' => [
        '{{WRAPPER}} h3.maps-cta-accordions__slide__title:after' => 'border-color: {{VALUE}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'content',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__description, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content'
    ]);

    $this->add_control('content_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__description' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_button', [
      'label' => __('Button', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('button_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__btn, {{WRAPPER}} .maps-cta-accordions__slide__content__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('tabs_button_style');

    $this->start_controls_tab('tab_button', [
      'label' => __('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'button',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link'
    ]);

    $this->add_control('button_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__btn' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('tab_button_hover', [
      'label' => __('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'button_hover',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link'
    ]);

    $this->add_control('button_colour_hover', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-cta-accordions__slide__btn:hover' => 'color: {{VALUE}}',
        '{{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background_hover',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn:hover, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border_hover',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-cta-accordions__slide__btn:hover, {{WRAPPER}} .maps-cta-accordions--horizontal .maps-cta-accordions__slide__content__link:hover'
    ]);

    $this->end_controls_tab();

    $this->end_controls_section();
  }

  public function render()
  {
    $settings = $this->get_settings_for_display();

    $accordion_classes = ['maps-cta-accordions'];

    $accordion_classes[] = isset($settings['skin']) && $settings['skin'] === 'maps-cta-accordion-horizontal-skin' ? 'maps-cta-accordions--horizontal' : 'maps-cta-accordions--default';
    $accordion_classes[] = isset($settings['wrap']) && $settings['wrap'] === 'wrap' ? 'maps-cta-accordions--wrap' : 'maps-cta-accordions--nowrap';

    $this->add_render_attribute('accordion', [
      'class' => $accordion_classes
    ]);

    $this->add_render_attribute('list', [
      'class' => ['maps-cta-accordions__slide']
    ]);

    $this->add_render_attribute('list_website', [
      'class' => ['maps-cta-accordions__slide__container']
    ]);

    $this->add_render_attribute('list_icon', [
      'class' => ['maps-cta-accordions__slide__icon']
    ]);
    ?>

      <?php if ($settings['list']): ?>
        <div <?php $this->print_render_attribute_string('accordion'); ?>>

          <?php foreach ($settings['list'] as $item):
            if (!empty($item['list_website']['url'])) {
              $this->add_link_attributes('list_website_' . $item['_id'], $item['list_website']);
            } ?>
            <a class="maps-cta-accordions__slide elementor-repeater-item-<?php echo $item['_id']; ?>" <?php $this->print_render_attribute_string('list_website_' . $item['_id']); ?>>
              <div class="maps-cta-accordions__slide__container">
                <div class="maps-cta-accordions__slide__icon">
                  <?php Icons_Manager::render_icon($item['list_icon'], ['aria-hidden' => 'true']); ?>
                </div>
                <h3 class="maps-cta-accordions__slide__title">
                  <?php echo $item['list_title']; ?>
                </h3>
                <div class="maps-cta-accordions__slide__description">
                  <?php echo $item['list_content']; ?>
                </div>
                <div class="maps-cta-accordions__slide__btn">Read More</div>
              </div>
            </a>
          <?php
          endforeach; ?>

        </div>
      <?php endif;
  }
}
