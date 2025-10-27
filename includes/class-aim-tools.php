<?php

namespace AIMPlugin\Core;

use \Exception;
use AIMPlugin\Core\Algolia\SearchClient;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Tools {
    /**
     * Check if a post type is allowed as an indice.
     * 
     * @param string $post_type
     * @return bool
     */
    public static function check_allowed_indice( string $post_type ) : bool {
        $disallowed = apply_filters( 'aim_disallowed_indices', [
            'attachment',
            'revision',
            'nav_menu',
            'nav_menu_item',
            'custom_css',
            'custom_emoji',
            'post_format',
            'post_tag',
            'wp_block',
            'wp_template',
            'wp_template_part',
        ] );

        if ( in_array( $post_type, $disallowed ) ) {
            return false;
        }

        if ( ! post_type_exists( $post_type ) ) {
            return false;
        }

        return true;
    }

    /**
     * Get the Algolia search client.
     * 
     * @return SearchClient
     */
    public static function get_search_client(): SearchClient {
        $app_id = defined( 'AIM_ALGOLIA_APPLICATION_ID' ) ? AIM_ALGOLIA_APPLICATION_ID : get_option( 'aim_algolia_application_id', '' );
        $write_key  = defined( 'AIM_ALGOLIA_WRITE_API_KEY' ) ? AIM_ALGOLIA_WRITE_API_KEY : get_option( 'aim_algolia_write_api_key', '' );

        if ( empty( $app_id ) || empty( $write_key ) ) {
            throw new Exception( __( 'Algolia Index Manager: Please configure your Algolia credentials in the settings page.', 'algolia-index-manager' ) );
        }
        
        return SearchClient::create( $app_id, $write_key );
    }

    /**
     * Get the indice prefix.
     * 
     * @return string
     */
    public static function get_indice_prefix() : string {
        return defined( 'AIM_ALGOLIA_INDICE_PREFIX' ) ? AIM_ALGOLIA_INDICE_PREFIX : get_option( 'aim_algolia_indice_prefix', 'wp_' );
    }
}
