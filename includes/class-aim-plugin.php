<?php

use AIMPlugin\WPCLI\Command;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AIMPlugin {
    /**
     * Load the plugin dependencies.
     * 
     * @return void
     */
    public function aim_load_dependencies() {
        require_once AIM_PLUGIN_DIR . 'includes/vendor/autoload.php';
        require_once AIM_PLUGIN_DIR . 'includes/class-aim-namespace.php';
        require_once AIM_PLUGIN_DIR . 'includes/class-aim-tools.php';
        require_once AIM_PLUGIN_DIR . 'includes/class-aim-handler.php';
        require_once AIM_PLUGIN_DIR . 'includes/class-aim-wpcli.php';
        require_once AIM_PLUGIN_DIR . 'admin/class-aim-admin.php';
        require_once AIM_PLUGIN_DIR . 'public/class-aim-public.php';
    }

    /**
     * Load the plugin WPCLI commands.
     * 
     * @return void
     */
    public static function aim_load_wpcli() {
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            Command::register();
        }
    }
}
