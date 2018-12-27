<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();

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
                echo '<div class="container">
                <div class="row default-text-block">
                    <div class="col-xs-12 col-sm-6">
                        <h2>'.get_sub_field('title').'</h2>
                        '.get_sub_field('text_area').'
                        <a class="primary-btn" href="'.get_sub_field('button_url').'">'.get_sub_field('button_text').'</a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <img src="'.get_sub_field('image')['url'].'" alt="'.get_sub_field('title').'">
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

    <div class="container">
        <div class="row default-text-block">
            <div class="col-xs-12 col-sm-6">
                <h2>Can't make the retreat? Check out our at home study programs</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a class="primary-btn" href="#">Link</a>
            </div>
            <div class="col-xs-12 col-sm-6">
                <img src="http://placehold.jp/1000x700.png" alt="...">
            </div>
        </div>
    </div>

    <div class="program-group-block">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h2>At home study programs</h2>
                    <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <img src="<?php echo get_the_post_thumbnail_url( 17234, 'full' )?>" alt="<?php echo get_post_meta( get_post_thumbnail_id( 17234 ), '_wp_attachment_image_alt', true ); ?>">
                            <h3><a href="<?php echo get_permalink( 17234 ); ?>"><?php $post = get_post( 17234 ); echo $post->post_title;?></a></h3>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="#">Link</a>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6">
                    <h2>At home study programs</h2>
                    <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                        <img src="http://placehold.jp/1000x700.png" alt="...">
                    <div class="button-wrapper">
                        <a class="secondary-btn" href="#">Link</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container featured-post-block">
        <div class="row">
            <div class="col-xs-12">
                <h2>Latest Learning Resources</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-5">
            <?php
                $args = array(
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
                $recent_posts = wp_get_recent_posts($args);
                foreach( $recent_posts as $recent ){
                    echo '<img src="'.get_the_post_thumbnail_url( $recent["ID"], 'full' ).'" alt="">';
                    echo '<h2><a href="' . get_permalink($recent["ID"]) . '">'.(__($recent["post_title"])).'</a></h2> ';

                }
                wp_reset_query();
            ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <?php
                    $args = array(
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
                    // The Query
                    $query1 = new WP_Query( $args );

                    if ( $query1->have_posts() ) {
                        // The Loop
                        while ( $query1->have_posts() ) {
                            $query1->the_post();
                            echo '<h2><a href="'. get_the_permalink() .'">' . get_the_title() . '</a></h2>';
                        }
                        
                        /* Restore original Post Data 
                        * NB: Because we are using new WP_Query we aren't stomping on the 
                        * original $wp_query and it does not need to be reset with 
                        * wp_reset_query(). We just need to set the post data back up with
                        * wp_reset_postdata().
                        */
                        wp_reset_postdata();
                    }
                ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?php
                        $args = array(
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'post_type' => 'post',
                            'posts_per_page' => 4,
                            'post_status' => 'publish',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'post_format',
                                    'field' => 'slug',
                                    'terms' => array('post-format-audio', 'post-format-video'),
                                    'operator' => 'NOT IN'
                                )
                            )
                        );
                        // The Query
                        $query1 = new WP_Query( $args );

                        if ( $query1->have_posts() ) {
                            // The Loop
                            while ( $query1->have_posts() ) {
                                $query1->the_post();
                                echo '<h2>' . get_the_title() . '</h2>';
                            }
                            
                            /* Restore original Post Data 
                            * NB: Because we are using new WP_Query we aren't stomping on the 
                            * original $wp_query and it does not need to be reset with 
                            * wp_reset_query(). We just need to set the post data back up with
                            * wp_reset_postdata().
                            */
                            wp_reset_postdata();
                        }
                    ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="button-wrapper">
                    <a class="primary-btn" href="#">Link</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Explore by Topic</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h2>Topic</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Guru Rinpoche Day Teachings</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a href="#">2008 | 2009 | 2010 | 2011 | 2012 | 2013 | 2014 | 2015 | 2016 | 2017 | 2018</a>
            </div>
        </div>

            <div class="row">
            <div class="col-xs-12 col-sm-6">
                <img src="http://placehold.jp/500x500.png" alt="...">
            </div>
            <div class="col-xs-12 col-sm-6">
                <h2>Guru Rinpoche Day Teachings</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a href="#">Link</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Weekly Reflections</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
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
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );