<?php

/**
 * Plugin Name: REST API Cache
 * Description: Cache WP REST API responses.
 * Version:     0.0.1
 * Author:      Allan Collins
 * Author URI:  http://www.allancollins.net/
 * License:     GPLv2 or later
 */
add_filter( 'json_pre_dispatch', 'rac_fetch_cache', 10, 2 );

/**
 * Retrieve or generate cache (if GET).
 * @param mixed $result Response to replace the requested version with. Can be anything a normal endpoint can return, or null to not hijack the request.
 * @param WP_JSON_ResponseHandler $server ResponseHandler instance (usually WP_JSON_Server)
 * @return mixed
 */
function rac_fetch_cache( $result, $server ) {
	// If we're not using a GET method -- let's bail.
	if ( 'GET' != $server->method ) {
		return $result;
	}
	$cache_key	 = md5( $_SERVER[ 'REQUEST_URI' ] );
	$result		 = wp_cache_get( $cache_key, 'rac' );
	// Check for the 'cache_bust=1' query string.
	$cache_bust	 = filter_input( INPUT_GET, 'cache_bust', FILTER_VALIDATE_BOOLEAN );
	if ( false == $result || $cache_bust ) {
		$result = $server->dispatch();
		wp_cache_set( $cache_key, $result, 'rac', apply_filters( 'rac_cache_time', 600 ) );
	}
	return $result;
}
