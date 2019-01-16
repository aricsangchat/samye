<?php
/**
 * BLOG 1 Style Content Block
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( function_exists('get_coauthors')) {
    $coauthors = get_coauthors( $id );
    $instructors = array();
    if(isset($coauthors)){
        foreach($coauthors as $k=>$inst){

            $instructor_id = $inst->ID;
            $displayname = bp_core_get_user_displayname($instructor_id);
            $author_posts_url = get_author_posts_url( $instructor_id );
            $instructor = '<a href="'.$author_posts_url.'">'.$displayname.'</a>';
            $instructors[] = $instructor;
        }
    }
}


$post_id = get_the_ID();


if(!empty($instructors)){
    $instructor = implode(',', $instructors);
}else{
    $instructor_id = get_the_author_meta( 'ID' );
    $author_posts_url = get_author_posts_url( $instructor_id );
    $displayname = get_the_author_meta( 'display_name' );
    $instructor = '<a href="'.$author_posts_url.'">'.$displayname.'</a>';
}


echo ' <div class="blogpost">
        <div class="featured">
            <div style="background-image: url('.get_the_post_thumbnail_url( $post_id, 'full' ).');"></div>
        </div>
        <div class="excerpt thumb">
            <div class="cats">
                '.get_the_category_list('','').'
            </div>
            <h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
            <p class="date">'.get_the_time('M j, Y').'</p>
            <p>'.excerpt(40).'</p>
            <a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
        </div>
    </div>';
