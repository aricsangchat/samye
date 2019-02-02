<?php

/**
 * The template for displaying Course font
 *
 * Override this template by copying it to yourtheme/course/single/front.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     2.0
 */

if ( !defined( 'ABSPATH' ) ) exit;
global $post;
$id= get_the_ID();

do_action('wplms_course_before_front_main');
$author = get_the_author();
?>

<div class="single-course-key-learning-panel-wrapper">
	<h3 class="panel-title">Key Learning Points</h3>
	<div class="row">
		<div class="col-xs-6">
			<p class="panel-bullet"><i class="fas fa-check"></i> Bullet Item</p>
		</div>
		<div class="col-xs-6">
			<p class="panel-bullet"><i class="fas fa-check"></i> Bullet Item</p>
		</div>
	</div>
</div>


<div class="single-course-requirements-list-wrapper">
	<h3 class="content-section-header">Requirements</h3>
	<ul>
		<li><i class="fas fa-check"></i> List Item</li>
		<li><i class="fas fa-check"></i> List Item</li>
	</ul>
</div>

<div class="course_title">
	<?php 
	// vibe_breadcrumbs(); 
	?>
	<?php //the_title(); ?>
	<?php if(!empty($post->post_excerpt) && strpos($post->post_content,$post->post_excerpt) === false){ echo '<h6>';the_excerpt(); echo '</h6>';} ?>
</div>

<?php 
do_action('wplms_before_course_description');
?>
<div class="course_description single-course-description-wrapper">
	<h3 class="content-section-header">Description</h3>
	<div class="small_desc">
	<?php 
		$more_flag = 1;
		$content=get_the_content(); 
		$middle=strpos( $post->post_content, '<!--more-->' );
		if($middle){
			echo apply_filters('the_content',substr($content, 0, $middle));
		}else{
			$more_flag=0;
			echo apply_filters('the_content',$content);
		}
	?>
	<?php 
		if($more_flag){
			echo '<a href="#" id="more_desc" class="link" data-middle="'.$middle.'">'.__('READ MORE','vibe').'</a>';
		}
	?>
	</div>
	<?php 
		if($more_flag){ 
	?>
		<div class="full_desc">
		<?php 
			echo apply_filters('the_content',substr($content, $middle,-1));
		?>
		<?php 
			echo '<a href="#" id="less_desc" class="link">'.__('LESS','vibe').'</a>';
		?>
		</div>
	<?php
		}
	?>	

</div>

<div class="single-course-curriculum-wrapper">
	<?php 
		//locate_template( array( 'course/single/curriculum.php'  ), true );
	?>
</div>

<?php
do_action('wplms_after_course_description');
?>
<?php
	$instructorArgs = array(
		'numberposts' => -1,
		'offset' => 0,
		'category' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'include' => '',
		'exclude' => '',
		'meta_key' => '',
		'meta_value' =>'',
		'post_type' => 'instructors',
		'post_status' => 'publish',
		'suppress_filters' => false,
	);
	$lastposts = get_posts( $instructorArgs );
	foreach ( $lastposts as $post ) :
	setup_postdata( $post );
	$instructor = get_the_title();
	if (strpos($author, $instructor) !== false) {

?>
	<div class="single-course-instructor-wrapper">
		<h2 class="content-section-header">About the Instructor</h2>
		<div class="row">
			<div class="col-xs-4">
				<img src="<?php the_post_thumbnail_url( 'full' ) ?>" />
			</div>
			<div class="col-xs-8">
				<h3 class="panel-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h3>
				<p class="bio"><?php echo excerpt('20', $post->ID); ?></p>
                <a class="primary-btn" href="<?php the_permalink() ?>">Read More</a>
			</div>
		</div>	
	</div>
<?php 
	}
	endforeach; 
	wp_reset_postdata(); 
?>


<div class="course_reviews">
<?php
	 //comments_template('/course-review.php',true);
?>
</div>

<div class="social_sharing">
	<h3>Share This Page</h3>
	<?php echo do_shortcode('[Sassy_Social_Share]') ?>  
</div>

<?php
