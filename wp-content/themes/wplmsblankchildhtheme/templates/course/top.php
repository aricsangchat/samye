<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$header = vibe_get_customizer('header_style');
if($header == 'transparent' || $header == 'generic'){
    echo '<section id="title">';
    do_action('wplms_before_title');
    echo '<div class="container"><div class="pagetitle"><h1>'.get_the_title().'</h1></div></div></section>';
}
?>

<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Learning Programs</h2>
            </div>
        </div>
    </div>
</section>

<section id="content">
	<div id="buddypress">
	    <div class="<?php echo vibe_get_container(); ?>">
			<?php do_action( 'bp_before_course_home_content' ); ?>
	        <div class="row">
	            <div class="col-sm-8">
					<div id="item-header" role="complementary">
						<?php 
						//locate_template( array( 'course/single/course-header.php' ), true ); 
						?>
					</div><!-- #item-header -->

					<div class="single-course-title-wrapper">
						<h2 class="single-post-title"><?php the_title() ?></h2>
					</div>
					<div class="single-course-image" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'full' )?>)">
						
					</div>

			
				
			