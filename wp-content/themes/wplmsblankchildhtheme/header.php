<?php
//Header File
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php
    wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=439066266484127&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="global" class="global">
    <?php
        get_template_part('mobile','sidebar');
    ?>
    <div class="pusher">
        <?php
            $fix=vibe_get_option('header_fix');
        ?>
        <header>
            <div class="<?php echo vibe_get_container(); ?>">
                <div class="row">
                    <div class="col-xs-6 col-sm-3 col-lg-4">
                        <?php
                            $url = apply_filters('wplms_logo_url',VIBE_URL.'/assets/images/logo.png','header');
                        ?>
                        <div class="logo-wrapper">
                            <a class="logo" href="<?php echo vibe_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-white.png" width="100" height="48" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-9 col-lg-8">
                        <div class="top-nav">
                            <?php
                                if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                            ?>
                                <ul class="login-menu logged-in">
                                    <li><a href="<?php bp_loggedin_user_link(); ?>" class="loggedin-icon"><?php bp_loggedin_user_avatar( 'type=full' ); ?></a></li>
                                    <?php do_action('wplms_header_top_login'); ?>
                                </ul>
                                <?php
                                    else :
                                ?>
                                    <ul class="login-menu logged-out">
                                        <li><a href="/login/" class=""><?php _e('Sign In','vibe'); ?></a></li>
                                        <li><?php 
                                            $enable_signup = apply_filters('wplms_enable_signup',0);
                                            if ( $enable_signup ) : 
                                            $registration_link = apply_filters('wplms_buddypress_registration_link',site_url( BP_REGISTER_SLUG . '/' ));
                                            printf( __( '<a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'">'.__('| Sign Up','vibe').'</a> ', 'vibe' ), $registration_link );
                                        endif; ?>
                                        </li>
                                    </ul>
                                <?php
                                endif;

                                do_action( 'wpml_add_language_selector' );

                                ?>
                        </div>
                        

                        <?php
                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="fa fa-search"></i></a></li></ul>',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 

                        ?>

                        <a id="trigger">
                            <span class="lines"></span>
                        </a> 
                    </div>
                </div>
            </div>
        </header>
