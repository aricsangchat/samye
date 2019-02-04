<?php
/**
 * Plugin Name: Custom Samye Shortcodes
 * Plugin URI: https://www.samyeinstitute.org
 * Description: Custom shortcodes for Samye
 * Version: 1.0
 * Author: Aric Sangchat
 * Author URI: https://www.samyeinstitute.org
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function samye_shortcodes_init(){
    add_shortcode( 'iframevideo', 'iframevideoShortcode' );
}
add_action('init', 'samye_shortcodes_init');

function iframevideoShortcode( $atts, $content = null, $tag = '' ){

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );

	$output = '<div class="iframe-video-wrapper">' . $content . '</div>';
	return $output;
}

