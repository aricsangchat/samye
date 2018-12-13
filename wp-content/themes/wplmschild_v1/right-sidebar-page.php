<?php
/**
 * Template Name: Right Sidebar Page
 */

get_header(); ?>
<style>
.content ul {margin: 0px !important;}
</style>


<section id="content">
    <div class="container">
        <div class="row">
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