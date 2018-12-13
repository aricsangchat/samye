<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
<div class="updated settings-error" id="setting-error-settings_updated"> 
    <p><strong><?php _e("Settings saved.", "gd-bbpress-tools"); ?></strong></p>
</div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-tools"); ?>
    <div class="d4p-settings">
        <fieldset>
            <h3><?php _e("Disable bbPress breadcrumbs", "gd-bbpress-tools"); ?></h3>
            <p><?php _e("If activated, this option will disable bbPress from displaying own breadcrumbs.", "gd-bbpress-tools"); ?></p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="tweak_disable_breadcrumbs"><?php _e("Active", "gd-bbpress-tools"); ?></label></th>
                        <td>
                            <input type="checkbox" <?php if ($options["tweak_disable_breadcrumbs"] == 1) echo " checked"; ?> name="tweak_disable_breadcrumbs" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3><?php _e("Topic tags field in reply form for author only", "gd-bbpress-tools"); ?></h3>
            <p><?php _e("If activated, only topic author can change topic tags from reply form.", "gd-bbpress-tools"); ?></p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="tweak_tags_in_reply_for_authors_only"><?php _e("Active", "gd-bbpress-tools"); ?></label></th>
                        <td>
                            <input type="checkbox" <?php if ($options["tweak_tags_in_reply_for_authors_only"] == 1) echo " checked"; ?> name="tweak_tags_in_reply_for_authors_only" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3><?php _e("Show lead topic", "gd-bbpress-tools"); ?></h3>
            <p><?php _e("If activated, bbPress will show main thread topic on top separated from replies.", "gd-bbpress-tools"); ?></p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="tweak_show_lead_topic"><?php _e("Active", "gd-bbpress-tools"); ?></label></th>
                        <td>
                            <input type="checkbox" <?php if ($options["tweak_show_lead_topic"] == 1) echo " checked"; ?> name="tweak_show_lead_topic" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <p class="submit">
            <input type="submit" value="<?php _e("Save Changes", "gd-bbpress-tools"); ?>" class="button-primary gdbb-tools-submit" id="gdbb-tweaks-submit" name="gdbb-tweaks-submit" />
        </p>
    </div>
    <div class="d4p-settings-second">
        <?php include(GDBBPRESSTOOLS_PATH.'forms/more/toolbox.php'); ?>
    </div>

    <div class="d4p-clear"></div>
</form>
