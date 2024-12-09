<?php

namespace MAPSElementor\Modules\Toggle_Timeline\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Toggle_Timeline extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-toggle-timeline';
  }

  public function get_title()
  {
    return __('MAPS Toggle Timeline', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-list';
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

  protected function register_controls()
  {
    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->start_controls_tabs('content_tabs');

    $repeater->start_controls_tab('content_text_tab', [
      'label' => esc_html__('Text', 'maps-marketing')
    ]);

    $repeater->add_control('list_title', [
      'label' => __('Title', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => __('List Title', 'maps-marketing')
    ]);

    $repeater->add_control('list_content', [
      'label' => __('Content', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::WYSIWYG,
      'default' => __('List Content', 'maps-marketing')
    ]);

    $repeater->add_control('list_button_text', [
      'label' => __('Button Text', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => __('Read More', 'maps-marketing')
    ]);

    $repeater->add_control('list_button_link', [
      'label' => esc_html__('Button Link', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::URL,
      'placeholder' => esc_html__('https://your-link.com', 'maps-marketing'),
      'default' => [
        'url' => '',
        'custom_attributes' => ''
      ]
    ]);

    $repeater->end_controls_tab();

    $repeater->start_controls_tab('content_background_tab', [
      'label' => esc_html__('Background', 'maps-marketing')
    ]);

    $repeater->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'list_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic'],
      'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .maps-toggle-timeline__items__item__image'
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
          'list_content' => __('Item content. Click the edit button to change this text.', 'maps-marketing')
        ],
        [
          'list_title' => __('Title #2', 'maps-marketing'),
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
      'label' => __('Settings', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_responsive_control('show_read_more', [
      'label' => esc_html__('Read More', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Show', 'maps-marketing'),
      'label_off' => esc_html__('Hide', 'maps-marketing'),
      'return_value' => 'yes',
      'default' => ''
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_menu', [
      'label' => __('Menu', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('menu_align', [
      'label' => esc_html__('Alignment', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::CHOOSE,
      'options' => [
        'row' => [
          'title' => esc_html__('Left', 'maps-marketing'),
          'icon' => 'eicon-text-align-left'
        ],
        'row-reverse' => [
          'title' => esc_html__('Right', 'maps-marketing'),
          'icon' => 'eicon-text-align-right'
        ]
      ],
      'default' => 'row',
      'toggle' => true,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline' => 'flex-direction: {{VALUE}};'
      ]
    ]);

    $this->add_responsive_control('menu_width', [
      'label' => __('Width', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 600
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs' => 'width: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('menu_top_spacing', [
      'label' => __('Top Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 160
      ],
      'frontend_available' => true
    ]);

    $this->add_responsive_control('menu_margin', [
      'label' => esc_html__('Margin', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'default' => ['80px', '0', '0', '0'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('menu_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'default' => ['0', '45px', '0', '90px'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_menu_item', [
      'label' => __('Menu Item', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('menu_item_spacing', [
      'label' => __('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 45
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__wrapper' => 'gap: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .maps-toggle-timeline__tabs__wrapper > .inner-wrapper-sticky' => 'gap: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'menu_item',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item a'
    ]);

    $this->start_controls_tabs('menus');

    $this->start_controls_tab('menu_item_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('menu_item_colour', [
      'label' => esc_html__('Text Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // menu_normal

    $this->start_controls_tab('menu_item_active', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_control('menu_item_colour_active', [
      'label' => esc_html__('Text Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item.on a' => 'color: {{VALUE}}'
      ]
    ]);

    $this->end_controls_tab(); // menu_active
    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('gap', [
      'label' => __('Gap', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'custom'],
      'default' => [
        'unit' => 'px',
        'size' => 40
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline' => 'gap: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'content_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
      'name' => 'content_box_shadow',
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item'
    ]);

    $this->add_responsive_control('content_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'default' => ['20px', 0, 0, 0],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_dots', [
      'label' => __('Dots', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('dots_size', [
      'label' => __('Size', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 37
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after' => 'left: calc(({{SIZE}}{{UNIT}} / 2) - ({{lines_width.size}}px / 2));'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'dots_line_background',
      'label' => 'Line',
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after'
    ]);

    $this->start_controls_tabs('dots');

    $this->start_controls_tab('dots_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'dots_background',
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item:before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'dots_border',
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item:before'
    ]);

    $this->end_controls_tab(); // dots_normal

    $this->start_controls_tab('dots_active', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'dots_background_active',
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item.on:before'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'dots_border_active',
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item.on:before'
    ]);

    $this->end_controls_tab(); // dots_active
    $this->end_controls_tabs();

    $this->add_control('heading_lines', [
      'label' => esc_html__('Lines', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::HEADING,
      'separator' => 'before'
    ]);

    $this->add_responsive_control('lines_width', [
      'label' => __('Width', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 3
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('lines_position', [
      'label' => esc_html__('Position', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
      'default' => ['8px', 0, 0, '-69px'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'lines_background',
      'types' => ['classic'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__tabs__item:not(:last-child):after'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_image', [
      'label' => __('Image', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('image_height', [
      'label' => __('Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 500
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item__image' => 'min-height: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_read', [
      'label' => __('Read More', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      'condition' => [
        'show_read_more' => 'yes'
      ]
    ]);

    $this->add_responsive_control('read_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'default' => ['12px', '0', '12px', '0'],
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('read');

    $this->start_controls_tab('read_normal', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_control('read_colour', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'read_background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'read_border',
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle'
    ]);

    $this->end_controls_tab(); // read_normal

    $this->start_controls_tab('read_hover', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_control('read_colour_hover', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_control('menu_item_colour_hover', [
      'label' => esc_html__('Text Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'read_background_hover',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'read_border_hover',
      'selector' => '{{WRAPPER}} .maps-toggle-timeline__items__item__toggle:hover'
    ]);

    $this->end_controls_tab(); // read_active
    $this->end_controls_tabs();

    $this->end_controls_section();
  }

  public function render()
  {
    $settings = $this->get_settings_for_display(); ?>

    <div class="maps-toggle-timeline">
      <div class="maps-toggle-timeline__tabs">
        <div class="maps-toggle-timeline__tabs__wrapper">

          <?php foreach ($settings['list'] as $index => $item):

            $list_title_key = $this->get_repeater_setting_key('list_title', 'list', $index);

            $this->add_render_attribute($list_title_key, 'href', "#maps-toggle-timeline__items__item--$item[_id]");
            $this->add_inline_editing_attributes($list_title_key, 'none');
            ?>
            <div class="maps-toggle-timeline__tabs__item elementor-repeater-item-<?php echo $item['_id']; ?>">
              <a <?php $this->print_render_attribute_string($list_title_key); ?>>
                <?php echo $item['list_title']; ?>
              </a>
            </div>
          <?php
          endforeach; ?>

        </div>
      </div>
      <div class="maps-toggle-timeline__items">

        <?php foreach ($settings['list'] as $index => $item):

          $list_content_key = $this->get_repeater_setting_key('list_content', 'list', $index);
          $itemKey = $this->get_repeater_setting_key('maps-toggle-timeline__items__item', 'list', $index);
          $itemContentKey = $this->get_repeater_setting_key('maps-toggle-timeline__items__item__content', 'list', $index);

          $this->add_render_attribute([
            $itemKey => [
              'id' => "maps-toggle-timeline__items__item--{$item['_id']}",
              'class' => ['maps-toggle-timeline__items__item', "elementor-repeater-item-{$item['_id']}"]
            ],
            $itemContentKey => [
              'class' => ['maps-toggle-timeline__items__item__content', 'yes' === $settings['show_read_more'] ? 'read-more-yes' : 'read-more-no']
            ],
            $list_content_key => [
              'class' => ['maps-toggle-timeline__items__item__content__wrapper']
            ]
          ]);

          $this->add_inline_editing_attributes($list_content_key, 'advanced');
          ?>
          <div <?php $this->print_render_attribute_string($itemKey); ?>>
            <div <?php $this->print_render_attribute_string($itemContentKey); ?>>
              <div <?php $this->print_render_attribute_string($list_content_key); ?>>
                <?php echo $item['list_content']; ?>
              </div>
              <div class="maps-toggle-timeline__items__item__content__btn">
                <a href="#" class="maps-toggle-timeline__items__item__toggle" role="button">
                  <?php _e('Read more', 'maps-marketing'); ?>
                </a>

                <?php if (!empty($item['list_button_link']['url'])): ?>
                  <a href="<?php echo $item['list_button_link']['url']; ?>" class="maps-toggle-timeline__items__item__btn elementor-button">
                    <?php echo $item['list_button_text']; ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="maps-toggle-timeline__items__item__image"></div>
          </div>
        <?php
        endforeach; ?>

      </div>
    </div>
    <?php
  }

  /*protected function content_template()
  {
    ?>
    <div class="maps-toggle-timeline">
      <div class="maps-toggle-timeline__tabs">
        <div class="maps-toggle-timeline__tabs__wrapper">
          <#
          _.each(settings.list, function(item, index) {
            var listTitleKey = view.getRepeaterSettingKey('list_title', 'list', index);
  
            view.addRenderAttribute(listTitleKey, 'href', "#maps-toggle-timeline__items__item--" + item._id);
            view.addInlineEditingAttributes(listTitleKey);
          #>
          <div class="maps-toggle-timeline__tabs__item elementor-repeater-item-{{ item._id }}">
            <a {{{ view.getRenderAttributeString(listTitleKey) }}}>
              {{{ item.list_title }}}
            </a>
          </div>
          <# }); #>
        </div>
      </div>
      <div class="maps-toggle-timeline__items">
        <#
        _.each(settings.list, function(item, index) {
          var listContentKey = view.getRepeaterSettingKey('list_content', 'list', index);
          var itemKey = view.getRepeaterSettingKey('maps-toggle-timeline__items__item', 'list', index);
          var itemContentKey = view.getRepeaterSettingKey('maps-toggle-timeline__items__item__content', 'list', index);
  
          view.addRenderAttribute({
            itemKey: {
              'id': 'maps-toggle-timeline__items__item--' + item._id,
              'class': ['maps-toggle-timeline__items__item', 'elementor-repeater-item-' + item._id]
            },
            itemContentKey: {
              'class': ['maps-toggle-timeline__items__item__content', ('yes' === settings.show_read_more) ? 'read-more-yes' : 'read-more-no']
            },
            listContentKey: {
              'class': ['maps-toggle-timeline__items__item__content__wrapper']
            }
          });
  
          view.addInlineEditingAttributes(listContentKey, 'advanced');
        #>
        <div {{{ view.getRenderAttributeString(itemKey) }}}>
          <div {{{ view.getRenderAttributeString(itemContentKey) }}}>
            <div {{{ view.getRenderAttributeString(listContentKey) }}}>
              {{{ item.list_content }}}
            </div>
            <div class="maps-toggle-timeline__items__item__content__btn">
              <a href="#" class="maps-toggle-timeline__items__item__toggle" role="button">
                <?php _e('Read more', 'maps-marketing'); ?>
              </a>
  
              <# if (!_.isEmpty(item.list_button_link.url)) { #>
                <a href="{{{ item.list_button_link.url }}}" class="maps-toggle-timeline__items__item__btn elementor-button">
                  {{{ item.list_button_text }}}
                </a>
              <# } #>
            </div>
          </div>
          <div class="maps-toggle-timeline__items__item__image"></div>
        </div>
        <# }); #>
      </div>
    </div>
    <?php
  }*/
}
