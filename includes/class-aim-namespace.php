<?php

/**
 * Define a `wrapper namespace` to load the library classes to prevent conflicts with other plugins.
 * 
 * @link https://github.com/sayandey18/algolia-index-manager
 * @since 1.0.0
 * @author Sayan Dey <mr.sayandey18@outlook.com>
 * @package AlgoliaIndexManager
 */

namespace AIMPlugin\Core\Algolia;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SearchClient extends \Algolia\AlgoliaSearch\SearchClient {

}
    
