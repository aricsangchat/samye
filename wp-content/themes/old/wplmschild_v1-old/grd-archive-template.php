<?php
/*
Template Name: GRD Main Yearly Archives Page
*/


get_header();?>
<style>
.content ul {margin: 0px !important;}
</style>


<section id="content">
    <div class="container">
        <div class="row">
            <!--div class="col-md-12 col-sm-12"-->
            <div class="col-md-9 col-sm-8">
                <div class="content">
        
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

				$title=get_post_meta(get_the_ID(),'vibe_title',true);
				if(vibe_validate($title) || empty($title)){
				?>
                <div class="pagetitle" style="margin-bottom:20px;margin-top:-20px;">
				                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(vibe_validate($breadcrumbs) || empty($breadcrumbs))
                        vibe_breadcrumbs(); 
                ?>
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
<?php
}
?>					
                    <?php
                        the_content();
                     ?>

                </div>
                <?php
                
                endwhile;
                endif;
                ?>

                <div class= "content archives-yearly">
                    <h2>Archives by Year:</h2>
                    <ul>
                        <?php get_cpt_archives('grd-teaching',true); ?>
                    </ul>
                </div>
           
			<!-- add this years posts here -->

			<!-- add sign up form here -->
             

            </div>
            <div class="col-md-3 col-sm-4">
                <div class="sidebar">
                    <?php
                    $sidebar = apply_filters('wplms_sidebar','mainsidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                    <?php endif; ?>
                </div>
            </div>            
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>