<?php

namespace AIMPlugin\Frontend;

use \Throwable;
use \WP_REST_Response;
use \WP_REST_Request;
use AIMPlugin\Core\Tools;
use AIMPlugin\Core\Handler;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class RestApis {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'api_restapi_route' ] );
    }

    /**
     * Register REST API routes.
     * 
     * @return void
     */
    public function api_restapi_route() {
        register_rest_route( 'aim/v1', '/reindex', [
            'methods' => 'POST',
            'callback' => [ $this, 'api_reindex_indice' ],
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
            'args' => [
                'post_type' => [
                    'required' => true
                ]
            ]
        ] );

        register_rest_route( 'aim/v1', '/index', [
            'methods' => 'POST',
            'callback' => [ $this, 'api_index_post' ],
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
            'args' => [
                'post' => [
                    'required' => true
                ]
            ]
        ] );

        register_rest_route( 'aim/v1', '/remove', [
            'methods' => 'POST',
            'callback' => [ $this, 'api_remove_indice' ],
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
            'args' => [
                'post_type' => [
                    'required' => true
                ]
            ]
        ] );
    }

    /**
     * Reindex all posts of a specific post type.
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function api_reindex_indice( WP_REST_Request $request ) {
        try {
            $post_type = $request->get_param( 'post_type' );
            $handler = new Handler();
            $response = $handler->aim_reindex_indice( $post_type );

            return new WP_REST_Response( [
                'code' => 'rest_aim_reindex_indice',
                'message' => __( 'Indice reindexed successfully', 'algolia-index-manager' ),
                'data' => $response
            ] );
        } catch ( Throwable $th ) {
            return new WP_REST_Response( [
                'code' => 'rest_aim_reindex_indice',
                'message' => __( 'Failed to reindex indice', 'algolia-index-manager' ),
                'error' => $th->getMessage()
            ], 500 );
        }
    }

    /**
     * Index a single post to Algolia.
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function api_index_post( WP_REST_Request $request ) {
        try {
            $post = $request->get_param( 'post' );
            $handler = new Handler();
            $response = $handler->aim_index_post( $post );

            return new WP_REST_Response( [
                'code' => 'rest_aim_index_post',
                'message' => __( 'Post indexed successfully', 'algolia-index-manager' ),
                'data' => $response
            ] );
        } catch ( Throwable $th ) {
            return new WP_REST_Response( [
                'code' => 'rest_aim_index_post',
                'message' => __( 'Failed to index post', 'algolia-index-manager' ),
                'error' => $th->getMessage()
            ], 500 );
        }
    }

    /**
     * Remove an indice from Algolia.
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function api_remove_indice( WP_REST_Request $request ) {
        try {
            $post_type = $request->get_param( 'post_type' );
            $handler = new Handler();
            $response = $handler->aim_remove_indice( $post_type );
            return new WP_REST_Response( [
                'code' => 'rest_aim_remove_indice',
                'message' => __( 'Indice removed successfully', 'algolia-index-manager' ),
                'data' => $response
            ] );
        } catch ( Throwable $th ) {
            return new WP_REST_Response( [
                'code' => 'rest_aim_remove_indice',
                'message' => __( 'Failed to remove indice', 'algolia-index-manager' ),
                'error' => $th->getMessage()
            ], 500 );
        }
    }
}

new RestApis();
