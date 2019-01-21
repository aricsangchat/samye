<?php

if ( !defined( 'ABSPATH' ) ) exit;

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();

?>

<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php echo get_post_type(get_the_ID()) == "post" ? "Wisdom Blog" : get_post_type(get_the_ID()); ?></h2>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="<?php echo vibe_get_container(); ?>">
        
        <div class="row">
            <div class="col-xs-12">
                <section id="breadcrumbs">
                    <div class="">
                        <div class="row">
                            <div class="col-xs-12">
                            <?php 
                                $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                                if(!isset($breadcrumbs) || !$breadcrumbs || vibe_validate($breadcrumbs)){
                                    vibe_breadcrumbs();
                                }   
                            ?>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="post-title">
                    <?php do_action('wplms_before_title'); ?>
                    <div class="">
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="date">~ <?php echo the_modified_date(); ?> ~</p>
                                <h1><?php the_title(); ?></h1>
                                <?php 
                                $postType = get_post_format(get_the_ID()) ? : "Article";
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo '<h2 class="category"><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . ' • </a>'. $postType .'</h2>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="content <?php echo get_post_type(get_the_ID()); ?>">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                    ?>
                    
                    <?php if($categories[0]->name === "wisdom-blogs"){ ?>

                    <div class="teaching-prep-msg">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-quote-red.png);" alt="">
                        <p>Please take a few moments before you begin this teaching to settle yourself. Sit upright, yet naturally relaxed. Before listening to and/ or reading the teaching make aspirations such as: "I am extremely fortunate to have the opportunity to listen to the precious Dharma. I am doing this for the benefit of all sentient beings so that they may be free from suffering and attain complete awakening".</p>
                    </div>

                    <?php
                    }
                    ?>

                    <div class="content-body">
                        <?php
                            the_content();
                        ?>
                    </div>

                    <div class="social_sharing">
                        <h3>Share This Article</h3>
                        <?php echo do_shortcode('[Sassy_Social_Share]') ?>  
                    </div>

                </div>
            </div>

        <?php
            
        endwhile;
        endif;
        ?>
        </div>
    </div>
</section>
<?php
get_footer(vibe_get_footer());
?>