<?php

namespace MAPSElementor\Modules\Posts;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Posts'];
  }

  public function get_name()
  {
    return 'posts';
  }
}
