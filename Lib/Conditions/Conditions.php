<?php

namespace MAPSElementor\Lib\Conditions;

use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module;
use MAPSElementor\Lib\Conditions\Date;
use MAPSElementor\Lib\Conditions\View;

class Conditions {
  public function __construct() {
    $this->defineHooks();
    $this->definePublicHooks();
  }

  private function defineHooks() {
    add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'addConditionFields'], 10, 3);
    add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'addConditionFields'], 10, 3);
    add_action('elementor/element/common/_section_style/after_section_end', [$this, 'addConditionFields'], 10, 3);
    add_action('elementor/element/popup/section_advanced/after_section_end', [$this, 'addConditionFields'], 10, 3);
    add_action('elementor/element/container/section_layout/after_section_end', [$this, 'addConditionFields'], 10, 3);
  }

  private function definePublicHooks() {
    $public = new View();

    add_action( 'wp_enqueue_scripts', [$public, 'enqueueScripts'] );

    // filter widgets
    add_action("elementor/frontend/widget/before_render", [$public, 'filterSectionContentBefore'], 10, 1);
    add_action("elementor/frontend/widget/after_render", [$public, 'filterSectionContentAfter'], 10, 1);

    // filter sections
    add_action("elementor/frontend/section/before_render", [$public, 'filterSectionContentBefore'], 10, 1);
    add_action("elementor/frontend/section/after_render", [$public, 'filterSectionContentAfter'], 10, 1);

    // filter columns
    add_action("elementor/frontend/column/before_render", [$public, 'filterSectionContentBefore'], 10, 1);
    add_action("elementor/frontend/column/after_render", [$public, 'filterSectionContentAfter'], 10, 1);

    // filter container
    add_action("elementor/frontend/container/before_render", [$public, 'filterSectionContentBefore'], 10, 1);
    add_action("elementor/frontend/container/after_render", [$public, 'filterSectionContentAfter'], 10, 1);

    // filter popup
    add_action("elementor/theme/before_do_popup", [$public, 'checkPopupsCondition'], 10, 1);
  }

  public function enqueueStyles() {
    wp_enqueue_style('maps-conditions-admin', MAPS_ELEMENTOR_ASSETS_URL . '/css/maps-conditions-admin.bundle.min.css', [], uniqid(), 'all');
  }

  public function addConditionFields($element, $section_id = null, $args = null) {
    $valueCondition = [
      'equal',
      'not_equal',
      'contains',
      'not_contains',
      'less',
      'greater',
      'between',
      'in_array',
      'in_array_contains'
    ];

    $allCondition = [
      'equal',
      'not_equal',
      'contains',
      'not_contains',
      'less',
      'greater',
      'between',
      'empty',
      'not_empty'
    ];

    $type = 'element';
    $renderType = 'ui';
    if (!empty($element) && is_object($element) && method_exists($element, 'get_type')) {
      $type = $element->get_type();
    }

    $categories = [
      Module::BASE_GROUP,
      Module::TEXT_CATEGORY,
      Module::URL_CATEGORY,
      Module::GALLERY_CATEGORY,
      Module::IMAGE_CATEGORY,
      Module::MEDIA_CATEGORY,
      Module::POST_META_CATEGORY,
    ];

    $categoriesTextOnly = [
      Module::BASE_GROUP,
      Module::TEXT_CATEGORY,
      Module::URL_CATEGORY,
      Module::POST_META_CATEGORY,
    ];

    if (defined(Module::class . '::COLOR_CATEGORY')) {
      $categories[] = Module::COLOR_CATEGORY;
    }

    $element->start_controls_section(
      'dynamicconditions_section',
      [
        'tab' => Controls_Manager::TAB_ADVANCED,
        'label' => __('Conditions', 'dynamicconditions'),
      ]
    );

    $element->add_control(
      'dynamicconditions_dynamic',
      [
        'label' => __('Dynamic Tag', 'dynamicconditions'),
        'type' => Controls_Manager::MEDIA,
        'dynamic' => [
          'active' => true,
          'categories' => $categories,
        ],
        'render_type' => $renderType,
        'placeholder' => __('Select condition field', 'dynamicconditions'),
      ]
    );

    $element->add_control(
      'dynamicconditions_visibility',
      [
        'label' => __('Show/Hide', 'dynamicconditions'),
        'type' => Controls_Manager::SELECT,
        'default' => 'hide',
        'options' => [
          'show' => __('Show when condition met', 'dynamicconditions'),
          'hide' => __('Hide when condition met', 'dynamicconditions'),
        ],
        'render_type' => $renderType,
        'separator' => 'before',
      ]
    );

    $element->add_control(
      'dynamicconditions_condition',
      [
        'label' => __('Condition', 'dynamicconditions'),
        'type' => Controls_Manager::SELECT2,
        'multiple' => false,
        'label_block' => true,
        'options' => [
          'equal' => __('Is equal to', 'dynamicconditions'),
          'not_equal' => __('Is not equal to', 'dynamicconditions'),
          'contains' => __('Contains', 'dynamicconditions'),
          'not_contains' => __('Does not contain', 'dynamicconditions'),
          'empty' => __('Is empty', 'dynamicconditions'),
          'not_empty' => __('Is not empty', 'dynamicconditions'),
          'between' => __('Between', 'dynamicconditions'),
          'less' => __('Less than', 'dynamicconditions'),
          'greater' => __('Greater than', 'dynamicconditions'),
          'in_array' => __('In array', 'dynamicconditions'),
          'in_array_contains' => __('In array contains', 'dynamicconditions'),
        ],
        'description' => __('Select your condition for this widget visibility.', 'dynamicconditions'),

        'prefix_class' => 'dc-has-condition dc-condition-',
        'render_type' => 'template',
      ]
    );

    $element->add_control(
      'dynamicconditions_type',
      [
        'label' => __('Compare Type', 'dynamicconditions'),
        'type' => Controls_Manager::SELECT,
        'multiple' => false,
        'label_block' => true,
        'options' => [
          'default' => __('Text', 'dynamicconditions'),
          'date' => __('Date', 'dynamicconditions'),
          'days' => __('Weekdays', 'dynamicconditions'),
          'months' => __('Months', 'dynamicconditions'),
          'strtotime' => __('String to time', 'dynamicconditions'),
        ],
        'default' => 'default',
        'render_type' => $renderType,
        'description' => __('Select what do you want to compare', 'dynamicconditions'),
        'condition' => [
          'dynamicconditions_condition' => $valueCondition,
        ],
      ]
    );

    $element->add_control(
      'dynamicconditions_value',
      [
        'type' => Controls_Manager::TEXTAREA,
        'label' => __('Conditional value', 'dynamicconditions'),
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'render_type' => $renderType,

        'dynamic' => [
          'active' => true,
          'categories' => $categoriesTextOnly,
        ],
        'condition' => [
          'dynamicconditions_condition' => $valueCondition,
          'dynamicconditions_type' => ['default', 'strtotime'],
        ],
      ]
    );

    $element->add_control(
      'dynamicconditions_value2',
      [
        'type' => Controls_Manager::TEXTAREA,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'description' => __('Add a second condition value, if between is selected', 'dynamicconditions'),
        'render_type' => $renderType,
        'dynamic' => [
          'active' => true,
          'categories' => $categoriesTextOnly,
        ],

        'condition' => [
          'dynamicconditions_condition' => ['between'],
          'dynamicconditions_type' => ['default', 'strtotime'],
        ],
      ]
    );


    $element->add_control(
      'dynamicconditions_date_value',
      [
        'type' => Controls_Manager::DATE_TIME,
        'label' => __('Conditional value', 'dynamicconditions'),
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'render_type' => $renderType,

        'condition' => [
          'dynamicconditions_condition' => $valueCondition,
          'dynamicconditions_type' => 'date',
        ],
      ]
    );

    $element->add_control(
      'dynamicconditions_date_value2',
      [
        'type' => Controls_Manager::DATE_TIME,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'description' => __('Add a second condition value, if between is selected', 'dynamicconditions'),
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['between'],
          'dynamicconditions_type' => 'date',
        ],
      ]
    );

    $element->add_control(
      'dynamicconditions_day_array_value',
      [
        'type' => Controls_Manager::SELECT2,
        'label' => __('Conditional value', 'dynamicconditions'),
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['in_array'],
          'dynamicconditions_type' => 'days',
        ],
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'options' => Date::getDaysTranslated(),
        'multiple' => true,
      ]
    );
    $element->add_control(
      'dynamicconditions_day_value',
      [
        'type' => Controls_Manager::SELECT,
        'label' => __('Conditional value', 'dynamicconditions'),
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => array_diff($valueCondition, ['in_array']),
          'dynamicconditions_type' => 'days',
        ],
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'options' => Date::getDaysTranslated(),
      ]
    );

    $element->add_control(
      'dynamicconditions_day_value2',
      [
        'type' => Controls_Manager::SELECT,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['between'],
          'dynamicconditions_type' => 'days',
        ],
        'description' => __('Add a second condition value, if between is selected', 'dynamicconditions'),
        'options' => Date::getDaysTranslated(),
      ]
    );

    $element->add_control(
      'dynamicconditions_month_array_value',
      [
        'type' => Controls_Manager::SELECT2,
        'label' => __('Conditional value', 'dynamicconditions'),
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['in_array'],
          'dynamicconditions_type' => 'months',
        ],
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'options' => Date::getMonthsTranslated(),
        'multiple' => true,
      ]
    );

    $element->add_control(
      'dynamicconditions_month_value',
      [
        'type' => Controls_Manager::SELECT,
        'label' => __('Conditional value', 'dynamicconditions'),
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => array_diff($valueCondition, ['in_array']),
          'dynamicconditions_type' => 'months',
        ],
        'description' => __('Add your conditional value to compare here.', 'dynamicconditions'),
        'options' => Date::getMonthsTranslated(),
      ]
    );

    $element->add_control(
      'dynamicconditions_month_value2',
      [
        'type' => Controls_Manager::SELECT,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['between'],
          'dynamicconditions_type' => 'months',
        ],
        'description' => __('Add a second condition value, if between is selected', 'dynamicconditions'),
        'options' => Date::getMonthsTranslated(),
      ]
    );


    $element->add_control(
      'dynamicconditions_in_array_description',
      [
        'type' => Controls_Manager::RAW_HTML,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['in_array'],
        ],
        'show_label' => false,
        'raw' => __('Use comma-separated values, to check if dynamic-value is equal with one of each item.', 'dynamicconditions'),
      ]
    );

    $element->add_control(
      'dynamicconditions_in_array_contains_description',
      [
        'type' => Controls_Manager::RAW_HTML,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => ['in_array_contains'],
        ],
        'show_label' => false,
        'raw' => __('Use comma-separated values, to check if dynamic-value contains one of each item.', 'dynamicconditions'),
      ]
    );

    $languageArray = explode('_', get_locale());
    $language = array_shift($languageArray);
    $element->add_control(
      'dynamicconditions_date_description',
      [
        'type' => Controls_Manager::RAW_HTML,
        'label' => __('Conditional value', 'dynamicconditions') . ' 2',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => $valueCondition,
          'dynamicconditions_type' => 'strtotime',
        ],
        'show_label' => false,
        'raw' => '<div class="elementor-control-field-description">'
          . '<a href="https://php.net/manual/' . $language . '/function.strtotime.php" target="_blank">'
          . __('Supported Date and Time Formats', 'dynamicconditions') . '</a></div>',
      ]
    );

    $element->add_control(
      'dynamicconditions_hr',
      [
        'type' => Controls_Manager::DIVIDER,
        'style' => 'thick',
        'condition' => [
          'dynamicconditions_condition' => $valueCondition,
        ],
      ]
    );

    $element->add_control(
      'dynamicconditions_hideContentOnly',
      [
        'type' => Controls_Manager::SWITCHER,
        'label' => __('Hide only content', 'dynamicconditions'),
        'description' => __('If checked, only the inner content will be hidden, so you will see an empty section', 'dynamicconditions'),
        'return_value' => 'on',
        'render_type' => $renderType,
        'condition' => [
          'dynamicconditions_condition' => $allCondition,
        ],
      ]
    );

    if ($type === 'column') {
      $element->add_control(
        'dynamicconditions_resizeOtherColumns',
        [
          'type' => Controls_Manager::SWITCHER,
          'label' => __('Resize other columns', 'dynamicconditions'),
          'render_type' => $renderType,
          'condition' => [
            'dynamicconditions_condition' => $allCondition,
            'dynamicconditions_hideContentOnly!' => 'on',
          ],
          'return_value' => 'on',
        ]
      );
    }


    $element->add_control(
      'dynamicconditions_headline_expert',
      [
        'label' => __('Expert', 'dynamicconditions'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $element->add_control(
      'dynamicconditions_parse_shortcodes',
      [
        'type' => Controls_Manager::SWITCHER,
        'label' => __('Parse shortcodes', 'dynamicconditions'),
        'render_type' => $renderType,
      ]
    );

    $element->add_control(
      'dynamicconditions_prevent_date_parsing',
      [
        'type' => Controls_Manager::SWITCHER,
        'label' => __('Prevent date parsing', 'dynamicconditions'),
        'render_type' => $renderType,
      ]
    );


    $element->add_control(
      'dynamicconditions_hr3',
      [
        'type' => Controls_Manager::DIVIDER,
        'style' => 'thick',
      ]
    );


    $element->add_control(
      'dynamicconditions_hideWrapper',
      [
        'type' => Controls_Manager::TEXT,
        'label' => __('Hide wrapper', 'dynamicconditions'),
        'description' => __('Will hide a parent matching the selector.', 'dynamicconditions'),
        'placeholder' => 'selector',
        'render_type' => $renderType,
      ]
    );

    $element->add_control(
      'dynamicconditions_hideOthers',
      [
        'type' => Controls_Manager::TEXT,
        'label' => __('Hide other elements', 'dynamicconditions'),
        'description' => __('Will hide all other elements matching the selector.', 'dynamicconditions'),
        'placeholder' => 'selector',
        'render_type' => $renderType,
      ]
    );

    $element->add_control(
      'dynamicconditions_hr4',
      [
        'type' => Controls_Manager::DIVIDER,
        'style' => 'thick',
      ]
    );

    $element->add_control(
      'dynamicconditions_widget_id',
      [
        'type' => Controls_Manager::TEXT,
        'label' => __('Widget-ID', 'dynamicconditions'),
        'render_type' => $renderType,
        'description' => '<script>
              $dcWidgetIdInput = jQuery(\'.elementor-control-dynamicconditions_widget_id input\');
              $dcWidgetIdInput.val(elementor.getCurrentElement().model.id);
              $dcWidgetIdInput.attr(\'readonly\', true);
              $dcWidgetIdInput.on(\'focus click\', function() { this.select();document.execCommand(\'copy\'); });
              </script>',
      ]
    );

    $element->add_control(
      'dynamicconditions_hr5',
      [
        'type' => Controls_Manager::DIVIDER,
        'style' => 'thick',
      ]
    );

    $element->add_control(
      'dynamicconditions_debug',
      [
        'type' => Controls_Manager::SWITCHER,
        'label' => __('Debug-Mode', 'dynamicconditions'),
        'render_type' => $renderType,
      ]
    );

    $element->end_controls_section();
  }
}
