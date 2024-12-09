<?php

namespace MAPSElementor\Modules\Slider_Tabs;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Slider_Tabs'];
  }

  public function get_name()
  {
    return 'maps-slider-tabs';
  }
}
