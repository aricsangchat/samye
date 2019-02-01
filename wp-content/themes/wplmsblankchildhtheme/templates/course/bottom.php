<?php
if ( ! defined( 'ABSPATH' ) ) exit;

do_action('wplms_single_course_content_end');
?>
				</div>
				<div class="col-sm-4">	
					
					<div class="widget pricing">
						<?php the_course_button(); ?>
						<?php the_course_details(); ?>
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

				 	<?php
				 		$sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
		                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
	               	<?php endif; ?>
				</div>
			</div><!-- .padder -->
		
		</div><!-- #container -->
	</div>
</section>	