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
        </div>
    </div>
</div>


<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );