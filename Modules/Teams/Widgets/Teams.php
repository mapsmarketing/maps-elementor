<?php

namespace MAPSElementor\Modules\Teams\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Teams extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'maps-teams';
  }

  public function get_title()
  {
    return __('MAPS Teams', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-users';
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
    $this->start_controls_section('section_title', [
      'label' => esc_html__('Teams', 'maps-marketing')
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('list_image', [
      'label' => esc_html__('Choose Image', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::MEDIA,
      'default' => [
        'url' => \Elementor\Utils::get_placeholder_image_src()
      ]
    ]);

    $repeater->add_control('list_name', [
      'label' => esc_html__('Name', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('Name', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_title', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::TEXT,
      'default' => esc_html__('Title', 'maps-marketing'),
      'label_block' => true
    ]);

    $repeater->add_control('list_description_toggle', [
      'label' => esc_html__('Show Description', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_off' => esc_html__('Hide', 'your-plugin'),
      'label_on' => esc_html__('Show', 'your-plugin'),
      'return_value' => 'yes',
      'default' => 'yes'
    ]);

    $repeater->add_control('list_content', [
      'label' => esc_html__('Description', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::WYSIWYG,
      'default' => esc_html__('Description', 'maps-marketing'),
      'show_label' => false,
      'condition' => [
        'list_description_toggle' => 'yes'
      ]
    ]);

    $this->add_control('list', [
      'label' => esc_html__('List', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'default' => [
        [
          'list_name' => esc_html__('John Doe', 'maps-marketing'),
          'list_title' => esc_html__('Director', 'maps-marketing'),
          'list_content' => esc_html__('Your text here.', 'maps-marketing')
        ]
      ],
      'title_field' => '{{{ list_name }}}',
      'label_block' => false
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_settings', [
      'label' => esc_html__('Settings', 'maps-marketing')
    ]);

    $this->add_responsive_control('slides_to_show', [
      'label' => esc_html__('Profiles per row', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'size' => 3
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-teams' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));'
      ]
    ]);

    $this->add_responsive_control('item_gap', [
      'label' => esc_html__('Gaps', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::GAPS,
      'size_units' => ['px', '%', 'em', 'rem', 'vm', 'custom'],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'separator' => 'before',
      'selectors' => [
        '{{SELECTOR}} .maps-teams' => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}}'
      ]
      // 'responsive' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_style_image', [
      'label' => esc_html__('Image', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('image_width', [
      'label' => esc_html__('Width', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%'],
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__image img' => 'width: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_group_control(\MAPSElementor\Controls\Group_Control_Object::get_type(), [
      'name' => 'image_object',
      'label' => esc_html__('Height', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-teams__item__image img'
    ]);

    $this->add_control('image_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%'],
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__image' => 'margin-bottom: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_style_name', [
      'label' => esc_html__('Name', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('name_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__footer__name' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'name_typography',
      'selector' => '{{WRAPPER}} .maps-teams__item__footer__name',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ]
    ]);

    $this->add_control('name_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%'],
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__footer__name' => 'margin-bottom: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_style_title', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('title_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__footer__title' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title_typography',
      'selector' => '{{WRAPPER}} .maps-teams__item__footer__title',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ]
    ]);

    $this->add_control('title_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%'],
      'range' => [
        'px' => [
          'min' => 1
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__footer__title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_style_description', [
      'label' => esc_html__('Description', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('description_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-teams__item__footer__content' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'description_typography',
      'selector' => '{{WRAPPER}} .maps-teams__item__footer__content',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ]
    ]);

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display(); ?>

    <div class="maps-teams">

      <?php foreach ($settings['list'] as $index => $item):

        $list_name_key = $this->get_repeater_setting_key('list_name', 'list', $index);
        $list_title_key = $this->get_repeater_setting_key('list_title', 'list', $index);
        $list_content_key = $this->get_repeater_setting_key('list_content', 'list', $index);

        $this->add_inline_editing_attributes($list_name_key, 'none');
        $this->add_inline_editing_attributes($list_title_key, 'none');
        $this->add_inline_editing_attributes($list_content_key, 'advanced');
        ?>
        <div class="maps-teams__item">
          <div class="maps-teams__item__image">
            <?php echo wp_get_attachment_image($item['list_image']['id'], 'full'); ?>
          </div>
          <div class="maps-teams__item__footer">
            <div class="maps-teams__item__footer__name">
              <div <?php $this->print_render_attribute_string($list_name_key); ?>>
                <?php echo $item['list_name']; ?>
              </div>
            </div>
            <div class="maps-teams__item__footer__title">
              <div <?php $this->print_render_attribute_string($list_title_key); ?>>
                <?php echo $item['list_title']; ?>
              </div>
            </div>

            <?php if ($item['list_description_toggle']): ?>
              <div class="maps-teams__item__footer__content">
                <div <?php $this->print_render_attribute_string($list_content_key); ?>>
                  <?php echo $item['list_content']; ?>
                </div>
              </div>
            <?php endif; ?>

          </div>
        </div>

      <?php
      endforeach; ?>

    </div>
  <?php
  }

  protected function content_template()
  {
    ?>
    <div class="maps-teams">
      <#
      _.each(settings.list, function(item, index) { 
        var listNameKey = view.getRepeaterSettingKey( 'list_name' , 'list' , index );
        var listTitleKey = view.getRepeaterSettingKey( 'list_title' , 'list' , index );
        var listContentKey = view.getRepeaterSettingKey( 'list_content' , 'list' , index );

        view.addInlineEditingAttributes( listNameKey, 'none' );
        view.addInlineEditingAttributes( listTitleKey, 'none' );
        view.addInlineEditingAttributes( listContentKey, 'advanced' );
      #>
        <div class="maps-teams__item" data-id="{{ index + 1 }}">
          <# if (item.list_image.url) { #>
          <div class="maps-teams__item__image">
            <img src="{{ item.list_image.url }}" alt="{{ item.list_name }}">
          </div>
          <# } #>

          <div class="maps-teams__item__footer">
            <div class="maps-teams__item__footer__name">
              <div {{{ view.getRenderAttributeString( listNameKey ) }}}>
                {{{ item.list_name }}}
              </div>
            </div>
            <div class="maps-teams__item__footer__title">
              <div {{{ view.getRenderAttributeString( listTitleKey ) }}}>
                {{{ item.list_title }}}
              </div>
            </div>

            <# if (item.list_description_toggle==='yes' ) { #>
              <div class="maps-teams__item__footer__content">
                <div {{{ view.getRenderAttributeString( listContentKey ) }}}>
                  {{{ item.list_content }}}
                </div>
              </div>
            <# } #>
          </div>
        </div>
        <# }); #>
    </div>
    <?php
  }
}
?>
