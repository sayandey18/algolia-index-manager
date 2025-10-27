<?php

namespace AIMPlugin\Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AIMAdmin {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'aim_add_menu_item' ] );
        add_filter( 'plugin_action_links_' . AIM_PLUGIN_BASE, [ $this, 'aim_add_quick_links' ] );
        add_action( 'admin_init', [ $this, 'aim_handle_settings_submission' ] );
    }

    /**
     * Add the admin menu page.
     * 
     * @return void
     */
    public function aim_add_menu_item() : void {
        add_menu_page(
            __( 'Algolia Index Manager', 'algolia-index-manager' ),
            __( 'WP Algolia', 'algolia-index-manager' ),
            'manage_options',
            'algolia-index-manager',
            [ $this, 'aim_admin_settings_page' ],
            $this->aim_menu_svg_icon()
        );
    }

    /**
     * Add quick links on the plugin page.
     * 
     * @param array $links
     * @return array
     */
    public function aim_add_quick_links( array $links ) : array {
        $links['settings'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            admin_url( 'admin.php?page=algolia-index-manager' ),
            __( 'Go to settings page', 'algolia-index-manager' ),
            __( 'Configure', 'algolia-index-manager' )
        );
        return $links;
    }

    /**
     * Handle the admin settings page form submission.
     * 
     * @return void
     */
    public function aim_handle_settings_submission() : void {
        if ( ! isset( $_POST['_algolia_settings_nonce'] ) || ! wp_verify_nonce( $_POST['_algolia_settings_nonce'], 'algolia_settings' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_POST['application_id'] ) && ! empty( $_POST['application_id'] ) ) {
            $application_id = sanitize_text_field( wp_unslash( $_POST['application_id'] ) );
            update_option( 'aim_algolia_application_id', $application_id );
        }

        if ( isset( $_POST['search_api_key'] ) && ! empty( $_POST['search_api_key'] ) ) {
            $search_api_key = sanitize_text_field( wp_unslash( $_POST['search_api_key'] ) );
            update_option( 'aim_algolia_search_api_key', $search_api_key );
        }

        if ( isset( $_POST['write_api_key'] ) && ! empty( $_POST['write_api_key'] ) ) {
            $write_api_key = sanitize_text_field( wp_unslash( $_POST['write_api_key'] ) );
            update_option( 'aim_algolia_write_api_key', $write_api_key );
        }

        if ( isset( $_POST['indice_prefix'] ) && ! empty( $_POST['indice_prefix'] ) ) {
            $indice_prefix = sanitize_text_field( wp_unslash( $_POST['indice_prefix'] ) );
            update_option( 'aim_algolia_indice_prefix', $indice_prefix );
        }
    }

    /**
     * Display the admin settings page content.
     * 
     * @return void
     */
    public function aim_admin_settings_page() : void {
        $credentials = $this->aim_load_algolia_credentials();
        require_once AIM_PLUGIN_DIR . 'admin/partials/class-aim-admin-settings.php';
    }

    /**
     * Load the Algolia credentials.
     * 
     * @return object
     */
    public function aim_load_algolia_credentials() : object {
        return ( object ) [
            'application_id' => ( object ) [
                'value' => defined( 'AIM_ALGOLIA_APPLICATION_ID' ) ? AIM_ALGOLIA_APPLICATION_ID : get_option( 'aim_algolia_application_id', '' ),
                'disabled' => defined( 'AIM_ALGOLIA_APPLICATION_ID' )
            ],
            'search_api_key' => ( object ) [
                'value' => defined( 'AIM_ALGOLIA_SEARCH_API_KEY' ) ? AIM_ALGOLIA_SEARCH_API_KEY : get_option( 'aim_algolia_search_api_key', '' ),
                'disabled' => defined( 'AIM_ALGOLIA_SEARCH_API_KEY' )
            ],
            'write_api_key'  => ( object ) [
                'value' => defined( 'AIM_ALGOLIA_WRITE_API_KEY' ) ? AIM_ALGOLIA_WRITE_API_KEY : get_option( 'aim_algolia_write_api_key', '' ),
                'disabled' => defined( 'AIM_ALGOLIA_WRITE_API_KEY' )
            ],
            'indice_prefix'  => ( object ) [
                'value' => defined( 'AIM_ALGOLIA_INDICE_PREFIX' ) ? AIM_ALGOLIA_INDICE_PREFIX : get_option( 'aim_algolia_indice_prefix', 'wp_' ),
                'disabled' => defined( 'AIM_ALGOLIA_INDICE_PREFIX' )
            ],
        ];
    }

    /**
     * Get the Algolia menu icon.
     * 
     * @param bool $base64
     * @return string
     */
    public function aim_menu_svg_icon( $base64 = true ) : string {
        $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="-12 -12 152 152" stroke-width="5" stroke="#a7aaad"><path fill="#a7aaad" d="M63.998-.042C29.024-.042.511 28.16.006 63.015c-.512 35.402 28.208 64.734 63.613 64.94 10.934.063 21.465-2.612 30.817-7.693a1.5 1.5 0 0 0 .276-2.438l-5.987-5.309c-1.216-1.08-2.95-1.385-4.447-.747-6.528 2.777-13.622 4.195-20.93 4.106-28.608-.351-51.722-24.153-51.266-52.761.45-28.244 23.567-51.084 51.916-51.084h51.924v92.295l-29.46-26.176c-.952-.848-2.414-.681-3.182.335-4.728 6.262-12.431 10.155-20.987 9.564-11.868-.82-21.483-10.373-22.374-22.236-1.062-14.152 10.15-26.004 24.082-26.004 12.598 0 22.973 9.697 24.056 22.018a4.3 4.3 0 0 0 1.416 2.85l7.672 6.801c.87.77 2.253.3 2.465-.845.553-2.957.748-6.041.53-9.203-1.237-18.02-15.831-32.514-33.858-33.625-20.667-1.275-37.946 14.894-38.494 35.161-.535 19.75 15.647 36.776 35.399 37.212a36.03 36.03 0 0 0 22.067-6.904l38.492 34.122c1.651 1.462 4.255.292 4.255-1.915V2.39a2.434 2.434 0 0 0-2.432-2.43z"/></svg>';
        if ( $base64 ) {
            return 'data:image/svg+xml;base64,' . base64_encode( $svg_icon );
        }

        return $svg_icon;
    }
}

new AIMAdmin();