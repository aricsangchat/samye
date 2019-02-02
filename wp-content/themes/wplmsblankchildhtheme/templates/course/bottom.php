<?php
if ( ! defined( 'ABSPATH' ) ) exit;

do_action('wplms_single_course_content_end');
global $post;
$course_author = $post->post_author;
$author = get_the_author();
?>
				</div>
				<div class="col-sm-4">

					<div class="program-details">
						<h2 class="section-header">Program Details</h2>
						<p class="student-count">42 Students Enrolled</p>
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
						<div class="instructor-wrapper">
							<h3 class="panel-title">Instructor</h3>
							
							<div class="instructor_course">
								<div class="item-avatar">
									<img src="<?php the_post_thumbnail_url( 'full' ) ?>" />
								</div>
								<h5 class="course_instructor"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a>
								</h5>
							</div>
						</div>
						<?php 
							}
							endforeach; 
							wp_reset_postdata(); 
						?>
						
						<h3 class="panel-title duration">Duration: <span><?php echo get_the_course_time($post->ID); ?></span></h3>
						<h3 class="panel-title cost">Price: <span><?php echo getCourseCost($post->ID); ?></span></h3>
						<div class="course-button-wrapper">
							<?php the_course_button(); ?>
						</div>
					</div>

					<div id="item-nav">
						<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
							<ul>
								<?php 
								bp_get_options_nav(); 
								?>
								<?php

								if(function_exists('bp_course_nav_menu'))
									bp_course_nav_menu();
								
								?>
								<?php 
								do_action( 'bp_course_options_nav' ); 
								?>
							</ul>
						</div>
					</div><!-- #item-nav -->
					<div class="sidebar-course-wrapper">
						<?php get_template_part( 'sidebar', 'course' ); ?>
					</div>
				</div>
			</div><!-- .padder -->
		
		</div><!-- #container -->
	</div>
</section>	