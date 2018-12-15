<?php
get_header('buddypress'); 
?>

<style>
.content ul {margin: 0px !important;}
</style>

<?php
$page_array = get_option('bp-pages');
if( isset($page_array['register']) ) {
	$id = $page_array['register'];
}
?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                        $title=get_post_meta(get_the_ID(),'vibe_title',true);
                        if( vibe_validate($title) || empty($title) ) {
                            ?>
                            <div class="pagetitle" style="margin-bottom:20px;margin-top:-20px;">
                            <?php
                            $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                            if( vibe_validate($breadcrumbs) || empty($breadcrumbs) ) {
                                vibe_breadcrumbs(); 
                            }
                            ?>
                            <h1><?php the_title(); ?></h1>
                            <?php the_sub_title(); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if( WC()->cart->cart_contents_count > 0 ) {
                            echo '<div class="message success">' . __( '<strong>Thank you</strong> for choosing to purchase the course, please create your account.', 'vibe' ) . '</div><br/><br/>';
                            echo '<div class="message information">' . __( 'Already have an account? Please <a href="http://samyedharma.org/wp-login.php?redirect_to=http%3A%2F%2Fsamyedharma.org%2Fcheckout"> Click here to login to your Samye account.</a>', 'vibe' ) . '</div>';
                        }
                        ?>		
                        <?php the_content(); ?>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<?php
get_footer('buddypress');
?>
