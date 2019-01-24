<?php

if ( !defined( 'VIBE_URL' ) )
define('VIBE_URL',get_template_directory_uri());
define( 'META_DIR', trailingslashit(get_stylesheet_directory_uri()) );

function meta_wp_enqueue_scripts() {
    wp_register_script( 'slickslider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'slickslider' );
    
    wp_register_script( 'meta_custom_js', META_DIR . 'assets/js/custom.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'meta_custom_js' );

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
    return $excerpt;
}

// Removing Menus from admin panel
function remove_menus(){
    // $user_meta=get_userdata(get_current_user_id());
    // $user_roles=$user_meta->roles;
    $current_user = wp_get_current_user();
    // var_dump( $current_user->user_login);

    if ($current_user->user_login != 'aric.sangchat' || $current_user->user_login != 'mattgoult') {
    } else {
        remove_menu_page( 'index.php' );                 //Dashboard
        // remove_menu_page( 'edit.php' );                   //Posts
        // remove_menu_page( 'upload.php' );                 //Media
        // remove_menu_page( 'edit.php?post_type=page' );   //Pages
        // remove_menu_page( 'edit-comments.php' );         //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );               //Plugins
        // remove_menu_page( 'users.php' );                 //Users
        remove_menu_page( 'tools.php' );                 //Tools
        remove_menu_page( 'options-general.php' );       //Settings
        remove_menu_page( 'wpengine-common' );
        remove_menu_page( 'jetpack' );
        remove_menu_page( 'wplms_options' );
        remove_menu_page( 'vc-general' );
        remove_menu_page( 'wpseo_dashboard' );
        remove_menu_page( 'mailchimp-for-wp' );
        remove_menu_page( 'pmxe-admin-home' );
        remove_menu_page( 'cptui_main_menu' );
        remove_menu_page( 'instant-articles-wizard' );
        remove_menu_page( 'sb-instagram-feed' );
        remove_menu_page( 'pixel-your-site' );
        remove_menu_page( 'heateor-sss-options' );
        remove_menu_page( 'edit.php?post_type=lazyblocks' );
        remove_menu_page( 'edit.php?post_type=acf-field-group' );
        remove_menu_page( 'WP-Lightbox-2' );
        remove_menu_page( 'smush' );
        remove_menu_page( 'master-slider' );
        remove_menu_page( 'mailchimp-woocommerce' );
    }
}
    
add_action( 'admin_init', 'remove_menus' );

// Debug Function for Removing Menus
// add_action( 'admin_init', 'wpse_136058_debug_admin_menu' );
// function wpse_136058_debug_admin_menu() {
//     echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
// }

// Prepopulate instructors drop down for contact form
add_filter( 'gform_pre_render_47', 'populate_posts' );
add_filter( 'gform_pre_validation_47', 'populate_posts' );
add_filter( 'gform_pre_submission_filter_47', 'populate_posts' );
add_filter( 'gform_admin_pre_render_47', 'populate_posts' );
function populate_posts( $form ) {
 
    foreach ( $form['fields'] as &$field ) {
 
        if ( $field->type != 'select' || strpos( $field->cssClass, 'select-instructor' ) === false ) {
            continue;
        }
 
        // you can add additional parameters here to alter the posts that are retrieved
        // more info: http://codex.wordpress.org/Template_Tags/get_posts
        $posts = get_posts( 'numberposts=-1&post_status=publish&post_type=instructors' );
 
        $choices = array();
 
        foreach ( $posts as $post ) {
            $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
        }
 
        // update 'Select Instructor' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select Instructor';
        $field->choices = $choices;
 
    }
 
    return $form;
}


//add extra fields to category edit form hook
add_action ( 'category_edit_form_fields', 'extra_category_fields');
//add extra fields to category edit form callback function
function extra_category_fields( $tag ) {    //check for existing featured ID
    // Check for existing taxonomy meta for the term you're editing  
    $t_id = $tag->term_id; // Get the ID of the term you're editing  
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check 
    ?>  
  
<tr class="form-field">  
    <th scope="row" valign="top">  
        <label for="presenter_id"><?php _e('When Writing Descriptions'); ?></label>  
    </th>  
    <td>  
        <br />  
        <span class="description"><?php  _e('Wrap the description between a P tag like this 	&#60;p&#62;Description goes here&#60;/p&#62; and use &#60;br /&#62; for line breaks.'); ?></span>  
    </td>  
</tr>  
  
<?php
}

?>
