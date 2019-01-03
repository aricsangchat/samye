<?php
global $post;
get_header(vibe_get_header());

// if ( have_posts() ) : while ( have_posts() ) : the_post();

?>

<?php

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <?php

    // check if the flexible content field has rows of data
    if( have_rows('flexible_layout') ):

        // loop through the rows of data
        while ( have_rows('flexible_layout') ) : the_row();

            if( get_row_layout() == 'hero_slider' ):
                
                if( have_rows('hero_slide') ):
                    echo '    <div id="hero-carousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#chero-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#hero-carousel" data-slide-to="1"></li>
                    </ol>
            
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">';
                    
                    while ( have_rows('hero_slide') ) : the_row();
                        if (get_row_index() === 1 ) {
                            echo '<div class="item active">
                            <div class="carousel-image" style="background-image: url('. get_sub_field('hero_image') .')">
                                <div class="hero-caption">
                                    <h2>'.get_sub_field('hero_title').'</h2>
                                    '.get_sub_field('hero_text_area').'
                                    <a class="primary-btn" href="'.get_sub_field('hero_button_url').'">'.get_sub_field('hero_button_text').'</a>
                                </div>
                            </div>
                            </div>';
                        } else {
                            echo '<div class="item">
                            <div class="carousel-image" style="background-image: url('. get_sub_field('hero_image') .')">
                                <div class="hero-caption">
                                    <h2>'.get_sub_field('hero_title').'</h2>
                                    '.get_sub_field('hero_text_area').'
                                    <a class="primary-btn" href="'.get_sub_field('hero_button_url').'">'.get_sub_field('hero_button_text').'</a>
                                </div>
                            </div>
                            </div>';
                        }
                        

                    endwhile;

                    echo '        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#hero-carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#hero-carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>';

                endif;

            elseif( get_row_layout() == '2_column_content_default' ): 
                echo '<div class="container about-section">
                <div class="row default-text-block">
                    <div class="col-xs-12 col-sm-6">
                        <h2>'.get_sub_field('title').'</h2>
                        '.get_sub_field('text_area').'
                        <a class="primary-btn" href="'.get_sub_field('button_url').'">'.get_sub_field('button_text').'</a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="about-image image-dropshadow" style="background-image: url('. get_sub_field('image')['url'] .')"></div>
                    </div>
                </div>
            </div>';
            elseif( get_row_layout() == 'download' ): 

                $file = get_sub_field('file');

            endif;

        endwhile;

    else :

        // no layouts found

    endif;

    ?>

    <div class="program-group-block">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-study-programs">
                    <h2>At home study programs</h2>
                    <p>Enroll in one of our short-term or long-term study programs and receive guidance from authentic tibetan teachers. Follow your sincere aspiration and blablabla The standard chunk of Lorem Ipsum used since the 1500s.</p>
                    <div class="row row-programs">
                        <div class="col-xs-6 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/courses">Explore Programs</a>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-groups">
                    <h2>Dharma Groups - Practice Together</h2>
                    <p>Practicing together is a real blessing for all of us. Etc. The standard chunk of Lorem Ipsum used since the 1500s is reproduced.</p>
                    <img class="icon-map" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-map.png" alt="dharma groups">
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="#">Explore Groups</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container latest-learning-resources">
        <div class="row">
            <div class="col-xs-12">
                <h2>Latest Learning Resources</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
                <?php
                    $args1 = array(
                        'numberposts' => 1,
                        'offset' => 0,
                        'category' => 0,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'include' => '',
                        'exclude' => '',
                        'meta_key' => '',
                        'meta_value' =>'',
                        'post_type' => 'course',
                        'post_status' => 'publish',
                        'suppress_filters' => true
                    );
                    $lastposts = get_posts( $args1 );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                    $category = get_the_category();
                    $category_id = get_cat_ID( $category[0]->cat_name );
                    $category_link = get_category_link( $category_id );
                ?>
                <div class="course-image" style="background-image: url('<?php the_post_thumbnail_url('full' ) ?>');"><div class="overlay-category"><p><span>Learning Program</span></p></div></div>
                <h3><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Additional Courses';  ?></a></h3>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php excerpt('22'); ?>
                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="row">
                    <?php
                        $args2 = array(
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'post_type' => 'post',
                            'posts_per_page' => 2,
                            'post_status' => 'publish',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'post_format',
                                    'field' => 'slug',
                                    'terms' => array('post-format-audio', 'post-format-video'),
                                    'operator' => 'IN'
                                )
                            )
                        );
                        $lastposts = get_posts( $args2 );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                        $category = get_the_category();
                        $category_id = get_cat_ID( $category[0]->cat_name );
                        $category_link = get_category_link( $category_id );
                    ?>
                    <div class="col-xs-6 col-sm-12 center-grid">
                        <div class="center-images" style="background-image: url('<?php the_post_thumbnail_url('full' ) ?>');">
                            <div class="overlay-category"><p><span><?php echo $format ?></span></p></div>
                        </div>
                        <h3><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Study & Practice';  ?></a></h3>
                        <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                        <?php excerpt('22'); ?>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4">
                <div class="row">
                    <?php
                        $args3 = array(
                            'numberposts' => 4,
                            'offset' => 0,
                            'category' => 0,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'include' => '',
                            'exclude' => '',
                            'meta_key' => '',
                            'meta_value' =>'',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => true,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'post_format',
                                    'field' => 'slug',
                                    'terms' => array('post-format-audio', 'post-format-video'),
                                    'operator' => 'NOT IN'
                                )
                            )
                        );
                        $lastposts = get_posts( $args3 );
                        foreach ( $lastposts as $post ) :
                        setup_postdata( $post );
                        $format = get_post_format() ? : 'Article';
                        $category = get_the_category();
                        $category_id = get_cat_ID( $category[0]->cat_name );
                        $category_link = get_category_link( $category_id );
                    ?>
                    <div class="col-xs-6 col-sm-4 col-md-12 col-articles">
                        <div class="article-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ) ?>');">
                            <div class="overlay-category"><p><span><?php echo $format ?></span></p></div>
                        </div>
                        <h3><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Study & Practice';  ?></a></h3>
                        <h2><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                        <?php excerpt('12'); ?>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="button-wrapper">
                    <a class="primary-btn" href="#">View All</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grd-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="grd-section-intro">
                        <h2>Guru Rinpoche Day Teachings</h2>
                        <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div id="grd-carousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#grd-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#grd-carousel" data-slide-to="1"></li>
                            <li data-target="#grd-carousel" data-slide-to="2"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <?php
                                $args4 = array(
                                    'numberposts' => 3,
                                    'offset' => 0,
                                    'category' => 0,
                                    'orderby' => 'post_date',
                                    'order' => 'DESC',
                                    'include' => '',
                                    'exclude' => '',
                                    'meta_key' => '',
                                    'meta_value' =>'',
                                    'post_type' => 'grd-teaching',
                                    'post_status' => 'publish',
                                    'suppress_filters' => true
                                );
                                $lastposts = get_posts( $args4 );
                                $i = -1;
                                foreach ( $lastposts as $post ) :
                                $i++;
                                setup_postdata( $post );
                            ?>
                            <div class="item <?php echo $i == 0 ? 'active' : '' ?>">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="post-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ) ?>')"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="post-content">
                                            <p class="date"><?php the_date(); ?></p>
                                            <h2><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                                            <?php the_excerpt(); ?>
                                            <a class="primary-btn-inverted" href="<?php the_permalink() ?>">Read more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                endforeach; 
                                wp_reset_postdata(); 
                            ?>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#grd-carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#grd-carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="#">View All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container weekly-reflection">
        <div class="row">
            <div class="col-xs-12">
                <h3>Weekly Reflections</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum"</p>
                <h4>Weekly Reflections</h2>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-knot.png" />
            </div>
        </div>
    </div>

    <div class="footer-newsletter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Join Our Mailing List</h2>
                    <p>To receive the latest teachings and information from Samye Institute, including Phakchok Rinpoche’s monthly Guru Rinpoche Day message.</p>
                    <!-- Begin Mailchimp Signup Form -->
                    <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
                    <style type="text/css">
                        #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; width:100%;}
                        /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
                        We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                    </style>
                    <style type="text/css">
                        #mc-embedded-subscribe-form input[type=checkbox]{display: inline; width: auto;margin-right: 10px;}
                        #mergeRow-gdpr {margin-top: 20px;}
                        #mergeRow-gdpr fieldset label {font-weight: normal;}
                        #mc-embedded-subscribe-form .mc_fieldset{border:none;min-height: 0px;padding-bottom:0px;}
                    </style>
                    <div id="mc_embed_signup">
                    <form action="https://samyedharma.us12.list-manage.com/subscribe/post?u=e199539057d67dc9afa4d34da&amp;id=4026ab410e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                        
                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e199539057d67dc9afa4d34da_4026ab410e" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                    </div>

                    <!--End mc_embed_signup-->
                </div>
            </div>
        </div>
    </div>

    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-xs-12">

                <!-- <div class="<?php echo $v_add_content;?> content"> -->
                    <?php
                        the_content();
                        $page_comments = vibe_get_option('page_comments');
                        if(!empty($page_comments))
                            comments_template();
                     ?>
                <!-- </div> -->
            </div>
        </div>
    </div>
</section>
<?php
// endwhile;
// endif; 
?>
<?php
get_footer( vibe_get_footer() );