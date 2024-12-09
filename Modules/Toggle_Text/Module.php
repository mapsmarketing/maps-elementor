<?php

namespace MAPSElementor\Modules\Toggle_Text;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_name()
  {
    return 'maps-toggle-text';
  }

  public function get_widgets()
  {
    return ['Toggle_Text'];
  }
}
