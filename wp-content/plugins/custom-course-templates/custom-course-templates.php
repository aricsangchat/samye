<?php
/**
 * Plugin Name: Custom Course Templates
 * Plugin URI: http://www.samyeinstitute.org
 * Description: When activated this overrides the default WPLMS course templates.
 * Version: 1.0
 * Author: Aric Sangchat
 * Author URI: http://www.samyeinstitute.org
 */

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    function bp_course_item_view(){
        global $post;
        $filter = apply_filters('bp_course_single_item_view',0,$post);
        if($filter){
            return;
        }
        global $post;
        $course_post_id = $post->ID;
        $course_author= $post->post_author;
        $course_classes = apply_filters('bp_course_single_item','course_single_item course_id_'.$post->ID.' course_status_'.$post->post_status.' course_author_'.$post->post_author,get_the_ID());
        $course_curriculum = bp_course_get_full_course_curriculum($course_post_id); 
            ?>	
            <li class="<?php echo $course_classes; ?>">
                <div class="row course-item-row">
                    <div class="col-xs-4">
                        <div class="post-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ) ?>')">
                            <a href="<?php the_permalink(); ?>" class="bg-a-link"></a>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="post-text">
                            <h2 class="post-title"><?php bp_course_title(); ?></h2>

                            <ul class="course-stats">
                                <li><i class="fas fa-file-alt"></i> <?php echo count($course_curriculum); ?> Modules</li>
                                <li><i class="fas fa-user-graduate"></i> <?php echo get_post_meta($course_post_id,'vibe_students',true); ?> Students Enrolled</li>
                            </ul>
                            
                            <p class="excerpt"><?php echo the_excerpt(); ?></p>
                            
                            
                            <ul class="instructor-row">
                                <li class="teacher"><i class="fas fa-dharmachakra"></i> Taught by:</li> 
                                <li><?php bp_course_instructor(array('instructor_id'=> $course_author)); ?></li>
                            </ul>

                            <ul class="cost-row">
                                <li><i class="fas fa-tag"></i> Course cost:</li>
                                <li><?php echo getCourseCost($post->ID); ?></li>
                            </ul>

                            <a class="primary-btn" href="<?php the_permalink() ?>">See More Details</a>

                            <div class="item-action"><?php bp_course_action() ?></div>
                            <?php do_action( 'bp_directory_course_item' ); ?>
                        </div>
                    </div>
                </div>
            </li>	
        <?php
        }