<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
    </div>
</section>

<?php

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="about-page-intro">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="video-title">A word from Phakchok Rinpoche</h2>
                <div class="video-outer-wrapper image-dropshadow">
                    <div class="video-wrapper">
                        <iframe class="vimeo-video" src="https://player.vimeo.com/video/138857111" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="about-intro-text">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="header">About the Samye Platform</h2>
                    <p class="description"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-quote.png" />In order to find the motivation to practice authentically, you need to listen to teachings, study them and reflect on their meaning. These days, with our busy lives and students who are spread out all over the world, this can be quite a challenge. So I have worked with some of my students to create a place on-line so that anyone with an internet connection can find guidance and support for their practice. I feel that this sort of endeavor is essential in today’s incredibly fast-paced world, so that we always can find inspiration at the touch of a button.</p>
                    <p class="name">Kyabgön Phakchok Rinpoche</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about-namesake">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/namesake.png" />
            </div>
            <div class="col-xs-12 col-sm-6">
                <h2 class="header">Our Name Sake</h2>
                <p class="text"><strong>SAMYE is the namesake of the Dharma activities of Kyabgön Phakchok Rinpoche.</strong></p>
                <p class="text">In eighth century Tibet, the tantric master Guru Padmasambhava, the Dharma-king Trisong Deutsen and the abbot Śāntarakita established Samye monastery. Guru Padmasambhava’s mastery of Buddhist teachings was complete, he is often referred to as another Buddha. Samye monastery ushered in an era of unparalleled spiritual mastery. The timeless wisdom that was first promulgated by Padmasbhava passed from master to disciple in an unbroken linage and this continues to this very day. Along the way untold numbers of students became realized masters of the tradition in their own right and even more found that this ancient wisdom transformed their lives.</p>
                <p class="text">Samye became a beacon of wisdom, compassion, and dignity in our world. Thus, for this reason, Samye is the auspicious name of our sangha’s Dharma activities. Inspired by the kindness of past masters, we have the idea to share these aspirations in a modern and accessible way via an on-line platform.  Additionally, Samye offers teachings and retreat programs and centers throughout the world.</p>
            </div>
        </div>
    </div>
</section>

<section id="about-vision">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-push-6">
                <div class="vision-image"></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-pull-6">
                <div class="text-wrapper">
                    <h2 class="header">Our Vision</h2>
                    <p class="text"><strong>Samye Institute includes both online and live teachings, instruction, retreats, and events derived from authentic Buddhist wisdom traditions.</strong> On the online platform individuals seeking answers as to how to live a meaningful life can explore how to work with their minds.</p>
                    <p class="text"><strong>Our aspiration is to offer tools and training to enable practitioners of all levels to practice authentically and confidently.</strong> Buddhist practitioners, both newer and long-term students, will find resources here to support their study, reflection, and meditation. Here you may find teachings on working with the emotions and the mind, introduction to meditation, training in compassion, Mahāmudrā meditation, and special practices unique to our tradition.</p>
                    <p class="text"><strong>Samye is a platform for sangha and community building.</strong> Here, students from around the globe can gather in forums to discuss their practices. This builds a strong sense of community and teaches us to care for others. In caring for others, we also care for ourselves. Together we build a community.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about-seal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/about-official-seal.jpg" />
            </div>
            <div class="col-xs-12 col-sm-6">
                <h2 class="header">About The Samye Seal</h2>
                <p class="text">The seal used by Samye was revealed as a treasure by Orgyen Chokgyur Lingpa in Tibet in the 19th century. It is now used as the emblem for the Chokling New Treasures lineage (Chokling Tersar) as well as all of the Dharma activities of Kyabgön Phakchok Rinpoche and his sangha.</p>
                <p class="text"><strong>This seal is not a man-made logo — it is Guru Padmasambhava’s own personal seal that he concealed as a treasure to be revealed in the future for the benefit of fortunate dharma practitioners.</strong></p>
                <p class="text">According to the outer meaning, the vajra that stands in the center is Guru Rinpoche while the two spheres on either side are the two wisdom-disciples, Khandro Yeshe Tsogyal and Lhacham Mandarava.</p>
                <p>According to the inner meaning, the vajra in the center represents the primordial wakefulness that is intrinsic to each and every sentient being, while the spheres on either side of the vajra represent the development and completion stages, which are the secret mantra path of realizing one’s innate nature.</p>
            </div>
        </div>
    </div>
</section>

<section id="more-about">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/about-rinpoche.png" />
                <div class="more-about-rinpoche">
                    <h2><a href="/kyabgon-phakchok-rinpoche/" >More about<br />Phakchok Rinpoche</a></h2>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/about-lineage.png" />
                <div class="more-about-lineage">
                    <h2><a href="/kyabgon-phakchok-rinpoche/lineage-information/" >More about<br />The Lineage</a></h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="teacher-bios">
    <h2 class="header">Teachers</h2>
    <div class="container">
        <div class="row">
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
            ?>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <img src="<?php the_post_thumbnail_url( 'full' ) ?>" />
                <h2 class="author"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                <p class="bio"><?php echo excerpt('20'); ?></p>
                <a href="<?php the_permalink() ?>">Read More</a>
            </div>
            <?php 
                endforeach; 
                wp_reset_postdata(); 
            ?>
        </div>
    </div>
</section>

<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );