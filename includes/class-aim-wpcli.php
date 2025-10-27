<?php

namespace AIMPlugin\WPCLI;

use \WP_CLI;
use \WP_Post;
use \Exception;
use AIMPlugin\Core\Handler;

class Command {
    /**
     * Register the WPCLI commands.
     * 
     * @return void
     */
    public static function register() {
        if ( ! class_exists( 'WP_CLI' ) ) {
            return;
        }

        WP_CLI::add_command( 'aim reindex', [ __CLASS__, 'reindex' ] );
        WP_CLI::add_command( 'aim index-post', [ __CLASS__, 'index_post' ] );
        WP_CLI::add_command( 'aim remove-index', [ __CLASS__, 'remove_index' ] );
    }

    /**
     * WP-CLI command to reindex all posts for a specific post type.
     * 
     * @param array $args Command arguments.
     * @param array $assoc_args Command associative arguments.
     * @return void
     */
    public static function reindex( array $args, array $assoc_args ) {
        if ( ! isset( $args[0] ) ) {
            WP_CLI::error( 'Please provide a post type (e.g., post, page, custom_post_type)' );
        }

        $post_type = $args[0];
        $handler = new Handler();

        try {
            $result = $handler->aim_reindex_indice( $post_type );
            WP_CLI::success( 'Reindex for post type ' . $post_type . ' completed successfully' );
            WP_CLI::line( 'Total posts indexed: ' . $result['total_posts'] );
            WP_CLI::line( 'Total time: ' . $result['total_time'] . ' seconds' );
        } catch ( Exception $e ) {
            WP_CLI::error( $e->getMessage() );
        }
    }

    /**
     * WP-CLI command to index a specific post.
     * 
     * @param array $args Command arguments.
     * @param array $assoc_args Command associative arguments.
     * @return void
     */
    public static function index_post( array $args, array $assoc_args ) {
        if ( ! isset( $args[0] ) ) {
            WP_CLI::error( 'Please provide a post ID' );
        }

        $post_id = $args[0];
        $post = get_post( $post_id );
        if ( ! $post instanceof WP_Post ) {
            WP_CLI::error( 'Post not found' );
        }

        $handler = new Handler();

        try {
            $result = $handler->aim_index_post( $post );
            WP_CLI::success( 'Post ID ' . $post_id . ' indexed successfully' );
            WP_CLI::line( 'Total time: ' . $result['total_time'] . ' seconds' );
        } catch ( Exception $e ) {
            WP_CLI::error( $e->getMessage() );
        }
    }

    /**
     * WP-CLI command to remove an indice from Algolia.
     * 
     * @param array $args Command arguments.
     * @param array $assoc_args Command associative arguments.
     * @return void
     */
    public static function remove_index( array $args, array $assoc_args ) {
        if ( ! isset( $args[0] ) ) {
            WP_CLI::error( 'Please provide a post type (e.g., post, page, custom_post_type)' );
        }

        $post_type = $args[0];
        $handler = new Handler();

        try {
            $result = $handler->aim_remove_indice( $post_type );
            WP_CLI::success( 'Indice for post type ' . $post_type . ' removed successfully' );
            WP_CLI::line( 'Total time: ' . $result['total_time'] . ' seconds' );
        } catch ( Exception $e ) {
            WP_CLI::error( $e->getMessage() );
        }
    }
}
