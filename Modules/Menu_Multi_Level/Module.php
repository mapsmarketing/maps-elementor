<?php

namespace MAPSElementor\Modules\Menu_Multi_Level;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Menu_Multi_Level'];
  }

  public function get_name()
  {
    return 'maps-menu-multi-level';
  }
}
