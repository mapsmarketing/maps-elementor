<?php

namespace MAPSElementor\Modules\Teams;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Teams'];
  }

  public function get_name()
  {
    return 'maps-teams';
  }
}
