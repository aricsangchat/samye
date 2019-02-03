<?php

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();



?>
<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-community.png);">
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

<div class="text-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="section-header">Connect with our Global Network</h2>
                <!-- <p class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> -->
            </div>
        </div>
    </div>
</div>

<div class="container community-dharma-groups">
    <div class="row">
        <div class="col-xs-12">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/community-dharma-map.png" alt="">
        </div>
    </div>
    <div class="row community-dharma-groups-facts">
        <div class="col-xs-6 col-sm-4 col-md-4">
            <h3 class="number">100</h3>
            <p class="description">Monestaries & Nunaries</p>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-4">
            <h3 class="number">50</h3>
            <p class="description">Centers & Groups</p>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-4">
            <h3 class="number">397</h3>
            <p class="description">Students On Samye</p>
        </div>
    </div>
</div>

<section id="community-dharma-facts">
    <div class="dharma-facts-col-1">
        <div class="dharma-facts-col-1-title-wrapper">
            <h2 class="section-header">Dharma Stream Groups</h2>
        </div>
        <div class="dharma-facts-col-1-description">
            <p>Access precious Dharma wherever you live</p>
        </div>
        <div class="dharma-facts-col-1-bullets">
            <ul>
                <li>Start a group of minimim 3 people in your city and start practicing together</li>
                <li>Receive personalized online teachings directly from Phakchok Rinpoche in a forthnighly broadcast</li>
                <li>Meditate together, support each other and discuss topics with people in your group</li>
                <li>Access precious Dharma wherever you live</li>
            </ul>
        </div>
        <div class="button-wrapper">
            <a class="secondary-btn" href="/dharma-stream-groups">Learn More</a>
        </div>
    </div>
    <div class="dharma-facts-col-2"></div>
</section>

<!-- <section id="community-dharma-facts">
    <div class="dharma-facts-col-1">
        <div class="dharma-facts-col-1-title-wrapper">
            <h2>Dharma Stream Groups</h2>
        </div>
        <div class="dharma-facts-col-1-description">
            <p>Practice in a local group with regular video teachings from Phachok Rinpoche and senior instructors</p>
        </div>
        <div class="dharma-facts-col-1-bullets">
            <ul>
                <li>Programs include Radically Happy, Dawn of Dharma, Eightfold Path Mind-training & The Jewel Ornament of Liberation</li>
                <li>Over 30 groups in 19 countries</li>
                <li>Study, reflect & meditate in a supportive group setting</li>
            </ul>
        </div>
        <div class="button-wrapper">
            <a class="secondary-btn" href="#">Find a Group Near You</a>
        </div>
    </div>
    <div class="dharma-facts-col-2"></div>
</section> -->

<!-- <section id="community-dharma-facts">
    <div class="dharma-facts-col-2"></div>
    <div class="dharma-facts-col-1">
        <div class="dharma-facts-col-1-title-wrapper">
            <h2>Interested in Forming a Group?</h2>
        </div>
        <div class="dharma-facts-col-1-description">
            <p>Form a new group in your area with a minimum of 3 people meeting twice a month</p>
        </div>
        <div class="dharma-facts-col-1-bullets">
            <ul>
                <li>Facilitator training & guidance</li>
                <li>Practice texts & study notes for each session</li>
                <li>Ongoing support & access to future udpates</li>
            </ul>
        </div>
        <div class="button-wrapper">
            <a class="secondary-btn" href="#">Apply Now</a>
        </div>
    </div>
</section> -->

<section id="community-news">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <h2 class="section-header">Community Conversations</h2>
                <!-- <h3 class="post-list-subtitle">Our Community</h3> -->
                <?php
                    $communityArgs = array(
                        'numberposts' => 3,
                        'offset' => 0,
                        'category' => 0,
                        'category_name' => 'community',
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'include' => '',
                        'exclude' => '',
                        'meta_key' => '',
                        'meta_value' =>'',
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'suppress_filters' => false,
                    );
                    $lastposts = get_posts( $communityArgs );
                    foreach ( $lastposts as $post ) :
                    setup_postdata( $post );
                ?>

                <div class="row post-wrapper">
                    <div class="col-xs-4">
                        <div class="post-image" style="background-image: url(<?php the_post_thumbnail_url( 'full' ) ?>)">
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="post-text">
                            <h3 class="post-tags"><?php echo get_the_tag_list('',' | '); ?></h3>
                            <h2 class="post-title"><a href="<?php the_permalink() ?>"><?php  the_title() ?></a></h2>
                            <h4 class="post-date"><?php the_date(); ?></h4>
                            <p class="post-excerpt"><?php echo excerpt(30, $post->ID); ?></p>
                        </div>
                    </div>
                </div>

                <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                ?>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="dakini-day-sidebar-widget">
                    <h2 class="section-header">Dakini Day Digest</h2>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dakini-day.png" alt="">
                    <h2 class="post-title">The activities of Kyabgön Phakchok Rinpoche</h2>
                    <p>The four mind changings form the foundation of the Vajrayāna path. When we commit to this path, we start by practicing the preliminary practices: the ngöndro. The four mind changings form the foundation of the Vajrayāna path. When we commit to this path, we start by practicing.</p>
                    <div class="button-wrapper">
                        <a class="primary-btn" href="http://dakiniday.samyeinstitute.org/">View Archive</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="community-banner">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="title">Community Practice Programs</h2>
                <h3 class="sub-title">6 Vajra Lines Supplication - Current Total: <?php echo do_shortcode( '[6VajraCount]' ); ?></h3>
                <ul class="facts">
                    <li>Kyabgön Phakchok Rinpoche has asked his sangha as well as all who are inspired to take part to accumulate 10,000,000 (ten million) recitations of the Six Vajra Verse supplication yearly. </li>
                </ul>
                <h3 class="sub-title">Giant Cloud of Blessings Magnetizing Prayer - Current Total: <?php echo do_shortcode( '[CloudBlessingCount]' ); ?></h3>
                <ul class="facts">
                    <li>Rinpoche has requested that all his students take up the magnetizing practice entitled "Giant Cloud of Blessings" as part of their daily practice.  He would like us as a global sangha to accumulate 1 million of these prayers annually!</li>
                </ul>
                <a class="primary-btn" href="/global-accumulations/">Enter Your Accumulations</a>
            </div>
        </div>
    </div>
</div>

<div class="talk-with-teacher">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-buddha.png" alt="">
            </div>
            <div class="col-xs-12 col-sm-8">
                <h2 class="title">Need Help With Your Practice?</h2>
                <h3 class="sub-title">Talk with a teacher</h3>
                <?php echo do_shortcode('[gravityform id="47" title="false" description="true" ajax="true"]') ?>
            </div>
        </div>
    </div>
</div>

<div class="volunteer-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                    <h2 class="title">Be a Volunteer!</h2>
                    <h3 class="sub-title">And help spreading the Dharma...</h3>
                    <p class="text">We’re looking for talented people to help us with our activities. Do you know Web Design? Or you’re good at writing? You know how to organize events, or you just want to help?<br /><br />We’d be glad to get in touch!</p>
                    <a class="secondary-btn" href="/volunteer">Find Out More</a>
            </div>
            <div class="col-xs-12 col-sm-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-volunteer.png" alt="">
            </div>
        </div>
    </div>
</div>

<div class="support-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="title">Or Support the Activities</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-puja.png" alt="">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <h3 class="sub-title">Sponsor a Puja</h3>
                        <p class="text">We’re looking for talented people to help us with our activities. Do you know Web Design? Or you’re good at writing? You know how to.</p>
                        <a href="/sponsor-puja">FIND OUT MORE</a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-donation.png" alt="">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <h3 class="sub-title">Make a Donation</h3>
                        <p class="text">We’re looking for talented people to help us with our activities. Do you know Web Design? Or you’re good at writing? You know how to.</p>
                        <a href="/donate">FIND OUT MORE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
endwhile;
endif; 
?>
<?php
get_footer( vibe_get_footer() );