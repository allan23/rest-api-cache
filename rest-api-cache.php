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

function rac_fetch_cache( $result, $server ) {
	if ( 'GET' != $server->method ) {
		return $result;
	}
	$cache_key	 = md5( $_SERVER[ 'REQUEST_URI' ] );
	$result		 = wp_cache_get( $cache_key, 'rac' );
	$cache_bust	 = filter_input( INPUT_GET, 'cache_bust', FILTER_VALIDATE_BOOLEAN );
	if ( false == $result || $cache_bust ) {
		$result = $server->dispatch();
		wp_cache_set( $cache_key, $result, 'rac', apply_filters( 'rac_cache_time', 600 ) );
	}
	return $result;
}
