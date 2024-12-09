<?php
/*
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

    wp_register_style($this->get_name() . '-css', MAPS_ELEMENTOR_ASSETS_URL . 'css/' . $this->get_name() . '.bundle.min.css', '', '1.0');

    wp_register_script($this->get_name() . '-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/' . $this->get_name() . '.bundle.min.js', ['elementor-frontend'], '1.0', true);
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

    $repeater->add_control('list_image', [
      'label' => esc_html__('Choose Image', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::MEDIA,
      'default' => [
        'url' => \Elementor\Utils::get_placeholder_image_src()
      ]
    ]);

    $repeater->add_control('list_image_position', [
      'label' => esc_html__('Position', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => '',
      'options' => [
        '' => esc_html__('Default', 'maps-marketing'),
        'center' => esc_html__('Centre Centre', 'maps-marketing'),
        'center left' => esc_html__('Centre Left', 'maps-marketing'),
        'center right' => esc_html__('Centre Right', 'maps-marketing'),
        'top center' => esc_html__('Top Centre', 'maps-marketing'),
        'top left' => esc_html__('Top Left', 'maps-marketing'),
        'top right' => esc_html__('Top Right', 'maps-marketing'),
        'bottom center' => esc_html__('Bottom Centre', 'maps-marketing'),
        'bottom left' => esc_html__('Bottom Left', 'maps-marketing'),
        'bottom right' => esc_html__('Bottom Right', 'maps-marketing'),
        'custom' => esc_html__('Custom', 'maps-marketing')
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'background-position: {{VALUE}};'
      ]
    ]);

    $repeater->add_control('list_image_attachment', [
      'label' => esc_html__('Attachment', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => '',
      'options' => [
        '' => esc_html__('Default', 'maps-marketing'),
        'scroll' => esc_html__('Scroll', 'maps-marketing'),
        'fixed' => esc_html__('Fixed', 'maps-marketing')
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'background-attachment: {{VALUE}};'
      ],
      'condition' => [
        // 'list_image' => 'yes',
      ]
    ]);

    $repeater->add_control('list_image_repeat', [
      'label' => esc_html__('Repeat', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => '',
      'options' => [
        '' => esc_html__('Default', 'maps-marketing'),
        'no-repeat' => esc_html__('No Repeat', 'maps-marketing'),
        'repeat' => esc_html__('Repeat', 'maps-marketing'),
        'repeat-x' => esc_html__('Repeat-x', 'maps-marketing'),
        'repeat-y' => esc_html__('Repeat-y', 'maps-marketing')
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'background-repeat: {{VALUE}};'
      ],
      'condition' => [
        // 'list_image' => 'yes',
      ]
    ]);

    $repeater->add_control('list_image_size', [
      'label' => esc_html__('Position', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SELECT,
      'default' => '',
      'options' => [
        '' => esc_html__('Default', 'maps-marketing'),
        'auto' => esc_html__('Auto', 'maps-marketing'),
        'cover' => esc_html__('Cover', 'maps-marketing'),
        'contain' => esc_html__('Contain', 'maps-marketing'),
        'custom' => esc_html__('Custom', 'maps-marketing')
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'background-size: {{VALUE}};'
      ],
      'condition' => [
        // 'list_image' => 'yes',
      ]
    ]);

    $repeater->add_control('list_image_size_custom', [
      'label' => __('Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', '%', 'vw'],
      'range' => [
        '%' => [
          'min' => 0,
          'max' => 100
        ]
      ],
      'default' => [
        'unit' => '%',
        'size' => 100
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'background-size: {{SIZE}}{{UNIT}};'
      ],
      'condition' => [
        'list_image_size' => 'custom'
        // 'list_image' => 'yes',
      ]
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

    $this->add_control('slider_autoplay', [
      'label' => esc_html__('Autoplay', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label_on' => esc_html__('Show', 'your-plugin'),
      'label_off' => esc_html__('Hide', 'your-plugin'),
      'return_value' => 'true',
      'default' => 'false',
      'frontend_available' => true
    ]);

    $this->add_control('slider_interval', [
      'label' => esc_html__('Autoplay Interval (ms)', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::NUMBER,
      'default' => 3000,
      'frontend_available' => true
    ]);

    $this->add_responsive_control('slider_height', [
      'label' => __('Min Height', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::SLIDER,
      'size_units' => ['px', 'em', 'rem', '%', 'vh'],
      'default' => [
        'unit' => 'px',
        'size' => 700
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__slides__item' => 'height: {{SIZE}}{{UNIT}}'
      ],
      'frontend_available' => true
    ]);

    $this->end_controls_section();

    $this->start_controls_section('style_tabs', [
      'label' => __('Tabs', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->start_controls_tabs('tabs_background');

    $this->start_controls_tab('tabs_background_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'tabs_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__title'
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('tabs_background_active_tab', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'tabs_background_active',
      'label' => __('Active Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'exclude' => ['image'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab'
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_title', [
      'label' => __('Title', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->start_controls_tabs('title_tabs');

    $this->start_controls_tab('title_normal_tab', [
      'label' => esc_html__('Normal', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title',
      'label' => __('Title', 'maps-marketing'),
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__title'
    ]);

    $this->add_control('title_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__item__title' => 'color: {{VALUE}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->start_controls_tab('title_active_tab', [
      'label' => esc_html__('Active', 'maps-marketing')
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'title_active',
      'label' => __('Title Active', 'maps-marketing'),
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__title'
    ]);

    $this->add_control('title_colour_active', [
      'label' => __('Active Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__title' => 'color: {{VALUE}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section('style_content', [
      'label' => __('Content', 'maps-marketing'),
      'tab' => \Elementor\Controls_Manager::TAB_STYLE
    ]);

    $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
      'name' => 'content',
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
      ],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__content'
    ]);

    $this->add_control('content_colour', [
      'label' => __('Colour', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::COLOR,
      'global' => [
        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY
      ],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__content' => 'color: {{VALUE}};'
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
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn'
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
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn'
    ]);

    $this->add_control('button_border_radius', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn:hover' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
      'name' => 'button_background_hover',
      'label' => __('Background', 'maps-marketing'),
      'types' => ['classic', 'gradient'],
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn:hover'
    ]);

    $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
      'name' => 'button_border_hover',
      'label' => __('Border', 'maps-marketing'),
      'selector' => '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn:hover'
    ]);

    $this->add_control('button_border_radius_hover', [
      'label' => esc_html__('Border Radius', 'maps-marketing'),
      'type' => \Elementor\Controls_Manager::DIMENSIONS,
      'size_units' => ['px', '%', 'em', 'rem'],
      'selectors' => [
        '{{WRAPPER}} .maps-slider-tabs__nav__item__tab__btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
      ]
    ]);

    $this->end_controls_tab();

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $autoplay = $settings['slider_autoplay'] ? $settings['slider_autoplay'] : 'false';
    ?>

    <?php if ($settings['list']): ?>

      <div class="maps-slider-tabs">

        <div class="maps-slider-tabs__slides" data-slick='{"autoplay": <?php echo $autoplay; ?>, "autoplaySpeed": <?php echo $settings['slider_interval']; ?>}'>

          <?php foreach ($settings['list'] as $item): ?>

            <div class="maps-slider-tabs__slides__item" style="background-image: url('<?php echo wp_get_attachment_image_src($item['list_image']['id'], 'full')[0]; ?>');">
              <?php
            // echo wp_get_attachment_image( $item['list_image']['id'], 'maps-slider-tabs', false );
            ?>
            </div>

          <?php endforeach; ?>

        </div>

        <div class="maps-slider-tabs__nav">

          <?php foreach ($settings['list'] as $item):

            $target = $item['list_button_link']['is_external'] ? ' target="_blank" ' : '';
            $nofollow = $item['list_button_link']['nofollow'] ? ' rel="nofollow" ' : '';
            ?>

            <div class="maps-slider-tabs__nav__item">
              <div class="maps-slider-tabs__nav__item__title"><?php echo $item['list_title']; ?></div>
              <div class="maps-slider-tabs__nav__item__tab">
                <h3 class="maps-slider-tabs__nav__item__tab__title"><?php echo $item['list_title']; ?></h3>
                <div class="maps-slider-tabs__nav__item__tab__content"><?php echo $item['list_content']; ?></div>

                <?php if ($item['list_button_text'] and $item['list_button_link']): ?>
                  <a href="<?php echo $item['list_button_link']['url']; ?>" class="maps-slider-tabs__nav__item__tab__btn elementor-button" <?php echo $target . $nofollow; ?>><?php echo $item['list_button_text']; ?></a>
                <?php endif; ?>
              </div>
            </div>

          <?php
          endforeach; ?>

        </div>

      </div>

    <?php endif; ?>

<?php
  }
}
*/
