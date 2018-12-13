<?php

if (!defined('ABSPATH')) exit;

class gdbbMod_Tweaks {
    public function __construct() {
        if (d4p_bbt_o('tweak_tags_in_reply_for_authors_only') == 1) {
            add_action('bbp_theme_before_reply_form', array($this, 'theme_before_reply_form'));
        }

        if (d4p_bbt_o('tweak_show_lead_topic')) {
            add_filter('bbp_show_lead_topic', '__return_true', 10000);
        }

        if (d4p_bbt_o('tweak_disable_breadcrumbs')) {
            add_filter('bbp_no_breadcrumb', '__return_true');
        }
    }

    public function theme_before_reply_form() {
        $topic_id = bbp_get_topic_id();

        if (get_current_user_id() != bbp_get_topic_author_id($topic_id)) {
            add_filter('bbp_allow_topic_tags', '__return_false', 10000);
        }
    }
}
