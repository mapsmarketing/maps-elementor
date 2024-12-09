<?php

namespace MAPSElementor\Modules\Taxonomy\Widgets;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Taxonomy extends \Elementor\Widget_Base // ElementorPro\Base\Base_Widget
{
  public function get_name()
  {
    return 'maps-taxonomy';
  }

  public function get_title()
  {
    return __('MAPS Taxonomy', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fas fa-plug';
  }

  public function get_categories()
  {
    return ['maps-marketing'];
  }

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());
  }

  public function get_script_depends()
  {
    return [];
  }

  public function get_style_depends()
  {
    return [$this->get_name() . '-css'];
  }

  protected function _register_controls()
  {
    $this->start_controls_section('content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_CONTENT
    ]);

    $this->add_responsive_control('columns', [
      'label' => __('Columns', 'elementor'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'default' => [
        'size' => 3
      ],
      'range' => [
        'px' => [
          'min' => 1
          // 		'max' => 50,
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-taxonomy' => 'grid-template-columns: repeat({{SIZE}},1fr);'
      ],
      'separator' => 'before'
    ]);

    $taxonomies = get_taxonomies();

    $this->add_control('taxonomy', [
      'label' => __('Taxonomy', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'options' => $taxonomies
      // 'default' => 'default',
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_layout', [
      'label' => __('Layout', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('columns_gap', [
      'label' => __('Columns Gap', 'elementor'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'default' => [
        'size' => 16
      ],
      'range' => [
        'px' => [
          'min' => 0
          // 		'max' => 50,
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-taxonomy' => 'grid-column-gap: {{SIZE}}{{UNIT}};'
      ],
      'separator' => 'before'
    ]);

    $this->add_responsive_control('rows_gap', [
      'label' => __('Rows Gap', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'default' => [
        'size' => 16
      ],
      'range' => [
        'px' => [
          'min' => 0
          // 		'max' => 50,
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-taxonomy' => 'grid-row-gap: {{SIZE}}{{UNIT}};'
      ],
      'separator' => 'before'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_image', [
      'label' => __('Image', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'image_border',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-taxonomy__link__attachment'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_caption', [
      'label' => __('Caption', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'caption_text',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-taxonomy__link__caption'
    ]);

    $this->start_controls_tabs('tabs_caption_style');

    $this->start_controls_tab('tab_caption', [
      'label' => __('Normal', 'maps-marketing')
    ]);

    $this->add_control('caption_colour', [
      'label' => __('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-taxonomy__link__caption' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'caption_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-taxonomy__link__caption'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('tab_caption_hover', [
      'label' => __('Hover', 'maps-marketing')
    ]);

    $this->add_control('caption_colour_hover', [
      'label' => __('Color', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-taxonomy__link:hover .maps-taxonomy__link__caption' => 'color: {{VALUE}};'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'caption_background_hover',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-taxonomy__link:hover .maps-taxonomy__link__caption'
    ]);

    $this->end_controls_tab();

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    if (!$settings['taxonomy']) {
      return;
    }

    $terms = get_terms([
      'taxonomy' => $settings['taxonomy']
    ]);
    ?>

    <div class="maps-taxonomy">

      <?php foreach ($terms as $term):
        $attachment = get_field('featured_image', $settings['taxonomy'] . '_' . $term->term_id); ?>

        <a href="<?php echo get_term_link($term, $settings['taxonomy']); ?>" class="maps-taxonomy__link">
          <?php echo wp_get_attachment_image($attachment, 'medium', false, [
            'class' => 'maps-taxonomy__link__attachment'
          ]); ?>
          <figcaption class="maps-taxonomy__link__caption"><?php echo $term->name; ?></figcaption>
        </a>

      <?php
      endforeach; ?>

    </div>
<?php
  }
}
