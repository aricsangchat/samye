<?php
/*
Template Name: Simple Homepage
*/


get_header();?>

<section id="content">
       <div class="container">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

	                the_content();
                     ?>

                <?php
                
                endwhile;
                endif;
                ?>
      </div>

</section>

<?php
get_footer();
?>