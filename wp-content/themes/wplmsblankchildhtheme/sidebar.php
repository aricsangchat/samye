<div class="sidebar">
    <div class="sidebar-widget-about">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-logo.png);" alt="About Samye">
        <h3 class="sidebar-section-header">About Samye</h3>
        <p>All Rinpoche's online activities are volunteer-based and rely on your kind funding to progress. Please consider making a donation.</p>
        <a href="/about-samye">READ MORE</a>
    </div>

    <div class="sidebar-widget-latest-courses">
        <h3 class="sidebar-section-header">Latest Online Courses</h3>
        <?php 
            $latestCoursesArgs = array(
                'numberposts' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'course',
                'post_status' => 'publish',
            );
            $lastposts = get_posts( $latestCoursesArgs );
            foreach ( $lastposts as $post ) :
            $id= get_the_ID();
            $course_curriculum = bp_course_get_full_course_curriculum($id); 
        ?>
        <div class="sidebar-widget-latest-course-wrapper">
            <div class="sidebar-widget-latest-course-image" style="background-image: url('<?php the_post_thumbnail_url('medium') ?>');"></div>
            <div class="sidebar-widget-latest-course-details">
                <h3 class="sidebar-widget-latest-course-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                <p><?php echo count($course_curriculum); ?> Modules</p>
            </div>
        </div>
        <?php 
            endforeach; 
            wp_reset_postdata(); 
        ?>
    </div>

    <div class="sidebar-ad">
        <a href="https://radicallyhappy.org/">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sidebar-ad-radically-happy.jpg" alt="radically happy">
        </a>
    </div>

    <div class="sidebar-widget-latest-courses">
        <h3 class="sidebar-section-header">Recent Blog Posts</h3>
        <?php 
            $latestPostArgs = array(
                'numberposts' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'post',
                'post_status' => 'publish',
            );
            $lastposts = get_posts( $latestPostArgs );
            foreach ( $lastposts as $post ) :
            $id= get_the_ID();
        ?>
        <div class="sidebar-widget-latest-course-wrapper">
            <div class="sidebar-widget-latest-course-image" style="background-image: url('<?php the_post_thumbnail_url('medium') ?>');"></div>
            <div class="sidebar-widget-latest-course-details">
                <h3 class="sidebar-widget-latest-course-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                <p><?php echo get_the_date(); ?></p>
            </div>
        </div>
        <?php 
            endforeach; 
            wp_reset_postdata(); 
        ?>
    </div>

    <div class="sidebar-widget-resources">
        <h3 class="sidebar-section-header">Quick Links</h3>
        <a href="#">Quick Links</a>
        <a href="#">Category</a>
        <a href="#">Link</a>
    </div>

    <div class="sidebar-widget-facebook">
        <h3 class="sidebar-section-header">See you on facebook</h3>
        <div class="fb-page" data-href="https://www.facebook.com/samyeinstitute/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/samyeinstitute/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/samyeinstitute/">Samye Institute</a></blockquote></div>
    </div>

    <div class="sidebar-widget-support">
        <h3 class="sidebar-section-header">Support Samye</h3>
        <p>All Rinpoche's online activities are volunteer-based and rely on your kind funding to progress. Please consider making a donation to help fund further online courses, live streaming teachings, webinars and more downloadable teachings.</p>
        <div class="button-wrapper">
            <a class="primary-btn" href="/donate">Donate</a>
        </div>
    </div>
</div>

