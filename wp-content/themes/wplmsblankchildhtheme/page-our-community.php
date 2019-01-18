<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
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

<div class="container community-dharma-groups">
    <div class="row">
        <div class="col-xs-12">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/community-dharma-map.png" alt="">
        </div>
    </div>
    <div class="row community-dharma-groups-facts">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <h3 class="number">100</h3>
            <p class="description">Total Members</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <h3 class="number">100</h3>
            <p class="description">Total Members</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <h3 class="number">100</h3>
            <p class="description">Total Members</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <h3 class="number">100</h3>
            <p class="description">Total Members</p>
        </div>
    </div>
</div>

<section id="community-dharma-facts">
    <div class="dharma-facts-col-1">
        <div class="dharma-facts-col-1-title-wrapper">
            <h2>Dharma Stream Groups</h2>
        </div>
        <div class="dharma-facts-col-1-description">
            <p>Access precious Dharma wherever you live</p>
        </div>
        <div class="dharma-facts-col-1-bullets">
            <ul>
                <li>Bullet</li>
                <li>Bullet</li>
                <li>Bullet</li>
            </ul>
        </div>
        <div class="button-wrapper">
            <a class="secondary-btn" href="#">Find a Group Near You</a>
        </div>
    </div>
    <div class="dharma-facts-col-2"></div>
</section>

<section id="community-news">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <h2 class="post-list-title">Sharing Our Experiences</h2>
                <h3 class="post-list-subtitle">Our Community</h3>
                <?php
                    $communityArgs = array(
                        'numberposts' => 3,
                        'offset' => 0,
                        'category' => 0,
                        'category_name' => 'community',
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'include' => '',
                        'exclude' => '',
                        'meta_key' => '',
                        'meta_value' =>'',
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'suppress_filters' => true
                    );
                    $lastposts = get_posts( $communityArgs );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                ?>

                <div class="post-wrapper">
                    <div class="post-image" style="background-image: url(<?php the_post_thumbnail_url( 'full' ) ?>)">
                    </div>
                    <div class="post-text">
                        <h3 class="post-tags"><?php echo get_the_tag_list('',' | '); ?></h3>
                        <h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                        <h4 class="post-date"><?php the_date(); ?></h4>
                        <p class="post-excerpt"><?php the_excerpt(); ?></p>
                    </div>
                </div>

                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="dakini-day-sidebar-widget">
                    <h2>Dakini Day Digest</h2>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dakini-day.png" alt="">
                    <h4>The activities of Kyabgön Phakchok Rinpoche</h2>
                    <p>The four mind changings form the foundation of the Vajrayāna path. When we commit to this path, we start by practicing the preliminary practices: the ngöndro. The four mind changings form the foundation of the Vajrayāna path. When we commit to this path, we start by practicing.</p>
                    <div class="button-wrapper">
                        <a class="primary-btn" href="http://dakiniday.samyeinstitute.org/">View Archive</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );