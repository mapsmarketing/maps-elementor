<?php

namespace MAPSElementor\Modules\Toggle_Timeline;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Toggle_Timeline'];
  }

  public function get_name()
  {
    return 'maps-toggle-timeline';
  }
}
