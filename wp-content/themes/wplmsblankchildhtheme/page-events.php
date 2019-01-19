<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-events.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
    </div>
</section>

<?php

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-9">
            <div class="events-intro">
                <h2 class="title">Upcoming Events</h2>
            </div>
            <?php
                $eventsArgs = array(
                    'numberposts' => 5,
                    'offset' => 0,
                    'category' => 0,
                    'category_name' => '',
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'include' => '',
                    'exclude' => '',
                    'meta_key' => '',
                    'meta_value' =>'',
                    'post_type' => 'tribe_events',
                    'post_status' => 'publish',
                    'suppress_filters' => true
                );
                $lastposts = get_posts( $eventsArgs );
                foreach ( $lastposts as $post ) :
                setup_postdata( $post );
            ?>

            <div class="event-item-wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-4 col-lg-3">
                        <div class="event-image" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-events.png)"><div class="date"><p class="month">Feb 18</p><p class="year">2019</p></div></div>
                    </div>
                    <div class="col-xs-12 col-md-8 col-lg-9">
                        <div class="event-info">
                            <p class="category"><?php echo get_the_tag_list('',' | '); ?></p>
                            <h2 class="event-title"><a href="#">Khenpo Gyaltsen Asia Tour: Malaysia - Indonesia - Singapore</a></h2>
                            <p class="excerpt">The four mind changings form the foundation of the Vajrayāna path. When we commit to this path, we start by practicing the preliminary practices: the ngöndro...</p>
                            <p class="location"><span>Location: </span>Ho Chi Minh, Vietnam</p>
                            <p class="time"><span>Time: </span>Feb 28 - Mar 22, 2019</p>
                            <a class="primary-btn" href="#">Find Out More</a>
                        </div>
                    </div>
                </div>
            </div>


            <?php 
                endforeach; 
                wp_reset_postdata(); 
            ?>

            <?php pagination(); ?>

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

<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );