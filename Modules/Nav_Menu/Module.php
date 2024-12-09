<?php

namespace MAPSElementor\Modules\Nav_Menu;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Nav_Menu'];
  }

  public function get_name()
  {
    return 'maps-nav-menu';
  }
}
