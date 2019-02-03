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

<div class="default-post-carousel post-carousel-outer-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="post-carousel-header">
                    <h2 class="section-header"><?php $term = get_term( 316 ); echo $term->name; ?></h2>
                    <p><?php echo $term->description; ?></p>
                </div>

                <div class="post-carousel">
                    <?php
                        $wisdomArgs = array(
                            'numberposts' => 12,
                            'offset' => 0,
                            'category' => 316,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'include' => '',
                            'exclude' => '',
                            'meta_key' => '',
                            'meta_value' =>'',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => false,
                        );
                        $lastposts = get_posts( $wisdomArgs );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                    ?>
                    <div class="post-carousel-item">
                        <div class="image">
                            <img src="<?php the_post_thumbnail_url( 'thumbnail' ) ?>" alt="">
                            <div class="category">
                                <p><?php echo $format ?></p>
                            </div>
                        </div>
                        <div class="title"><h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2></div>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>

                <div class="view-all">
                    <a href="/category/wisdom">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="primary-post-carousel post-carousel-outer-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="post-carousel-header">
                <h2 class="section-header"><?php $term = get_term( 1197 ); echo $term->name; ?></h2>
                    <p><?php echo $term->description; ?></p>
                </div>

                <div class="post-carousel">
                    <?php
                        $wisdomArgs = array(
                            'numberposts' => 12,
                            'offset' => 0,
                            'category' => 1197,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'include' => '',
                            'exclude' => '',
                            'meta_key' => '',
                            'meta_value' =>'',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => false,
                        );
                        $lastposts = get_posts( $wisdomArgs );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                    ?>
                    <div class="post-carousel-item">
                        <div class="image">
                            <img src="<?php the_post_thumbnail_url( 'thumbnail' ) ?>" alt="">
                            <div class="category">
                                <p><?php echo $format ?></p>
                            </div>
                        </div>
                        <div class="title"><h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2></div>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>

                <div class="view-all">
                    <a href="/category/guru-rinpoche-day">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="default-post-carousel post-carousel-outer-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="post-carousel-header">
                <h2 class="section-header"><?php $term = get_term( 989 ); echo $term->name; ?></h2>
                    <p><?php echo $term->description; ?></p>
                </div>

                <div class="post-carousel">
                    <?php
                        $wisdomArgs = array(
                            'numberposts' => 12,
                            'offset' => 0,
                            'category' => 989,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'include' => '',
                            'exclude' => '',
                            'meta_key' => '',
                            'meta_value' =>'',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => false,
                        );
                        $lastposts = get_posts( $wisdomArgs );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                    ?>
                    <div class="post-carousel-item">
                        <div class="image">
                            <img src="<?php the_post_thumbnail_url( 'thumbnail' ) ?>" alt="">
                            <div class="category">
                                <p><?php echo $format ?></p>
                            </div>
                        </div>
                        <div class="title"><h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2></div>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>

                <div class="view-all">
                    <a href="/category/radically-happy">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="secondary-post-carousel post-carousel-outer-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="post-carousel-header">
                <h2 class="section-header"><?php $term = get_term( 1153 ); echo $term->name; ?></h2>
                    <p><?php echo $term->description; ?></p>
                </div>

                <div class="post-carousel">
                    <?php
                        $wisdomArgs = array(
                            'numberposts' => 12,
                            'offset' => 0,
                            'category' => 1153,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'include' => '',
                            'exclude' => '',
                            'meta_key' => '',
                            'meta_value' =>'',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => false,
                        );
                        $lastposts = get_posts( $wisdomArgs );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                    ?>
                    <div class="post-carousel-item">
                        <div class="image">
                            <img src="<?php the_post_thumbnail_url( 'thumbnail' ) ?>" alt="">
                            <div class="category">
                                <p><?php echo $format ?></p>
                            </div>
                        </div>
                        <div class="title"><h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2></div>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>

                <div class="view-all">
                    <a href="/category/wellness">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="explore-by-topic-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="section-header">Explore by Topic</h2>
                <div class="select-nav">
                    <select name="topic">
                        <?php
                            $tags = get_tags();
                            
                            foreach ( $tags as $tag ) {
                                if ($tag->count > 20) {                            
                        ?>
                                    
                                    <option value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></option>
                                            
                        <?php
                                }
                            }
                        ?>
                    </select> 
                </div>
                <div class="filter-nav">
                <?php
                    $tags = get_tags();
                    $tagsArr = array();
                    $filterNavIndex = 0;

                    foreach ( $tags as $tag ) {
                        if ($tag->count > 20) {
                            $filterNavIndex++;
                            $tag_link = get_tag_link( $tag->term_id );
                            array_push($tagsArr, $tag->slug);
                ?>
                            

                            <div class="filter-item">
                                <button class="<?php echo $tag->slug; ?> <?php echo $filterNavIndex == 1 ? 'active' : '' ?>"><?php echo $tag->name; ?></button>
                            </div>
                            
                <?php
                        }
                    }
                ?>
                </div>

                <div class="carousel-container">
                    <?php
                        $carouselIndex = 0;
                        foreach ( $tagsArr as $tag ) {
                            $carouselIndex++;
                    ?>
                        <div class="post-carousel-wrapper <?php echo $tag; ?> <?php echo $carouselIndex == 1 ? 'active' : '' ?>">
                            <div class="post-carousel">
                                <?php
                                    $wisdomArgs = array(
                                        'numberposts' => 12,
                                        'offset' => 0,
                                        'category' => 0,
                                        'tag_slug__in' => array($tag),
                                        'orderby' => 'post_date',
                                        'order' => 'DESC',
                                        'include' => '',
                                        'exclude' => '',
                                        'meta_key' => '',
                                        'meta_value' =>'',
                                        'post_type' => 'post',
                                        'post_status' => 'publish',
                                        'suppress_filters' => false,
                                    );
                                    $lastposts = get_posts( $wisdomArgs );
                                    foreach ( $lastposts as $post ) :
                                    setup_postdata( $post );
                                    $format = get_post_format() ? : 'Article';
                                ?>
                                <div class="post-carousel-item">
                                    <div class="image">
                                        <img src="<?php the_post_thumbnail_url( 'thumbnail' ) ?>" alt="">
                                        <div class="category">
                                            <p><?php echo $format ?></p>
                                        </div>
                                    </div>
                                    <div class="title"><h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2></div>
                                </div>
                                <?php 
                                    endforeach; 
                                    wp_reset_postdata(); 
                                ?>
                            </div>
                        </div>
                    <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="social_sharing all-posts">
    <h2 class="section-header">Share This Page</h3>
    <?php echo do_shortcode('[Sassy_Social_Share]') ?>  
</div>

<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );