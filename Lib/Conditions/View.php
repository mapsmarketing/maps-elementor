<?php

namespace MAPSElementor\Lib\Conditions;

use Elementor\Plugin;
use ElementorPro\Modules\ThemeBuilder\Module;
use MAPSElementor\Lib\Conditions\Date;

if (!defined('ABSPATH')) {
  die;
}

class View {

  private $elementSettings = [];

  private $dateInstance;

  private static $debugCssRendered = false;

  private $shortcodeTags = [];

  /**
   * The function initializes a new Date instance in PHP.
   */
  public function __construct() {
    $this->dateInstance = new Date();
  }

  /**
   * The function `getElementSettings` retrieves and organizes settings for a given element, including
   * dynamic conditions and date parsing.
   * 
   * @param element The `getElementSettings` function you provided seems to be handling settings for a
   * specific element. It takes an `` parameter, which is an object representing the element
   * for which the settings are being retrieved.
   * 
   * @return The `getElementSettings` function returns an array containing various settings and data
   * related to the provided element. This includes settings for dynamic conditions, date parsing,
   * field values, tag data, and other information specific to the element. The returned array is
   * stored in the `elementSettings` array with the element's ID as the key.
   */
  private function getElementSettings($element) {
    $id = $element->get_id();

    $clonedElement = clone $element;

    $fields = '__dynamic__
            dynamicconditions_dynamic
            dynamicconditions_condition
            dynamicconditions_type
            dynamicconditions_resizeOtherColumns
            dynamicconditions_hideContentOnly
            dynamicconditions_visibility
            dynamicconditions_day_value
            dynamicconditions_day_value2
            dynamicconditions_day_array_value
            dynamicconditions_month_value
            dynamicconditions_month_value2
            dynamicconditions_month_array_value
            dynamicconditions_date_value
            dynamicconditions_date_value2
            dynamicconditions_value
            dynamicconditions_value2
            dynamicconditions_parse_shortcodes
            dynamicconditions_debug
            dynamicconditions_hideOthers
            dynamicconditions_hideWrapper
            _column_size
            _inline_size';

    $fieldArray = explode("\n", $fields);

    $this->elementSettings[$id]['dynamicconditions_dynamic_raw'] = $element->get_settings_for_display('dynamicconditions_dynamic');

    $preventDateParsing = $element->get_settings_for_display('dynamicconditions_prevent_date_parsing');
    $this->elementSettings[$id]['preventDateParsing'] = $preventDateParsing;

    if (empty($preventDateParsing)) {

      $currentLocale = setlocale(LC_ALL, 0);
      setlocale(LC_ALL, 'en_GB');
      add_filter('date_i18n', [$this->dateInstance, 'filterDateI18n'], 10, 4);
      add_filter('get_the_date', [$this->dateInstance, 'filterPostDate'], 10, 3);
      add_filter('get_the_modified_date', [$this->dateInstance, 'filterPostDate'], 10, 3);
    }

    foreach ($fieldArray as $field) {
      $field = trim($field);
      $this->elementSettings[$id][$field] = $clonedElement->get_settings_for_display($field);
    }
    unset($clonedElement);

    if (empty($preventDateParsing)) {
      remove_filter('date_i18n', [$this->dateInstance, 'filterDateI18n'], 10);
      remove_filter('get_the_date', [$this->dateInstance, 'filterPostDate'], 10);
      remove_filter('get_the_modified_date', [$this->dateInstance, 'filterPostDate'], 10);

      Date::setLocale($currentLocale);
    }

    $tagData = $this->getDynamicTagData($id);
    $this->convertAcfDate($id, $tagData);

    $this->elementSettings[$id]['dynamicConditionsData'] = [
      'id' => $id,
      'type' => $element->get_type(),
      'name' => $element->get_name(),
      'selectedTag' => $tagData['selectedTag'],
      'tagData' => $tagData['tagData'],
      'tagKey' => $tagData['tagKey'],
    ];

    return $this->elementSettings[$id];
  }

  /**
   * The function `getDynamicTagData` retrieves dynamic tag data based on certain conditions and
   * settings, while the function `convertAcfDate` converts ACF date values to timestamps under
   * specific conditions.
   * 
   * @param id It seems like you have shared some code snippets related to handling dynamic tag data
   * and converting ACF date fields. However, you have mentioned the parameters "id:" without providing
   * any specific value. Could you please provide the specific value for the "id" parameter so that I
   * can assist you further with understanding
   * 
   * @return The `getDynamicTagData` function returns an array with keys 'selectedTag', 'tagData', and
   * 'tagKey'. The `convertAcfDate` function does not return anything, it updates the
   * `dynamicconditions_dynamic` value in the `` array.
   */
  private function getDynamicTagData($id) {
    $dynamicEmpty = empty($this->elementSettings[$id]['__dynamic__'])
      || empty($this->elementSettings[$id]['__dynamic__']['dynamicconditions_dynamic']);
    $staticEmpty = empty($this->elementSettings[$id]['dynamicconditions_dynamic'])
      || empty($this->elementSettings[$id]['dynamicconditions_dynamic']['url']);

    if ($dynamicEmpty && $staticEmpty) {

      return [
        'selectedTag' => null,
        'tagData' => null,
        'tagKey' => null,
      ];
    }

    $selectedTag = null;
    $tagSettings = null;
    $tagData = [];
    $tagKey = null;

    if ($dynamicEmpty) {

      $this->elementSettings[$id]['__dynamic__'] = [
        'dynamicconditions_dynamic' => $this->elementSettings[$id]['dynamicconditions_dynamic'],
      ];
      $selectedTag = 'static';
    }

    $tag = $this->elementSettings[$id]['__dynamic__']['dynamicconditions_dynamic'];
    if (is_array($tag)) {
      return [
        'selectedTag' => null,
        'tagData' => null,
        'tagKey' => null,
      ];
    }
    $splitTag = explode(' name="', $tag);

    if (!empty($splitTag[1])) {
      $splitTag2 = explode('"', $splitTag[1]);
      $selectedTag = $splitTag2[0];
    }

    if (strpos($selectedTag, 'acf-') === 0) {
      $splitTag = explode(' settings="', $tag);
      if (!empty($splitTag[1])) {
        $splitTag2 = explode('"', $splitTag[1]);
        $tagSettings = json_decode(urldecode($splitTag2[0]), true);
        if (!empty($tagSettings['key'])) {
          $tagKey = $tagSettings['key'];
          $tagData = get_field_object(explode(':', $tagSettings['key'])[0]);
        }
      }
    }

    return [
      'selectedTag' => $selectedTag,
      'tagData' => $tagData,
      'tagKey' => $tagKey,
    ];
  }

  private function convertAcfDate($id, array $data) {
    if (empty($data)) {
      return;
    }

    if (!empty($this->elementSettings[$id]['preventDateParsing'])) {
      return;
    }

    $allowedTypes = [
      'date_time_picker',
      'date_picker',
    ];

    $tagData = $data['tagData'];

    if (empty($data['tagKey']) || strpos($data['selectedTag'], 'acf-') !== 0) {
      return;
    }

    if (empty($tagData['type']) || !in_array(trim($tagData['type']), $allowedTypes, true)) {
      return;
    }

    if (empty($tagData['value']) || empty($tagData['return_format'])) {
      return;
    }

    $time = \DateTime::createFromFormat($tagData['return_format'], Date::unTranslateDate($tagData['value']));

    if (empty($time)) {
      return;
    }

    if ($tagData['type'] === 'date_picker') {
      $time->setTime(0, 0, 0);
    }

    $timestamp = $time->getTimestamp();

    $this->elementSettings[$id]['dynamicconditions_dynamic'] = $timestamp;
  }

  /**
   * The function `checkPopupsCondition` checks conditions for popups and removes them from a location
   * manager if they meet certain criteria.
   * 
   * @param locationManager The `locationManager` parameter in the `checkPopupsCondition` function
   * seems to be an object that is responsible for managing the locations of popups. It likely has
   * methods such as `remove_doc_from_location` that allow you to manipulate the popups displayed at
   * different locations on a website.
   * 
   * @return If the mode is not 'website', nothing is being returned from the function.
   */
  public function checkPopupsCondition($locationManager) {
    if ($this->getMode() !== 'website') {
      return;
    }

    $conditionManager = Module::instance()->get_conditions_manager();
    $module = $conditionManager->get_documents_for_location('popup');

    foreach ($module as $documentId => $document) {
      $settings = $this->getElementSettings($document);
      $hide = $this->checkCondition($settings);

      if ($hide) {
        $locationManager->remove_doc_from_location('popup', $documentId);
      }
    }
  }

  /**
   * The function filters section content before displaying it based on certain conditions.
   * 
   * @param section The `filterSectionContentBefore` function takes a `` parameter as input.
   * This parameter is used to filter the content of a section before displaying it on the page. The
   * function checks the mode, element settings, and conditions to determine if the section content
   * should be hidden or modified.
   * 
   * @return If the `getMode()` method returns `'edit'`, nothing will be returned. Otherwise, if the
   * condition `` is true, nothing will be returned as well.
   */
  public function filterSectionContentBefore($section) {
    if ($this->getMode() === 'edit') {
      return;
    }

    $settings = $this->getElementSettings($section);
    $hide = $this->checkCondition($settings);

    if (!$hide) {
      return;
    }

    $section->dynamicConditionIsHidden = true;
    $section->dynamicConditionSettings = $settings;

    $this->shortcodeTags += $GLOBALS['shortcode_tags'];
    $GLOBALS['shortcode_tags'] = [];

    ob_start();
  }

  /**
   * This PHP function filters and modifies content based on dynamic conditions set for a specific
   * section.
   * 
   * @param section The `filterSectionContentAfter` function takes a `` parameter as input.
   * This parameter is used to filter and modify the content of a specific section after certain
   * conditions are met. The function performs various actions based on the properties and settings of
   * the `` object.
   * 
   * @return If the condition `empty() || empty(->dynamicConditionIsHidden)` is met,
   * then the function will return early and not execute the rest of the code block.
   */
  public function filterSectionContentAfter($section) {

    $GLOBALS['shortcode_tags'] += $this->shortcodeTags;
    if (empty($section) || empty($section->dynamicConditionIsHidden)) {
      return;
    }

    $content = ob_get_clean();
    $matches = [];
    $regex = preg_match('/<link.*?\/?>/', $content, $matches);
    echo implode('', $matches);

    $type = $section->get_type();
    $settings = $section->dynamicConditionSettings;

    if (!empty($settings['dynamicconditions_hideContentOnly'])) {

      $section->before_render();
      $section->after_render();
    } else if ($type == 'column' && $settings['dynamicconditions_resizeOtherColumns']) {
      echo '<div class="dc-hidden-column" data-size="' . $settings['_column_size'] . '"></div>';
    }

    if (!empty($settings['dynamicconditions_hideWrapper'])) {
      echo '<div class="dc-hide-wrapper" data-selector="' . $settings['dynamicconditions_hideWrapper'] . '"></div>';
    }

    if (!empty($settings['dynamicconditions_hideOthers'])) {
      echo '<div class="dc-hide-others" data-selector="' . $settings['dynamicconditions_hideOthers'] . '"></div>';
    }

    echo "<!-- hidden $type -->";
  }

  /**
   * The function `checkCondition` checks a condition based on settings and returns a boolean value
   * indicating whether to hide or show based on the condition and visibility settings.
   * 
   * @param settings The `checkCondition` function takes in a parameter called ``. This
   * parameter is used to determine the visibility of a certain condition based on the mode and other
   * settings.
   * 
   * @return The function `checkCondition` is returning a boolean value based on the conditions
   * evaluated within the function. The return value will be `true` if the condition to hide is met,
   * and `false` otherwise.
   */
  public function checkCondition($settings) {
    if (!$this->hasCondition($settings)) {
      return false;
    }

    if ($this->getMode() === 'edit') {
      return false;
    }

    $condition = $this->loopValues($settings);

    $hide = false;

    $visibility = self::checkEmpty($settings, 'dynamicconditions_visibility', 'hide');
    switch ($visibility) {
      case 'show':
        if (!$condition) {
          $hide = true;
        }
        break;
      case 'hide':
      default:
        if ($condition) {
          $hide = true;
        }
        break;
    }

    return $hide;
  }

  /**
   * The function "loopValues" iterates through dynamic tag values, compares them based on specified
   * conditions, and returns a final condition result.
   * 
   * @param settings Based on the provided code snippet, the `loopValues` function takes in a parameter
   * named ``. This parameter is used within the function to perform various operations such
   * as retrieving dynamic tag values, comparing values, parsing shortcodes, and rendering debug
   * information.
   * 
   * @return The function `loopValues` is returning the value of the variable ``, which is
   * determined based on the comparisons and conditions within the loop.
   */
  private function loopValues($settings) {
    $condition = false;
    $dynamicTagValueArray = self::checkEmpty($settings, 'dynamicconditions_dynamic');

    if (!is_array($dynamicTagValueArray)) {
      $dynamicTagValueArray = [$dynamicTagValueArray];
    }

    $compareType = self::checkEmpty($settings, 'dynamicconditions_type', 'default');
    $checkValues = $this->getCheckValue($compareType, $settings);
    $checkValue = $checkValues[0];
    $checkValue2 = $checkValues[1];

    $debugValue = '';

    foreach ($dynamicTagValueArray as $dynamicTagValue) {
      if (is_array($dynamicTagValue)) {
        if (!empty($dynamicTagValue['id'])) {
          $dynamicTagValue = wp_get_attachment_url($dynamicTagValue['id']);
        } else {
          continue;
        }
      }

      if (!empty($settings['dynamicconditions_parse_shortcodes'])) {
        $dynamicTagValue = do_shortcode($dynamicTagValue);
      }

      $this->parseDynamicTagValue($dynamicTagValue, $compareType);

      $debugValue .= $dynamicTagValue . '~~*#~~';

      $compareValues = $this->compareValues($settings['dynamicconditions_condition'], $dynamicTagValue, $checkValue, $checkValue2);
      $condition = $compareValues[0];
      $break = $compareValues[1];
      $breakFalse = $compareValues[2];

      if ($break && $condition) {

        break;
      }

      if ($breakFalse && !$condition) {

        break;
      }
    }

    $this->renderDebugInfo($settings, $debugValue, $checkValue, $checkValue2, $condition);

    return $condition;
  }

  /**
   * The function `compareValues` in PHP compares dynamic tag values based on different conditions like
   * equality, containment, emptiness, numeric comparisons, array checks, and more.
   * 
   * @param compare The `compareValues` function you provided is a PHP function that takes in four
   * parameters: ``, ``, ``, and ``.
   * @param dynamicTagValue The `dynamicTagValue` parameter in the `compareValues` function represents
   * the value that you want to compare against other values based on the specified comparison
   * operation. It could be a variable holding a value that you want to evaluate using the comparison
   * logic defined in the function.
   * @param checkValue The `checkValue` parameter in the `compareValues` function represents the value
   * that is being compared against the `dynamicTagValue` based on the specified comparison operation.
   * It could be a string, number, or an array depending on the comparison operation being performed.
   * @param checkValue2 The `checkValue2` parameter in the `compareValues` function seems to be used in
   * the 'between' case. In this case, the function checks if the `dynamicTagValue` is between
   * `checkValue` and `checkValue2` inclusively.
   * 
   * @return The `compareValues` function returns an array containing three elements:
   * 1. The result of the comparison operation based on the specified condition.
   * 2. A boolean flag indicating whether to break out of the loop or not (true if break is needed).
   * 3. A boolean flag indicating whether to break out of the loop with a false condition (true if
   * break is needed with a false condition).
   */
  private function compareValues($compare, $dynamicTagValue, $checkValue, $checkValue2) {
    $break = false;
    $breakFalse = false;
    $condition = false;

    switch ($compare) {
      case 'equal':
        $condition = $checkValue == $dynamicTagValue;
        $break = true;
        break;

      case 'not_equal':
        $condition = $checkValue != $dynamicTagValue;
        $breakFalse = true;
        break;

      case 'contains':
        if (empty($checkValue)) {
          break;
        }
        $condition = strpos($dynamicTagValue, $checkValue) !== false;
        $break = true;
        break;

      case 'not_contains':
        if (empty($checkValue)) {
          break;
        }
        $condition = strpos($dynamicTagValue, $checkValue) === false;
        $breakFalse = true;
        break;

      case 'empty':
        $condition = empty($dynamicTagValue);
        $breakFalse = true;
        break;

      case 'not_empty':
        $condition = !empty($dynamicTagValue);
        $break = true;
        break;

      case 'less':
        if (is_numeric($dynamicTagValue)) {
          $condition = $dynamicTagValue < $checkValue;
        } else {
          $condition = strlen($dynamicTagValue) < strlen($checkValue);
        }
        $break = true;
        break;

      case 'greater':
        if (is_numeric($dynamicTagValue)) {
          $condition = $dynamicTagValue > $checkValue;
        } else {
          $condition = strlen($dynamicTagValue) > strlen($checkValue);
        }
        $break = true;
        break;

      case 'between':
        $condition = $dynamicTagValue >= $checkValue && $dynamicTagValue <= $checkValue2;
        $break = true;
        break;

      case 'in_array':
        $condition = in_array($dynamicTagValue, explode(',', $checkValue)) !== false;
        $break = true;
        break;

      case 'in_array_contains':
        foreach (explode(',', $checkValue) as $toCheck) {
          $condition = strpos($dynamicTagValue, $toCheck) !== false;
          if ($condition) {
            break;
          }
        }
        $break = true;
        break;
    }

    return [
      $condition,
      $break,
      $breakFalse,
    ];
  }

  /**
   * The function `parseDynamicTagValue` takes a dynamic tag value and a compare type as input, and
   * modifies the dynamic tag value based on the compare type, such as converting dates to specific
   * formats.
   * 
   * @param dynamicTagValue The `dynamicTagValue` parameter is a variable that holds a value which
   * needs to be parsed based on the `compareType` provided to the `parseDynamicTagValue` function. The
   * function will modify the `dynamicTagValue` based on the `compareType` specified in the switch
   * case.
   * @param compareType The `compareType` parameter in the `parseDynamicTagValue` function is used to
   * determine how the `dynamicTagValue` should be parsed. The function contains a switch statement
   * that checks the value of `compareType` and performs different actions based on the case.
   */
  private function parseDynamicTagValue(&$dynamicTagValue, $compareType) {
    switch ($compareType) {
      case 'days':
        $dynamicTagValue = date('N', Date::stringToTime($dynamicTagValue));
        break;

      case 'months':
        $dynamicTagValue = date('n', Date::stringToTime($dynamicTagValue));
        break;

      case 'strtotime':

      case 'date':
        $dynamicTagValue = Date::stringToTime($dynamicTagValue);
        break;
    }
  }

  /**
   * The function `getCheckValue` in PHP retrieves and processes check values based on the specified
   * comparison type and settings.
   * 
   * @param compareType The `compareType` parameter in the `getCheckValue` function determines the type
   * of comparison being performed. It can have values like 'days', 'months', 'date', 'strtotime', or
   * 'default'.
   * @param settings The `getCheckValue` function takes two parameters: `` and ``.
   * The `` parameter is an array that contains various dynamic conditions values used for
   * comparison in the function. The function then processes these settings based on the ``
   * provided.
   * 
   * @return The `getCheckValue` function returns an array containing two values: `` and
   * ``.
   */
  private function getCheckValue($compareType, $settings) {

    switch ($compareType) {
      case 'days':
        if ($settings['dynamicconditions_condition'] === 'in_array') {
          $checkValue = self::checkEmpty($settings, 'dynamicconditions_day_array_value');
          $checkValue = $this->parseShortcode($checkValue, $settings);
          $checkValue = implode(',', $checkValue);
        } else {
          $checkValue = self::checkEmpty($settings, 'dynamicconditions_day_value');
          $checkValue = $this->parseShortcode($checkValue);
        }
        $checkValue2 = self::checkEmpty($settings, 'dynamicconditions_day_value2');
        $checkValue2 = $this->parseShortcode($checkValue2, $settings);
        $checkValue = Date::unTranslateDate($checkValue);
        $checkValue2 = Date::unTranslateDate($checkValue2);
        break;

      case 'months':
        if ($settings['dynamicconditions_condition'] === 'in_array') {
          $checkValue = self::checkEmpty($settings, 'dynamicconditions_month_array_value');
          $checkValue = $this->parseShortcode($checkValue, $settings);
          $checkValue = implode(',', $checkValue);
        } else {
          $checkValue = self::checkEmpty($settings, 'dynamicconditions_month_value');
          $checkValue = $this->parseShortcode($checkValue, $settings);
        }
        $checkValue2 = self::checkEmpty($settings, 'dynamicconditions_month_value2');
        $checkValue2 = $this->parseShortcode($checkValue2, $settings);
        $checkValue = Date::unTranslateDate($checkValue);
        $checkValue2 = Date::unTranslateDate($checkValue2);
        break;

      case 'date':
        $checkValue = self::checkEmpty($settings, 'dynamicconditions_date_value');
        $checkValue2 = self::checkEmpty($settings, 'dynamicconditions_date_value2');
        $checkValue = $this->parseShortcode($checkValue, $settings);
        $checkValue2 = $this->parseShortcode($checkValue2, $settings);
        $checkValue = Date::stringToTime($checkValue);
        $checkValue2 = Date::stringToTime($checkValue2);
        break;

      case 'strtotime':
        $checkValue = self::checkEmpty($settings, 'dynamicconditions_value');
        $checkValue2 = self::checkEmpty($settings, 'dynamicconditions_value2');
        $checkValue = $this->parseShortcode($checkValue, $settings);
        $checkValue2 = $this->parseShortcode($checkValue2, $settings);
        $checkValue = Date::stringToTime($checkValue);
        $checkValue2 = Date::stringToTime($checkValue2);
        break;

      case 'default':
      default:
        $checkValue = self::checkEmpty($settings, 'dynamicconditions_value');
        $checkValue2 = self::checkEmpty($settings, 'dynamicconditions_value2');
        $checkValue = $this->parseShortcode($checkValue, $settings);
        $checkValue2 = $this->parseShortcode($checkValue2, $settings);
        break;
    }

    return [
      $checkValue,
      $checkValue2,
    ];
  }

  /**
   * The `parseShortcode` function parses a shortcode in a given value based on settings, while the
   * `checkEmpty` function checks for the presence of a key in an array and returns the corresponding
   * value or a fallback.
   * 
   * @param value The `value` parameter in the `parseShortcode` function is the input value that needs
   * to be processed. It could be a string containing a shortcode that needs to be parsed.
   * @param settings The `parseShortcode` function takes two parameters: `` and ``. The
   * `` parameter is the content that may contain shortcodes to be parsed, and the ``
   * parameter is an optional array that can contain configuration settings. If the
   * `dynamicconditions_parse_shortcodes`
   * 
   * @return The `parseShortcode` function returns the parsed shortcode value if the
   * `dynamicconditions_parse_shortcodes` key is not empty in the `` array. Otherwise, it
   * returns the original ``.
   */
  private function parseShortcode($value, $settings = []) {
    if (empty($settings['dynamicconditions_parse_shortcodes'])) {
      return $value;
    }
    return do_shortcode($value);
  }

  public static function checkEmpty($array = [], $key = null, $fallback = null) {
    if (empty($key)) {
      return !empty($array) ? $array : $fallback;
    }

    return !empty($array[$key]) ? $array[$key] : $fallback;
  }

  /**
   * The function `hasCondition` checks if certain conditions are met in the provided settings array
   * and returns a boolean value accordingly.
   * 
   * @param settings The `hasCondition` function checks if the `dynamicconditions_condition` and
   * `selectedTag` keys are present and not empty in the `` array. If either of these keys is
   * empty, the function returns `false`, indicating that the condition is not met. Otherwise, it
   * returns `true
   * 
   * @return If the `dynamicconditions_condition` or `selectedTag` in the `dynamicConditionsData` array
   * of the `` parameter is empty, then `false` is being returned. Otherwise, `true` is being
   * returned.
   */
  public function hasCondition($settings) {
    if (
      empty($settings['dynamicconditions_condition']) || empty($settings['dynamicConditionsData']['selectedTag'])
    ) {

      return false;
    }

    return true;
  }

  /**
   * The function `renderDebugInfo` renders debug information based on certain conditions and settings.
   * 
   * @param settings The `settings` parameter in the `renderDebugInfo` function seems to be an array
   * containing various configuration settings for debugging purposes. It likely includes information
   * such as debug mode status, visibility settings, and dynamic raw data.
   * @param dynamicTagValue The `dynamicTagValue` parameter is a value that is being processed and
   * sanitized in the `renderDebugInfo` function. It is first checked if it is null using the null
   * coalescing operator `??` and then passed through `htmlentities` function to encode special
   * characters. Additionally, square
   * @param checkValue The `checkValue` parameter in the `renderDebugInfo` function is a variable that
   * is sanitized and prepared for output in the debug information. It is passed as an argument to the
   * function and then processed using the `str_replace`, `htmlentities`, and `checkEmpty` functions
   * before being included
   * @param checkValue2 The `checkValue2` parameter in the `renderDebugInfo` function is used as a
   * variable to store a value that will be sanitized and processed later in the function. It is
   * initially set to the value of the `` parameter passed to the function, or an empty
   * string if
   * @param conditionMets The `conditionMets` parameter seems to be missing from the list of parameters
   * in the `renderDebugInfo` function. If you need assistance with how to handle or use the
   * `conditionMets` parameter within the function, please let me know.
   * 
   * @return The `renderDebugInfo` function returns nothing if the condition
   * `['dynamicconditions_debug']` is false or if the current user does not have the
   * capability to edit posts or pages. If any of these conditions are met, the function will exit
   * early and not execute the rest of the code.
   */
  private function renderDebugInfo($settings, $dynamicTagValue, $checkValue, $checkValue2, $conditionMets) {
    if (!$settings['dynamicconditions_debug']) {
      return;
    }

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
      return;
    }

    $visibility = self::checkEmpty($settings, 'dynamicconditions_visibility', 'hide');

    $dynamicTagValue = str_replace('[', '&#91;', htmlentities($dynamicTagValue ?? ''));
    $dynamicTagValue = str_replace('~~*#~~', '<br />', $dynamicTagValue);
    $checkValue = str_replace('[', '&#91;', htmlentities($checkValue ?? ''));
    $checkValue2 = str_replace('[', '&#91;', htmlentities($checkValue2 ?? ''));
    $dynamicTagValueRaw = self::checkEmpty($settings, 'dynamicconditions_dynamic_raw', '');

    include('views/debug.php');

    $this->renderDebugCss();
  }

  /**
   * The function renderDebugCss() is a private PHP method that includes and outputs the contents of a
   * debug.css file within a <style> tag, ensuring it is only rendered once.
   * 
   * @return If `self::` is true, the function will return early and nothing will be
   * output.
   */
  private function renderDebugCss() {
    if (self::$debugCssRendered) {
      return;
    }
    self::$debugCssRendered = true;

    echo '<style>';
    include('css/debug.css');
    echo '</style>';
  }

  /**
   * The function `getMode()` determines the mode (edit, preview, or website) based on Elementor
   * Plugin's existence and current state.
   * 
   * @return The function `getMode()` will return one of the following values:
   * - 'edit' if Elementor editor is in edit mode
   * - 'preview' if Elementor editor is in preview mode
   * - 'website' if Elementor editor is not in edit or preview mode
   */
  private function getMode() {
    if (!class_exists('Elementor\Plugin')) {
      return '';
    }

    if (!empty(Plugin::$instance->editor) && Plugin::$instance->editor->is_edit_mode()) {
      return 'edit';
    }

    if (!empty(Plugin::$instance->preview) && Plugin::$instance->preview->is_preview_mode()) {
      return 'preview';
    }

    return 'website';
  }

  /**
   * The function `enqueueScripts` conditionally enqueues a JavaScript file in WordPress based on the
   * mode being 'edit'.
   * 
   * @return If the mode is 'edit', nothing is being returned. If the mode is not 'edit', the script is
   * being enqueued.
   */
  public function enqueueScripts() {
    if ($this->getMode() === 'edit') {
      return;
    }
    wp_enqueue_script('maps-conditions-public', MAPS_ELEMENTOR_ASSETS_URL . '/js/maps-conditions-public.bundle.min.js', ['jquery'], uniqid(), true);
  }
}
