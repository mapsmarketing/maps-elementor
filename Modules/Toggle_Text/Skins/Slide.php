<?php

namespace MAPSElementor\Modules\Toggle_Text\Skins;

use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Slide extends \Elementor\Skin_Base
{
  public function get_id()
  {
    return 'slide';
  }

  public function get_title()
  {
    return __('Slide', 'maps-marketing');
  }

  public function render()
  {
    $settings = $this->parent->get_settings_for_display(); ?>

    <div class="maps-toggle-text">
      <div class="maps-toggle-text__content">
        <div <?php $this->parent->print_render_attribute_string('text'); ?>>
          <?php echo $settings['text']; ?>
        </div>
      </div>
      <div class="maps-toggle-text__footer">
        <button type="button" class="maps-toggle-text__btn elementor-button">
          <span <?php $this->parent->print_render_attribute_string('button'); ?>>
            <?php echo $settings['button']; ?>
          </span>
        </button>
      </div>
    </div>
  <?php
  }
}
