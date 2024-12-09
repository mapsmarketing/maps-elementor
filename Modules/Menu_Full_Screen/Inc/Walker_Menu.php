<?php

namespace MAPSElementor\Modules\Menu_Full_Screen\Inc;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Walker_Menu extends \Walker_Nav_Menu {
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $indent = str_repeat("\t", $depth);

    // Add the classes for the `<li>` element
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
    $id = $id ? ' id="' . esc_attr($id) . '"' : '';

    // Open the `<li>` element
    $output .= $indent . '<li' . $id . $class_names . '>';

    // Attributes for the `<a>` tag
    $atts = [];
    $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
    $atts['target'] = !empty($item->target) ? $item->target : '';
    $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
    $atts['href']   = !empty($item->url) ? $item->url : '';

    // Add the `data-featured-image` attribute
    $atts['data-featured-image'] = get_the_post_thumbnail_url($item->object_id, 'full') ?: '';

    $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

    // Build the `<a>` tag attributes string
    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (!empty($value)) {
        $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    // Build the item output
    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    // Append the item output to the `$output`
    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  public function end_el(&$output, $item, $depth = 0, $args = null) {
    // Close the `<li>` element
    $output .= "</li>\n";
  }
}
