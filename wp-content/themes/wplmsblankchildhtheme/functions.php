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

function excerpt($num, $id) {
    $limit = $num+1;
    $excerpt = explode(' ', get_the_excerpt($id), $limit);
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


// handle post format icon 
function handlePostFormatIcon() {
    $format = get_post_format();
    if ($format === 'audio') {
        return 'icon-audio.png';
    } else if ($format === 'video') {
        return 'icon-video.png';
    } else {
        return 'icon-audio.png';
    }
}

function getCourseCost($id) {
    //bp_course_credits(); 
    $free_course = get_post_meta($id,'vibe_course_free',true);
    //var_dump(get_post_meta($post->ID,'vibe_product',true));
    $product_id = get_post_meta($id,'vibe_product',true);
    $product = wc_get_product( $product_id );
    $is_open_price = get_post_meta( $product_id, '_wcj_product_open_price_enabled', true );
    if(vibe_validate($free_course)){
       return "<span>FREE</span>";
    } else if (is_object($product) && $is_open_price == 'no') {
        //var_dump($product);
        return '<span>'.$product->get_price_html().'</span>';

    } else if ($is_open_price == 'yes') {
        return '<span>Donation Based</span>';
    }
}

// ADD DONATION BUTTON TO COURSE PAGE
function meta_add_open_price_input_field_to_frontend($product_id) {
    $the_product = wc_get_product($product_id);
    $is_open_price = get_post_meta( $product_id, '_wcj_product_open_price_enabled', true );

    if( isset($is_open_price) && $is_open_price == 'yes' ) {
        // Title
        $title = get_option( 'wcj_product_open_price_label_frontend', __( 'Name Your Price', 'woocommerce-jetpack' ) );
        // Prices
        $min_price     = get_post_meta( $the_product->id, '_' . 'wcj_product_open_price_min_price', true );
        $max_price     = get_post_meta( $the_product->id, '_' . 'wcj_product_open_price_max_price', true );
        $default_price = get_post_meta( $the_product->id, '_' . 'wcj_product_open_price_default_price', true );
        // Input field
        $value = ( isset( $_POST['wcj_open_price'] ) ) ? $_POST['wcj_open_price'] : $default_price;
        $default_price_step = 1 / pow( 10, absint( get_option( 'woocommerce_price_num_decimals', 2 ) ) );
        $custom_attributes = '';
        $custom_attributes .= 'step="' . get_option( 'wcj_product_open_price_price_step', $default_price_step ) . '" ';
        $custom_attributes .= ( '' == $min_price || 'no' === get_option( 'wcj_product_open_price_enable_js_validation', 'no' ) ) ? 'min="0" ' : 'min="' . $min_price . '" ';
        $custom_attributes .= ( '' == $max_price || 'no' === get_option( 'wcj_product_open_price_enable_js_validation', 'no' ) ) ? ''         : 'max="' . $max_price . '" ';
        $input_field = '<input '
            . 'type="number" '
            . 'class="text" '
            . 'style="' . get_option( 'wcj_product_open_price_input_style', 'width:70px;text-align:center;' ). '" '
            . 'name="wcj_open_price" '
            . 'id="wcj_open_price" '
            . 'placeholder="' . get_option( 'wcj_product_open_price_input_placeholder', '' ) . '" '
            . 'value="' . $value . '" '
            . $custom_attributes . '>';
        // Currency symbol
        $currency_symbol = get_woocommerce_currency_symbol();
        // Replacing final values
        $replacement_values = array(
            '%frontend_label%'       => '$', // $title
            '%open_price_input%'     => $input_field,
            '%currency_symbol%'      => '', // $currency_symbol
            '%min_price_simple%'     => $min_price,
            '%max_price_simple%'     => $max_price,
            '%default_price_simple%' => $default_price,
            '%min_price%'            => wc_price( $min_price ),
            '%max_price%'            => wc_price( $max_price ),
            '%default_price%'        => wc_price( $default_price ),
        );
        return str_replace(
            array_keys( $replacement_values ),
            array_values( $replacement_values ),
            get_option( 'wcj_product_open_price_frontend_template', '<label for="wcj_open_price">%frontend_label%</label>%currency_symbol% %open_price_input%' )
        );
    }
}

add_action( 'bp_before_course_body', 'meta_custom_wc_print_notices', 10 );
function meta_custom_wc_print_notices() {
    echo '<div class="woocommerce">';
    wc_print_notices();
    echo '</div>';
}

add_filter( 'wc_add_to_cart_message', 'meta_custom_wc_add_to_cart_message' );
function meta_custom_wc_add_to_cart_message() {
    echo '<style type="text/css">.page-id-1145 .woocommerce-message {display: none !important;}</style>';
}

add_filter( 'woocommerce_cart_item_name', 'meta_custom_woocommerce_cart_item_name', 10, 3 );
function meta_custom_woocommerce_cart_item_name($html, $cart_item, $cart_item_key) {
    if( !isset($cart_item['product_id']) ) {
        return $html;
    }
        
    $vcourses = vibe_sanitize( get_post_meta( $cart_item['product_id'], 'vibe_courses', false ) );
    
    if( empty($vcourses) || !isset($vcourses[0]) ) {
        return $html;
    }

    return sprintf( '<a href="%s">%s </a>', esc_url( get_permalink($vcourses[0]) ), get_the_title($vcourses[0]) );
}

add_filter( 'woocommerce_order_item_name', 'meta_custom_woocommerce_order_item_name', 10, 3 );
function meta_custom_woocommerce_order_item_name($html, $item, $is_visible) {
    if( !isset($item['product_id']) || !is_page(array(1145, 1146)) || is_admin() ) {
        return $html;
    }
        
    $vcourses = vibe_sanitize( get_post_meta( $item['product_id'], 'vibe_courses', false ) );
    
    if( empty($vcourses) || !isset($vcourses[0]) ) {
        return $html;
    }

    return sprintf( '<a href="%s">%s </a>', esc_url( get_permalink($vcourses[0]) ), get_the_title($vcourses[0]) );
}

add_filter( 'woocommerce_add_cart_item_data', 'meta_custom_woocommerce_add_cart_item_data', 10, 3 );
function meta_custom_woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    $is_open_price = get_post_meta( $product_id, '_wcj_product_open_price_enabled', true );

    if( isset($is_open_price) && $is_open_price == 'yes' ) {
        foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
            $_product = $values['data'];

            if( $product_id == $_product->id ) {
                WC()->cart->remove_cart_item( $cart_item_key );
            }
        }
    }

    return $cart_item_data;
}

add_filter( 'woocommerce_add_to_cart_redirect', 'meta_custom_woocommerce_add_to_cart_redirect' );
function meta_custom_woocommerce_add_to_cart_redirect( $url ) {
    if( !isset( $_REQUEST['add-to-cart'] ) || !is_numeric( $_REQUEST['add-to-cart'] ) ) {
        return $url;
    }
    
    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint($_REQUEST['add-to-cart'] ) );
    $is_open_price = get_post_meta( $product_id, '_wcj_product_open_price_enabled', true );
    
    if( isset($is_open_price) && $is_open_price == 'yes' ) {
        $url = wc_get_cart_url();
    }
    
    return $url;
}

// add_filter( 'wplms_expired_course_button', 'meta_add_donation_button', 10, 2 );
add_filter( 'wplms_take_course_button_html', 'meta_add_donation_button', 10, 2 );
function meta_add_donation_button($html, $course_id) {
    $product_id = get_post_meta( $course_id, 'vibe_product', true );
    $is_open_price = get_post_meta( $product_id, '_wcj_product_open_price_enabled', true );

    if( isset($is_open_price) && $is_open_price == 'yes' ) {

        $field = '<form class="cart" method="post" enctype="multipart/form-data">';
        $field .= meta_add_open_price_input_field_to_frontend($product_id);
        $field .= '<input type="hidden" name="add-to-cart" value="' .$product_id. '"/>';
        $field .= '<button type="submit" class="single_add_to_cart_button button course_button full">TAKE THIS COURSE</button>';
        $field .= '</form>';

        $html = $field;
    }
    
    return $html;
}
// END ADD DONATION BUTTON TO COURSE PAGE





?>
