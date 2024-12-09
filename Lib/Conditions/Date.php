<?php

namespace MAPSElementor\Lib\Conditions;

use WP_Post;

if (!defined('ABSPATH')) {
  die;
}

class Date {
  public function filterDateI18n($formatedDate, $reqFormat, $unixTimestamp, $gmt) {
    return $unixTimestamp;
  }

  public function filterPostDate($theTime, $dateFormat, $post) {
    if (empty($dateFormat)) {
      $dateFormat = get_option('date_format');
    }

    $date = \DateTime::createFromFormat($dateFormat, self::unTranslateDate($theTime));

    if (empty($date)) {
      $date = \DateTime::createFromFormat($dateFormat,  $theTime);
    }

    if (empty($date)) {
      return $theTime;
    }

    return $date->getTimestamp();
  }

  public static function stringToTime($string = '') {
    $timestamp = $string;
    $strToTime = strtotime($string, time());
    if (!empty($strToTime) && !self::isTimestamp($timestamp)) {
      $timestamp = $strToTime;
    }

    return intval($timestamp);
  }

  public static function isTimestamp($string) {
    if (!is_numeric($string)) {
      return false;
    }
    try {
      new \DateTime('@' . $string);
    } catch (\Exception $e) {
      return false;
    }
    return true;
  }

  public static function unTranslateDate($needle = '', $setLocale = null) {

    $translatedMonths = self::getMonthsTranslated();
    $translatedDays = self::getDaysTranslated();

    $englishMonths = self::getMonths();
    $englishDays = self::getDays();

    $needle = str_ireplace($translatedDays, $englishDays, $needle);
    $needle = str_ireplace($translatedMonths, $englishMonths, $needle);

    return $needle;
  }

  public static function getMonthsTranslated() {
    $monthList = [];

    $monthList[1] = __('January');
    $monthList[2] = __('February');
    $monthList[3] = __('March');
    $monthList[4] = __('April');
    $monthList[5] = __('May');
    $monthList[6] = __('June');
    $monthList[7] = __('July');
    $monthList[8] = __('August');
    $monthList[9] = __('September');
    $monthList[10] = __('October');
    $monthList[11] = __('November');
    $monthList[12] = __('December');

    return $monthList;
  }

  private static function getMonths() {
    $monthList = [];
    $monthList[1] = 'January';
    $monthList[2] = 'February';
    $monthList[3] = 'March';
    $monthList[4] = 'April';
    $monthList[5] = 'May';
    $monthList[6] = 'June';
    $monthList[7] = 'July';
    $monthList[8] = 'August';
    $monthList[9] = 'September';
    $monthList[10] = 'October';
    $monthList[11] = 'November';
    $monthList[12] = 'December';

    return $monthList;
  }

  public static function getDaysTranslated() {
    $dayList = [];

    $dayList[1] = __('Monday');
    $dayList[2] = __('Tuesday');
    $dayList[3] = __('Wednesday');
    $dayList[4] = __('Thursday');
    $dayList[5] = __('Friday');
    $dayList[6] = __('Saturday');
    $dayList[7] = __('Sunday');

    return $dayList;
  }

  private static function getDays() {
    $dayList = [];
    $dayList[1] = 'Monday';
    $dayList[2] = 'Tuesday';
    $dayList[3] = 'Wednesday';
    $dayList[4] = 'Thursday';
    $dayList[5] = 'Friday';
    $dayList[6] = 'Saturday';
    $dayList[7] = 'Sunday';

    return $dayList;
  }

  public static function setLocale($locale) {
    $localeSettings = explode(";", $locale);

    foreach ($localeSettings as $localeSetting) {
      if (strpos($localeSetting, "=") !== false) {
        $categorylocale = explode("=", $localeSetting);
        $category = $categorylocale[0];
        $locale = $categorylocale[1];
      } else {
        $category = LC_ALL;
        $locale = $localeSetting;
      }

      if (is_string($category) && defined($category)) {
        $category = constant($category);
      }

      if (!is_integer($category)) {
        continue;
      }

      setlocale($category, $locale);
    }
  }
}
