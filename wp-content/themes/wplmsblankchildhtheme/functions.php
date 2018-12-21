<?php

if ( !defined( 'VIBE_URL' ) )
define('VIBE_URL',get_template_directory_uri());
define( 'META_DIR', trailingslashit(get_stylesheet_directory_uri()) );

function meta_wp_enqueue_scripts() {
    wp_register_script( 'meta_custom_js', META_DIR . 'assets/js/custom.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'meta_custom_js' );

    wp_register_script( 'slickslider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'slickslider' );

    wp_enqueue_style( 'customstyles', get_stylesheet_directory_uri() . '/assets/css/source.css' );
}

add_action( 'wp_enqueue_scripts', 'meta_wp_enqueue_scripts', 20 );


?>
