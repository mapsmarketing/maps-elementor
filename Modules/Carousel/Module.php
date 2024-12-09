<?php

namespace MAPSElementor\Modules\Carousel;

// use \ElementorPro\Base\Module_Base as Module_Base;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Carousel'];
  }

  public function get_name()
  {
    return 'maps-carousel';
  }
}
