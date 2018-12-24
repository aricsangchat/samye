<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();

?>

<?php

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="carousel-image" style="background-image: url(http://placehold.jp/1500x480.png)"></div>
                <div class="carousel-caption">
                    <p>Caption</p>
                </div>
            </div>
            <div class="item">
                <div class="carousel-image" style="background-image: url(http://placehold.jp/1500x480.png)"></div>
                <div class="carousel-caption">
                    <p>Caption</p>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

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
                            <img src="<?php echo get_the_post_thumbnail_url( $recent[13862], 'full' )?>" alt="...">
                            <h3><?php $post = get_post( 13862 ); echo $post->post_title;?></h3>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <img src="http://placehold.jp/1000x700.png" alt="...">
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <img src="http://placehold.jp/1000x700.png" alt="...">
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
                <h2>Explore by Topic</h2>
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
                    echo '<h2><a href="' . get_permalink($recent["ID"]) . '">'.$recent["post_title"].'</a></h2> ';

                }
                wp_reset_query();
            ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <img src="http://placehold.jp/500x200.png" alt="...">
                <h2>Recent Videos</h2>
                <img src="http://placehold.jp/500x200.png" alt="...">
                <h2>Recent Videos</h2>
            </div>
            <div class="col-xs-12 col-md-3">
                <img src="http://placehold.jp/500x200.png" alt="...">
                <h2>Recent Post</h2>
                <img src="http://placehold.jp/500x200.png" alt="...">
                <h2>Recent Post</h2>
                <img src="http://placehold.jp/500x200.png" alt="...">
                <h2>Recent Post</h2>
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