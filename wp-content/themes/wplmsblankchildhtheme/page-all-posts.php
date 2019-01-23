<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();

?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php the_title(); ?></h2>
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