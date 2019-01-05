<?php
if( !defined( 'VIBE_URL' ) ) {
    define('VIBE_URL',get_template_directory_uri());
}
define( 'META_VER', '2.1' );
define( 'META_DIR', trailingslashit(get_stylesheet_directory_uri()) );

// REMOVE ACTION
if( class_exists( 'Tribe__Admin__Notice__Archive_Slug_Conflict' ) ) {
    remove_action( 'admin_init', array( Tribe__Admin__Notice__Archive_Slug_Conflict::instance(), 'maybe_add_admin_notice' ) );
}
// END REMOVE ACTION

// ADD ACTION
add_action( 'wp', 'meta_custom_wp' );
add_action( 'init', 'meta_custom_action_init' );
add_action( 'wp_enqueue_scripts', 'meta_wp_enqueue_scripts', 20 );
add_action( 'template_redirect', 'meta_template_redirect', 999);
add_action( 'pre_get_posts', 'meta_pre_get_posts', 5 );
add_action( 'bp_init', 'meta_bp_init', 999 );
add_action( 'bp_email', 'meta_bp_email', 10, 2 );

function meta_wp_enqueue_scripts() {
    wp_register_script( 'meta_custom_js', META_DIR . 'js/custom.js', array('jquery'), META_VER, true );
    wp_enqueue_script( 'meta_custom_js' );

    wp_register_style( 'meta_fonts', '//fonts.googleapis.com/css?family=Droid+Sans:400,700', array() );
    wp_enqueue_style( 'meta_fonts' );
}

function meta_template_redirect() {
    $register_id = buddypress()->pages->register;
    $url = home_url('/');

    if( !empty( $register_id ) ) {
        $url = get_permalink( $register_id->id );
    }
    
    if( !is_user_logged_in() && (bbp_is_forum_archive() || 
        bp_is_current_component( 'course') )) {
        wp_redirect($url);
        exit;
    }

    if( !is_user_logged_in() && is_checkout() ) {
        wp_redirect($url);
        exit;
    }
}

function meta_custom_wp() {
    $url = home_url('/');
    
    if( !empty( buddypress()->pages->register ) ) {
        $url = get_permalink( buddypress()->pages->register->id);
    }

    if( !is_user_logged_in() && bp_is_current_component( 'course') ) {
        wp_redirect($url);
    }
}

function meta_custom_action_init() {
    $WPLMS_Actions = WPLMS_Actions::init();
    remove_action( 'bp_core_activated_user', array( $WPLMS_Actions, 'vibe_redirect_after_registration' ), 99 );
}

function meta_pre_get_posts($query) {
    if( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if( is_post_type_archive('grd-teaching') && ( is_year() || is_date() ) ) {
        $query->set( 'posts_per_page', 120 );
        return;
    }
}

function meta_bp_init() {
    global $bp;
    $bp->bp_options_nav[$bp->groups->current_group->slug]['home'] = false;
}

function meta_bp_email($email_type, $email_obj) {
    $email_obj->set_from( "no-reply@samyeinstitute.org", "Samye Dharma" );    
}
// END ADD ACTION

// REMOVE FILTER
remove_filter('wp_mail_from', 'bp_core_email_from_address_filter' );
remove_filter('wp_mail_from_name', 'bp_core_email_from_name_filter');
// END REMOVE FILTER

// ADD FILTER
add_filter( 'wp_mail_from', 'meta_wp_mail_from' );
add_filter( 'bp_do_redirect_canonical', '__return_false' );
add_filter( 'gform_is_ssl', '__return_true' );
add_filter( 'wplms_logged_in_top_menu', 'meta_wplms_logged_in_top_menu', 999 );
add_filter( 'wplms_breadcrumbs_course_category', 'meta_wplms_breadcrumbs_course_category', 10 );
add_filter( 'wplms_course_nav_menu', 'meta_wplms_course_nav_menu', 5 );
add_filter( 'wplms_course_excerpt_limit', function() { return 2000; } );
add_filter( 'wplms_sidebar', 'meta_wplms_sidebar', 90, 2 );
add_filter( 'wplms_login_widget_action', 'meta_wplms_login_widget_action' );
add_filter( 'bp_groups_default_extension', 'meta_bp_groups_default_extension' );
add_filter( 'comment_form_defaults', 'meta_comment_form_defaults' );
add_filter( 'excerpt_length', 'meta_excerpt_length', 999 );
add_filter( 'term_link', 'meta_term_link', 10, 3 );
add_filter( 'init', 'meta_custom_filter_init' );

function meta_wplms_logged_in_top_menu($loggedin_menu) {
    unset($loggedin_menu['dashboard']);
    unset($loggedin_menu['stats']);
    unset($loggedin_menu['membership']);

    $loggedin_menu['orders']['label'] = 'My Orders';
    return $loggedin_menu;
}

function meta_wplms_breadcrumbs_course_category($course_category) {
    global $post;
    $course_category = '';
    $terms = get_the_terms( $post->ID, 'course-cat' );
    if( !empty($terms) ) {
        foreach( $terms as $term ) {
            $course_category .= '<li><span>' .$term->name. '</span></li>';
        }   
    }
    return $course_category;
}

function meta_wplms_course_nav_menu($defaults) {
    $key = 'activity';
    unset($defaults[$key]);
    return $defaults;
}

function meta_wplms_sidebar( $sidebar, $id = NULL ) {
    if( isset($id) && is_single() ) {
        $post_categories = wp_get_post_categories( $id );
        if( !empty($post_categories) && in_array(316, $post_categories) ) {
            $sidebar = 'WisdomBlogSide';
        }
    }
    return $sidebar;
}

function meta_wplms_login_widget_action($login_url) {
    $login_url .='&wpe-login=samyeinstitute';
    return $login_url;
}

function meta_bp_groups_default_extension($default_tab) {
    $group = groups_get_current_group();
    if( empty($group) ) {
        return $default_tab;
    }

    $default_tab = 'forum';

    return $default_tab;
}

function meta_comment_form_defaults($defaults) {
    global $post;

    $defaults['title_reply'] = __( 'LEAVE A MESSAGE' );
    if( in_category('wisdom', $post->ID) ) {
        $defaults['title_reply'] = __( 'Have any questions or comments on this teaching? Please leave them here:' );
    }
    
    return $defaults;
}

function meta_wp_mail_from($email){
    return 'no-reply@samyeinstitute.org';
}

function meta_excerpt_length($length) {
    return 21;
}


function meta_term_link( $url, $term, $taxonomy ) {
    if( is_category( 'public-teachings' ) ) {
        $url = site_url( '/study-practice/public-video-teachings' );
        wp_safe_redirect( $url, 301 );
        exit;
    } elseif( is_category( 'wisdom' ) ) {
        $url = site_url( 'https://samyeinstitute.org/wisdom' );
        wp_safe_redirect( $url, 301 );
        exit;
    } elseif( 'grdteaching' == $taxonomy && preg_match( '@^\d{4}$@', get_query_var('year') ) ) {
        return trailingslashit($link) . get_query_var('year');
    }

    return $url;
}

function meta_custom_filter_init() {
    $cpt = 'grd-teaching';

    add_rewrite_rule($cpt.'/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})?$',
        'index.php?post_type='.$cpt.'&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]',
        'top');

    add_rewrite_rule($cpt.'/([0-9]{4})/([0-9]{1,2})?$',
        'index.php?post_type='.$cpt.'&year=$matches[1]&monthnum=$matches[2]',
        'top');

    add_rewrite_rule($cpt.'/([0-9]{4})?$',
        'index.php?post_type='.$cpt.'&year=$matches[1]',
        'top');
}

remove_filter( 'wpmu_signup_user_notification_email', array( 'GFUserSignups', 'modify_signup_user_notification_message' ), 10 );
add_filter( 'wpmu_signup_user_notification_email', 'sen_wpmu_signup_user_notification_email', 10, 4 );
function sen_wpmu_signup_user_notification_email( $message, $user, $user_email, $key ) {
    $url = add_query_arg( array( 'page' => 'gf_activation', 'key' => $key ), home_url( '/' ) );

    if( gf_user_registration()->is_bp_active() ) {
        $activate_url = esc_url_raw( sprintf( '%s?key=%s', bp_get_activation_page(), $key ) );
        $message      = str_replace( $activate_url, '%s', $message );
    }
    
    $url = esc_url_raw( $url );
    $link = '<a href="' .$url. '">' .$url. '</a>';

    return sprintf( $message, $link );
}

// END ADD FILTER

function get_cpt_archives( $cpt, $echo = false ) {
    global $wpdb; 
    $sql = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = %s AND post_status = 'publish' GROUP BY YEAR($wpdb->posts.post_date) ORDER BY $wpdb->posts.post_date DESC", $cpt);
    $results = $wpdb->get_results($sql);

    if ( $results )
    {
        $archive = array();
        foreach ($results as $r)
        {
            $year = date('Y', strtotime( $r->post_date ) );
            $link = get_bloginfo('siteurl') . '/' . $cpt . '/' . $year;// . '/' . $month_num;
            $this_archive = array( 'year' => $year, 'link' => $link );
            array_push( $archive, $this_archive );
        }

        if( !$echo ) {
            return $archive;
        }
        foreach( $archive as $a )
        {
            echo '<li class="year"><a href="' . $a['link'] . '">' . $a['year'] . '</a></li>';
        }
    }
    return false;
}

/* Add fb:pages meta data */
add_action('wp_head','custom_add_meta_tags');
function custom_add_meta_tags(){
  echo '<meta property="fb:pages" content="521904857961681" />';
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
            '%frontend_label%'       => $title,
            '%open_price_input%'     => $input_field,
            '%currency_symbol%'      => $currency_symbol,
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
            get_option( 'wcj_product_open_price_frontend_template', '<label for="wcj_open_price">%frontend_label%</label> %open_price_input% %currency_symbol%' )
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

/* Remove Author information from FaceBook Instant Articles */
function tk_instant_articles_authors( $authors, $post_id) {
	$authors = array();
    return $authors;
}
add_filter('instant_articles_authors', 'tk_instant_articles_authors', 10, 2);




function modify_login_menu_item( $item ) {

    if(isset($item->title)){
        if( $item->title =='Login' ) {
            if(is_user_logged_in()){
                $item->url = wp_logout_url( get_permalink() );
                $item->title = 'Logout';
            } else {
                $item->url = site_url( 'login' );
            }
        } elseif ( $item->title =='Logout' ) {
            if(!is_user_logged_in()){
                $item->url = site_url( 'login' );
                $item->title = 'Login';
            } else {
                $item->url = wp_logout_url( get_permalink() );
            }
        }
    }
    return $item;
}
add_filter( 'wp_setup_nav_menu_item', 'modify_login_menu_item' );

function modify_signup_menu_item( $item ) {

    if(isset($item->title)){
        if( $item->title =='Sign Up' ) { 
            // Change Sign Up to Profile
            if(is_user_logged_in()){			
				$current_user = wp_get_current_user();
				$user=$current_user->user_login ;
				$profilelink = 'https://samyeinstitute.org/study-practice/members/' . $user . '/profile/';
                $item->url = $profilelink;
                $item->title = 'My Profile';
            } else {
                $item->url = 'https://samyeinstitute.org/register/';
            }
        } elseif ( $item->title ==='My Profile' ) {
            if(!is_user_logged_in()){
                $item->url = 'https://samyeinstitute.org/register/';
                $item->title = 'Sign Up';
            } else {
                $current_user = wp_get_current_user();
				$user=$current_user->user_login ;
				$profilelink = 'https://samyeinstitute.org/study-practice/members/' . $user . '/profile/';
                $item->url = $profilelink;
            }
        }
    }
    return $item;
}
add_filter( 'wp_setup_nav_menu_item', 'modify_signup_menu_item' );


// Remove plain-text conversion - WP Better Emails with BBPress.
add_action( 'bp_ges_before_bp_send_email', function() {
    remove_filter( 'bp_email_get_property', 'ass_email_convert_html_to_plaintext', 20, 3 );
} );
add_filter( 'wpbe_plaintext_body', 'strip_tags' );





// customizing woocommerce product page (for fundraising 'product') j00lz
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

function woocommerce_template_product_description() {
  woocommerce_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

add_filter('woocommerce_product_description_heading', '__return_null');

// skip cart and go straight to checkout
add_filter('add_to_cart_redirect', 'fundraising_add_to_cart_redirect');
function fundraising_add_to_cart_redirect() {
 global $woocommerce;
 $checkout_url = $woocommerce->cart->get_checkout_url();
 return $checkout_url;
}


remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('display_woocommerce_cart_button', 'woocommerce_template_single_add_to_cart', 1);

function shortcode_mobility_disclaimer( $atts ){
    extract(shortcode_atts([
    ], $atts));
    $output = '<div id="mobility-disclaimer-form">';
    $output .= '<form action="#" method="post">';
    $output .= '<label>';
    $output .= '<input type="checkbox" name="accept" value="yes">';
    $output .= '<span>I have read and understand these precautions. I wish to continue now to the video.</span>';
    $output .= '</label>';
    $output .= '</form>';
    $output .= '</div>';

    return $output;
}
add_shortcode( 'mobility_disclaimer', 'shortcode_mobility_disclaimer' );

function shortcode_vimeo_embed( $atts ) {
    extract(shortcode_atts([
        'id' => ''
    ], $atts));
    
    return '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/' .trim( $id ). '" style="position:absolute;left:0;top:0;width:100%;height:100%;" frameborder="0" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
}
add_shortcode( 'vimeo_embed', 'shortcode_vimeo_embed' );

//[crowdfunding_button]
function func_crowdfunding_button( $atts ){
    if ( ! is_admin() ) { // prevents error when editing from admin screen
    	ob_start();
        	do_action( 'display_woocommerce_cart_button');
        	$shortcode_output = ob_get_contents();
    	ob_end_clean();
	}
    return $shortcode_output;
}
add_shortcode( 'crowdfunding_button', 'func_crowdfunding_button' );



function generateHero( $params = [], $content = null ) {
    extract(shortcode_atts([
		'heading' => 'SAMYE',
        'link_text' => 'link',
        'link_to' => '#'
	], $params));
   
    return 
      
'<div class="hero"><div class="hero-text"><h1>' . $heading . '</h1><p>' . $content .
'</p><a href="' . $link_to . '" class="hero-link">' . $link_text . '</a></div><div class="hero-empty"></div></div>';          

}
// Cards
function generateCardsContainer($params =[], $content=null) {
   return 
     '<div class="cards-container">' . do_shortcode($content) . '</div>';
}

function generateCard( $params = [], $content = null ) {
    extract(shortcode_atts([
		'header' => '',
        'subheader' => '',
        'image' => '', 
        'link_text' => '',
        'link_to' => ''
	], $params));
    
    $output = '<div class="sd_card">';
    if( !empty( $header ) ) {
        $output .= '<div class="sd_card__header"><div class="sd_card__header-text">' . $header . '&nbsp;&nbsp;&nbsp;' . '</div></div>';
        $output .= '<div class="sd_card__body">';
        $output .= '<div class="sd_card__imagecontainer"><img src="' . $image . '" class="sd_card__image"></div>';
        $output .= '<div class="sd_card__infotext">';
        $output .= '<h2>' . $subheader . '</h2>';
        $output .= '<p>' . $content . '</p>';
        $output .= '<a href="' . $link_to . '">' . $link_text . '</a>';
        $output .= '</div><!-- sd_card__infotext -->';
        $output .= '</div><!-- sd_card__body -->';
    }
    $output .= '</div><!-- sd_card -->';

    return $output;
}

function register_shortcodes(){
   add_shortcode('hero', 'generateHero');
   add_shortcode('cards', 'generateCardsContainer');
   add_shortcode('card', 'generateCard');
}

add_action( 'init', 'register_shortcodes');


// Add Guru Rinpoche Day posts to taxonomy views
function add_grd_to_taxonomy_view( $query ) {
    if ( $query->is_tag() || $query->is_category() && $query->is_main_query() ) {
        // echo '<textarea cols="50" rows="10">'; print_r( $query ); echo '</textarea>';

        $query->set( 'post_type', array( 'post', 'grd-teaching' ) );
    }
}
add_action( 'pre_get_posts', 'add_grd_to_taxonomy_view' );
// EOF

// ADD CUSTOM FIELD IN COURSE
add_filter( 'wplms_course_metabox', 'sen_wplms_course_metabox' );
function sen_wplms_course_metabox( $metabox ) {
    $metabox['vibe_course_details'] = array(
        'label' => 'Custom Course Curriculum',
        'desc'  => 'Add custom content for the curriculum section',
        'id'    => 'sen_custom_curriculum',
        'type'  => 'editor',
        'std'   => ''
    );

    return $metabox;
}
// END ADD CUSTOM FIELD

// CUSTOM LOGIN REDIRECT
add_filter( 'login_redirect', 'sen_login_redirect', 99, 3 );
function sen_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
    if( WC()->cart->is_empty() ) {
    } else {
        $redirect_to = wc_get_checkout_url();
    }

    return $redirect_to;
}
// END CUSTOM LOGIN REDIRECT

// ADD CART LINK TO CHECKOUT
add_action( 'woocommerce_before_checkout_form', 'sen_cart_link_checkout', 8 );
function sen_cart_link_checkout() {
    echo '<div class="woocommerce-info cart-link"><a href="' .wc_get_cart_url(). '">View cart</a></div>';
}
// END ADD CART LINK TO CHECKOUT

add_filter( 'bbp_get_topic_post_type_supports', 'sen_bbp_get_topic_post_type_supports' );
function sen_bbp_get_topic_post_type_supports( $supports ) {
    $supports[] = 'author';
    return $supports;
}
?>