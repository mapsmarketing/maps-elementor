<?php

namespace MAPSElementor\Modules\Document_Links\Widgets;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Document_Links extends \Elementor\Widget_Base {
  public function get_name() {
    return 'maps-documents-links';
  }

  public function get_title() {
    return __('MAPS Document Links', 'maps-marketing');
  }

  public function get_icon() {
    return 'fa fa-code';
  }

  public function get_categories() {
    return ['maps-marketing'];
  }

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    // wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    // wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], null, true);

    add_action('elementor/frontend/after_enqueue_scripts', [$this, '_enqueue_scripts']);
  }

  public function _enqueue_scripts() {
    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', [], uniqid());

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], uniqid(), true);
  }

  public function get_script_depends() {
    return [$this->get_name() . '-js'];
  }

  public function get_style_depends() {
    return [$this->get_name() . '-css'];
  }

  protected function register_controls() {
    $this->start_controls_section('section_title', [
      'label' => esc_html__('Documents', 'maps-marketing')
    ]);

    $this->add_control('icon_internal', [
      'label' => esc_html__('Icon Document Internal', 'maps-marketing'),
      'type' => Controls_Manager::ICONS,
      'separator' => 'before',
      'fa4compatibility' => 'icon',
      'default' => [
        'value' => 'fas fa-file-alt',
        'library' => 'fa-solid'
      ],
      'recommended' => [
        'fa-solid' => ['file-alt']
      ],
      'skin' => 'inline',
      'label_block' => false
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_additional_options', [
      'label' => esc_html__('Additional Options', 'maps-marketing')
    ]);

    // additional options
    $this->add_control('display', [
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
        '{{WRAPPER}} .maps-documents-links' => 'display: {{VALUE}};'
      ]
      // 'responsive' => true
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
        '{{WRAPPER}} .maps-documents-links' => '{{VALUE}};'
      ],
      'condition' => [
        'display' => 'flex'
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
        '{{WRAPPER}} .maps-documents-links' => 'grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, 1fr));'
      ],
      'condition' => [
        'display' => 'grid'
      ]
    ]);

    $this->add_responsive_control('flex_width', [
      'label' => __('Columns', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px', '%', 'em', 'rem', 'custom'],
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
        '{{WRAPPER}} .maps-documents-links__link' => 'width: calc(100% / {{SIZE}} - {{gap.column}}{{gap.unit}});'
      ],
      'condition' => [
        'display' => 'flex',
        'direction' => 'row'
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
        '{{WRAPPER}} .maps-documents-links' => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}}'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_title_style', [
      'label' => esc_html__('Documents', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em', '%'],
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->start_controls_tabs('style_documents_tabs');

    $this->start_controls_tab('style_documents_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'background',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-documents-links__link'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'border',
      'selector' => '{{WRAPPER}} .maps-documents-links__link'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('style_documents_hover_tab', [
      'label' => esc_html__('Hover', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'background_hover',
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-documents-links__link:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'border_hover',
      'selector' => '{{WRAPPER}} .maps-documents-links__link:hover'
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('section_toggle_style_title', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('title_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link__title' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ]
    ]);

    $this->add_control('title_active_color', [
      'label' => esc_html__('Active Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link:hover .maps-documents-links__link__title' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title_typography',
      'selector' => '{{WRAPPER}} .maps-documents-links__link__title',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_toggle_style_icon', [
      'label' => esc_html__('Icon', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('icon_align', [
      'label' => esc_html__('Alignment', 'maps-marketing'),
      'type' => Controls_Manager::CHOOSE,
      'options' => [
        'left' => [
          'title' => esc_html__('Start', 'maps-marketing'),
          'icon' => 'eicon-h-align-left'
        ],
        'right' => [
          'title' => esc_html__('End', 'maps-marketing'),
          'icon' => 'eicon-h-align-right'
        ]
      ],
      'default' => is_rtl() ? 'right' : 'left',
      'toggle' => false
    ]);

    $this->add_control('icon_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link__icon' => 'color: {{VALUE}};',
        '{{WRAPPER}} .maps-documents-links__link__icon svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_control('icon_active_color', [
      'label' => esc_html__('Active Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link:hover .maps-documents-links__link__icon' => 'color: {{VALUE}};',
        '{{WRAPPER}} .maps-documents-links__link:hover .maps-documents-links__link__icon svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_control('icon_size', [
      'label' => esc_html__('Icon Size', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 100
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link__icon' => 'font-size: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} img.maps-documents-links__link__icon' => 'width: {{SIZE}}px;'
      ]
    ]);

    $this->add_responsive_control('icon_space', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'range' => [
        'px' => [
          'min' => 0,
          'max' => 100
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-documents-links__link--left .maps-documents-links__link__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .maps-documents-links__link--right .maps-documents-links__link__icon' => 'margin-left: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();
  }

  protected function render() {
    global $post;

    if (!$post) {
      return;
    }

    $settings = $this->get_settings_for_display();

    $documents = get_posts([
      'post_type' => 'maps_document',
      'posts_per_page' => -1,
      'orderby' => [
        'menu_order' => 'ASC',
        'post_title' => 'ASC'
      ],
      'meta_query' => [
        [
          'key' => 'page_link',
          'value' => '"' . get_the_id() . '"',
          'compare' => 'LIKE'
        ]
      ]
    ]);

    if (!$documents) {
      return;
    }

    $icon = $settings['icon_internal'];
?>

    <div class="maps-documents-links">
      <?php foreach ($documents as $document):

        $type = get_field('type_of_document', $document);
        $url = false;

        if ($type == 'external') {
          $url = get_field('link', $document);
        } else {
          $file = get_field('file', $document);
          $url = wp_get_attachment_url($file);
        }
      ?>

        <a href="<?php echo $url; ?>" target="_blank" rel="nofollow" class="maps-documents-links__link maps-documents-links__link--<?php echo esc_attr($settings['icon_align']); ?>">

          <?php if ($icon['library'] == 'svg'): ?>
            <img src="<?php echo $icon['value']['url']; ?>" class="maps-documents-links__link__icon">
          <?php elseif (isset($icon['library'])): ?>
            <i class="maps-documents-links__link__icon <?php echo $icon['value']; ?>"></i>
          <?php endif; ?>

          <span class="maps-documents-links__link__title"><?php echo get_the_title($document); ?></span>
        </a>

      <?php
      endforeach; ?>
    </div>
<?php
  }
}
