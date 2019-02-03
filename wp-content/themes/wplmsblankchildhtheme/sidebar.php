<div class="sidebar">
    <div class="sidebar-widget-about">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-logo.png);" alt="About Samye">
        <h2 class="section-header">About Samye</h2>
        <p>Samye Institute includes both online and live teachings, instruction, retreats, and events derived from authentic Buddhist wisdom traditions. On the online platform individuals seeking answers as to how to live a meaningful life can explore how to work with their minds.</p>
        <!-- <a href="/about-samye">READ MORE</a> -->
        <div class="button-wrapper">
            <a class="primary-btn" href="/about-samye">Learn More</a>
        </div>
    </div>

    <div class="sidebar-widget-latest-courses">
        <h2 class="section-header">Latest Online Courses</h2>
        <?php 
            $latestCoursesArgs = array(
                'numberposts' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'course',
                'post_status' => 'publish',
                'suppress_filters' => false,
            );
            $lastposts = get_posts( $latestCoursesArgs );
            foreach ( $lastposts as $post ) :
            $id= get_the_ID();
            $course_curriculum = bp_course_get_full_course_curriculum($id); 
        ?>
        <div class="row sidebar-widget-latest-course-wrapper">
            <div class="col-xs-4">
                <div class="sidebar-widget-latest-course-image" style="background-image: url('<?php the_post_thumbnail_url('medium') ?>');"></div>
            </div>
            <div class="col-xs-8">
                <div class="sidebar-widget-latest-course-details">
                    <h2 class="post-title sidebar-post-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                    <p><?php echo count($course_curriculum); ?> Modules</p>
                </div>
            </div>
        </div>
        <?php 
            endforeach; 
            wp_reset_postdata(); 
        ?>
    </div>

    <div class="sidebar-ad-widget">
        <a href="https://radicallyhappy.org/">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sidebar-ad-radically-happy.jpg" alt="radically happy">
        </a>
    </div>

    <div class="sidebar-widget-latest-courses">
        <h2 class="section-header">Recent Blog Posts</h2>
        <?php 
            $latestPostArgs = array(
                'numberposts' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'post',
                'post_status' => 'publish',
                'suppress_filters' => false,
            );
            $lastposts = get_posts( $latestPostArgs );
            foreach ( $lastposts as $post ) :
            $id= get_the_ID();
        ?>
        <div class="row sidebar-widget-latest-course-wrapper">
            <div class="col-xs-4">
                <div class="sidebar-widget-latest-course-image" style="background-image: url('<?php the_post_thumbnail_url('medium') ?>');"></div>
            </div>
            <div class="col-xs-8">
                <div class="sidebar-widget-latest-course-details">
                    <h2 class="post-title sidebar-post-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                    <p><?php echo get_the_date(); ?></p>
                </div>
            </div>
        </div>
        <?php 
            endforeach; 
            wp_reset_postdata(); 
        ?>
    </div>

    <div class="sidebar-widget-resources">
        <h2 class="section-header">Quick Links</h2>
        <a href="/dharma-stream">Dharma Stream Groups</a>
        <a href="/events">Upcoming Events</a>
        <a href="/community/global-accumulations">Global Accumulations</a>
        <a href="/category/community">Community Blog</a>
    </div>

    <div class="sidebar-widget-facebook">
        <h2 class="section-header">See you on facebook</h2>
        <div class="fb-page" data-href="https://www.facebook.com/samyeinstitute/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/samyeinstitute/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/samyeinstitute/">Samye Institute</a></blockquote></div>
    </div>

    <div class="sidebar-widget-support">
        <h2 class="section-header">Support Samye</h2>
        <p>All Rinpoche's online activities are volunteer-based and rely on your kind funding to progress. Please consider making a donation to help fund further online courses, live streaming teachings, webinars and more downloadable teachings.</p>
        <div class="button-wrapper">
            <a class="primary-btn" href="/donate">Donate</a>
        </div>
    </div>
</div>

