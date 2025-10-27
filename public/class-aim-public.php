<?php

namespace AIMPlugin\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AIMPublic {
    public function __construct() {
        $this->aim_load_public_apis();
    }

    /**
     * Load all public APIs.
     * 
     * @return void
     */
    public function aim_load_public_apis() : void {
        require_once AIM_PLUGIN_DIR . 'public/restapi/class-aim-public-apis.php';
    }
}

new AIMPublic();