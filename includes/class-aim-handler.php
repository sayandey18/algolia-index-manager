<?php

namespace AIMPlugin\Core;

use \WP_Post;
use \WP_Query;
use \Exception;
use AIMPlugin\Core\Tools;

class Handler {
    /**
     * Reindex all posts of a specific post type.
     * 
     * @param string $post_type
     * @return array
     */
    public function aim_reindex_indice( string $post_type ) : array {
        if ( ! Tools::check_allowed_indice( $post_type ) ) {
            throw new Exception( __( 'Post type not allowed as indice', 'algolia-index-manager' ) );
        }

        $start_time = microtime( true );
        
        $client = Tools::get_search_client();
        $prefix = Tools::get_indice_prefix();
        $indice = $prefix . $post_type;

        $index = $client->initIndex( $indice );
        if ( $index->exists() ) {
            $index->clearObjects()->wait();
        }

        $paged = 1;
        $count = 0;

        do {
            $query = new WP_Query( [
                'post_type' => $post_type,
                'posts_per_page' => 100,
                'paged' => $paged,
                'post_status' => 'publish'
            ] );

            if ( ! $query->have_posts() ) {
                break;
            }

            $objects = [];
            while ( $query->have_posts() ) {
                $query->the_post();
                $object = ( array ) apply_filters( 
                    'aim_prepare_object_for_indice', 
                    $this->prepare_object( $query->post ) 
                );

                if ( ! isset( $object['objectID'] ) ) {
                    $object['objectID'] = $query->post->ID;
                }

                $objects[] = $object;
            }

            $index->saveObjects( $objects );
            $count += count( $objects );
            $paged++;

            wp_reset_postdata();
        } while ( true );

        $end_time = microtime( true );
        $total_time = number_format( $end_time - $start_time, 2 );

        return [
            'success' => true,
            'total_posts' => $count,
            'indice_name' => $indice,
            'total_time'  => $total_time
        ];
    }

    /**
     * Index a single post to Algolia.
     * 
     * @param WP_Post $post
     * @return array
     */
    public function aim_index_post( WP_Post $post ) : array {
        if ( ! Tools::check_allowed_indice( $post->post_type ) ) {
            throw new Exception( __( 'Post type not allowed as indice', 'algolia-index-manager' ) );
        }

        $start_time = microtime( true );

        $client = Tools::get_search_client();
        $prefix = Tools::get_indice_prefix();
        $indice = $prefix . $post->post_type;

        $index = $client->initIndex( $indice );
        $object = ( array ) apply_filters( 'aim_prepare_object_for_indice', $this->prepare_object( $post ) );

        if ( ! isset( $object['objectID'] ) ) {
            $object['objectID'] = $post->ID;
        }

        if ( 'trash' == $post->post_status ) {
            $index->deleteObject( $post->ID );
        } else {
            $index->saveObject( $object );
        }

        $end_time = microtime( true );
        $total_time = number_format( $end_time - $start_time, 2 );

        return [
            'success' => true,
            'post_id' => $post->ID,
            'indice_name' => $indice,
            'total_time' => $total_time
        ];
    }

    /**
     * Remove an indice from Algolia.
     * 
     * @param string $post_type
     * @return array
     */
    public function aim_remove_indice( string $post_type ) : array {
        if ( ! Tools::check_allowed_indice( $post_type ) ) {
            throw new Exception( __( 'Post type not allowed as indice', 'algolia-index-manager' ) );
        }

        $start_time = microtime( true );

        $client = Tools::get_search_client();
        $prefix = Tools::get_indice_prefix();
        $indice = $prefix . $post_type;

        $index = $client->initIndex( $indice );
        if ( ! $index->exists() ) {
            throw new Exception( __( 'Indice not found', 'algolia-index-manager' ) );
        }

        $index->delete();

        $end_time = microtime( true );
        $total_time = number_format( $end_time - $start_time, 2 );

        return [
            'success' => true,
            'indice_name' => $indice,
            'total_time' => $total_time
        ];
    }

    /**
     * Prepare an object for indexing.
     * 
     * @param WP_Post $post
     * @return array
     */
    private function prepare_object( WP_Post $post ) : array {
        return [
            'objectID' => $post->ID,
            'date' => $post->post_date,
            'slug' => $post->post_name,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt,
            'content' => $post->post_content,
            'author' => $post->post_author,
            'permalink' => get_permalink( $post->ID ),
            'thumbnail' => [
                'url' => get_the_post_thumbnail_url( $post->ID ) ?: '',
                'alt' => get_post_meta( $post->ID, '_thumbnail_id', true ) ?: '',
            ],
            'categories' => wp_get_post_categories( $post->ID ) ?: [],
            'tags' => wp_get_post_tags( $post->ID ) ?: []
        ];
    }
}

