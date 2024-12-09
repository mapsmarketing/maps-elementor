<?php
/*
Plugin Name: MAPS Elementor
Plugin URI: https://mapsmarketing.com.au/
Description: Custom developed Elementor widgets and tools
Version: 1.3.1
Author: MAPS Marketing
Author URI: https://mapsmarketing.com.au/
Requires at least: 6.5
Tested up to: 6.7
Requires PHP: 7.4
Icon1x: https://plugins.mapsmarketing.com.au/wp-content/uploads/2020/09/wordpress-MAPS-logo-adminbar-new.png
BannerHigh: https://plugins.mapsmarketing.com.au/wp-content/uploads/2024/03/217807404_4125760467512970_866057697495801250_n.jpg
*/

/**
 * Icon1x: https://plugins.mapsmarketing.com.au/wp-content/uploads/2020/09/wordpress-MAPS-logo-adminbar-new.png
 * Icon2x: https://plugins.mapsmarketing.com.au/wp-content/uploads/2020/09/wordpress-MAPS-logo-adminbar-new.png
 * BannerHigh: https://plugins.mapsmarketing.com.au/wp-content/uploads/2024/03/217807404_4125760467512970_866057697495801250_n.jpg
 * BannerLow: https://plugins.mapsmarketing.com.au/wp-content/uploads/2024/03/217807404_4125760467512970_866057697495801250_n.jpg
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

// Autload classes from vendor from the plugin
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

define('MAPS_ELEMENTOR_VERSION', '1.3.1');
define('MAPS_ELEMENTOR__FILE__', __FILE__);
define('MAPS_ELEMENTOR_PLUGIN_BASE', plugin_basename(MAPS_ELEMENTOR__FILE__));
define('MAPS_ELEMENTOR_PATH', plugin_dir_path(MAPS_ELEMENTOR__FILE__));
define('MAPS_ELEMENTOR_ASSETS_PATH', MAPS_ELEMENTOR_PATH . 'assets/');
define('MAPS_ELEMENTOR_MODULES_PATH', MAPS_ELEMENTOR_PATH . 'Modules/');
define('MAPS_ELEMENTOR_URL', plugins_url('/', MAPS_ELEMENTOR__FILE__));
define('MAPS_ELEMENTOR_ASSETS_URL', MAPS_ELEMENTOR_URL . 'assets/');
define('MAPS_ELEMENTOR_MODULES_URL', MAPS_ELEMENTOR_URL . 'Modules/');

// Instantiate the plugin and load all functionality
\MAPSElementor\Plugin::instance();
