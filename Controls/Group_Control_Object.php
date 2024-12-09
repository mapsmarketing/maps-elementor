<?php

namespace MAPSElementor\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Group_Control_Object extends Group_Control_Base
{
  protected static $fields;

  public static function get_type()
  {
    return 'maps-group-control-object';
  }

  protected function init_fields()
  {
    $fields = [];

    $fields['height'] = [
      'label' => esc_html__('Height', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'responsive' => true,
      'size_units' => ['px', 'em', 'rem', '%'],
      'range' => [
        'px' => [
          'max' => 500,
        ],
      ],
      'selectors' => [
        '{{SELECTOR}}' => 'height: {{SIZE}}{{UNIT}};',
      ],
    ];

    $fields['object_fit'] = [
      'label' => esc_html__('Object Fit', 'maps-marketing'),
      'type' => Controls_Manager::SELECT,
      'responsive' => true,
      'options' => [
        '' => esc_html__('Default', 'maps-marketing'),
        'fill' => esc_html__('Fill', 'maps-marketing'),
        'cover' => esc_html__('Cover', 'maps-marketing'),
        'contain' => esc_html__('Contain', 'maps-marketing'),
      ],
      'default' => 'fill',
      'condition' => [
        'height[size]!' => '',
      ],
      'selectors' => [
        '{{SELECTOR}}' => 'object-fit: {{VALUE}};',
      ],
    ];

    $fields['object_position'] = [
      'label' => esc_html__('Object Position', 'maps-marketing'),
      'type' => Controls_Manager::SELECT,
      'responsive' => true,
      'options' => [
        'center center' => esc_html__('Center Center', 'maps-marketing'),
        'center left' => esc_html__('Center Left', 'maps-marketing'),
        'center right' => esc_html__('Center Right', 'maps-marketing'),
        'top center' => esc_html__('Top Center', 'maps-marketing'),
        'top left' => esc_html__('Top Left', 'maps-marketing'),
        'top right' => esc_html__('Top Right', 'maps-marketing'),
        'bottom center' => esc_html__('Bottom Center', 'maps-marketing'),
        'bottom left' => esc_html__('Bottom Left', 'maps-marketing'),
        'bottom right' => esc_html__('Bottom Right', 'maps-marketing'),
        '' => esc_html__('Custom', 'maps-marketing'),
      ],
      'default' => 'center center',
      'condition' => [
        'object_fit' => 'cover',
      ],
      'selectors' => [
        '{{SELECTOR}}' => 'object-position: {{VALUE}};',
      ],
    ];

    $fields['position_x'] = [
      'label' => esc_html__('Position X', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'responsive' => true,
      'range' => [
        'px' => [
          'min' => -1000,
          'max' => 1000,
        ],
      ],
      'default' => [
        'size' => 0,
      ],
      'condition' => [
        'object_fit' => 'cover',
        'object_position' => '',
      ],
      'selectors' => [
        '{{SELECTOR}}' =>
          'object-position: {{SIZE}}{{UNIT}} {{position_y.SIZE}}{{position_y.UNIT}};',
      ],
    ];

    $fields['position_y'] = [
      'label' => esc_html__('Position Y', 'maps-marketing'),
      'type' => Controls_Manager::SLIDER,
      'responsive' => true,
      'range' => [
        'px' => [
          'min' => -1000,
          'max' => 1000,
        ],
      ],
      'default' => [
        'size' => 0,
      ],
      'condition' => [
        'object_fit' => 'cover',
        'object_position' => '',
      ],
      'selectors' => [
        '{{SELECTOR}}' =>
          'object-position: {{position_x.SIZE}}{{position_x.UNIT}} {{SIZE}}{{UNIT}};',
      ],
    ];

    return $fields;
  }
}
