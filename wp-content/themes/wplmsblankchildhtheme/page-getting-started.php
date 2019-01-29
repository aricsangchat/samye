<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



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
                        $curratedCategories = array('buddhism', 'meditation', 'emotions', 'compassion', 'mindfulness', 'addiction' );
                        $catIndex = 0;
                        foreach ( $curratedCategories as $cat ) :
                        $catIndex++;
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="<?php echo $cat.'heading'; ?>">
                        <h4 class="panel-title">
                            <a class="<?php echo $catIndex == 1 ? '': 'collapsed'; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $cat; ?>" aria-expanded="true" aria-controls="<?php echo $cat; ?>">
                            <?php echo $cat; ?> <span>3</span>
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
                            $format = get_post_format() ? : 'Article';
                            $category = get_the_category();
                            $category_id = get_cat_ID( $category[0]->cat_name );
                            $category_link = get_category_link( $category_id );
                        ?>

                            <div class="menu-item <?php echo $catPostsIndex == 1 ? 'active': ''; ?>"><p><?php echo $catPostsIndex; ?>.</p><button class="<?php echo $cat.$catPostsIndex; ?>"><?php the_title() ?></button></div>
                        
                        <?php 
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
            <h3>Category</h3>
            <h2>Title</h2>
            <p class="excerpt">Excerpt</p>

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