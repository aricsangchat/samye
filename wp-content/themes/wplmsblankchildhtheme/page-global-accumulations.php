<?php
global $post;
get_header(vibe_get_header());
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

<section id="content">

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="accumulations-wrapper">
                <h2>6 Vajra Lines</h2>
                
                <p class="accumulations-description">Kyabgön Phakchok Rinpoche has asked his sangha as well as all who are inspired to take part to accumulate 10,000,000 (ten million) recitations of the Six Vajra Verse supplication yearly.
                <br /><br />This blessed supplication was revealed as a treasure by the great treasure revealer Chokgyur Dechen Lingpa in the 19th century. The first three lines are supplications to three forms of Guru Rinpoche, the fourth line is requesting Guru Rinpoche to grant blessings, the fifth line is requesting that all levels of obstacles may be removed, and the sixth line is requesting that all wishes may be spontaneously accomplished.<br /><br />
                It is taught that there is no greater refuge in these times than Guru Padmasambhava. Make this supplication with a sense of weariness towards samsara, with love and compassion towards all sentient beings, and a heart full of genuine devotion. There is no doubt that the blessings will manifest in one’s mind stream when you supplicate in this way.<br /><br />You may recite this supplication during your daily practice sessions as well as while going about your daily activities.</p>

                <p class="total-count">Total: <?php echo do_shortcode( '[6VajraCount]' ); ?></p>
                <?php if (is_user_logged_in()) : ?>
                    <p class="user-count">Your Total: <?php echo do_shortcode( '[gravitywp_count formid="42" number_field="1" created_by="current" decimals="0" ]' ); ?></p>
                    <h3>Add Your Accumulations</h3>
                    <?php echo do_shortcode( '[gravityform id="42" title="false" description="false" ajax="false"]' ); ?>
                    <p class="or">OR</p>
                    <div class="button-wrapper">
                        <a class="primary-btn-inverted" href="#">Enter Practice</a>
                    </div>
                <?php else : ?>
                    <div class="button-wrapper">
                        <a class="primary-btn" href="#">Login to Add Accumulations</a>
                    </div>
                <?php endif; ?> 
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="accumulations-wrapper">
                <h2>Giant Cloud Blessings</h2>
                <p class="accumulations-description">"Learning how to work together as a global sangha to magnetize auspicious conditions for the resounding of the Dharma and the vast aspirations of the purest intent to be swiftly fulfilled." ~ Kyabgön Phakchok Rinpoche<br /><br />
                Rinpoche has requested that all his students take up the magnetizing practice entitled "Giant Cloud of Blessings" as part of their daily practice.  He would like us as a global sangha to accumulate 1 million of these prayers annually!</p>
                <p class="total-count">Total: <?php echo do_shortcode( '[CloudBlessingCount]' ); ?></p>
                
                <?php if (is_user_logged_in()) : ?>
                    <p class="user-count">Your Total: <?php echo do_shortcode( ' [gravitywp_count formid="40" number_field="1" created_by="current" decimals="0" ]' ); ?></p>
                    <h3>Add Your Accumulations</h3>
                    <?php echo do_shortcode( '[gravityform id="40" title="false" description="false" ajax="false"]' ); ?>
                    <p class="or">OR</p>
                    <div class="button-wrapper">
                        <a class="primary-btn-inverted" href="#">Enter Practice</a>
                    </div>
                <?php else : ?>
                    <div class="button-wrapper">
                        <a class="primary-btn" href="#">Login to Add Accumulations</a>
                    </div>
                <?php endif; ?>                
            </div>
        </div>
    </div>
</div>

</section>

<?php
get_footer( vibe_get_footer() );