<?php


?>
<div class="pagesidebar">
    <div class="sidebarcontent">   
        <?php
            if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                ?>
                <ul class="login-menu">
                    <li><a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php $n=vbp_current_user_notification_count(); echo ((isset($n) && $n)?'<em></em>':''); bp_loggedin_user_avatar( 'type=full' ); ?></a></li>
                    <?php do_action('wplms_header_top_login'); ?>
                </ul>
            <?php
            else :
                ?>
                <ul class="login-menu logged-out">
                    <li><a href="#login" class="vbplogin"><?php _e('Sign In | ','vibe'); ?></a></li>
                    <li><?php 
                        $enable_signup = apply_filters('wplms_enable_signup',0);
                        if ( $enable_signup ) : 
                        $registration_link = apply_filters('wplms_buddypress_registration_link',site_url( BP_REGISTER_SLUG . '/' ));
                        printf( __( '<a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'">'.__('Sign Up','vibe').'</a> ', 'vibe' ), $registration_link );
                    endif; ?>
                    </li>
                </ul>
            <?php
            endif;
        ?> 
        <?php
            $args = apply_filters('wplms-mobile-menu',array(
                'theme_location'  => 'mobile-menu',
                'container'       => '',
                'menu_class'      => 'sidemenu',
                'items_wrap' => '<div class="mobile_icons"><a id="mobile_searchicon"><i class="fa fa-search"></i></a>'.( (function_exists('WC')) ?'<a href="'.esc_url( wc_get_cart_url() ).'"><span class="fa fa-shopping-basket"><em>'.WC()->cart->cart_contents_count.'</em></span></a>':'').'</div><ul id="%1$s" class="%2$s">%3$s</ul>',
                'fallback_cb'     => 'vibe_set_menu',
            ));

            wp_nav_menu( $args );
        ?>
    </div>
</div>  