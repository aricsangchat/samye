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
                            <div class="hero-caption">
                                <h2>'.get_sub_field('hero_title').'</h2>
                                <p>'.get_sub_field('hero_text_area').'</p>
                                <a class="primary-btn" href="'.get_sub_field('hero_button_url').'">'.get_sub_field('hero_button_text').'</a>
                            </div>
                        </div>
                        </div>';
                        
                    endwhile;

                    echo '</div>';

                endif;

            elseif( get_row_layout() == '2_column_content_default' ): 
                echo '<div class="container about-section">
                <div class="row default-text-block">
                    <div class="col-xs-12 col-sm-6">
                        <div class="about-section-text">
                            <h2>'.get_sub_field('title').'</h2>
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

    <div class="program-group-block">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-study-programs">
                    <h2>At home study programs</h2>
                    <p>Enroll in one of our short-term or long-term study programs and receive guidance from authentic tibetan teachers. Follow your sincere aspiration and blablabla The standard chunk of Lorem Ipsum used since the 1500s.</p>
                    <div class="row row-programs">
                        <div class="col-xs-6 col-sm-4">
                            <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>)"></div>
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 17055, 'full' )?>)"></div>
                            <h3><a href="<?php echo get_permalink( 17055 ); ?>"><?php $post = get_post( 17055 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <div class="program-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( 2585, 'full' )?>)"></div>
                            <h3><a href="<?php echo get_permalink( 2585 ); ?>"><?php $post = get_post( 2585 ); echo $post->post_title;?></a></h3>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/programs">Explore Programs</a>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-groups">
                    <h2>Dharma Groups - Practice Together</h2>
                    <p>Practicing together is a real blessing for all of us. Etc. The standard chunk of Lorem Ipsum used since the 1500s is reproduced.</p>
                    <img class="icon-map" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-map.png" alt="dharma groups">
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="/dharma-groups">Explore Groups</a>
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
                <p><?php echo excerpt('22'); ?></p>
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
                        <p><?php echo excerpt('22'); ?></p>
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
                        <p><?php echo excerpt('12'); ?></p>
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

    <div class="home-banner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <a href="https://radicallyhappy.org/">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-home-radically-happy.jpg" alt="radically happy">
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
                        <h2>Guru Rinpoche Day Teachings</h2>
                        <p>Phakchok Rinpoche sends out short inspiring teachings to all students via email on every Guru Rinpoche day. Rinpoche often uses these teachings to answer frequently asked questions.  Also, he suggests topics that remind us to bring our minds home in the midst of our busy lives.</p>
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
                                    'category_name' => 'guru-rinpoche-day',
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
                        <a class="secondary-btn" href="/guru-rinpoche-day-teachings">View All</a>
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
                        'suppress_filters' => true
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