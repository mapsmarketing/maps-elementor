<?php

namespace MAPSElementor\Modules\Documents\Inc;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly.
}

class Documents
{
  /**
   * The function is used to initialize various actions in WordPress, such as registering custom post
   * types and taxonomies, and redirecting templates.
   */
  public function __construct()
  {
    add_action('init', [$this, 'acf']);
    add_action('init', [$this, 'register_post_types']);
    add_action('init', [$this, 'register_taxonomies']);
    add_action('template_redirect', [$this, 'redirect']);
  }

  /**
   * The above function is used to add a custom field group for the "MAPS Documents" post type in
   * WordPress using the Advanced Custom Fields (ACF) plugin.
   */
  public function acf()
  {
    if (function_exists('acf_add_local_field_group')) {
      acf_add_local_field_group([
        'key' => 'group_60bd9ea1e635e',
        'title' => 'MAPS Documents',
        'fields' => [
          [
            'key' => 'field_60cbf3f0dff9a',
            'label' => 'Type of Document?',
            'name' => 'type_of_document',
            'type' => 'select',
            'instructions' => 'Choose \'Media Library\' if it\'s an uploaded document or \'External Link\' if you are linking it to a document not located on your website.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => ''
            ],
            'choices' => [
              'media' => 'Media Library',
              'external' => 'External Link'
            ],
            'default_value' => 'media',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => ''
          ],
          [
            'key' => 'field_60bd9ea9f795a',
            'label' => 'File',
            'name' => 'file',
            'type' => 'file',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_60cbf3f0dff9a',
                  'operator' => '==',
                  'value' => 'media'
                ]
              ]
            ],
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => ''
            ],
            'return_format' => 'id',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => ''
          ],
          [
            'key' => 'field_60cbf4b6dff9b',
            'label' => 'Link',
            'name' => 'link',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_60cbf3f0dff9a',
                  'operator' => '==',
                  'value' => 'external'
                ]
              ]
            ],
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => ''
            ],
            'default_value' => '',
            'placeholder' => ''
          ],
          [
            'key' => 'field_6115f736292e8',
            'label' => 'Page Link',
            'name' => 'page_link',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => ''
            ],
            'post_type' => [
              0 => 'page'
            ],
            'taxonomy' => '',
            'filters' => [
              0 => 'search',
              1 => 'post_type'
            ],
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'id'
          ]
        ],
        'location' => [
          [
            [
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'maps_document'
            ]
          ]
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => [
          0 => 'permalink'
        ],
        'active' => true,
        'description' => ''
      ]);
    }
  }

  /**
   * The function `register_post_types()` registers a custom post type called "maps_document" with
   * various settings and options.
   */
  public function register_post_types()
  {
    register_post_type('maps_document', [
      'label' => __('Documents', 'maps-marketing'),
      'labels' => [
        'name' => __('Documents', 'maps-marketing'),
        'singular_name' => __('Document', 'maps-marketing')
      ],
      'description' => '',
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_rest' => true,
      'rest_base' => '',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
      'has_archive' => 'documents',
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'delete_with_user' => false,
      'exclude_from_search' => false,
      'capability_type' => 'post',
      'map_meta_cap' => true,
      'hierarchical' => false,
      'rewrite' => [
        'slug' => 'document',
        'with_front' => false
      ],
      'query_var' => true,
      'supports' => ['title'],
      'show_in_graphql' => false
    ]);
  }

  /**
   * The function `register_taxonomies()` registers a custom taxonomy called "maps_document_category"
   * for the "maps_document" post type in WordPress.
   */
  public function register_taxonomies()
  {
    register_taxonomy(
      'maps_document_category',
      ['maps_document'],
      [
        'label' => __('Document Categories', 'maps-marketing'),
        'labels' => [
          'name' => __('Document Categories', 'maps-marketing'),
          'singular_name' => __('Document Category', 'maps-marketing')
        ],
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'query_var' => true,
        'rewrite' => [
          'slug' => 'documents',
          'with_front' => false
        ],
        'show_admin_column' => false,
        'show_in_rest' => true,
        'rest_base' => 'maps_document_category',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'show_in_quick_edit' => true,
        'show_in_graphql' => false,
        'default_term' => [
          'name' => 'Uncategorised'
        ]
      ]
    );
  }

  /**
   * The function redirects the user to a specific link or file based on the type of document.
   */
  public function redirect()
  {
    if (is_singular('maps_document')) {
      $link = false;
      $type = get_field('type_of_document');

      if ($type == 'external') {
        $link = get_field('link');
      } else {
        $file = get_field('file');
        $link = wp_get_attachment_url($file);
      }

      wp_redirect($link);

      exit();
    }
  }
}
