<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="footer-newsletter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3>Join Our Mailing List</h2>
                <p>To receive the latest teachings and information from Samye Institute, including Phakchok Rinpoche’s monthly Guru Rinpoche Day message.</p>
                <!-- Begin Mailchimp Signup Form -->
                <!-- <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css"> -->
                <div id="mc_embed_signup">
                <form action="https://samyedharma.us12.list-manage.com/subscribe/post?u=e199539057d67dc9afa4d34da&amp;id=4026ab410e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                    
                    <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="your email..." required>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e199539057d67dc9afa4d34da_4026ab410e" tabindex="-1" value=""></div>
                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                </form>
                </div>

                <!--End mc_embed_signup-->
            </div>
        </div>
    </div>
</div>

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
                    <li><a href="/global-accumulations">Global Accumulations</a></li>
                    <li><a href="/forums">Discussion Groups</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/contact-us/">Contact Us</a></li>
                    <li><a href="/privacy-policy/">Privacy Policy</a></li>
                    <li><a href="/donate">Donate</a></li>
                    <li><a href="/volunteer">Volunteer</a></li>
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