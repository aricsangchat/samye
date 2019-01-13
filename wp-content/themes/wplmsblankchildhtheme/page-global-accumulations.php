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
            <h2>6 Vajra Lines</h2>
            <p>Kyabgön Phakchok Rinpoche has asked his sangha as well as all who are inspired to take part to accumulate 10,000,000 (ten million) recitations of the Six Vajra Verse supplication yearly.</p>
            <div class="button-wrapper">
                <a class="primary-btn" href="#">Add Your Accumulations</a>
            </div>
            <div class="button-wrapper">
                <a class="primary-btn-inverted" href="#">Enter the Practice</a>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6">
            <h2>Giant Cloud Blessing</h2>
            <p>Kyabgön Phakchok Rinpoche has asked his sangha as well as all who are inspired to take part to accumulate 10,000,000 (ten million) recitations of the Six Vajra Verse supplication yearly.</p>
            <div class="button-wrapper">
                <a class="primary-btn" href="#">Add Your Accumulations</a>
            </div>
            <div class="button-wrapper">
                <a class="primary-btn-inverted" href="#">Enter the Practice</a>
            </div>

        </div>
    </div>
</div>

</section>

<?php
get_footer( vibe_get_footer() );