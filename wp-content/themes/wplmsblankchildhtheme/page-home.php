<?php
global $post;
get_header(vibe_get_header());
?>

<section id="content">
    <?php

    // check if the flexible content field has rows of data
    if( have_rows('flexible_layout') ):

        // loop through the rows of data
        while ( have_rows('flexible_layout') ) : the_row();

            if( get_row_layout() == 'hero_slider' ):
                
                if( have_rows('hero_slide') ):
                    echo '    <div class="hero-slider">';
                    
                    while ( have_rows('hero_slide') ) : the_row();
                        echo '<div class="item">
                        <div class="carousel-image" style="background-image: url('. get_sub_field('hero_image') .')">
                        </div>
                        <div class="hero-caption">
                        <h2 class="hero-header">'.get_sub_field('hero_title').'</h2>
                        <p>'.get_sub_field('hero_text_area').'</p>
                        <a class="primary-btn" href="'.get_sub_field('hero_button_url').'">'.get_sub_field('hero_button_text').'</a>
                        </div>
                        </div>';
                        
                    endwhile;

                    echo '</div>';

                endif;

            // elseif( get_row_layout() == '2_column_content_default' ): 
            //     echo '<div class="container about-section">
            //     <div class="row default-text-block">
            //         <div class="col-xs-12 col-sm-6">
            //             <div class="about-section-text">
            //                 <h2 class="section-header">'.get_sub_field('title').'</h2>
            //                     '.get_sub_field('text_area').'
            //                 <a class="primary-btn" href="'.get_sub_field('button_url').'">'.get_sub_field('button_text').'</a>
            //             </div>
            //         </div>
            //         <div class="col-xs-12 col-sm-6">
            //             <div class="about-image image-dropshadow" style="background-image: url('. get_sub_field('image')['url'] .')"></div>
            //         </div>
            //     </div>
            // </div>';
            elseif( get_row_layout() == 'download' ): 

                $file = get_sub_field('file');

            endif;

        endwhile;

    else :

        // no layouts found

    endif;

    ?>

    <div class="container latest-learning-resources">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="section-header">Latest Learning Resources</h2>
            </div>
        </div>
        <div class="row block-row">
            <div class="col-xs-12 col-sm-6 col-md-4 course-block">
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
                        'suppress_filters' => false
                    );
                    $lastposts = get_posts( $args1 );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                    $category = get_the_category();
                    $category_id = get_cat_ID( $category[0]->cat_name );
                    $category_link = get_category_link( $category_id );
                ?>
                <div class="row content-row">
                    <div class="col-xs-4 col-sm-12">
                        <div class="course-image" style="background-image: url('<?php the_post_thumbnail_url('full' ) ?>');"><a href="<?php the_permalink(); ?>" class="bg-a-link"></a><div class="overlay-category"><p><span>Learning Program</span></p></div></div>
                    </div>
                    <div class="col-xs-8 col-sm-12">
                        <h3 class="category"><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Additional Courses';  ?></a></h3>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="excerpt"><?php echo excerpt('22'); ?></p>
                    </div>
                </div>
                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 media-block">
                <div class="row">
                    <?php
                        $args2 = array(
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'post_type' => 'post',
                            'posts_per_page' => 2,
                            'post_status' => 'publish',
                            'suppress_filters' => false,
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
                    <div class="col-xs-12 col-sm-12 center-grid">
                        <div class="row content-row">
                            <div class="col-xs-4 col-sm-12">
                                <div class="center-images" style="background-image: url('<?php the_post_thumbnail_url('full' ) ?>');">
                                    <a href="<?php the_permalink(); ?>" class="bg-a-link"></a>
                                    <div class="overlay-category"><p><span><?php echo $format ?></span></p></div>
                                </div>
                            </div>
                            <div class="col-xs-8 col-sm-12">
                                <h3 class="category"><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Study & Practice';  ?></a></h3>
                                <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                                <p class="excerpt"><?php echo excerpt('22'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                    ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 article-block">
                <div class="row article-row">
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
                            'suppress_filters' => false,
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
                    <div class="col-xs-12 col-sm-4 col-md-12 col-articles">
                        <div class="row content-row">
                            <div class="col-xs-4 col-sm-12 col-md-6 col-lg-5">
                                <div class="article-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ) ?>');">
                                    <a href="<?php the_permalink(); ?>" class="bg-a-link"></a>
                                    <div class="overlay-category"><p><span><?php echo $format ?></span></p></div>
                                </div>
                            </div>
                            <div class="col-xs-8 col-sm-12 col-md-6 col-lg-7">
                                <h3 class="category"><a href="<?php echo $category_link ? $category_link : '#'; ?>"><?php  echo $category[0]->cat_name ? $category[0]->cat_name : 'Study & Practice';  ?></a></h3>
                                <h2><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                                <p class="excerpt"><?php echo excerpt('12'); ?></p>
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
        <div class="row">
            <div class="col-xs-12">
                <div class="button-wrapper">
                    <a class="primary-btn" href="/study-practice">View All</a>
                </div>
            </div>
        </div>
    </div>

    <div class="program-group-block">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-study-programs">
                    <h2 class="section-header">Looking for at home study programs?</h2>
                    <p>Enroll in one of our short-term or long-term study programs and receive guidance from Tibetan & Western instructors.</p>
                    <div class="row row-programs">
                        <div class="col-xs-12 col-sm-4">
                            <div class="row row-programs-item">
                                <div class="col-xs-4 col-sm-12">
                                    <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>)"><a class="bg-a-link" href="<?php echo get_permalink( 17234 ); ?>"></a></div>
                                </div>
                                <div class="col-xs-8 col-sm-12">
                                    <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="row row-programs-item">
                                <div class="col-xs-4 col-sm-12">
                                    <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 17055, 'full' )?>)"><a class="bg-a-link" href="<?php echo get_permalink( 17055 ); ?>"></a></div>
                                </div>
                                <div class="col-xs-8 col-sm-12">
                                    <h3><a href="<?php echo get_permalink( 17055 ); ?>"><?php $post = get_post( 17055 ); echo $post->post_title;?></a></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="row row-programs-item">
                                <div class="col-xs-4 col-sm-12">
                                    <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 2585, 'full' )?>)"><a class="bg-a-link" href="<?php echo get_permalink( 2585 ); ?>"></a></div>
                                </div>
                                <div class="col-xs-8 col-sm-12">
                                    <h3><a href="<?php echo get_permalink( 2585 ); ?>"><?php $post = get_post( 2585 ); echo $post->post_title;?></a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/programs">Explore Programs</a>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-groups">
                    <h2 class="section-header">Looking for Community & Dharma Groups?</h2>
                    <p>Samye is more than just online Dharma teachings, we also have a global network of local groups & Dharma centers. Find out more about our community activities & groups.</p>
                    <img class="icon-map" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-map.png" alt="dharma groups">
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/community">Explore the Community</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    wp_reset_postdata(); 
    // check if the flexible content field has rows of data
    if( have_rows('flexible_layout') ):

        // loop through the rows of data
        while ( have_rows('flexible_layout') ) : the_row();

            if( get_row_layout() == 'hero_slider' ):
                
                // if( have_rows('hero_slide') ):
                //     echo '    <div class="hero-slider">';
                    
                //     while ( have_rows('hero_slide') ) : the_row();
                //         echo '<div class="item">
                //         <div class="carousel-image" style="background-image: url('. get_sub_field('hero_image') .')">
                //         </div>
                //         <div class="hero-caption">
                //         <h2 class="hero-header">'.get_sub_field('hero_title').'</h2>
                //         <p>'.get_sub_field('hero_text_area').'</p>
                //         <a class="primary-btn" href="'.get_sub_field('hero_button_url').'">'.get_sub_field('hero_button_text').'</a>
                //         </div>
                //         </div>';
                        
                //     endwhile;

                //     echo '</div>';

                // endif;

            elseif( get_row_layout() == '2_column_content_default' ): 
                echo '<div class="container about-section">
                <div class="row default-text-block">
                    <div class="col-xs-12 col-sm-6">
                        <div class="about-section-text">
                            <h2 class="section-header">'.get_sub_field('title').'</h2>
                                '.get_sub_field('text_area').'
                            <a class="primary-btn" href="'.get_sub_field('button_url').'">'.get_sub_field('button_text').'</a>
                        </div>
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

    <div class="home-banner">
        <div class="container">
            <div class="row">
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

    <div class="grd-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="grd-section-intro">
                        <h2 class="section-header">Guru Rinpoche Day Teachings</h2>
                        <p>Phakchok Rinpoche sends out short inspiring teachings to all students via email on every Guru Rinpoche day. Rinpoche often uses these teachings to answer frequently asked questions.  Also, he suggests topics that remind us to bring our minds home in the midst of our busy lives.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="grd-carousel" role="listbox">
                        <?php
                            $args4 = array(
                                'numberposts' => 3,
                                'offset' => 0,
                                'category_name' => 'guru-rinpoche-day',
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
                            $lastposts = get_posts( $args4 );
                            $i = -1;
                            foreach ( $lastposts as $post ) :
                            $i++;
                            setup_postdata( $post );
                        ?>
                        <div class="item <?php echo $i == 0 ? 'active' : '' ?>">
                            <div class="row">
                                <div class="col-xs-4 col-sm-6">
                                    <div class="post-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ) ?>')">
                                        <a href="<?php the_permalink(); ?>" class="bg-a-link"></a>
                                    </div>
                                </div>
                                <div class="col-xs-8 col-sm-6">
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
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/guru-rinpoche-day-teachings">See All Teachings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container weekly-reflection">
        <div class="row">
            <div class="col-xs-12">
                <h3>Weekly Reflections</h2>
                <?php
                    $currentWeekNumber = date('W');
                ?>
                <?php
                    $args5 = array(
                        'numberposts' => 1,
                        'offset' => $currentWeekNumber,
                        'category' => 0,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'include' => '',
                        'exclude' => '',
                        'meta_key' => '',
                        'meta_value' =>'',
                        'post_type' => 'reflection_quotes',
                        'post_status' => 'publish',
                        'suppress_filters' => false,
                    );
                    $lastposts = get_posts( $args5 );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                ?>

                <p id="weekly-reflection"><?php the_field('quote'); ?></p>
                <h4 id="author"><?php the_field('author'); ?></h2>

                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-knot.png" />
            </div>
        </div>
    </div>

</section>

<?php
get_footer( vibe_get_footer() );