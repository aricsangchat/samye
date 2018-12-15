<?php

if ( !defined( 'VIBE_URL' ) )
define('VIBE_URL',get_template_directory_uri());
define( 'META_DIR', trailingslashit(get_stylesheet_directory_uri()) );

function meta_wp_enqueue_scripts() {
    wp_register_script( 'meta_custom_js', META_DIR . 'js/custom.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'meta_custom_js' );

    wp_register_script( 'slickslider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'slickslider' );
}

add_action( 'wp_enqueue_scripts', 'meta_wp_enqueue_scripts', 20 );

function gutenberg_boilerplate_block() {
    wp_register_script(
        'gutenberg-boilerplate-es5-step01',
        plugins_url( 'step-01/block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element' )
    );

    register_block_type( 'gutenberg-boilerplate-es5/hello-world-step-01', array(
        'editor_script' => 'gutenberg-boilerplate-es5-step01',
    ) );
}
add_action( 'init', 'gutenberg_boilerplate_block' );


?>
