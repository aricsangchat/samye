<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <h4>Our Mandala</h4>
                <ul>
                    <li><a target="_blank" href="http://www.phakchokrinpoche.org/">Phakchok Rinpoche’s Site</a></li>
                    <li><a target="_blank" href="https://www.nekhor.org/">Nekhor</a></li>
                    <li><a target="_blank" href="https://www.cglf.org/">Chokgyur Lingpa Foundation</a></li>
                    <li><a target="_blank" href="https://www.lotuslightdharmainstitute.org/">Lotus Light Dharma Institute</a></li>
                    <li><a target="_blank" href="https://lhaseylotsawa.org/">Lhasey Lotsawa Translations and Publications</a></li>
                    <li><a target="_blank" href="https://radicallyhappy.org/">Radically Happy</a></li>
                    <li><a target="_blank" href="https://akaracollection.com/">Akara Collection – Dharma Shop</a></li>
                    <li><a target="_blank" href="https://basic-goodness.org/">Basic Goodness Foundation</a></li>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-4">
                <h4>Quick Links</h4>
                <ul>
                    <li><a target="_blank" href="#">Dakini Day Digest</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="/contact-us/">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Sitemap</a></li>
                    <li><a href="#">Donate</a></li>
                    <li><a href="#">Volunteer</a></li>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-4">
                <h4>Follow Us on Social Media</h4>
                <ul class="social-icon-menu">
                    <li><a target="_blank" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-fb.png" /></a></li>
                    <li><a target="_blank" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-vm.png" /></a></li>
                    <li><a target="_blank" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-yt.png" /></a></li>
                    <li><a target="_blank" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-ig.png" /></a></li>
                </ul>
                <h4>Select Language</h4>
                <?php do_action( 'wpml_add_language_selector' ); ?>
            </div>
        </div>
    </div>
    <div id="scrolltop">
        <a><i class="icon-arrow-1-up"></i><span><?php _e('top','vibe'); ?></span></a>
    </div>
</footer>
<div class="copyright">
    <p>© 2018 Samye Institute · All Rights Reserved</p>
</div>
</div><!-- END PUSHER -->
</div><!-- END MAIN -->
	<!-- SCRIPTS -->
<?php
wp_footer(); 
?>
</body>
</html>