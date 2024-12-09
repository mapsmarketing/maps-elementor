<?php

namespace MAPSElementor;

use MAPSElementor\Modules\Dynamic_Tags\Tags\Documents;
use MAPSElementor\Modules\Dynamic_Tags\Tags\Post_Meta_Image;
use MAPSElementor\Modules\Dynamic_Tags\Tags\Post_Content;
use MAPSElementor\Controls\Group_Control_Object;

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

include_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Initilisation class which loads the entire plugin
 */
final class Plugin {
  /**
   * @var Plugin
   */
  private static $_instance;

  /**
   * @var array Module instances
   */
  private $modules = [];

  /**
   * @var array Include instances
   */
  private $incs = [];

  /**
   * @var array Library instances
   */
  public $libs = [];

  /**
   * @var WP_Package_Updater Plugin updater
   */
  private $updater;

  /**
   * Create instance of the plugin
   *
   * @return Plugin
   */
  public static function instance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Plugin constructor
   */
  private function __construct() {
    // Check if Elementor/Pro is active
    if (!is_plugin_active('elementor/elementor.php') || !is_plugin_active('elementor-pro/elementor-pro.php')) {
      return;
    }

    // Handle plugin updates.
    $this->updater = new \WP_Package_Updater('https://plugins.mapsmarketing.com.au', wp_normalize_path(MAPS_ELEMENTOR__FILE__), wp_normalize_path(plugin_dir_path(MAPS_ELEMENTOR__FILE__)));

    // Include any non-Elementor classes relevant for widgets
    $this->register_inc();
    // Include any non-Elementor classes which extend functionality of WordPress or other plugins
    $this->register_lib();

    // Enqueue site wide scripts and styles
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    // Register custom Elementor categories
    add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
    // Load in all Elementor widget modules
    add_action('elementor/init', [$this, 'register_modules']);
    // Load in all Elementor custom controls
    add_action('elementor/controls/register', [$this, 'register_controls']);

    // add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tags' ] );
    // add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ], 15, 1 );
  }

  /**
   * The function registers JavaScript files
   */
  public function enqueue_scripts() {
    wp_enqueue_script('vendor-js', MAPS_ELEMENTOR_ASSETS_URL . 'js/vendor.bundle.min.js', ['jquery', 'jquery-ui-core'], uniqid(), true);
  }

  /**
   * Load in custom includes
   */
  private function register_inc() {
    $classes = [
      'documents' => '\MAPSElementor\Modules\Documents\Inc\Documents'
    ];

    foreach ($classes as $class) {
      if (class_exists($class)) {
        $this->incs[ $class ] = new $class();
      }
    }
  }

  /**
   * Load in custom libraries
   */
  private function register_lib() {
    $classes = [
      'importer' => '\MAPSElementor\Lib\Events\Importer',
      // 'reorder' => '\MAPSElementor\Lib\Reorder\Reorder',
      // 'conditions' => '\MAPSElementor\Lib\Conditions\Conditions'
    ];

    foreach ($classes as $class) {
      if (class_exists($class)) {
        $this->libs[ $class ] = new $class();
      }
    }
  }

  /**
   * Elementor custom categories
   *
   * @param $elements_manager Class which allows registering custom categories/groups
   */
  public function register_categories($elements_manager) {
    $elements_manager->add_category('maps-marketing', [
      'title' => __('MAPS Marketing', 'maps-marketing'),
      'icon' => 'fas fa-plug'
    ]);
  }

  /**
   * Elementor custom dynamic tag
   *
   * @param $dynamic_tags Class which allows registering of custom dynamic tags
   */
  public function register_tags($dynamic_tags_manager) {
    $dynamic_tags_manager->register_group('maps-dynamic-tags', [
      'title' => esc_html__('MAPS Marketing', 'maps-marketing')
    ]);

    $dynamic_tags_manager->register(new Documents);
    $dynamic_tags_manager->register(new Post_Meta_Image);
    $dynamic_tags_manager->register(new Post_Content);
  }

  /**
   * Elementor custom controls
   *
   * @param $controls_manager Class which allows the registering of custom controls
   */
  public function register_controls($controls_manager) {
    // $controls_manager->register( 'file-select', new \FileSelect_Control() );
    $controls_manager->add_group_control('maps-group-control-object', new Group_Control_Object);
  }

  /**
   * Elementor custom widgets, controls, dynamic tags, etc. module loader
   */
  public function register_modules() {
    $modules_path = MAPS_ELEMENTOR_PATH . 'Modules';
    $directory_iterator = new \DirectoryIterator($modules_path);

    foreach ($directory_iterator as $fileinfo) {
      if ($fileinfo->isDir() && !$fileinfo->isDot()) {
        $module_name = $fileinfo->getFilename();
        $class_name = '\MAPSElementor\Modules\\' . $module_name . '\Module';

        if (class_exists($class_name) && method_exists($class_name, 'is_active') && $class_name::is_active()) {
          $this->modules[$class_name] = $class_name::instance();
        }
      }
    }
  }
}
