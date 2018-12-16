<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



?>
<section id="title">
    <?php do_action('wplms_before_title'); ?>
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
                    <?php
                        $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                        if(vibe_validate($breadcrumbs) || empty($breadcrumbs))
                            vibe_breadcrumbs(); 

                        $title=get_post_meta(get_the_ID(),'vibe_title',true);
                        if(vibe_validate($title) || empty($title)){
                    ?>
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); }?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
            <img src="http://placehold.jp/1500x500.png" alt="...">
            <div class="carousel-caption">
                <p>Caption</p>
            </div>
            </div>
            <div class="item">
            <img src="http://placehold.jp/1500x500.png" alt="...">
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
        <div class="row">
            <div class="col-xs-12">
                <h1>Tag Line Goes Here, Spreading the Vibe</h1>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a href="#">Link</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <h2>Can't make the retreat? Check out our at home study programs</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a href="#">Link</a>
            </div>
            <div class="col-xs-12 col-md-6">
                <h2>Find a Dharma Group in your area for local Study & Practice</h2>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                <a href="#">Link</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Tag Line Goes Here, Spreading the Vibe</h2>
                <img src="http://placehold.jp/1500x300.png" alt="...">
            </div>
        </div>
    </div>

    <!-- <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-12">

                <div class="<?php echo $v_add_content;?> content">
                    <?php
                        the_content();
                        $page_comments = vibe_get_option('page_comments');
                        if(!empty($page_comments))
                            comments_template();
                     ?>
                </div>
            </div>
        </div>
    </div> -->
</section>
<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );