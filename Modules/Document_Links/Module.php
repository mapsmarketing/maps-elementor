<?php

namespace MAPSElementor\Modules\Document_Links;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['Document_Links'];
  }

  public function get_name()
  {
    return 'maps-documents-links';
  }
}
