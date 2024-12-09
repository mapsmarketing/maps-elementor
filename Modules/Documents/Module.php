<?php

namespace MAPSElementor\Modules\Documents;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Documents'];
  }

  public function get_name()
  {
    return 'maps-documents';
  }
}
