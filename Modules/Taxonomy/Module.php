<?php

namespace MAPSElementor\Modules\Taxonomy;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Taxonomy'];
  }

  public function get_name()
  {
    return 'maps-taxonomy';
  }
}
