<?php

namespace MAPSElementor\Modules\CTA_Accordion;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

class Module extends \ElementorPro\Base\Module_Base
{
  public function get_widgets()
  {
    return ['CTA_Accordion'];
  }

  public function get_name()
  {
    return 'maps-cta-accordion';
  }
}
