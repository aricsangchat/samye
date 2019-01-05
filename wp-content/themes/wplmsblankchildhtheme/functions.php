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

function isacustom_excerpt_length($length) {
    global $post;
    if ($post->post_type == 'post')
    return 32;
    else if ($post->post_type == 'grd-teaching')
    return 30;
    else if ($post->post_type == 'course')
    return 75;
    else
    return 80;
}
add_filter('excerpt_length', 'isacustom_excerpt_length', 100);

function excerpt($num) {
    $limit = $num+1;
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt)."...";
    echo '<p>'.$excerpt.'</p>';
}

?>
