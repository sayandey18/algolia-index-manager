<?php

/**
 * Plugin Name: Algolia Index Manager
 * Plugin URI: https://github.com/sayandey18/algolia-index-manager
 * Description: Easily manage, sync, and optimize your Algolia search indexes directly from your WordPress dashboard. No coding required! It's fast, secure, and fully automated!
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Author: Sayan Dey
 * Author URI: https://github.com/sayandey18
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: algolia-index-manager
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'AIM_PLUGIN_VERSION', '1.0.0' );
define( 'AIM_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'AIM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AIM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include_once AIM_PLUGIN_DIR . 'includes/class-aim-plugin.php';

function aim_run_plugin() {
    $plugin = new AIMPlugin();
	$plugin->aim_load_dependencies();
	$plugin->aim_load_wpcli();
}

aim_run_plugin();
