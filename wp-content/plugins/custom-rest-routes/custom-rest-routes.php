<?php
/**
 * Plugin Name: Custom Rest Routes
 * Plugin URI: http://www.samye.org
 * Description: Creates custom rest routes.
 * Version: 1.0
 * Author: Aric Sangchat
 * Author URI: http://www.samye.org
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    /**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function my_awesome_func( $data ) {
	$posts = get_posts( array(
		'author' => $data['id'],
	) );

	if ( empty( $posts ) ) {
		return null;
	}

	return $posts[0]->post_title;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'my_awesome_func',
	) );
} );