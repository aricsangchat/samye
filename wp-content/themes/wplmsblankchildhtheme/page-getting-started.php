<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();

$curratedCategories = array('buddhism', 'meditation', 'emotions', 'compassion', 'mindfulness', 'addiction' );


?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-getting-started.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
    </div>
</section>

<div class="container-fluid getting-started-container">
    <div class="row">
        <div class="col-sm-4 menu-column">
            <div class="getting-started-menu">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php
                        $catIndex = 0;
                        $carouselIndex = 0;
                        foreach ( $curratedCategories as $cat ) :
                        $catIndex++;
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="<?php echo $cat.'heading'; ?>">
                        <h4 class="panel-title">
                            <a class="<?php echo $catIndex == 1 ? '': 'collapsed'; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $cat; ?>" aria-expanded="true" aria-controls="<?php echo $cat; ?>">
                            <?php echo $cat; ?> <span class="cat-count"></span>
                            </a>
                        </h4>
                        </div>
                        <div id="<?php echo $cat; ?>" class="panel-collapse collapse <?php echo $catIndex == 1 ? 'in': ''; ?>" role="tabpanel" aria-labelledby="<?php echo $cat.'heading'; ?>">
                        <div class="panel-body">
                            
                        <?php
                            $catPostsIndex = 0;
                            $args = array(
                                'orderby' => 'post_date',
                                'order' => 'DESC',
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'suppress_filters' => false,
                                'tag_slug__and' => array( $cat, 'where-to-start' )
                            );
                            $lastposts = get_posts( $args );
                            foreach ( $lastposts as $post ) :
                            $catPostsIndex++;
                            setup_postdata( $post );
                            
                        ?>

                            <div class="menu-item" data-index="<?php echo $carouselIndex; ?>"><p><?php echo $catPostsIndex; ?>.</p><button class="<?php echo $cat.$catPostsIndex; ?>" data-index="<?php echo $carouselIndex; ?>"><?php the_title() ?></button><div class="format"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/<?php echo handlePostFormatIcon(); ?>" /></div></div>
                        
                        <?php 
                            $carouselIndex++;
                            endforeach; 
                            wp_reset_postdata(); 
                        ?>
                        
                        </div>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-8 content-column">
            <div class="getting-started-mobile-nav">
                <h2 class="section-header">Change Topic/Article</h2>
                <select>
                    <?php
                        $catIndex = 0;
                        $carouselIndex = 0;
                        foreach ( $curratedCategories as $cat ) :
                        $catIndex++;
                    ?>
                    <optgroup label="<?php echo $cat; ?>">
                        <?php
                            $catPostsIndex = 0;
                            $args = array(
                                'orderby' => 'post_date',
                                'order' => 'DESC',
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'suppress_filters' => false,
                                'tag_slug__and' => array( $cat, 'where-to-start' )
                            );
                            $lastposts = get_posts( $args );
                            foreach ( $lastposts as $post ) :
                            $catPostsIndex++;
                            setup_postdata( $post );
                            
                        ?>
                        <option value="<?php echo $carouselIndex; ?>"><?php  the_title() ?></option>
                        <?php
                            $carouselIndex++;
                            endforeach; 
                            wp_reset_postdata(); 
                        ?>
                    </optgroup>
                    <?php 
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="getting-started-carousel">

            <?php
                $catIndex = 0;
                foreach ( $curratedCategories as $cat ) :
                $catIndex++;
            ?>
            <?php
                $catPostsIndex = 0;
                $args = array(
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'suppress_filters' => false,
                    'tag_slug__and' => array( $cat, 'where-to-start' )
                );
                $lastposts = get_posts( $args );
                foreach ( $lastposts as $post ) :
                $catPostsIndex++;
                setup_postdata( $post );
                
            ?>
            <div class="post-container <?php echo $cat.$catPostsIndex; ?>">    
                <h3 class="category"><?php echo $cat; ?></h3>
                <h2 class="post-title"><?php  the_title() ?></h2>
                <p class="excerpt"><?php the_content(); ?></p>
            </div>
            <?php 
                endforeach; 
                wp_reset_postdata(); 
            ?>
            <?php 
                endforeach;
            ?>
                            
            </div>

            <div class="row row-banner">
                <div class="col-xs-12">
                    <a href="https://radicallyhappy.org/">
                        <picture>
                            <source media="(min-width: 768px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-home-radically-happy.jpg">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-sidebar-radically-happy.jpg" alt="IfItDoesntMatchAnyMedia">
                        </picture>
                    </a>
                </div>
            </div>

            <div class="row related-courses-section">
                <div class="col-xs-12">
                    <h2 class="section-header">Find these articles helpfule? Then We recommend you check out these courses</h2>
                </div>
                <?php
                    $args4 = array(
                        'numberposts' => 2,
                        'offset' => 0,
                        'category_name' => '',
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'include' => array('17234','2585'),
                        'exclude' => '',
                        'meta_key' => '',
                        'meta_value' =>'',
                        'post_type' => 'course',
                        'post_status' => 'publish',
                        'suppress_filters' => false,
                    );
                    $lastposts = get_posts( $args4 );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                ?>
                <div class="col-xs-12 col-sm-6">
                    <div class="row post-row">
                        <div class="col-xs-4 col-sm-12">
                            <div class="image" style="background-image: url('<?php the_post_thumbnail_url('full' ) ?>');"><a href="<?php the_permalink(); ?>" class="bg-a-link"></a></div>
                        </div>
                        <div class="col-xs-8 col-sm-12">
                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="excerpt"><?php echo excerpt('22'); ?></p>
                            <p>
                            <a class="primary-btn" href="<?php the_permalink(); ?>">Find Out More</a>
                            </p>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
            </div>
        </div>
    </div>
</div>


<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );