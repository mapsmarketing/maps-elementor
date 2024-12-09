<?php

namespace MAPSElementor\Modules\Documents\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Icons_Manager;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Documents extends Widget_Base
{
  public function get_name()
  {
    return 'maps-documents';
  }

  public function get_title()
  {
    return __('MAPS Documents', 'maps-marketing');
  }

  public function get_icon()
  {
    return 'fa fa-code';
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
      'label' => esc_html__('Accordion', 'maps-marketing')
    ]);

    $document_categories = get_terms([
      'taxonomy' => 'maps_document_category',
      'hide_empty' => false
    ]);

    $keys = wp_list_pluck($document_categories, 'term_id');
    $values = wp_list_pluck($document_categories, 'name');
    $document_categories = array_combine($keys, $values);

    $this->add_control('document_categories', [
      'label' => __('Document Categories', 'maps-marketing'),
      'type' => Controls_Manager::SELECT2,
      'multiple' => true,
      'options' => $document_categories
      // 'default' => [ 'title', 'description' ],
    ]);

    $this->add_control('view', [
      'label' => esc_html__('View', 'maps-marketing'),
      'type' => Controls_Manager::HIDDEN,
      'default' => 'traditional'
    ]);

    $this->add_control('document_icon', [
      'label' => esc_html__('Document Icon', 'maps-marketing'),
      'type' => Controls_Manager::ICONS,
      'separator' => 'before',
      'fa4compatibility' => 'icon',
      'default' => [
        'value' => 'fas fa-file-alt',
        'library' => 'fa-solid'
      ],
      'skin' => 'inline',
      'label_block' => false
    ]);

    $this->add_control('selected_icon', [
      'label' => esc_html__('Icon', 'maps-marketing'),
      'type' => Controls_Manager::ICONS,
      'separator' => 'before',
      'fa4compatibility' => 'icon',
      'default' => [
        'value' => 'fas fa-plus',
        'library' => 'fa-solid'
      ],
      'recommended' => [
        'fa-solid' => ['chevron-down', 'angle-down', 'angle-double-down', 'caret-down', 'caret-square-down'],
        'fa-regular' => ['caret-square-down']
      ],
      'skin' => 'inline',
      'label_block' => false
    ]);

    $this->add_control('selected_active_icon', [
      'label' => esc_html__('Active Icon', 'maps-marketing'),
      'type' => Controls_Manager::ICONS,
      'fa4compatibility' => 'icon_active',
      'default' => [
        'value' => 'fas fa-minus',
        'library' => 'fa-solid'
      ],
      'recommended' => [
        'fa-solid' => ['chevron-up', 'angle-up', 'angle-double-up', 'caret-up', 'caret-square-up'],
        'fa-regular' => ['caret-square-up']
      ],
      'skin' => 'inline',
      'label_block' => false,
      'condition' => [
        'selected_icon[value]!' => ''
      ]
    ]);

    $this->add_control('title_html_tag', [
      'label' => esc_html__('Title HTML Tag', 'maps-marketing'),
      'type' => Controls_Manager::SELECT,
      'options' => [
        'h1' => 'H1',
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
        'h5' => 'H5',
        'h6' => 'H6',
        'div' => 'div'
      ],
      'default' => 'div',
      'separator' => 'before'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('tabs_section', [
      'label' => esc_html__('Accordion', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('tabs_gap', [
      'label' => esc_html__('Gap', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'default' => [
        'unit' => 'px',
        'size' => 30
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-documents' => 'gap: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('accordion_section', [
      'label' => esc_html__('Documents', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_responsive_control('accordion_gap', [
      'label' => esc_html__('Gap', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'default' => [
        'unit' => 'px',
        'size' => 30
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-content__wrapper' => 'gap: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('title_section', [
      'label' => esc_html__('Title', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'title_typography',
      'selector' => '{{WRAPPER}} .elementor-tab-title',
      'global' => [
        'default' => Global_Typography::TYPOGRAPHY_PRIMARY
      ]
    ]);

    $this->add_group_control(Group_Control_Text_Shadow::get_type(), [
      'name' => 'title_shadow',
      'selector' => '{{WRAPPER}} .elementor-accordion-title'
    ]);

    $this->start_controls_tabs('title_tabs');

    $this->start_controls_tab('title_tab', [
      'label' => esc_html__('Closed', 'maps-marketing')
    ]);

    $this->add_control('title_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-accordion-title' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => Global_Colors::COLOR_PRIMARY
      ]
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
      'name' => 'title_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .elementor-tab-title'
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'title_border',
      'selector' => '{{WRAPPER}} .elementor-tab-title'
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'box_shadow',
      'selector' => '{{WRAPPER}} .elementor-tab-title'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('title_tab_active', [
      'label' => esc_html__('Open', 'maps-marketing')
    ]);

    $this->add_control('title_color_active', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-title:hover .elementor-accordion-title' => 'color: {{VALUE}};',
        '{{WRAPPER}} .elementor-tab-title.elementor-active .elementor-accordion-title' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => Global_Colors::COLOR_ACCENT
      ]
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
      'name' => 'title_background_active',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .elementor-tab-title:hover, {{WRAPPER}} .elementor-tab-title.elementor-active'
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'title_border_active',
      'selector' => '{{WRAPPER}} .elementor-tab-title:hover, {{WRAPPER}} .elementor-tab-title.elementor-active'
    ]);

    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'box_shadow_active',
      'selector' => '{{WRAPPER}} .elementor-tab-title:hover, {{WRAPPER}} .elementor-tab-title.elementor-active'
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_responsive_control('title_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em', '%'],
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('title_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em', '%'],
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('document_icon_section_style', [
      'label' => esc_html__('Document Icon', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('document_icon_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .type-maps_document' => 'color: {{VALUE}};',
        '{{WRAPPER}} .type-maps_document svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_control('document_icon_color_active', [
      'label' => esc_html__('Active Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .type-maps_document:hover' => 'color: {{VALUE}};',
        '{{WRAPPER}} .type-maps_document:hover svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_responsive_control('document_icon_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 0
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 50
      ],
      'selectors' => [
        '{{WRAPPER}} .type-maps_document i' => 'font-size: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('document_icon_spacing', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'range' => [
        'px' => [
          'min' => 0
        ]
      ],
      'selectors' => [
        '{{WRAPPER}} .type-maps_document' => 'gap: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('icon_section_style', [
      'label' => esc_html__('Icon', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE,
      'condition' => [
        'selected_icon[value]!' => ''
      ]
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
        '{{WRAPPER}} .elementor-tab-title .elementor-accordion-icon i' => 'color: {{VALUE}};',
        '{{WRAPPER}} .elementor-tab-title .elementor-accordion-icon svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_control('icon_color_active', [
      'label' => esc_html__('Active Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-title:hover .elementor-accordion-icon i' => 'color: {{VALUE}};',
        '{{WRAPPER}} .elementor-tab-title:hover .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
        '{{WRAPPER}} .elementor-tab-title.elementor-active .elementor-accordion-icon i' => 'color: {{VALUE}};',
        '{{WRAPPER}} .elementor-tab-title.elementor-active .elementor-accordion-icon svg' => 'fill: {{VALUE}};'
      ]
    ]);

    $this->add_control('icon_size', [
      'label' => esc_html__('Size', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 0
        ]
      ],
      'default' => [
        'unit' => 'px',
        'size' => 15
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->add_responsive_control('icon_space', [
      'label' => esc_html__('Spacing', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'default' => [
        'unit' => 'px',
        'size' => 10
      ],
      'selectors' => [
        '{{WRAPPER}} .elementor-accordion-icon.elementor-accordion-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .elementor-accordion-icon.elementor-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();

    $this->start_controls_section('content_section_style', [
      'label' => esc_html__('Content', 'maps-marketing'),
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('content_color', [
      'label' => esc_html__('Color', 'maps-marketing'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};'
      ],
      'global' => [
        'default' => Global_Colors::COLOR_TEXT
      ]
    ]);

    $this->add_group_control(Group_Control_Background::get_type(), [
      'name' => 'content_background',
      'label' => esc_html__('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .elementor-tab-content'
    ]);

    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'content_border',
      'selector' => '{{WRAPPER}} .elementor-tab-content'
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'content_typography',
      'selector' => '{{WRAPPER}} .elementor-tab-content',
      'global' => [
        'default' => Global_Typography::TYPOGRAPHY_TEXT
      ]
    ]);

    $this->add_group_control(Group_Control_Text_Shadow::get_type(), [
      'name' => 'content_shadow',
      'selector' => '{{WRAPPER}} .elementor-tab-content'
    ]);

    $this->add_responsive_control('content_padding', [
      'label' => esc_html__('Padding', 'maps-marketing'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px', 'em', '%'],
      'selectors' => [
        '{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_section();
  }

  protected function render()
  {
    global $post;

    $settings = $this->get_settings_for_display();
    $migrated = isset($settings['__fa4_migrated']['selected_icon']);

    if (!isset($settings['icon']) && !Icons_Manager::is_migration_allowed()) {
      $settings['icon'] = 'fa fa-plus';
      $settings['icon_active'] = 'fa fa-minus';
      $settings['icon_align'] = $this->get_settings('icon_align');
    }

    $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
    $has_icon = !$is_new || !empty($settings['selected_icon']['value']);
    $document_icon = $settings['document_icon'];
    $id_int = substr($this->get_id_int(), 0, 3);

    $uncategorised = get_term_by('slug', 'uncategorised', 'maps_document_category');
    $categories = get_terms([
      'taxonomy' => 'maps_document_category',
      'include' => $settings['document_categories'],
      'exclude' => [$uncategorised->term_id]
    ]);
    ?>
    <div class="maps-documents elementor-accordion" role="tablist">
      <?php foreach ($categories as $index => $category):

        $documents = get_posts([
          'post_type' => 'maps_document',
          'posts_per_page' => -1,
          'orderby' => 'post_title',
          'order' => 'ASC',
          'tax_query' => [
            [
              'taxonomy' => 'maps_document_category',
              'terms' => $category->slug,
              'field' => 'slug'
            ]
          ]
        ]);

        $tab_count = $index + 1;

        $this->add_render_attribute('tab_title_' . $id_int . $tab_count, [
          'id' => 'elementor-tab-title-' . $id_int . $tab_count,
          'class' => ['elementor-tab-title'],
          'data-tab' => $tab_count,
          'role' => 'tab',
          'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
          'aria-expanded' => 'false'
        ]);

        $this->add_render_attribute('tab_content_' . $id_int . $tab_count, [
          'id' => 'elementor-tab-content-' . $id_int . $tab_count,
          'class' => ['elementor-tab-content', 'elementor-clearfix'],
          'data-tab' => $tab_count,
          'role' => 'tabpanel',
          'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count
        ]);
        ?>
        <div class="elementor-accordion-item">
          <<?php \Elementor\Utils::print_validated_html_tag($settings['title_html_tag']); ?> <?php $this->print_render_attribute_string('tab_title_' . $id_int . $tab_count); ?>>

            <?php if ($has_icon): ?>
              <span class="elementor-accordion-icon elementor-accordion-icon-<?php echo esc_attr($settings['icon_align']); ?>" aria-hidden="true">
                <?php if ($is_new || $migrated) { ?>
                  <span class="elementor-accordion-icon-closed"><?php Icons_Manager::render_icon($settings['selected_icon']); ?></span>
                  <span class="elementor-accordion-icon-opened"><?php Icons_Manager::render_icon($settings['selected_active_icon']); ?></span>
                <?php } else { ?>
                  <i class="elementor-accordion-icon-closed <?php echo esc_attr($settings['icon']); ?>"></i>
                  <i class="elementor-accordion-icon-opened <?php echo esc_attr($settings['icon_active']); ?>"></i>
                <?php } ?>
              </span>
            <?php endif; ?>

            <a class="elementor-accordion-title" href=""><?php echo $category->name; ?></a>
          </<?php \Elementor\Utils::print_validated_html_tag($settings['title_html_tag']); ?>>

          <div <?php $this->print_render_attribute_string('tab_content_' . $id_int . $tab_count); ?>>
            <div class="elementor-tab-content__wrapper">

              <?php
              foreach ($documents as $post):

                setup_postdata($post);

                $link = false;
                $type = get_field('type_of_document');

                if ($type == 'external') {
                  $link = get_field('link');
                } else {
                  $file = get_field('file');
                  $link = wp_get_attachment_url($file);
                }
                ?>

                <a href="<?php echo $link; ?>" target="_blank" rel="nofollow" <?php post_class(); ?>>

                  <?php if ($document_icon['library'] == 'svg'): ?>
                    <img src="<?php echo $document_icon['value']['url']; ?>" class="elementor-accordion-document-icon">
                  <?php elseif (isset($document_icon['library'])): ?>
                    <i class="<?php echo $document_icon['value']; ?>"></i>
                  <?php endif; ?>

                  <?php the_title(); ?>
                </a>

              <?php
              endforeach;
              wp_reset_postdata();
              ?>

            </div>
          </div>
        </div>
      <?php
      endforeach; ?>
    </div>
<?php
  }
}
