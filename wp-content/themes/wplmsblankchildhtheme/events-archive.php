<?php
/**
 * Template Name: Events Archive
 */

get_header('schedule'); ?>

<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-events.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Events</h2>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-9">
                <div class="events-intro">
                    <h2 class="title">Upcoming Events</h2>
                </div>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?> 
                <?php
                    the_content();
                ?>
                <?php
                
                endwhile;
                endif;
                ?>

                <div class="social_sharing">
                    <h3>Share This Page</h3>
                    <?php echo do_shortcode('[Sassy_Social_Share]') ?>  
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php get_template_part( 'sidebar' ); ?>
            </div>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>