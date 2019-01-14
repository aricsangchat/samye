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
            <?php
                $template = get_post_meta(get_the_ID(),'vibe_template',true);
                if($template == 'right'){
                    echo '<div class="col-md-9 col-sm-8 right">';
                }else if($template == 'full'){
                    echo '<div class="col-md-12">';
                }else{
                    echo '<div class="col-md-9 col-sm-8 ">';
                }

            ?>

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


                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <div class="content">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                    ?>

                    <div class="teaching-prep-msg">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-quote-red.png);" alt="">
                        <p>Please take a few moments before you begin this teaching to settle yourself. Sit upright, yet naturally relaxed. Before listening to and/ or reading the teaching make aspirations such as: "I am extremely fortunate to have the opportunity to listen to the precious Dharma. I am doing this for the benefit of all sentient beings so that they may be free from suffering and attain complete awakening".</p>
                    </div>

                    <div class="content-body">
                        <?php
                            the_content();
                        ?>
                    </div>

                    <div class="teaching-prep-msg">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-quote-red.png);" alt="">
                        <p>At the end of the teaching, please remember to dedicate the merit of receiving a Dharma teaching. As you go through your day, take a few moments from time to time to recall these instructions.</p>
                    </div>

                    <div class="tags">
                        <p class="tag-header">Tags:</p>
                        <?php echo  the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
                    </div>

                    <div class="social_sharing">
                        <h3>Share This Article</h3>
                        <?php echo do_shortcode('[Sassy_Social_Share]') ?>  
                    </div>

                    <div class="related-articles">
                        <h3>Related Articles</h3>
                        <div class="row">
                            <?php 
                                $relatedArgs = array(
                                    'numberposts' => 4,
                                    'orderby' =>  'RAND('.rand(1,100).')',
                                    'category__in' => $categories[0]->term_id,
                                    'post_type' => array('post', 'grd-teaching'),
                                    'post_status' => 'publish',
                                );
                                $lastposts = get_posts( $relatedArgs );
                                foreach ( $lastposts as $post ) :
                            ?>
                            <div class="col-xs-12 col-sm-6 col-lg-3">
                                <div class="related-article-wrapper">
                                    <div class="related-article-image" style="background-image: url(<?php the_post_thumbnail_url( 'medium' ) ?>);"></div>
                                    <h4><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h4>
                                </div>
                            </div>
                            <?php 
                                endforeach; 
                                wp_reset_postdata(); 
                            ?>      
                        </div>
                    </div>
                </div>


                <?php
                        $prenex=get_post_meta(get_the_ID(),'vibe_prev_next',true);
                        if(vibe_validate($prenex)){
                    ?>
                    <div class="prev_next_links">
                        <ul class="prev_next">
                            <?php 
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            echo '<li>';
                            if(!empty($prev_post))
                            echo '<a href="'.get_permalink($prev_post->ID).'" class="prev"><strong>'.get_the_post_thumbnail($prev_post->ID,'thumbnail').'<span>'.$prev_post->post_title.'</span></strong></a>';

                            echo '<li>';
                            if(!empty($next_post))
                            echo '<a href="'.get_permalink($next_post->ID).'" class="next"><strong>'.get_the_post_thumbnail($next_post->ID,'thumbnail').'<span>'.$next_post->post_title.'</span></strong></a>';
                            echo '</li>';
                            ?>
                        </ul>    
                    </div>
                    <?php
                        }
                    ?>
                </div>
                
                <?php
                    $author = getPostMeta($post->ID,'vibe_author',true);
                    if(vibe_validate($author)){
                        if ( function_exists('get_coauthors')){
                            $coauthors = get_coauthors( $id );
                            if(isset($coauthors)){
                                foreach($coauthors as $k=>$inst){

                                    $instructor_id = $inst->ID;
                                    $displayname = $inst->display_name;
                                    $author_posts_url = get_author_posts_url( $instructor_id );
                                    $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
                                    $description = get_user_meta($instructor_id,'description',true);
                                    $website = $inst->user_url;
                                    ?>
                                    <div class="postauthor">
                                        <div class="auth_image">
                                            <?php
                                                echo get_avatar( $instructor_id, '160');
                                             ?>
                                        </div>
                                        <div class="author_info">
                                            <a href="<?php echo $author_posts_url; ?>" class="readmore link"><?php _e('Posts','vibe'); ?></a><a class="readmore">&nbsp;|&nbsp;</a><a href="<?php echo $author_posts_url.$instructing_courses; ?>" class="readmore link"><?php _e('Courses','vibe'); ?></a>
                                            <h6><?php echo $displayname; ?></h6>
                                            <div class="author_desc">
                                                <p>
                                                    <?php echo $description; ?>
                                                </p>
                                                <p class="website"><?php _e('Website','vibe');?> : <a href="<?php  echo $website; ?>" target="_blank"><?php echo $website ;?></a></p>
                                                <?php
                                                    vibe_author_social_icons($instructor_id);
                                                ?>  
                                            </div>     
                                        </div>    
                                    </div>
                                    <?php
                                }
                            }
                        }else{
                            ?>
                            <div class="postauthor">
                                <div class="auth_image">
                                    <?php
                                        echo get_avatar( get_the_author_meta('email'), '160');
                                        $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
                                     ?>
                                </div>
                                <div class="author_info">
                                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="readmore link"><?php _e('Posts','vibe'); ?></a><a class="readmore">&nbsp;|&nbsp;</a><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ).$instructing_courses; ?>" class="readmore link"><?php _e('Courses','vibe'); ?></a>
                                    <h6><?php the_author(); ?></h6>
                                    <div class="author_desc">
                                        <p>
                                            <?php  the_author_meta( 'description' );?>
                                        </p>
                                        <p class="website"><?php _e('Website','vibe');?> : <a href="<?php  the_author_meta( 'url' );?>" target="_blank"><?php  the_author_meta( 'url' );?></a></p>
                                        <?php
                                            $author_id=  get_the_author_meta('ID');
                                            vibe_author_social_icons($author_id);
                                        ?>  
                                    </div>     
                                </div>    
                            </div>
                            <?php
                        }
                    } 
                
                comments_template();
                endwhile;
                endif;
                ?>
            </div>
            <?php
             if($template != 'full'){
            ?>
            <div class="col-md-3 col-sm-3">
                <?php get_template_part( 'sidebar' ); ?>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>
<?php
get_footer(vibe_get_footer());
?>