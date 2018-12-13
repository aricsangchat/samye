<?php
/*
Plugin Name: 2Checkout Gateway for Gravity Forms
Plugin URI: http://www.patsatech.com/shop/twocheckout-gravity-forms
Description: Integrates Gravity Forms with 2Checkout, enabling end users to purchase goods and services through Gravity Forms.
Version: 1.0.0
Author: PatSaTECH
Author URI: http://www.patsatech.com/
*/

add_action('parse_request', array("GFTWOCHECKOUT", "process_ipn"));
add_action('wp',  array('GFTWOCHECKOUT', 'maybe_thankyou_page'), 5);

add_action('init',  array('GFTWOCHECKOUT', 'init'));
register_activation_hook( __FILE__, array("GFTWOCHECKOUT", "add_permissions"));

if(!defined("GF_TWOCHECKOUT_PLUGIN_PATH"))
    define("GF_TWOCHECKOUT_PLUGIN_PATH", dirname( plugin_basename( __FILE__ ) ) );
	
if(!defined("GF_TWOCHECKOUT_PLUGIN"))
    define("GF_TWOCHECKOUT_PLUGIN", dirname( plugin_basename( __FILE__ ) ) . "/twocheckout.php" );

if(!defined("GF_TWOCHECKOUT_BASE_URL"))
    define("GF_TWOCHECKOUT_BASE_URL", plugins_url(null, __FILE__) );
    
if(!defined("GF_TWOCHECKOUT_BASE_PATH"))
    define("GF_TWOCHECKOUT_BASE_PATH", WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) );

class GFTWOCHECKOUT {

    private static $path = GF_TWOCHECKOUT_PLUGIN;
    private static $url = "https://www.patsatech.com";
    private static $slug = "gravityformstwocheckout";
    private static $version = "1.0.0";
    private static $min_gravityforms_version = "1.6.4";
    private static $supported_fields = array("checkbox", "radio", "select", "text", "website", "textarea", "email", "hidden", "number", "phone", "multiselect", "post_title", "post_tags", "post_custom_field", "post_content", "post_excerpt");

    //Plugin starting point. Will load appropriate files
    public static function init(){
		//supports logging
		add_filter("gform_logging_supported", array("GFTWOCHECKOUT", "set_logging_supported"));

        if(basename($_SERVER['PHP_SELF']) == "plugins.php") {

            //loading translations
            load_plugin_textdomain('gravityformstwocheckout', FALSE, GF_TWOCHECKOUT_PLUGIN_PATH . '/languages' );

        }

        if(!self::is_gravityforms_supported())
           return;

        if(is_admin()){
            //loading translations
            load_plugin_textdomain('gravityformstwocheckout', FALSE, GF_TWOCHECKOUT_PLUGIN_PATH . '/languages' );

            //integrating with Members plugin
            if(function_exists('members_get_capabilities'))
                add_filter('members_get_capabilities', array("GFTWOCHECKOUT", "members_get_capabilities"));

            //creates the subnav left menu
            add_filter("gform_addon_navigation", array('GFTWOCHECKOUT', 'create_menu'));

            //add actions to allow the payment status to be modified
            add_action('gform_payment_status', array('GFTWOCHECKOUT','admin_edit_payment_status'), 3, 3);
            add_action('gform_entry_info', array('GFTWOCHECKOUT','admin_edit_payment_status_details'), 4, 2);
            add_action('gform_after_update_entry', array('GFTWOCHECKOUT','admin_update_payment'), 4, 2);

            if(self::is_twocheckout_page()){

                //loading Gravity Forms tooltips
                require_once(GFCommon::get_base_path() . "/tooltips.php");
                add_filter('gform_tooltips', array('GFTWOCHECKOUT', 'tooltips'));

                //enqueueing sack for AJAX requests
                wp_enqueue_script(array("sack"));

                //loading data lib
                require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

                //runs the setup when version changes
                self::setup();

            }
            else if(in_array(RG_CURRENT_PAGE, array("admin-ajax.php"))){

                //loading data class
                require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

                add_action('wp_ajax_gf_twocheckout_update_feed_active', array('GFTWOCHECKOUT', 'update_feed_active'));
                add_action('wp_ajax_gf_select_twocheckout_form', array('GFTWOCHECKOUT', 'select_twocheckout_form'));
                add_action('wp_ajax_gf_twocheckout_load_notifications', array('GFTWOCHECKOUT', 'load_notifications'));

            }
            else if(RGForms::get("page") == "gf_settings"){
                RGForms::add_settings_page("2Checkout", array("GFTWOCHECKOUT", "settings_page"), GF_TWOCHECKOUT_BASE_URL . "/assets/images/twocheckout_wordpress_icon_32.png");
            }
        }
        else{
            //loading data class
            require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

            //handling post submission.
            add_filter("gform_confirmation", array("GFTWOCHECKOUT", "send_to_twocheckout"), 1000, 4);

            //setting some entry metas
            //add_action("gform_after_submission", array("GFTWOCHECKOUT", "set_entry_meta"), 5, 2);

            add_filter("gform_disable_post_creation", array("GFTWOCHECKOUT", "delay_post"), 10, 3);
            add_filter("gform_disable_user_notification", array("GFTWOCHECKOUT", "delay_autoresponder"), 10, 3);
            add_filter("gform_disable_admin_notification", array("GFTWOCHECKOUT", "delay_admin_notification"), 10, 3);
            add_filter("gform_disable_notification", array("GFTWOCHECKOUT", "delay_notification"), 10, 4);

            // ManageWP premium update filters
            add_filter( 'mwp_premium_update_notification', array('GFTWOCHECKOUT', 'premium_update_push') );
            add_filter( 'mwp_premium_perform_update', array('GFTWOCHECKOUT', 'premium_update') );
        }
    }	
	
    public static function update_feed_active(){
        check_ajax_referer('gf_twocheckout_update_feed_active','gf_twocheckout_update_feed_active');
        $id = $_POST["feed_id"];
        $feed = GFTWOCHECKOUTData::get_feed($id);
        GFTWOCHECKOUTData::update_feed($id, $feed["form_id"], $_POST["is_active"], $feed["meta"]);
    }

    //-------------- Automatic upgrade ---------------------------------------


    //Integration with ManageWP
    public static function premium_update_push( $premium_update ){

        if( !function_exists( 'get_plugin_data' ) )
            include_once( ABSPATH.'wp-admin/includes/plugin.php');

        $update = GFCommon::get_version_info();
        if( $update["is_valid_key"] == true && version_compare(self::$version, $update["version"], '<') ){
            $plugin_data = get_plugin_data( __FILE__ );
            $plugin_data['type'] = 'plugin';
            $plugin_data['slug'] = self::$path;
            $plugin_data['new_version'] = isset($update['version']) ? $update['version'] : false ;
            $premium_update[] = $plugin_data;
        }

        return $premium_update;
    }

    //Integration with ManageWP
    public static function premium_update( $premium_update ){

        if( !function_exists( 'get_plugin_data' ) )
            include_once( ABSPATH.'wp-admin/includes/plugin.php');

        $update = GFCommon::get_version_info();
        if( $update["is_valid_key"] == true && version_compare(self::$version, $update["version"], '<') ){
            $plugin_data = get_plugin_data( __FILE__ );
            $plugin_data['slug'] = self::$path;
            $plugin_data['type'] = 'plugin';
            $plugin_data['url'] = isset($update["url"]) ? $update["url"] : false; // OR provide your own callback function for managing the update

            array_push($premium_update, $plugin_data);
        }
        return $premium_update;
    }
	
    private static function get_key(){
        if(self::is_gravityforms_supported())
            return GFCommon::get_key();
        else
            return "";
    }
    //------------------------------------------------------------------------

    //Creates 2Checkout left nav menu under Forms
    public static function create_menu($menus){

        // Adding submenu if user has access
        $permission = self::has_access("gravityforms_twocheckout");
        if(!empty($permission))
            $menus[] = array("name" => "gf_twocheckout", "label" => __("2Checkout", "gravityformstwocheckout"), "callback" =>  array("GFTWOCHECKOUT", "twocheckout_page"), "permission" => $permission);

        return $menus;
    }

    //Creates or updates database tables. Will only run when version changes
    private static function setup(){
        if(get_option("gf_twocheckout_version") != self::$version)
            GFTWOCHECKOUTData::update_table();

        update_option("gf_twocheckout_version", self::$version);
    }

    //Adds feed tooltips to the list of tooltips
    public static function tooltips($tooltips){
        $twocheckout_tooltips = array(
            "twocheckout_transaction_type" => "<h6>" . __("Transaction Type", "gravityformstwocheckout") . "</h6>" . __("Select which 2Checkout transaction type should be used. Products and Services, Donations or Subscription.", "gravityformstwocheckout"),
            "twocheckout_gravity_form" => "<h6>" . __("Gravity Form", "gravityformstwocheckout") . "</h6>" . __("Select which Gravity Forms you would like to integrate with 2Checkout.", "gravityformstwocheckout"),
            "twocheckout_customer" => "<h6>" . __("Customer", "gravityformstwocheckout") . "</h6>" . __("Map your Form Fields to the available 2Checkout customer information fields.", "gravityformstwocheckout"),
            "twocheckout_cancel_url" => "<h6>" . __("Cancel URL", "gravityformstwocheckout") . "</h6>" . __("Enter the URL the user should be sent to should they cancel before completing their 2Checkout payment.", "gravityformstwocheckout"),
            "twocheckout_options" => "<h6>" . __("Options", "gravityformstwocheckout") . "</h6>" . __("Turn on or off the available 2Checkout checkout options.", "gravityformstwocheckout"),
            "twocheckout_conditional" => "<h6>" . __("2Checkout Condition", "gravityformstwocheckout") . "</h6>" . __("When the 2Checkout condition is enabled, form submissions will only be sent to 2Checkout when the condition is met. When disabled all form submissions will be sent to 2Checkout.", "gravityformstwocheckout"),
            "twocheckout_edit_payment_amount" => "<h6>" . __("Amount", "gravityformstwocheckout") . "</h6>" . __("Enter the amount the user paid for this transaction.", "gravityformstwocheckout"),
            "twocheckout_edit_payment_date" => "<h6>" . __("Date", "gravityformstwocheckout") . "</h6>" . __("Enter the date of this transaction.", "gravityformstwocheckout"),
            "twocheckout_edit_payment_transaction_id" => "<h6>" . __("Transaction ID", "gravityformstwocheckout") . "</h6>" . __("The transacation id is returned from 2Checkout and uniquely identifies this payment.", "gravityformstwocheckout"),
            "twocheckout_edit_payment_status" => "<h6>" . __("Status", "gravityformstwocheckout") . "</h6>" . __("Set the payment status. This status can only be altered if not currently set to Approved.", "gravityformstwocheckout")
        );
        return array_merge($tooltips, $twocheckout_tooltips);
    }

    public static function delay_post($is_disabled, $form, $lead){
        //loading data class
        require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

        $config = GFTWOCHECKOUTData::get_feed_by_form($form["id"]);
        if(!$config)
            return $is_disabled;

        $config = $config[0];
        if(!self::has_twocheckout_condition($form, $config))
            return $is_disabled;

        return $config["meta"]["delay_post"] == true;
    }

    //Kept for backwards compatibility
    public static function delay_admin_notification($is_disabled, $form, $lead){
        $config = self::get_active_config($form);

        if(!$config)
            return $is_disabled;

        return isset($config["meta"]["delay_notification"]) ? $config["meta"]["delay_notification"] == true : $is_disabled;
    }

    //Kept for backwards compatibility
    public static function delay_autoresponder($is_disabled, $form, $lead){
        $config = self::get_active_config($form);

        if(!$config)
            return $is_disabled;

        return isset($config["meta"]["delay_autoresponder"]) ? $config["meta"]["delay_autoresponder"] == true : $is_disabled;
    }

    public static function delay_notification($is_disabled, $notification, $form, $lead){
        $config = self::get_active_config($form);

        if(!$config)
            return $is_disabled;

        $selected_notifications = is_array(rgar($config["meta"], "selected_notifications")) ? rgar($config["meta"], "selected_notifications") : array();

        return isset($config["meta"]["delay_notifications"]) && in_array($notification["id"], $selected_notifications) ? true : $is_disabled;
    }

    private static function get_selected_notifications($config, $form){
        $selected_notifications = is_array(rgar($config['meta'], 'selected_notifications')) ? rgar($config['meta'], 'selected_notifications') : array();

        if(empty($selected_notifications)){
            //populating selected notifications so that their delayed notification settings get carried over
            //to the new structure when upgrading to the new 2Checkout Add-On
            if(!rgempty("delay_autoresponder", $config['meta'])){
                $user_notification = self::get_notification_by_type($form, "user");
                if($user_notification)
                    $selected_notifications[] = $user_notification["id"];
            }

            if(!rgempty("delay_notification", $config['meta'])){
                $admin_notification = self::get_notification_by_type($form, "admin");
                if($admin_notification)
                    $selected_notifications[] = $admin_notification["id"];
            }
        }

        return $selected_notifications;
    }

    private static function get_notification_by_type($form, $notification_type){
        if(!is_array($form["notifications"]))
            return false;

        foreach($form["notifications"] as $notification){
            if($notification["type"] == $notification_type)
                return $notification;
        }

        return false;

    }

    public static function twocheckout_page(){
        $view = rgget("view");
        if($view == "edit")
            self::edit_page(rgget("id"));
        else if($view == "stats")
            self::stats_page(rgget("id"));
        else
            self::list_page();
    }

    //Displays the twocheckout feeds list page
    private static function list_page(){
        if(!self::is_gravityforms_supported()){
            die(__(sprintf("2Checkout Add-On requires Gravity Forms %s. Upgrade automatically on the %sPlugin page%s.", self::$min_gravityforms_version, "<a href='plugins.php'>", "</a>"), "gravityformstwocheckout"));
        }

        if(rgpost('action') == "delete"){
            check_admin_referer("list_action", "gf_twocheckout_list");

            $id = absint($_POST["action_argument"]);
            GFTWOCHECKOUTData::delete_feed($id);
            ?>
            <div class="updated fade" style="padding:6px"><?php _e("Feed deleted.", "gravityformstwocheckout") ?></div>
            <?php
        }
        else if (!empty($_POST["bulk_action"])){
            check_admin_referer("list_action", "gf_twocheckout_list");
            $selected_feeds = $_POST["feed"];
            if(is_array($selected_feeds)){
                foreach($selected_feeds as $feed_id)
                    GFTWOCHECKOUTData::delete_feed($feed_id);
            }
            ?>
            <div class="updated fade" style="padding:6px"><?php _e("Feeds deleted.", "gravityformstwocheckout") ?></div>
            <?php
        }

        ?>
        <div class="wrap">
            <img alt="<?php _e("2Checkout Transactions", "gravityformstwocheckout") ?>" src="<?php echo GF_TWOCHECKOUT_BASE_URL?>/assets/images/twocheckout_wordpress_icon_32.png" style="float:left; margin:15px 7px 0 0;"/>
            <h2><?php
            _e("2Checkout Forms", "gravityformstwocheckout");

            if(get_option("gf_twocheckout_configured")){
                ?>
                <a class="button add-new-h2" href="admin.php?page=gf_twocheckout&view=edit&id=0"><?php _e("Add New", "gravityformstwocheckout") ?></a>
                <?php
            }
            ?>
            </h2>

            <form id="feed_form" method="post">
                <?php wp_nonce_field('list_action', 'gf_twocheckout_list') ?>
                <input type="hidden" id="action" name="action"/>
                <input type="hidden" id="action_argument" name="action_argument"/>

                <div class="tablenav">
                    <div class="alignleft actions" style="padding:8px 0 7px 0;">
                        <label class="hidden" for="bulk_action"><?php _e("Bulk action", "gravityformstwocheckout") ?></label>
                        <select name="bulk_action" id="bulk_action">
                            <option value=''> <?php _e("Bulk action", "gravityformstwocheckout") ?> </option>
                            <option value='delete'><?php _e("Delete", "gravityformstwocheckout") ?></option>
                        </select>
                        <?php
                        echo '<input type="submit" class="button" value="' . __("Apply", "gravityformstwocheckout") . '" onclick="if( jQuery(\'#bulk_action\').val() == \'delete\' && !confirm(\'' . __("Delete selected feeds? ", "gravityformstwocheckout") . __("\'Cancel\' to stop, \'OK\' to delete.", "gravityformstwocheckout") .'\')) { return false; } return true;"/>';
                        ?>
                    </div>
                </div>
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
                            <th scope="col" id="active" class="manage-column check-column"></th>
                            <th scope="col" class="manage-column"><?php _e("Form", "gravityformstwocheckout") ?></th>
                            <th scope="col" class="manage-column"><?php _e("Transaction Type", "gravityformstwocheckout") ?></th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
                            <th scope="col" id="active" class="manage-column check-column"></th>
                            <th scope="col" class="manage-column"><?php _e("Form", "gravityformstwocheckout") ?></th>
                            <th scope="col" class="manage-column"><?php _e("Transaction Type", "gravityformstwocheckout") ?></th>
                        </tr>
                    </tfoot>

                    <tbody class="list:user user-list">
                        <?php


                        $settings = GFTWOCHECKOUTData::get_feeds();
			            $seller_id = get_option("gf_twocheckout_settings");
						$key = rgar($seller_id,"seller_id");
                        if(empty($key)){
                            ?>
                            <tr>
                                <td colspan="3" style="padding:20px;">
                                    <?php echo sprintf(__("To get started, please configure your %s2Checkout Settings%s.", "gravityformstwocheckout"), '<a href="admin.php?page=gf_settings&addon=2Checkout">', "</a>"); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        else if(is_array($settings) && sizeof($settings) > 0){
                            foreach($settings as $setting){
                                ?>
                                <tr class='author-self status-inherit' valign="top">
                                    <th scope="row" class="check-column"><input type="checkbox" name="feed[]" value="<?php echo $setting["id"] ?>"/></th>
                                    <td><img src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/active<?php echo intval($setting["is_active"]) ?>.png" alt="<?php echo $setting["is_active"] ? __("Active", "gravityformstwocheckout") : __("Inactive", "gravityformstwocheckout");?>" title="<?php echo $setting["is_active"] ? __("Active", "gravityformstwocheckout") : __("Inactive", "gravityformstwocheckout");?>" onclick="ToggleActive(this, <?php echo $setting['id'] ?>); " /></td>
                                    <td class="column-title">
                                        <a href="admin.php?page=gf_twocheckout&view=edit&id=<?php echo $setting["id"] ?>" title="<?php _e("Edit", "gravityformstwocheckout") ?>"><?php echo $setting["form_title"] ?></a>
                                        <div class="row-actions">
                                            <span class="edit">
                                            <a title="<?php _e("Edit", "gravityformstwocheckout")?>" href="admin.php?page=gf_twocheckout&view=edit&id=<?php echo $setting["id"] ?>" ><?php _e("Edit", "gravityformstwocheckout") ?></a>
                                            |
                                            </span>
                                            <span class="view">
                                            <a title="<?php _e("View Stats", "gravityformstwocheckout")?>" href="admin.php?page=gf_twocheckout&view=stats&id=<?php echo $setting["id"] ?>"><?php _e("Stats", "gravityformstwocheckout") ?></a>
                                            |
                                            </span>
                                            <span class="view">
                                            <a title="<?php _e("View Entries", "gravityformstwocheckout")?>" href="admin.php?page=gf_entries&view=entries&id=<?php echo $setting["form_id"] ?>"><?php _e("Entries", "gravityformstwocheckout") ?></a>
                                            |
                                            </span>
                                            <span class="trash">
                                            <a title="<?php _e("Delete", "gravityformstwocheckout") ?>" href="javascript: if(confirm('<?php _e("Delete this feed? ", "gravityformstwocheckout") ?> <?php _e("\'Cancel\' to stop, \'OK\' to delete.", "gravityformstwocheckout") ?>')){ DeleteSetting(<?php echo $setting["id"] ?>);}"><?php _e("Delete", "gravityformstwocheckout")?></a>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="column-date">
                                        <?php
                                            switch($setting["meta"]["type"]){
                                                case "product" :
                                                    _e("Product and Services", "gravityformstwocheckout");
                                                break;

                                                case "donation" :
                                                    _e("Donation", "gravityformstwocheckout");
                                                break;

                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        else{
                            ?>
                            <tr>
                                <td colspan="4" style="padding:20px;">
                                    <?php echo sprintf(__("You don't have any 2Checkout feeds configured. Let's go %screate one%s!", "gravityformstwocheckout"), '<a href="admin.php?page=gf_twocheckout&view=edit&id=0">', "</a>"); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            function DeleteSetting(id){
                jQuery("#action_argument").val(id);
                jQuery("#action").val("delete");
                jQuery("#feed_form")[0].submit();
            }
            function ToggleActive(img, feed_id){
                var is_active = img.src.indexOf("active1.png") >=0
                if(is_active){
                    img.src = img.src.replace("active1.png", "active0.png");
                    jQuery(img).attr('title','<?php _e("Inactive", "gravityformstwocheckout") ?>').attr('alt', '<?php _e("Inactive", "gravityformstwocheckout") ?>');
                }
                else{
                    img.src = img.src.replace("active0.png", "active1.png");
                    jQuery(img).attr('title','<?php _e("Active", "gravityformstwocheckout") ?>').attr('alt', '<?php _e("Active", "gravityformstwocheckout") ?>');
                }

                var mysack = new sack(ajaxurl);
                mysack.execute = 1;
                mysack.method = 'POST';
                mysack.setVar( "action", "gf_twocheckout_update_feed_active" );
                mysack.setVar( "gf_twocheckout_update_feed_active", "<?php echo wp_create_nonce("gf_twocheckout_update_feed_active") ?>" );
                mysack.setVar( "feed_id", feed_id );
                mysack.setVar( "is_active", is_active ? 0 : 1 );
                mysack.onError = function() { alert('<?php _e("Ajax error while updating feed", "gravityformstwocheckout" ) ?>' )};
                mysack.runAJAX();

                return true;
            }


        </script>
        <?php
    }

    public static function load_notifications(){
        $form_id = $_POST["form_id"];
        $form = RGFormsModel::get_form_meta($form_id);
        $notifications = array();
        if(is_array(rgar($form, "notifications"))){
            foreach($form["notifications"] as $notification){
                $notifications[] = array("name" => $notification["name"], "id" => $notification["id"]);
            }
        }
        die(json_encode($notifications));
    }

    public static function settings_page(){

        if(rgpost("uninstall")){
            check_admin_referer("uninstall", "gf_twocheckout_uninstall");
            self::uninstall();

            ?>
            <div class="updated fade" style="padding:20px;"><?php _e(sprintf("Gravity Forms 2Checkout Add-On have been successfully uninstalled. It can be re-activated from the %splugins page%s.", "<a href='plugins.php'>","</a>"), "gravityformstwocheckout")?></div>
            <?php
            return;
        }
        else if(isset($_POST["gf_twocheckout_submit"])){
            check_admin_referer("update", "gf_twocheckout_update");
            $settings = array(	"seller_id" => rgpost("gf_twocheckout_seller_id"),
								"secret_word" => rgpost("gf_twocheckout_secret_word"),
                                "mode" => rgpost("gf_twocheckout_mode")
								);


            update_option("gf_twocheckout_settings", $settings);
        }
        else{
            $settings = get_option("gf_twocheckout_settings");
        }
        
        if(!empty($settings)){
        	update_option("gf_twocheckout_configured", TRUE);
		}

        ?>
        <style>
            .valid_credentials{color:green;}
            .invalid_credentials{color:red;}
            .size-1{width:400px;}
        </style>

        <form method="post" action="">
            <?php wp_nonce_field("update", "gf_twocheckout_update") ?>

            <h3><?php _e("2Checkout Information", "gravityformstwocheckout") ?></h3>
            <p style="text-align: left;">
                <?php _e(sprintf("2Checkout works by sending the user to 2Checkout to enter their payment information. If you don't have a 2Checkout account, you can %ssign up for one here%s.", "<a href='https://www.2checkout.com/referral?r=pats2co' target='_blank'>" , "</a>"), "gravityformstwocheckout") ?>
            </p>

            <table class="form-table">
                <tr>
                    <th scope="row" nowrap="nowrap"><label for="gf_twocheckout_seller_id"><?php _e("Seller ID", "gravityformstwocheckout"); ?></label> </th>
                    <td width="88%">
                        <input class="size-1" id="gf_twocheckout_seller_id" name="gf_twocheckout_seller_id" value="<?php echo esc_attr(rgar($settings,"seller_id")) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row" nowrap="nowrap"><label for="gf_twocheckout_secret_word"><?php _e("Secret Word", "gravityformstwocheckout"); ?></label> </th>
                    <td width="88%">
                        <input class="size-1" id="gf_twocheckout_secret_word" name="gf_twocheckout_secret_word" value="<?php echo esc_attr(rgar($settings,"secret_word")) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row" nowrap="nowrap"><label for="gf_twocheckout_mode"><?php _e("Mode", "gravityformstwocheckout"); ?></label> </th>
                    <td width="88%">
                        <input type="radio" name="gf_twocheckout_mode" id="gf_twocheckout_mode_production" value="production" <?php echo rgar($settings, 'mode') != "test" ? "checked='checked'" : "" ?>/>
                        <label class="inline" for="gf_twocheckout_mode_production"><?php _e("Production", "gravityformstwocheckout"); ?></label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gf_twocheckout_mode" id="gf_twocheckout_mode_test" value="test" <?php echo rgar($settings, 'mode') == "test" ? "checked='checked'" : "" ?>/>
                        <label class="inline" for="gf_twocheckout_mode_test"><?php _e("Test", "gravityformstwocheckout"); ?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ><input type="submit" name="gf_twocheckout_submit" class="button-primary" value="<?php _e("Save Settings", "gravityformstwocheckout") ?>" /></td>
                </tr>
            </table>
        </form>

        <form action="" method="post">
            <?php wp_nonce_field("uninstall", "gf_twocheckout_uninstall") ?>
            <?php if(GFCommon::current_user_can_any("gravityforms_twocheckout_uninstall")){ ?>
                <div class="hr-divider"></div>

                <h3><?php _e("Uninstall 2Checkout Add-On", "gravityformstwocheckout") ?></h3>
                <div class="delete-alert"><?php _e("Warning! This operation deletes ALL 2Checkout Feeds.", "gravityformstwocheckout") ?>
                    <?php
                    $uninstall_button = '<input type="submit" name="uninstall" value="' . __("Uninstall 2Checkout Add-On", "gravityformstwocheckout") . '" class="button" onclick="return confirm(\'' . __("Warning! ALL 2Checkout Feeds will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", "gravityformstwocheckout") . '\');"/>';
                    echo apply_filters("gform_twocheckout_uninstall_button", $uninstall_button);
                    ?>
                </div>
            <?php } ?>
        </form>
        <?php
    }

    private static function get_product_field_options($productFields, $selectedValue){
        $options = "<option value=''>" . __("Select a product", "gravityformstwocheckout") . "</option>";
        foreach($productFields as $field){
            $label = GFCommon::truncate_middle($field["label"], 30);
            $selected = $selectedValue == $field["id"] ? "selected='selected'" : "";
            $options .= "<option value='{$field["id"]}' {$selected}>{$label}</option>";
        }

        return $options;
    }

    private static function stats_page(){
        ?>
        <style>
          .twocheckout_graph_container{clear:both; padding-left:5px; min-width:789px; margin-right:50px;}
        .twocheckout_message_container{clear: both; padding-left:5px; text-align:center; padding-top:120px; border: 1px solid #CCC; background-color: #FFF; width:100%; height:160px;}
        .twocheckout_summary_container {margin:30px 60px; text-align: center; min-width:740px; margin-left:50px;}
        .twocheckout_summary_item {width:160px; background-color: #FFF; border: 1px solid #CCC; padding:14px 8px; margin:6px 3px 6px 0; display: -moz-inline-stack; display: inline-block; zoom: 1; *display: inline; text-align:center;}
        .twocheckout_summary_value {font-size:20px; margin:5px 0; font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif}
        .twocheckout_summary_title {}
        #twocheckout_graph_tooltip {border:4px solid #b9b9b9; padding:11px 0 0 0; background-color: #f4f4f4; text-align:center; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; -khtml-border-radius: 4px;}
        #twocheckout_graph_tooltip .tooltip_tip {width:14px; height:14px; background-image:url(<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/tooltip_tip.png); background-repeat: no-repeat; position: absolute; bottom:-14px; left:68px;}

        .twocheckout_tooltip_date {line-height:130%; font-weight:bold; font-size:13px; color:#21759B;}
        .twocheckout_tooltip_sales {line-height:130%;}
        .twocheckout_tooltip_revenue {line-height:130%;}
            .twocheckout_tooltip_revenue .twocheckout_tooltip_heading {}
            .twocheckout_tooltip_revenue .twocheckout_tooltip_value {}
            .twocheckout_trial_disclaimer {clear:both; padding-top:20px; font-size:10px;}
        </style>
        <script type="text/javascript" src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/js/flot/jquery.flot.min.js"></script>
        <script type="text/javascript" src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/js/currency.js"></script>

        <div class="wrap">
            <img alt="<?php _e("2Checkout", "gravityformstwocheckout") ?>" style="margin: 15px 7px 0pt 0pt; float: left;" src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/twocheckout_wordpress_icon_32.png"/>
            <h2><?php _e("2Checkout Stats", "gravityformstwocheckout") ?></h2>

            <form method="post" action="">
                <ul class="subsubsub">
                    <li><a class="<?php echo (!RGForms::get("tab") || RGForms::get("tab") == "daily") ? "current" : "" ?>" href="?page=gf_twocheckout&view=stats&id=<?php echo $_GET["id"] ?>"><?php _e("Daily", "gravityforms"); ?></a> | </li>
                    <li><a class="<?php echo RGForms::get("tab") == "weekly" ? "current" : ""?>" href="?page=gf_twocheckout&view=stats&id=<?php echo $_GET["id"] ?>&tab=weekly"><?php _e("Weekly", "gravityforms"); ?></a> | </li>
                    <li><a class="<?php echo RGForms::get("tab") == "monthly" ? "current" : ""?>" href="?page=gf_twocheckout&view=stats&id=<?php echo $_GET["id"] ?>&tab=monthly"><?php _e("Monthly", "gravityforms"); ?></a></li>
                </ul>
                <?php
                $config = GFTWOCHECKOUTData::get_feed(RGForms::get("id"));

                switch(RGForms::get("tab")){
                    case "monthly" :
                        $chart_info = self::monthly_chart_info($config);
                    break;

                    case "weekly" :
                        $chart_info = self::weekly_chart_info($config);
                    break;

                    default :
                        $chart_info = self::daily_chart_info($config);
                    break;
                }

                if(!$chart_info["series"]){
                    ?>
                    <div class="twocheckout_message_container"><?php _e("No payments have been made yet.", "gravityformstwocheckout") ?> <?php echo $config["meta"]["trial_period_enabled"] && empty($config["meta"]["trial_amount"]) ? " **" : ""?></div>
                    <?php
                }
                else{
                    ?>
                    <div class="twocheckout_graph_container">
                        <div id="graph_placeholder" style="width:100%;height:300px;"></div>
                    </div>

                    <script type="text/javascript">
                        var twocheckout_graph_tooltips = <?php echo $chart_info["tooltips"] ?>;

                        jQuery.plot(jQuery("#graph_placeholder"), <?php echo $chart_info["series"] ?>, <?php echo $chart_info["options"] ?>);
                        jQuery(window).resize(function(){
                            jQuery.plot(jQuery("#graph_placeholder"), <?php echo $chart_info["series"] ?>, <?php echo $chart_info["options"] ?>);
                        });

                        var previousPoint = null;
                        jQuery("#graph_placeholder").bind("plothover", function (event, pos, item) {
                            startShowTooltip(item);
                        });

                        jQuery("#graph_placeholder").bind("plotclick", function (event, pos, item) {
                            startShowTooltip(item);
                        });

                        function startShowTooltip(item){
                            if (item) {
                                if (!previousPoint || previousPoint[0] != item.datapoint[0]) {
                                    previousPoint = item.datapoint;

                                    jQuery("#twocheckout_graph_tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);

                                    showTooltip(item.pageX, item.pageY, twocheckout_graph_tooltips[item.dataIndex]);
                                }
                            }
                            else {
                                jQuery("#twocheckout_graph_tooltip").remove();
                                previousPoint = null;
                            }
                        }

                        function showTooltip(x, y, contents) {
                            jQuery('<div id="twocheckout_graph_tooltip">' + contents + '<div class="tooltip_tip"></div></div>').css( {
                                position: 'absolute',
                                display: 'none',
                                opacity: 0.90,
                                width:'150px',
                                height:'<?php echo $config["meta"]["type"] == "subscription" ? "75px" : "60px" ;?>',
                                top: y - <?php echo $config["meta"]["type"] == "subscription" ? "100" : "89" ;?>,
                                left: x - 79
                            }).appendTo("body").fadeIn(200);
                        }


                        function convertToMoney(number){
                            var currency = getCurrentCurrency();
                            return currency.toMoney(number);
                        }
                        function formatWeeks(number){
                            number = number + "";
                            return "<?php _e("Week ", "gravityformstwocheckout") ?>" + number.substring(number.length-2);
                        }

                        function getCurrentCurrency(){
                            <?php
                            if(!class_exists("RGCurrency"))
                                require_once(ABSPATH . "/" . PLUGINDIR . "/gravityforms/currency.php");

                            $current_currency = RGCurrency::get_currency(GFCommon::get_currency());
                            ?>
                            var currency = new Currency(<?php echo GFCommon::json_encode($current_currency)?>);
                            return currency;
                        }
                    </script>
                <?php
                }
                $payment_totals = RGFormsModel::get_form_payment_totals($config["form_id"]);
                $transaction_totals = GFTWOCHECKOUTData::get_transaction_totals($config["form_id"]);

                switch($config["meta"]["type"]){
                    case "product" :
                        $total_sales = $payment_totals["orders"];
                        $sales_label = __("Total Orders", "gravityformstwocheckout");
                    break;

                    case "donation" :
                        $total_sales = $payment_totals["orders"];
                        $sales_label = __("Total Donations", "gravityformstwocheckout");
                    break;
                }

                $total_revenue = empty($transaction_totals["payment"]["revenue"]) ? 0 : $transaction_totals["payment"]["revenue"];
                ?>
                <div class="twocheckout_summary_container">
                    <div class="twocheckout_summary_item">
                        <div class="twocheckout_summary_title"><?php _e("Total Revenue", "gravityformstwocheckout")?></div>
                        <div class="twocheckout_summary_value"><?php echo GFCommon::to_money($total_revenue) ?></div>
                    </div>
                    <div class="twocheckout_summary_item">
                        <div class="twocheckout_summary_title"><?php echo $chart_info["revenue_label"]?></div>
                        <div class="twocheckout_summary_value"><?php echo $chart_info["revenue"] ?></div>
                    </div>
                    <div class="twocheckout_summary_item">
                        <div class="twocheckout_summary_title"><?php echo $sales_label?></div>
                        <div class="twocheckout_summary_value"><?php echo $total_sales ?></div>
                    </div>
                    <div class="twocheckout_summary_item">
                        <div class="twocheckout_summary_title"><?php echo $chart_info["sales_label"] ?></div>
                        <div class="twocheckout_summary_value"><?php echo $chart_info["sales"] ?></div>
                    </div>
                </div>
                <?php
                if(!$chart_info["series"] && $config["meta"]["trial_period_enabled"] && empty($config["meta"]["trial_amount"])){
                    ?>
                    <div class="twocheckout_trial_disclaimer"><?php _e("** Free trial transactions will only be reflected in the graph after the first payment is made (i.e. after trial period ends)", "gravityformstwocheckout") ?></div>
                    <?php
                }
                ?>
            </form>
        </div>
        <?php
    }
    private function get_graph_timestamp($local_datetime){
        $local_timestamp = mysql2date("G", $local_datetime); //getting timestamp with timezone adjusted
        $local_date_timestamp = mysql2date("G", gmdate("Y-m-d 23:59:59", $local_timestamp)); //setting time portion of date to midnight (to match the way Javascript handles dates)
        $timestamp = ($local_date_timestamp - (24 * 60 * 60) + 1) * 1000; //adjusting timestamp for Javascript (subtracting a day and transforming it to milliseconds
        return $timestamp;
    }

    private static function matches_current_date($format, $js_timestamp){
        $target_date = $format == "YW" ? $js_timestamp : date($format, $js_timestamp / 1000);

        $current_date = gmdate($format, GFCommon::get_local_timestamp(time()));
        return $target_date == $current_date;
    }

    private static function daily_chart_info($config){
        global $wpdb;

        $tz_offset = self::get_mysql_tz_offset();

        $results = $wpdb->get_results("SELECT CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "') as date, sum(t.amount) as amount_sold, sum(is_renewal) as renewals, sum(is_renewal=0) as new_sales
                                        FROM {$wpdb->prefix}rg_lead l
                                        INNER JOIN {$wpdb->prefix}rg_twocheckout_transaction t ON l.id = t.entry_id
                                        WHERE form_id={$config["form_id"]} AND t.transaction_type='payment'
                                        GROUP BY date(date)
                                        ORDER BY payment_date desc
                                        LIMIT 30");

        $sales_today = 0;
        $revenue_today = 0;
        $tooltips = "";

        if(!empty($results)){

            $data = "[";

            foreach($results as $result){
                $timestamp = self::get_graph_timestamp($result->date);
                if(self::matches_current_date("Y-m-d", $timestamp)){
                    $sales_today += $result->new_sales;
                    $revenue_today += $result->amount_sold;
                }
                $data .="[{$timestamp},{$result->amount_sold}],";

                $sales_line = "<div class='twocheckout_tooltip_sales'><span class='twocheckout_tooltip_heading'>" . __("Orders", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . $result->new_sales . "</span></div>";
                
                $tooltips .= "\"<div class='twocheckout_tooltip_date'>" . GFCommon::format_date($result->date, false, "", false) . "</div>{$sales_line}<div class='twocheckout_tooltip_revenue'><span class='twocheckout_tooltip_heading'>" . __("Revenue", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . GFCommon::to_money($result->amount_sold) . "</span></div>\",";
            }
            $data = substr($data, 0, strlen($data)-1);
            $tooltips = substr($tooltips, 0, strlen($tooltips)-1);
            $data .="]";

            $series = "[{data:" . $data . "}]";
            $month_names = self::get_chart_month_names();
            $options ="
            {
                xaxis: {mode: 'time', monthnames: $month_names, timeformat: '%b %d', minTickSize:[1, 'day']},
                yaxis: {tickFormatter: convertToMoney},
                bars: {show:true, align:'right', barWidth: (24 * 60 * 60 * 1000) - 10000000},
                colors: ['#a3bcd3', '#14568a'],
                grid: {hoverable: true, clickable: true, tickColor: '#F1F1F1', backgroundColor:'#FFF', borderWidth: 1, borderColor: '#CCC'}
            }";
        }
        switch($config["meta"]["type"]){
            case "product" :
                $sales_label = __("Orders Today", "gravityformstwocheckout");
            break;

            case "donation" :
                $sales_label = __("Donations Today", "gravityformstwocheckout");
            break;

            case "subscription" :
                $sales_label = __("Subscriptions Today", "gravityformstwocheckout");
            break;
        }
        $revenue_today = GFCommon::to_money($revenue_today);
        return array("series" => $series, "options" => $options, "tooltips" => "[$tooltips]", "revenue_label" => __("Revenue Today", "gravityformstwocheckout"), "revenue" => $revenue_today, "sales_label" => $sales_label, "sales" => $sales_today);
    }

    private static function weekly_chart_info($config){
            global $wpdb;

            $tz_offset = self::get_mysql_tz_offset();

            $results = $wpdb->get_results("SELECT yearweek(CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "')) week_number, sum(t.amount) as amount_sold, sum(is_renewal) as renewals, sum(is_renewal=0) as new_sales
                                            FROM {$wpdb->prefix}rg_lead l
                                            INNER JOIN {$wpdb->prefix}rg_twocheckout_transaction t ON l.id = t.entry_id
                                            WHERE form_id={$config["form_id"]} AND t.transaction_type='payment'
                                            GROUP BY week_number
                                            ORDER BY week_number desc
                                            LIMIT 30");
            $sales_week = 0;
            $revenue_week = 0;
            $tooltips = "";
            if(!empty($results))
            {
                $data = "[";

                foreach($results as $result){
                    if(self::matches_current_date("YW", $result->week_number)){
                        $sales_week += $result->new_sales;
                        $revenue_week += $result->amount_sold;
                    }
                    $data .="[{$result->week_number},{$result->amount_sold}],";

					$sales_line = "<div class='twocheckout_tooltip_sales'><span class='twocheckout_tooltip_heading'>" . __("Orders", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . $result->new_sales . "</span></div>";
                    
                    $tooltips .= "\"<div class='twocheckout_tooltip_date'>" . substr($result->week_number, 0, 4) . ", " . __("Week",  "gravityformstwocheckout") . " " . substr($result->week_number, strlen($result->week_number)-2, 2) . "</div>{$sales_line}<div class='twocheckout_tooltip_revenue'><span class='twocheckout_tooltip_heading'>" . __("Revenue", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . GFCommon::to_money($result->amount_sold) . "</span></div>\",";
                }
                $data = substr($data, 0, strlen($data)-1);
                $tooltips = substr($tooltips, 0, strlen($tooltips)-1);
                $data .="]";

                $series = "[{data:" . $data . "}]";
                $month_names = self::get_chart_month_names();
                $options ="
                {
                    xaxis: {tickFormatter: formatWeeks, tickDecimals: 0},
                    yaxis: {tickFormatter: convertToMoney},
                    bars: {show:true, align:'center', barWidth:0.95},
                    colors: ['#a3bcd3', '#14568a'],
                    grid: {hoverable: true, clickable: true, tickColor: '#F1F1F1', backgroundColor:'#FFF', borderWidth: 1, borderColor: '#CCC'}
                }";
            }

            switch($config["meta"]["type"]){
                case "product" :
                    $sales_label = __("Orders this Week", "gravityformstwocheckout");
                break;

                case "donation" :
                    $sales_label = __("Donations this Week", "gravityformstwocheckout");
                break;
				
            }
            $revenue_week = GFCommon::to_money($revenue_week);

            return array("series" => $series, "options" => $options, "tooltips" => "[$tooltips]", "revenue_label" => __("Revenue this Week", "gravityformstwocheckout"), "revenue" => $revenue_week, "sales_label" => $sales_label , "sales" => $sales_week);
    }

    private static function monthly_chart_info($config){
            global $wpdb;
            $tz_offset = self::get_mysql_tz_offset();

            $results = $wpdb->get_results("SELECT date_format(CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "'), '%Y-%m-02') date, sum(t.amount) as amount_sold, sum(is_renewal) as renewals, sum(is_renewal=0) as new_sales
                                            FROM {$wpdb->prefix}rg_lead l
                                            INNER JOIN {$wpdb->prefix}rg_twocheckout_transaction t ON l.id = t.entry_id
                                            WHERE form_id={$config["form_id"]} AND t.transaction_type='payment'
                                            group by date
                                            order by date desc
                                            LIMIT 30");

            $sales_month = 0;
            $revenue_month = 0;
            $tooltips = "";
            if(!empty($results)){

                $data = "[";

                foreach($results as $result){
                    $timestamp = self::get_graph_timestamp($result->date);
                    if(self::matches_current_date("Y-m", $timestamp)){
                        $sales_month += $result->new_sales;
                        $revenue_month += $result->amount_sold;
                    }
                    $data .="[{$timestamp},{$result->amount_sold}],";

					$sales_line = "<div class='twocheckout_tooltip_sales'><span class='twocheckout_tooltip_heading'>" . __("Orders", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . $result->new_sales . "</span></div>";
                    
                    $tooltips .= "\"<div class='twocheckout_tooltip_date'>" . GFCommon::format_date($result->date, false, "F, Y", false) . "</div>{$sales_line}<div class='twocheckout_tooltip_revenue'><span class='twocheckout_tooltip_heading'>" . __("Revenue", "gravityformstwocheckout") . ": </span><span class='twocheckout_tooltip_value'>" . GFCommon::to_money($result->amount_sold) . "</span></div>\",";
                }
                $data = substr($data, 0, strlen($data)-1);
                $tooltips = substr($tooltips, 0, strlen($tooltips)-1);
                $data .="]";

                $series = "[{data:" . $data . "}]";
                $month_names = self::get_chart_month_names();
                $options ="
                {
                    xaxis: {mode: 'time', monthnames: $month_names, timeformat: '%b %y', minTickSize: [1, 'month']},
                    yaxis: {tickFormatter: convertToMoney},
                    bars: {show:true, align:'center', barWidth: (24 * 60 * 60 * 30 * 1000) - 130000000},
                    colors: ['#a3bcd3', '#14568a'],
                    grid: {hoverable: true, clickable: true, tickColor: '#F1F1F1', backgroundColor:'#FFF', borderWidth: 1, borderColor: '#CCC'}
                }";
            }
            switch($config["meta"]["type"]){
                case "product" :
                    $sales_label = __("Orders this Month", "gravityformstwocheckout");
                break;

                case "donation" :
                    $sales_label = __("Donations this Month", "gravityformstwocheckout");
                break;
            }
            $revenue_month = GFCommon::to_money($revenue_month);
            return array("series" => $series, "options" => $options, "tooltips" => "[$tooltips]", "revenue_label" => __("Revenue this Month", "gravityformstwocheckout"), "revenue" => $revenue_month, "sales_label" => $sales_label, "sales" => $sales_month);
    }

    private static function get_mysql_tz_offset(){
        $tz_offset = get_option("gmt_offset");

        //add + if offset starts with a number
        if(is_numeric(substr($tz_offset, 0, 1)))
            $tz_offset = "+" . $tz_offset;

        return $tz_offset . ":00";
    }

    private static function get_chart_month_names(){
        return "['" . __("Jan", "gravityformstwocheckout") ."','" . __("Feb", "gravityformstwocheckout") ."','" . __("Mar", "gravityformstwocheckout") ."','" . __("Apr", "gravityformstwocheckout") ."','" . __("May", "gravityformstwocheckout") ."','" . __("Jun", "gravityformstwocheckout") ."','" . __("Jul", "gravityformstwocheckout") ."','" . __("Aug", "gravityformstwocheckout") ."','" . __("Sep", "gravityformstwocheckout") ."','" . __("Oct", "gravityformstwocheckout") ."','" . __("Nov", "gravityformstwocheckout") ."','" . __("Dec", "gravityformstwocheckout") ."']";
    }

    // Edit Page
    private static function edit_page(){
        ?>
        <style>
            #twocheckout_submit_container{clear:both;}
            .twocheckout_col_heading{padding-bottom:2px; border-bottom: 1px solid #ccc; font-weight:bold; width:120px;}
            .twocheckout_field_cell {padding: 6px 17px 0 0; margin-right:15px;}

            .twocheckout_validation_error{ background-color:#FFDFDF; margin-top:4px; margin-bottom:6px; padding-top:6px; padding-bottom:6px; border:1px dotted #C89797;}
            .twocheckout_validation_error span {color: red;}
            .left_header{float:left; width:200px;}
            .margin_vertical_10{margin: 10px 0; padding-left:5px;}
            .margin_vertical_30{margin: 30px 0; padding-left:5px;}
            .width-1{width:300px;}
            .gf_twocheckout_invalid_form{margin-top:30px; background-color:#FFEBE8;border:1px solid #CC0000; padding:10px; width:600px;}
        </style>
        <script type="text/javascript">
            var form = Array();
            function ToggleNotifications(){

                var container = jQuery("#gf_twocheckout_notification_container");
                var isChecked = jQuery("#gf_twocheckout_delay_notifications").is(":checked");

                if(isChecked){
                    container.slideDown();
                    var isLoaded = jQuery(".gf_twocheckout_notification").length > 0
                    if(!isLoaded){
                        container.html("<li><img src='<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/loading.gif' title='<?php _e("Please wait...", "gravityformstwocheckout"); ?>'></li>");
                        jQuery.post(ajaxurl, {
                            action: "gf_twocheckout_load_notifications",
                            form_id: form["id"],
                            },
                            function(response){

                                var notifications = jQuery.parseJSON(response);
                                if(!notifications){
                                    container.html("<li><div class='error' padding='20px;'><?php _e("Notifications could not be loaded. Please try again later or contact support", "gravityformstwocheckout") ?></div></li>");
                                }
                                else if(notifications.length == 0){
                                    container.html("<li><div class='error' padding='20px;'><?php _e("The form selected does not have any notifications.", "gravityformstwocheckout") ?></div></li>");
                                }
                                else{
                                    var str = "";
                                    for(var i=0; i<notifications.length; i++){
                                        str += "<li class='gf_twocheckout_notification'>"
                                            +       "<input type='checkbox' value='" + notifications[i]["id"] + "' name='gf_twocheckout_selected_notifications[]' id='gf_twocheckout_selected_notifications' checked='checked' /> "
                                            +       "<label class='inline' for='gf_twocheckout_selected_notifications'>" + notifications[i]["name"] + "</label>";
                                            +  "</li>";
                                    }
                                    container.html(str);
                                }
                            }
                        );
                    }
                    jQuery(".gf_twocheckout_notification input").prop("checked", true);
                }
                else{
                    container.slideUp();
                    jQuery(".gf_twocheckout_notification input").prop("checked", false);
                }
            }
        </script>
        <div class="wrap">
            <img alt="<?php _e("2Checkout", "gravityformstwocheckout") ?>" style="margin: 15px 7px 0pt 0pt; float: left;" src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/twocheckout_wordpress_icon_32.png"/>
            <h2><?php _e("2Checkout Transaction Settings", "gravityformstwocheckout") ?></h2>

        <?php

        //getting setting id (0 when creating a new one)
        $id = !empty($_POST["twocheckout_setting_id"]) ? $_POST["twocheckout_setting_id"] : absint($_GET["id"]);
        $config = empty($id) ? array("meta" => array(), "is_active" => true) : GFTWOCHECKOUTData::get_feed($id);
        $is_validation_error = false;
        
        $config["form_id"] = rgpost("gf_twocheckout_submit") ? absint(rgpost("gf_twocheckout_form")) : $config["form_id"];

        $form = isset($config["form_id"]) && $config["form_id"] ? $form = RGFormsModel::get_form_meta($config["form_id"]) : array();

        //updating meta information
        if(rgpost("gf_twocheckout_submit")){
        	
            $config["meta"]["type"] = rgpost("gf_twocheckout_type");
            $config["meta"]["product_type"] = rgpost("gf_twocheckout_product");
            $config["meta"]["cancel_url"] = rgpost("gf_twocheckout_cancel_url");
            $config["meta"]["delay_post"] = rgpost('gf_twocheckout_delay_post');
            $config["meta"]["update_post_action"] = rgpost('gf_twocheckout_update_action');

            if(isset($form["notifications"])){
                //new notification settings
                $config["meta"]["delay_notifications"] = rgpost('gf_twocheckout_delay_notifications');
                $config["meta"]["selected_notifications"] = $config["meta"]["delay_notifications"] ? rgpost('gf_twocheckout_selected_notifications') : array();

                if(isset($config["meta"]["delay_autoresponder"]))
                    unset($config["meta"]["delay_autoresponder"]);
                if(isset($config["meta"]["delay_notification"]))
                    unset($config["meta"]["delay_notification"]);
            }

            // twocheckout conditional
            $config["meta"]["twocheckout_conditional_enabled"] = rgpost('gf_twocheckout_conditional_enabled');
            $config["meta"]["twocheckout_conditional_field_id"] = rgpost('gf_twocheckout_conditional_field_id');
            $config["meta"]["twocheckout_conditional_operator"] = rgpost('gf_twocheckout_conditional_operator');
            $config["meta"]["twocheckout_conditional_value"] = rgpost('gf_twocheckout_conditional_value');

            //-----------------

            $customer_fields = self::get_customer_fields();
            $config["meta"]["customer_fields"] = array();
            foreach($customer_fields as $field){
                $config["meta"]["customer_fields"][$field["name"]] = $_POST["twocheckout_customer_field_{$field["name"]}"];
            }

            $config = apply_filters('gform_twocheckout_save_config', $config);

            $is_validation_error = apply_filters("gform_twocheckout_config_validation", false, $config);

            if(!$is_validation_error){
                $id = GFTWOCHECKOUTData::update_feed($id, $config["form_id"], $config["is_active"], $config["meta"]);
                ?>
                <div class="updated fade" style="padding:6px"><?php echo sprintf(__("Feed Updated. %sback to list%s", "gravityformstwocheckout"), "<a href='?page=gf_twocheckout'>", "</a>") ?></div>
                <?php
            }
            else{
                $is_validation_error = true;
            }

        }

        ?>
        <form method="post" action="">
            <input type="hidden" name="twocheckout_setting_id" value="<?php echo $id ?>" />

            <div class="margin_vertical_10 <?php echo $is_validation_error ? "twocheckout_validation_error" : "" ?>">
                <?php
                if($is_validation_error){
                    ?>
                    <span><?php _e('There was an issue saving your feed. Please address the errors below and try again.'); ?></span>
                    <?php
                }
                ?>
            </div> <!-- / validation message -->

            <div class="margin_vertical_10">
                <label class="left_header" for="gf_twocheckout_type"><?php _e("Transaction Type", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_transaction_type") ?></label>

                <select id="gf_twocheckout_type" name="gf_twocheckout_type" onchange="SelectType(jQuery(this).val());">
                    <option value=""><?php _e("Select a transaction type", "gravityformstwocheckout") ?></option>
                    <option value="product" <?php echo rgar($config['meta'], 'type') == "product" ? "selected='selected'" : "" ?>><?php _e("Products and Services", "gravityformstwocheckout") ?></option>
                    <option value="donation" <?php echo rgar($config['meta'], 'type') == "donation" ? "selected='selected'" : "" ?>><?php _e("Donations", "gravityformstwocheckout") ?></option>
                </select>
            </div>
			
            <div id="twocheckout_form_container" valign="top" class="margin_vertical_10" <?php echo empty($config["meta"]["type"]) ? "style='display:none;'" : "" ?>>
			
			
            <div class="margin_vertical_10">
                <label class="left_header"><?php _e("Product Type", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_product_type") ?></label>

                <input type="radio" name="gf_twocheckout_product" id="gf_twocheckout_product_tangible" value="tangible" <?php echo rgar($config['meta'], 'product_type') != "intangible" ? "checked='checked'" : "" ?>/>
                <label class="inline" for="gf_twocheckout_product_tangible"><?php _e("Tangible", "gravityformstwocheckout"); ?></label>
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="gf_twocheckout_product" id="gf_twocheckout_product_intangible" value="intangible" <?php echo rgar($config['meta'], 'product_type') == "intangible" ? "checked='checked'" : "" ?>/>
                <label class="inline" for="gf_twocheckout_product_intangible"><?php _e("Intangible", "gravityformstwocheckout"); ?></label>
            </div>
			
                <label for="gf_twocheckout_form" class="left_header"><?php _e("Gravity Form", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_gravity_form") ?></label>

                <select id="gf_twocheckout_form" name="gf_twocheckout_form" onchange="SelectForm(jQuery('#gf_twocheckout_type').val(), jQuery(this).val(), '<?php echo rgar($config, 'id') ?>');">
                    <option value=""><?php _e("Select a form", "gravityformstwocheckout"); ?> </option>
                    <?php

                    $active_form = rgar($config, 'form_id');
                    $available_forms = GFTWOCHECKOUTData::get_available_forms($active_form);

                    foreach($available_forms as $current_form) {
                        $selected = absint($current_form->id) == rgar($config, 'form_id') ? 'selected="selected"' : '';
                        ?>

                            <option value="<?php echo absint($current_form->id) ?>" <?php echo $selected; ?>><?php echo esc_html($current_form->title) ?></option>

                        <?php
                    }
                    ?>
                </select>
                <img src="<?php echo GF_TWOCHECKOUT_BASE_URL ?>/assets/images/loading.gif" id="twocheckout_wait" style="display: none;"/>

                <div id="gf_twocheckout_invalid_product_form" class="gf_twocheckout_invalid_form"  style="display:none;">
                    <?php _e("The form selected does not have any Product fields. Please add a Product field to the form and try again.", "gravityformstwocheckout") ?>
                </div>
                <div id="gf_twocheckout_invalid_donation_form" class="gf_twocheckout_invalid_form" style="display:none;">
                    <?php _e("The form selected does not have any Product fields. Please add a Product field to the form and try again.", "gravityformstwocheckout") ?>
                </div>
            </div>
            <div id="twocheckout_field_group" valign="top" <?php echo empty($config["meta"]["type"]) || empty($config["form_id"]) ? "style='display:none;'" : "" ?>>

                <div class="margin_vertical_10">
                    <label class="left_header"><?php _e("Customer", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_customer") ?></label>

                    <div id="twocheckout_customer_fields">
                        <?php
                            if(!empty($form))
                                echo self::get_customer_information($form, $config);
                        ?>
                    </div>
                </div>

                <div class="margin_vertical_10">
                    <label class="left_header" for="gf_twocheckout_cancel_url"><?php _e("Cancel URL", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_cancel_url") ?></label>
                    <input type="text" name="gf_twocheckout_cancel_url" id="gf_twocheckout_cancel_url" class="width-1" value="<?php echo rgars($config, "meta/cancel_url") ?>"/>
                </div>

                <div class="margin_vertical_10">
                    <ul style="overflow:hidden;">

                        <li id="twocheckout_delay_notification" <?php echo isset($form["notifications"]) ? "style='display:none;'" : "" ?>>
                            <input type="checkbox" name="gf_twocheckout_delay_notification" id="gf_twocheckout_delay_notification" value="1" <?php echo rgar($config["meta"], 'delay_notification') ? "checked='checked'" : ""?> />
                            <label class="inline" for="gf_twocheckout_delay_notification"><?php _e("Send admin notification only when payment is received.", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_delay_admin_notification") ?></label>
                        </li>
                        <li id="twocheckout_delay_autoresponder" <?php echo isset($form["notifications"]) ? "style='display:none;'" : "" ?>>
                            <input type="checkbox" name="gf_twocheckout_delay_autoresponder" id="gf_twocheckout_delay_autoresponder" value="1" <?php echo rgar($config["meta"], 'delay_autoresponder') ? "checked='checked'" : ""?> />
                            <label class="inline" for="gf_twocheckout_delay_autoresponder"><?php _e("Send user notification only when payment is received.", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_delay_user_notification") ?></label>
                        </li>

                        <?php
                        $display_post_fields = !empty($form) ? GFCommon::has_post_field($form["fields"]) : false;
                        ?>
                        <li id="twocheckout_post_action" <?php echo $display_post_fields ? "" : "style='display:none;'" ?>>
                            <input type="checkbox" name="gf_twocheckout_delay_post" id="gf_twocheckout_delay_post" value="1" <?php echo rgar($config["meta"],"delay_post") ? "checked='checked'" : ""?> />
                            <label class="inline" for="gf_twocheckout_delay_post"><?php _e("Create post only when payment is received.", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_delay_post") ?></label>
                        </li>

                        <li id="twocheckout_post_update_action" <?php echo $display_post_fields && $config["meta"]["type"] == "subscription" ? "" : "style='display:none;'" ?>>
                            <input type="checkbox" name="gf_twocheckout_update_post" id="gf_twocheckout_update_post" value="1" <?php echo rgar($config["meta"],"update_post_action") ? "checked='checked'" : ""?> onclick="var action = this.checked ? 'draft' : ''; jQuery('#gf_twocheckout_update_action').val(action);" />
                            <label class="inline" for="gf_twocheckout_update_post"><?php _e("Update Post when subscription is cancelled.", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_update_post") ?></label>
                            <select id="gf_twocheckout_update_action" name="gf_twocheckout_update_action" onchange="var checked = jQuery(this).val() ? 'checked' : false; jQuery('#gf_twocheckout_update_post').attr('checked', checked);">
                                <option value=""></option>
                                <option value="draft" <?php echo rgar($config["meta"],"update_post_action") == "draft" ? "selected='selected'" : ""?>><?php _e("Mark Post as Draft", "gravityformstwocheckout") ?></option>
                                <option value="delete" <?php echo rgar($config["meta"],"update_post_action") == "delete" ? "selected='selected'" : ""?>><?php _e("Delete Post", "gravityformstwocheckout") ?></option>
                            </select>
                        </li>

                        <?php do_action("gform_twocheckout_action_fields", $config, $form) ?>
                    </ul>
                </div>

                <div class="margin_vertical_10" id="gf_twocheckout_notifications" <?php echo !isset($form["notifications"]) ? "style='display:none;'" : "" ?>>
                    <label class="left_header"><?php _e("Notifications", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_notifications") ?></label>
                    <?php
                    $has_delayed_notifications = rgar($config['meta'], 'delay_notifications') || rgar($config['meta'], 'delay_notification') || rgar($config['meta'], 'delay_autoresponder');
                    ?>
                    <div style="overflow:hidden;">
                        <input type="checkbox" name="gf_twocheckout_delay_notifications" id="gf_twocheckout_delay_notifications" value="1" onclick="ToggleNotifications();" <?php checked("1", $has_delayed_notifications)?> />
                        <label class="inline" for="gf_twocheckout_delay_notifications"><?php _e("Send notifications only when payment is received.", "gravityformstwocheckout"); ?></label>

                        <ul id="gf_twocheckout_notification_container" style="padding-left:20px; <?php echo $has_delayed_notifications ? "" : "display:none;"?>">
                        <?php
                        if(!empty($form) && is_array($form["notifications"])){
                            $selected_notifications = self::get_selected_notifications($config, $form);

                            foreach($form["notifications"] as $notification){
                                ?>
                                <li class="gf_twocheckout_notification">
                                    <input type="checkbox" name="gf_twocheckout_selected_notifications[]" id="gf_twocheckout_selected_notifications" value="<?php echo $notification["id"]?>" <?php checked(true, in_array($notification["id"], $selected_notifications))?> />
                                    <label class="inline" for="gf_twocheckout_selected_notifications"><?php echo $notification["name"]; ?></label>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        </ul>
                    </div>
                </div>

                <?php do_action("gform_twocheckout_add_option_group", $config, $form); ?>

                <div id="gf_twocheckout_conditional_section" valign="top" class="margin_vertical_10">
                    <label for="gf_twocheckout_conditional_optin" class="left_header"><?php _e("2Checkout Condition", "gravityformstwocheckout"); ?> <?php gform_tooltip("twocheckout_conditional") ?></label>

                    <div id="gf_twocheckout_conditional_option">
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <input type="checkbox" id="gf_twocheckout_conditional_enabled" name="gf_twocheckout_conditional_enabled" value="1" onclick="if(this.checked){jQuery('#gf_twocheckout_conditional_container').fadeIn('fast');} else{ jQuery('#gf_twocheckout_conditional_container').fadeOut('fast'); }" <?php echo rgar($config['meta'], 'twocheckout_conditional_enabled') ? "checked='checked'" : ""?>/>
                                    <label for="gf_twocheckout_conditional_enable"><?php _e("Enable", "gravityformstwocheckout"); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="gf_twocheckout_conditional_container" <?php echo !rgar($config['meta'], 'twocheckout_conditional_enabled') ? "style='display:none'" : ""?>>

                                        <div id="gf_twocheckout_conditional_fields" style="display:none">
                                            <?php _e("Send to 2Checkout if ", "gravityformstwocheckout") ?>
                                            <select id="gf_twocheckout_conditional_field_id" name="gf_twocheckout_conditional_field_id" class="optin_select" onchange='jQuery("#gf_twocheckout_conditional_value_container").html(GetFieldValues(jQuery(this).val(), "", 20));'>
                                            </select>
                                            <select id="gf_twocheckout_conditional_operator" name="gf_twocheckout_conditional_operator">
                                                <option value="is" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "is" ? "selected='selected'" : "" ?>><?php _e("is", "gravityformstwocheckout") ?></option>
                                                <option value="isnot" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "isnot" ? "selected='selected'" : "" ?>><?php _e("is not", "gravityformstwocheckout") ?></option>
                                                <option value=">" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == ">" ? "selected='selected'" : "" ?>><?php _e("greater than", "gravityformstwocheckout") ?></option>
                                                <option value="<" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "<" ? "selected='selected'" : "" ?>><?php _e("less than", "gravityformstwocheckout") ?></option>
                                                <option value="contains" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "contains" ? "selected='selected'" : "" ?>><?php _e("contains", "gravityformstwocheckout") ?></option>
                                                <option value="starts_with" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "starts_with" ? "selected='selected'" : "" ?>><?php _e("starts with", "gravityformstwocheckout") ?></option>
                                                <option value="ends_with" <?php echo rgar($config['meta'], 'twocheckout_conditional_operator') == "ends_with" ? "selected='selected'" : "" ?>><?php _e("ends with", "gravityformstwocheckout") ?></option>
                                            </select>
                                            <div id="gf_twocheckout_conditional_value_container" name="gf_twocheckout_conditional_value_container" style="display:inline;"></div>
                                        </div>

                                        <div id="gf_twocheckout_conditional_message" style="display:none">
                                            <?php _e("To create a registration condition, your form must have a field supported by conditional logic.", "gravityform") ?>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> <!-- / twocheckout conditional -->

                <div id="twocheckout_submit_container" class="margin_vertical_30">
                    <input type="submit" name="gf_twocheckout_submit" value="<?php echo empty($id) ? __("  Save  ", "gravityformstwocheckout") : __("Update", "gravityformstwocheckout"); ?>" class="button-primary"/>
                    <input type="button" value="<?php _e("Cancel", "gravityformstwocheckout"); ?>" class="button" onclick="javascript:document.location='admin.php?page=gf_twocheckout'" />
                </div>
            </div>
        </form>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function(){
                SetPeriodNumber('#gf_twocheckout_billing_cycle_number', jQuery("#gf_twocheckout_billing_cycle_type").val());
                SetPeriodNumber('#gf_twocheckout_trial_period_number', jQuery("#gf_twocheckout_trial_period_type").val());
            });

            function SelectType(type){
                jQuery("#twocheckout_field_group").slideUp();

                jQuery("#twocheckout_field_group input[type=\"text\"], #twocheckout_field_group select").val("");
                jQuery("#gf_twocheckout_trial_period_type, #gf_twocheckout_billing_cycle_type").val("M");

                jQuery("#twocheckout_field_group input:checked").attr("checked", false);

                if(type){
                    jQuery("#twocheckout_form_container").slideDown();
                    jQuery("#gf_twocheckout_form").val("");
                }
                else{
                    jQuery("#twocheckout_form_container").slideUp();
                }
            }

            function SelectForm(type, formId, settingId){
                if(!formId){
                    jQuery("#twocheckout_field_group").slideUp();
                    return;
                }

                jQuery("#twocheckout_wait").show();
                jQuery("#twocheckout_field_group").slideUp();

                var mysack = new sack(ajaxurl);
                mysack.execute = 1;
                mysack.method = 'POST';
                mysack.setVar( "action", "gf_select_twocheckout_form" );
                mysack.setVar( "gf_select_twocheckout_form", "<?php echo wp_create_nonce("gf_select_twocheckout_form") ?>" );
                mysack.setVar( "type", type);
                mysack.setVar( "form_id", formId);
                mysack.setVar( "setting_id", settingId);
                mysack.onError = function() {jQuery("#twocheckout_wait").hide(); alert('<?php _e("Ajax error while selecting a form", "gravityformstwocheckout") ?>' )};
                mysack.runAJAX();

                return true;
            }

            function EndSelectForm(form_meta, customer_fields, recurring_amount_options){

                //setting global form object
                form = form_meta;

                var type = jQuery("#gf_twocheckout_type").val();

                jQuery(".gf_twocheckout_invalid_form").hide();
                if( (type == "product" || type =="subscription") && GetFieldsByType(["product"]).length == 0){
                    jQuery("#gf_twocheckout_invalid_product_form").show();
                    jQuery("#twocheckout_wait").hide();
                    return;
                }
                else if(type == "donation" && GetFieldsByType(["product", "donation"]).length == 0){
                    jQuery("#gf_twocheckout_invalid_donation_form").show();
                    jQuery("#twocheckout_wait").hide();
                    return;
                }

                jQuery(".twocheckout_field_container").hide();
                jQuery("#twocheckout_customer_fields").html(customer_fields);
                jQuery("#gf_twocheckout_recurring_amount").html(recurring_amount_options);

                //displaying delayed post creation setting if current form has a post field
                var post_fields = GetFieldsByType(["post_title", "post_content", "post_excerpt", "post_category", "post_custom_field", "post_image", "post_tag"]);
                if(post_fields.length > 0){
                    jQuery("#twocheckout_post_action").show();
                }
                else{
                    jQuery("#gf_twocheckout_delay_post").attr("checked", false);
                    jQuery("#twocheckout_post_action").hide();
                }

                if(type == "subscription" && post_fields.length > 0){
                    jQuery("#twocheckout_post_update_action").show();
                }
                else{
                    jQuery("#gf_twocheckout_update_post").attr("checked", false);
                    jQuery("#twocheckout_post_update_action").hide();
                }

                SetPeriodNumber('#gf_twocheckout_billing_cycle_number', jQuery("#gf_twocheckout_billing_cycle_type").val());
                SetPeriodNumber('#gf_twocheckout_trial_period_number', jQuery("#gf_twocheckout_trial_period_type").val());

                //Calling callback functions
                jQuery(document).trigger('twocheckoutFormSelected', [form]);

                jQuery("#gf_twocheckout_conditional_enabled").attr('checked', false);
                Set2CheckoutCondition("","");

                if(form["notifications"]){
                    jQuery("#gf_twocheckout_notifications").show();
                    jQuery("#twocheckout_delay_autoresponder, #twocheckout_delay_notification").hide();
                }
                else{
                    jQuery("#twocheckout_delay_autoresponder, #twocheckout_delay_notification").show();
                    jQuery("#gf_twocheckout_notifications").hide();
                }

                jQuery("#twocheckout_field_container_" + type).show();
                jQuery("#twocheckout_field_group").slideDown();
                jQuery("#twocheckout_wait").hide();
            }

            function SetPeriodNumber(element, type){
                var prev = jQuery(element).val();

                var min = 1;
                var max = 0;
                switch(type){
                    case "D" :
                        max = 100;
                    break;
                    case "W" :
                        max = 52;
                    break;
                    case "M" :
                        max = 12;
                    break;
                    case "Y" :
                        max = 5;
                    break;
                }
                var str="";
                for(var i=min; i<=max; i++){
                    var selected = prev == i ? "selected='selected'" : "";
                    str += "<option value='" + i + "' " + selected + ">" + i + "</option>";
                }
                jQuery(element).html(str);
            }

            function GetFieldsByType(types){
                var fields = new Array();
                for(var i=0; i<form["fields"].length; i++){
                    if(IndexOf(types, form["fields"][i]["type"]) >= 0)
                        fields.push(form["fields"][i]);
                }
                return fields;
            }

            function IndexOf(ary, item){
                for(var i=0; i<ary.length; i++)
                    if(ary[i] == item)
                        return i;

                return -1;
            }

        </script>

        <script type="text/javascript">

            // 2Checkout Conditional Functions

            <?php
            if(!empty($config["form_id"])){
                ?>

                // initilize form object
                form = <?php echo GFCommon::json_encode($form)?> ;

                // initializing registration condition drop downs
                jQuery(document).ready(function(){
                    var selectedField = "<?php echo str_replace('"', '\"', $config["meta"]["twocheckout_conditional_field_id"])?>";
                    var selectedValue = "<?php echo str_replace('"', '\"', $config["meta"]["twocheckout_conditional_value"])?>";
                    Set2CheckoutCondition(selectedField, selectedValue);
                });

                <?php
            }
            ?>

            function Set2CheckoutCondition(selectedField, selectedValue){

                // load form fields
                jQuery("#gf_twocheckout_conditional_field_id").html(GetSelectableFields(selectedField, 20));
                var optinConditionField = jQuery("#gf_twocheckout_conditional_field_id").val();
                var checked = jQuery("#gf_twocheckout_conditional_enabled").attr('checked');

                if(optinConditionField){
                    jQuery("#gf_twocheckout_conditional_message").hide();
                    jQuery("#gf_twocheckout_conditional_fields").show();
                    jQuery("#gf_twocheckout_conditional_value_container").html(GetFieldValues(optinConditionField, selectedValue, 20));
                    jQuery("#gf_twocheckout_conditional_value").val(selectedValue);
                }
                else{
                    jQuery("#gf_twocheckout_conditional_message").show();
                    jQuery("#gf_twocheckout_conditional_fields").hide();
                }

                if(!checked) jQuery("#gf_twocheckout_conditional_container").hide();

            }

            function GetFieldValues(fieldId, selectedValue, labelMaxCharacters){
                if(!fieldId)
                    return "";

                var str = "";
                var field = GetFieldById(fieldId);
                if(!field)
                    return "";

                var isAnySelected = false;

                if(field["type"] == "post_category" && field["displayAllCategories"]){
					str += '<?php $dd = wp_dropdown_categories(array("class"=>"optin_select", "orderby"=> "name", "id"=> "gf_twocheckout_conditional_value", "name"=> "gf_twocheckout_conditional_value", "hierarchical"=>true, "hide_empty"=>0, "echo"=>false)); echo str_replace("\n","", str_replace("'","\\'",$dd)); ?>';
				}
				else if(field.choices){
					str += '<select id="gf_twocheckout_conditional_value" name="gf_twocheckout_conditional_value" class="optin_select">'


	                for(var i=0; i<field.choices.length; i++){
	                    var fieldValue = field.choices[i].value ? field.choices[i].value : field.choices[i].text;
	                    var isSelected = fieldValue == selectedValue;
	                    var selected = isSelected ? "selected='selected'" : "";
	                    if(isSelected)
	                        isAnySelected = true;

	                    str += "<option value='" + fieldValue.replace(/'/g, "&#039;") + "' " + selected + ">" + TruncateMiddle(field.choices[i].text, labelMaxCharacters) + "</option>";
	                }

	                if(!isAnySelected && selectedValue){
	                    str += "<option value='" + selectedValue.replace(/'/g, "&#039;") + "' selected='selected'>" + TruncateMiddle(selectedValue, labelMaxCharacters) + "</option>";
	                }
	                str += "</select>";
				}
				else
				{
					selectedValue = selectedValue ? selectedValue.replace(/'/g, "&#039;") : "";
					//create a text field for fields that don't have choices (i.e text, textarea, number, email, etc...)
					str += "<input type='text' placeholder='<?php _e("Enter value", "gravityforms"); ?>' id='gf_twocheckout_conditional_value' name='gf_twocheckout_conditional_value' value='" + selectedValue.replace(/'/g, "&#039;") + "'>";
				}

                return str;
            }

            function GetFieldById(fieldId){
                for(var i=0; i<form.fields.length; i++){
                    if(form.fields[i].id == fieldId)
                        return form.fields[i];
                }
                return null;
            }

            function TruncateMiddle(text, maxCharacters){
                if(!text)
                    return "";

                if(text.length <= maxCharacters)
                    return text;
                var middle = parseInt(maxCharacters / 2);
                return text.substr(0, middle) + "..." + text.substr(text.length - middle, middle);
            }

            function GetSelectableFields(selectedFieldId, labelMaxCharacters){
                var str = "";
                var inputType;
                for(var i=0; i<form.fields.length; i++){
                    fieldLabel = form.fields[i].adminLabel ? form.fields[i].adminLabel : form.fields[i].label;
                    inputType = form.fields[i].inputType ? form.fields[i].inputType : form.fields[i].type;
                    if (IsConditionalLogicField(form.fields[i])) {
                        var selected = form.fields[i].id == selectedFieldId ? "selected='selected'" : "";
                        str += "<option value='" + form.fields[i].id + "' " + selected + ">" + TruncateMiddle(fieldLabel, labelMaxCharacters) + "</option>";
                    }
                }
                return str;
            }

            function IsConditionalLogicField(field){
			    inputType = field.inputType ? field.inputType : field.type;
			    var supported_fields = ["checkbox", "radio", "select", "text", "website", "textarea", "email", "hidden", "number", "phone", "multiselect", "post_title",
			                            "post_tags", "post_custom_field", "post_content", "post_excerpt"];

			    var index = jQuery.inArray(inputType, supported_fields);

			    return index >= 0;
			}

        </script>

        <?php

    }

    public static function select_twocheckout_form(){

        check_ajax_referer("gf_select_twocheckout_form", "gf_select_twocheckout_form");

        $type = $_POST["type"];
        $form_id =  intval($_POST["form_id"]);
        $setting_id =  intval($_POST["setting_id"]);

        //fields meta
        $form = RGFormsModel::get_form_meta($form_id);

        $customer_fields = self::get_customer_information($form);
        $recurring_amount_fields = self::get_product_options($form, "");

        die("EndSelectForm(" . GFCommon::json_encode($form) . ", '" . str_replace("'", "\'", $customer_fields) . "', '" . str_replace("'", "\'", $recurring_amount_fields) . "');");
    }

    public static function add_permissions(){
        global $wp_roles;
        $wp_roles->add_cap("administrator", "gravityforms_twocheckout");
        $wp_roles->add_cap("administrator", "gravityforms_twocheckout_uninstall");
		
    }

    //Target of Member plugin filter. Provides the plugin with Gravity Forms lists of capabilities
    public static function members_get_capabilities( $caps ) {
        return array_merge($caps, array("gravityforms_twocheckout", "gravityforms_twocheckout_uninstall"));
    }

    public static function get_active_config($form){

        require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

        $configs = GFTWOCHECKOUTData::get_feed_by_form($form["id"], true);
        if(!$configs)
            return false;

        foreach($configs as $config){
            if(self::has_twocheckout_condition($form, $config))
                return $config;
        }

        return false;
    }

    public static function send_to_twocheckout($confirmation, $form, $entry, $ajax){

        // ignore requests that are not the current form's submissions
        if(RGForms::post("gform_submit") != $form["id"])
        {
            return $confirmation;
		}

        //$config = self::get_active_config($form);
        $config = GFTWOCHECKOUTData::get_feed_by_form($form["id"]);

        if(!$config)
        {
            self::log_debug("NOT sending to 2Checkout: No 2Checkout setup was located for form_id = {$form['id']}.");
            return $confirmation;
		}else{
            $config = $config[0]; //using first twocheckout feed (only one twocheckout feed per form is supported)
		}

        // updating entry meta with current feed id
        gform_update_meta($entry["id"], "twocheckout_feed_id", $config["id"]);

        // updating entry meta with current payment gateway
        gform_update_meta($entry["id"], "payment_gateway", "twocheckout");

        //updating lead's payment_status to Processing
        RGFormsModel::update_lead_property($entry["id"], "payment_status", 'Processing');

        $settings = get_option("gf_twocheckout_settings");
		$seller_id = rgar($settings,"seller_id");
		$secret_word = rgar($settings,"secret_word");  
		$mode = rgar($settings,"mode");  
		
        $testmode = $mode == "production" ? 'N' : 'Y'; 
		
        $tangible = $config["meta"]["product_type"] == "tangible" ? "Y" : "N";
		
        //Current Currency
        $currency = GFCommon::get_currency();
		
        $invoice_id = apply_filters("gform_twocheckout_invoice", "", $form, $entry);
	
		if(empty($invoice_id)){
			$invoice_id = $entry['id'];			
		}
		
        //Customer fields
        $customer = self::customer_query_string($config, $entry);
		
		$ship_customer = array();
		
		if( $tangible == 'Y' ){
			$name = '';
	        foreach( $customer as $k=>$v){
				if($k == "first_name"){
					$first_name = $v;
				}else if($k == "last_name"){
					$last_name = $v;
				}else{
	                $ship_customer['ship_'.$k] = $v;	
				}					
	        }
			$ship_customer['ship_name'] = $first_name.' '.$last_name;			
		}
		
        $custom_field = $entry["id"] . "|" . wp_hash($entry["id"]);
		
        $return_url = self::return_url($form["id"], $entry["id"]);

        //Cancel URL
        $cancel_url = !empty($config["meta"]["cancel_url"]) ? $config["meta"]["cancel_url"] : "";

        //URL that will listen to notifications from WorldPay
        $ipn_url = get_bloginfo("url") . "/?page=gf_twocheckout_ipn";
		
        switch($config["meta"]["type"]){
            case "product" :
                $item = self::get_product_query_string($form, $entry, $item, $tangible);
            break;

            case "donation" :
                $item = self::get_donation_query_string($form, $entry, $item, $tangible);
            break;
        }
		
		$twocheckout_args = array_merge(array(
											'mode' => '2CO',
											'fixed' => 'Y',
			                				'sid' => $seller_id,
											'demo' => $testmode,
											'x_receipt_link_url' => $return_url,
											'return_url' => $cancel_url,
											'id_type' => 1,
											'cart_order_id'	=> $invoice_id,
											'merchant_order_id'	=> $invoice_id,
											'total' => GFCommon::get_order_total($form, $entry)
			            				), $customer, $ship_customer, $item);
		
		$req = '';
		foreach($twocheckout_args as $k => $v){
			$req .= $k.'='.urldecode($v).'&';
		}
		
		$url = 'https://www.2checkout.com/checkout/purchase?'.$req;
		
       	self::log_debug("Sending to 2Checkout: {$url}");
		
        if(headers_sent() || $ajax){
            $confirmation = "<script>function gformRedirect(){document.location.href='$url';}";
            if(!$ajax)
                $confirmation .="gformRedirect();";
            $confirmation .="</script>";
        }
        else{
            $confirmation = array("redirect" => $url);
        }

        return $confirmation;
    }

    public static function has_twocheckout_condition($form, $config) {

        $config = $config["meta"];

        $operator = isset($config["twocheckout_conditional_operator"]) ? $config["twocheckout_conditional_operator"] : "";
        $field = RGFormsModel::get_field($form, $config["twocheckout_conditional_field_id"]);

        if(empty($field) || !$config["twocheckout_conditional_enabled"])
            return true;

        // if conditional is enabled, but the field is hidden, ignore conditional
        $is_visible = !RGFormsModel::is_field_hidden($form, $field, array());

        $field_value = RGFormsModel::get_field_value($field, array());

        $is_value_match = RGFormsModel::is_value_match($field_value, $config["twocheckout_conditional_value"], $operator);
        $go_to_twocheckout = $is_value_match && $is_visible;

        return $go_to_twocheckout;
    }

    public static function get_config($form_id){
        if(!class_exists("GFTWOCHECKOUTData"))
            require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

        //Getting twocheckout settings associated with this transaction
        $config = GFTWOCHECKOUTData::get_feed_by_form($form_id);

        //Ignore IPN messages from forms that are no longer configured with the 2Checkout add-on
        if(!$config)
            return false;

        return $config[0]; //only one feed per form is supported (left for backwards compatibility)
    }

    public static function get_config_by_entry($entry) {

        if(!class_exists("GFTWOCHECKOUTData"))
            require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

        $feed_id = gform_get_meta($entry["id"], "twocheckout_feed_id");
        $feed = GFTWOCHECKOUTData::get_feed($feed_id);

        return !empty($feed) ? $feed : false;
    }

    public static function maybe_thankyou_page(){

        if(!self::is_gravityforms_supported())
            return;
		
        if($str = RGForms::get("gf_twocheckout_return"))
        {
            $str = base64_decode($str);

            parse_str($str, $query);
            if(wp_hash("ids=" . $query["ids"]) == $query["hash"]){
			
		        $settings = get_option("gf_twocheckout_settings");
				$seller_id = rgar($settings,"seller_id");
				$secret_word = rgar($settings,"secret_word");
				$mode = rgar($settings,"mode");
				
				if($_REQUEST["credit_card_processed"] == 'Y'){
					$status = 'completed';
				}else{
					$status = 'failed';
				}
				
				// GET THE RETURN FROM 2Checkout
				$RefNr = $_REQUEST["merchant_order_id"];
				$order_number = $_REQUEST["order_number"];
				$total = $_REQUEST["total"];
				$twocheckoutMD5 = $_REQUEST["key"];
				
				// Calculate our specific MD5Hash so we can validate it with the one sent from 2Checkout
				// If this is a test purchase we need to change the order number to 1
				if ( $mode == 'test' ):
					$string_to_hash = $secret_word . $seller_id . "1" . $_REQUEST["total"];		
				else :
					$string_to_hash = $secret_word . $seller_id . $_REQUEST["order_number"] . $_REQUEST["total"];		
				endif;
					
				$check_key = strtoupper(md5($string_to_hash));

				// Put the variables returned from twocheckout in an array so we can pass them on 
				// to the successful_request function.
				$trvalue = array(
					"check_key" 		=> 	$check_key,
					"RefNr" 			=> $RefNr,
					"sale_id" 			=> $order_number,
					"total" 			=> $total,
					"twocheckoutMD5" 	=> $twocheckoutMD5
				);
				
				if ( isset($trvalue['check_key']) && $check_key == $twocheckoutMD5 ){
					
					$entry_id = $trvalue['RefNr'];
					$entry = RGFormsModel::get_lead($entry_id);
					
					// config ID is stored in entry via send_to_twocheckout() function
					$config = self::get_config_by_entry($entry);
					
					//Ignore IPN messages from forms that are no longer configured with the 2Checkout add-on
					if(!$config){
						self::log_error("Form no longer is configured with 2Checkout Addon. Form ID: {$entry["form_id"]}. Aborting.");
					    return;
					}
					
					//Ignore orphan IPN messages (ones without an entry)
					if(!$entry){
						self::log_error("Entry could not be found. Entry ID: {$entry_id}. Aborting.");
					    return;
					}
					
					self::log_debug("Entry has been found." . print_r($entry, true));
							
					self::log_debug("Form {$entry["form_id"]} is properly configured.");
					
					//Pre IPN processing filter. Allows users to cancel IPN processing
					$cancel = apply_filters("gform_twocheckout_pre_ipn", false, $_REQUEST, $entry, $config);
							
					if(!$cancel) {
						self::log_debug("Setting payment status...");
		    			self::set_payment_status($config, $entry, $status, $order_number, $order_number, $total );
					}else{
					 	self::log_debug("IPN processing cancelled by the gform_twocheckout_pre_ipn filter. Aborting.");
					}
				}
					
                list($form_id, $lead_id) = explode("|", $query["ids"]);

                $form = RGFormsModel::get_form_meta($form_id);
                $lead = RGFormsModel::get_lead($lead_id);

                if(!class_exists("GFFormDisplay"))
                    require_once(GFCommon::get_base_path() . "/form_display.php");

                $confirmation = GFFormDisplay::handle_confirmation($form, $lead, false);

                if(is_array($confirmation) && isset($confirmation["redirect"])){
                    header("Location: {$confirmation["redirect"]}");
                    exit;
                }

                GFFormDisplay::$submission[$form_id] = array("is_confirmation" => true, "confirmation_message" => $confirmation, "form" => $form, "lead" => $lead);
            }
        }
    }

    public static function process_ipn($wp){

        if(!self::is_gravityforms_supported())
           return;

        //Ignore requests that are not IPN
        if(RGForms::get("page") != "gf_twocheckout_ipn")
            return;
		
        $settings = get_option("gf_twocheckout_settings");
		$seller_id = rgar($settings,"seller_id");
		$secret_word = rgar($settings,"secret_word");   
		
		self::log_debug("IPN request received. Starting to process...");
		
		// GET THE RETURN FROM 2Checkout
		$RefNr = $_REQUEST["vendor_order_id"];
		$sale_id = $_REQUEST["sale_id"];
		$invoice_id = $_REQUEST["invoice_id"];
		$twocheckoutMD5 = $_REQUEST["md5_hash"];
		$vendor_id = $_REQUEST["vendor_id"];
					
		// Calculate our specific MD5Hash so we can validate it with the one sent from 2Checkout
		$string_to_hash = $sale_id . $seller_id . $invoice_id . $secret_word;		
		$check_key = strtoupper(md5($string_to_hash));
		
		// Put the variables returned from twocheckout in an array so we can pass them on 
		// to the successful_request function.
		$twocheckout_return_values = array(
			"check_key" 		=> $check_key,
			"RefNr" 			=> $RefNr,
			"invoice_id"		=> $invoice_id,
			"sale_id" 			=> $sale_id,
			"vendor_id" 		=> $vendor_id,
			"twocheckoutMD5" 	=> $twocheckoutMD5
		);
					
		if ( isset($twocheckout_return_values['check_key']) && $check_key == $twocheckoutMD5 ){
			self::log_error("Hash Keys don't match.");
		    return;
		}
				
		if($_REQUEST["invoice_status"] == 'approved'){
			$status = 'completed';
		}if($_REQUEST["invoice_status"] == 'pending'){
			$status = 'pending';
		}if($_REQUEST["invoice_status"] == 'deposited'){
			$status = 'completed';
		}else{
			$status = 'failed';
		}
		
		$entry_id = $twocheckout_return_values['RefNr'];
		$entry = RGFormsModel::get_lead($entry_id);
		
		// config ID is stored in entry via send_to_twocheckout() function
		$config = self::get_config_by_entry($entry);
		
		//Ignore IPN messages from forms that are no longer configured with the 2Checkout add-on
		if(!$config){
			self::log_error("Form no longer is configured with 2Checkout Addon. Form ID: {$entry["form_id"]}. Aborting.");
		    return;
		}
		
		//Ignore orphan IPN messages (ones without an entry)
		if(!$entry){
			self::log_error("Entry could not be found. Entry ID: {$entry_id}. Aborting.");
		    return;
		}
		
		self::log_debug("Entry has been found." . print_r($entry, true));
				
		self::log_debug("Form {$entry["form_id"]} is properly configured.");
				
		//Pre IPN processing filter. Allows users to cancel IPN processing
		$cancel = apply_filters("gform_twocheckout_pre_ipn", false, $_REQUEST, $entry, $config);
				
		if(!$cancel) {
			self::log_debug("Setting payment status...");
		    self::set_payment_status($config, $entry, $status, $sale_id, $sale_id, $_REQUEST['invoice_list_amount'] );
		}else{
		 	self::log_debug("IPN processing cancelled by the gform_twocheckout_pre_ipn filter. Aborting.");
		}
		
		self::log_debug("Before gform_twocheckout_post_ipn.");
		//Post IPN processing action
		do_action("gform_twocheckout_post_ipn", $_POST, $entry, $config, $cancel);
		
		self::log_debug("IPN processing complete.");
	
    }

    public static function set_payment_status($config, $entry, $status, $transaction_id, $parent_transaction_id, $amount){
        global $current_user;
		
        $user_id = 0;
        $user_name = "System";
        if($current_user && $user_data = get_userdata($current_user->ID)){
            $user_id = $current_user->ID;
            $user_name = $user_data->display_name;
        }
        self::log_debug("Payment status: {$status} - Transaction ID: {$transaction_id} - Parent Transaction: {$parent_transaction_id} - Amount: {$amount}");
        self::log_debug("Entry: " . print_r($entry, true));

		//handles products and donation
        switch(strtolower($status)){
        	case "completed" :
            	self::log_debug("Processing a completed payment");
                if($entry["payment_status"] != "Approved"){
                	if(self::is_valid_initial_payment_amount($config, $entry, $amount)){
                    	self::log_debug("Entry is not already approved. Proceeding...");
                        $entry["payment_status"] = "Approved";
                        $entry["payment_amount"] = $amount;
                        $entry["payment_date"] = gmdate("y-m-d H:i:s");
                        $entry["transaction_id"] = $transaction_id;
                        $entry["transaction_type"] = 1; //payment

						if(!$entry["is_fulfilled"]){
                            self::log_debug("Payment has been made. Fulfilling order.");
                            self::fulfill_order($entry, $transaction_id, $amount);
                            self::log_debug("Order has been fulfilled");
                        	$entry["is_fulfilled"] = true;
                        }
						
                        self::log_debug("Updating entry.");
                        RGFormsModel::update_lead($entry);
                        self::log_debug("Adding note.");
                    	RGFormsModel::add_note($entry["id"], $user_id, $user_name, sprintf(__("Payment has been approved. Amount: %s. Transaction Id: %s", "gravityforms"), GFCommon::to_money($entry["payment_amount"], $entry["currency"]), $transaction_id));
					
					}else{
						self::log_debug("Payment amount does not match product price. Entry will not be marked as Approved.");
                        RGFormsModel::add_note($entry["id"], $user_id, $user_name, sprintf(__("Payment amount (%s) does not match product price. Entry will not be marked as Approved. Transaction Id: %s", "gravityforms"), GFCommon::to_money($amount, $entry["currency"]), $transaction_id));
					}
				}
                self::log_debug("Inserting transaction.");
                GFTWOCHECKOUTData::insert_transaction($entry["id"], "payment", $transaction_id, $parent_transaction_id, $amount);
				
			break;

            case "failed" :
            	self::log_debug("Processed a Failed request.");
                if($entry["payment_status"] != "Failed"){
                	if($entry["transaction_type"] == 1){
                    	$entry["payment_status"] = "Failed";
                        self::log_debug("Setting entry as Failed.");
                        RGFormsModel::update_lead($entry);
					}
                    RGFormsModel::add_note($entry["id"], $user_id, $user_name, sprintf(__("Payment has Failed. Failed payments occur when they are made via your customer's bank account and could not be completed. Transaction Id: %s", "gravityforms"), $transaction_id));
			    }

            	GFTWOCHECKOUTData::insert_transaction($entry["id"], "failed", $transaction_id, $parent_transaction_id, $amount);
			break;

		}
				
        self::log_debug("Before gform_post_payment_status.");
        do_action("gform_post_payment_status", $config, $entry, $status, $transaction_id, $amount);
		
		return $url;
    }

    public static function fulfill_order(&$entry, $transaction_id, $amount){

        $config = self::get_config_by_entry($entry);
        if(!$config){
            self::log_error("Order can't be fulfilled because feed wasn't found for form: {$entry["form_id"]}");
            return;
        }

        $form = RGFormsModel::get_form_meta($entry["form_id"]);
        if($config["meta"]["delay_post"]){
            self::log_debug("Creating post.");
            RGFormsModel::create_post($form, $entry);
        }

        if(isset($config["meta"]["delay_notifications"])){
            //sending delayed notifications
            GFCommon::send_notifications($config["meta"]["selected_notifications"], $form, $entry, true, "form_submission");

        }
        else{

            //sending notifications using the legacy structure
            if($config["meta"]["delay_notification"]){
               self::log_debug("Sending admin notification.");
               GFCommon::send_admin_notification($form, $entry);
            }

            if($config["meta"]["delay_autoresponder"]){
               self::log_debug("Sending user notification.");
               GFCommon::send_user_notification($form, $entry);
            }
        }

        self::log_debug("Before gform_twocheckout_fulfillment.");
        do_action("gform_twocheckout_fulfillment", $entry, $config, $transaction_id, $amount);
    }

    private static function customer_query_string($config, $lead ){
        $fields = array();
        foreach(self::get_customer_fields() as $field){
            $field_id = $config["meta"]["customer_fields"][$field["name"]];
            $value = rgar($lead,$field_id);

            if($field["name"] == "country")
                $value = GFCommon::get_country_code($value);

                $fields[$field["name"]] = $value;
				
        }

        return $fields;
    }

    private static function is_valid_initial_payment_amount($config, $lead, $payment_amount){

        $form = RGFormsModel::get_form_meta($lead["form_id"]);
        $products = GFCommon::get_product_fields($form, $lead, true);

        $product_amount = 0;
        switch($config["meta"]["type"]){
            case "product" :
                $product_amount = GFCommon::get_order_total($form, $lead);
            break;

            case "donation" :
                $product_amount = self::get_donation_total($form, $lead);
            break;

        }

        //initial payment is valid if it is equal to or greater than product/subscription amount
        if(floatval($payment_amount) >= floatval($product_amount)){
            return true;
        }

        return false;

    }
	
    private static function get_product_query_string($form, $entry, $item, $tangible){
        $fields = "";
        $products = GFCommon::get_product_fields($form, $entry, true);
        $product_index = 1;
        $total = 0;
        $discount = 0;
		$item = array();

        foreach($products["products"] as $product){
            $option_fields = "";
            $price = GFCommon::to_number($product["price"]);
            if(is_array(rgar($product,"options"))){
                $option_index = 1;
                foreach($product["options"] as $option){
                    $field_label = $option["field_label"];
                    $option_name = $option["option_name"];
                    $option_fields .= "&on{$option_index}_{$product_index}={$field_label}&os{$option_index}_{$product_index}={$option_name}";
                    $price += GFCommon::to_number($option["price"]);
                    $option_index++;
                }
            }
			
            if($price > 0)
            {
			
				$item['li_'.$product_index.'_type'] = 'product';
				$item['li_'.$product_index.'_name'] = $product["name"];
				$item['li_'.$product_index.'_product_id'] = '';
				$item['li_'.$product_index.'_quantity'] = $product['quantity'];
				$item['li_'.$product_index.'_price'] = $price;
				$item['li_'.$product_index.'_tangible'] = $tangible;
								
            }
            else{
				$item['li_'.$product_index.'_type'] = 'coupon';
				$item['li_'.$product_index.'_name'] = __('Discount', 'woothemes');
				$item['li_'.$product_index.'_product_id'] = '';
				$item['li_'.$product_index.'_quantity'] = 1;
				$item['li_'.$product_index.'_price'] = abs($price);
				$item['li_'.$product_index.'_tangible'] = 'N';
				
            }
			
            $product_index++;

        }
		
		if(!empty($products["shipping"]["price"])){
			$product_index++;
			$item['li_'.$product_index.'_type'] = 'shipping';
			$item['li_'.$product_index.'_name'] = __('Shipping cost', 'woothemes');
			$item['li_'.$product_index.'_product_id'] = '';
			$item['li_'.$product_index.'_quantity'] = 1;
			$item['li_'.$product_index.'_price'] = $products["shipping"]["price"];
			$item['li_'.$product_index.'_tangible'] = 'Y';
		}
		
        return $item;
    }

    private static function get_donation_query_string($form, $entry, $item, $tangible){
        $fields = "";

        //getting all donation fields
        $donations = GFCommon::get_fields_by_type($form, array("donation"));
        $total = 0;
        $purpose = "";
		$item = array();
        foreach($donations as $donation){
            $value = RGFormsModel::get_lead_field_value($entry, $donation);
            list($name, $price) = explode("|", $value);
            if(empty($price)){
                $price = $name;
                $name = $donation["label"];
            }
            $purpose .= $name . ", ";
            $price = GFCommon::to_number($price);
            $total += $price;
        }

        //using product fields for donation if there aren't any legacy donation fields in the form
        if($total == 0){
            //getting all product fields
            $products = GFCommon::get_product_fields($form, $entry, true);
            foreach($products["products"] as $product){
                $options = "";
                if(is_array($product["options"]) && !empty($product["options"])){
                    $options = " (";
                    foreach($product["options"] as $option){
                        $options .= $option["option_name"] . ", ";
                    }
                    $options = substr($options, 0, strlen($options)-2) . ")";
                }
                $quantity = GFCommon::to_number($product["quantity"]);
                $quantity_label = $quantity > 1 ? $quantity . " " : "";
                $purpose .= $quantity_label . $product["name"] . $options . ", ";
            }

            $total = GFCommon::get_order_total($form, $entry);
        }

        if(!empty($purpose))
            $purpose = substr($purpose, 0, strlen($purpose)-2);

        //truncating to maximum length allowed by 2Checkout
        if(strlen($purpose) > 127)
            $purpose = substr($purpose, 0, 124) . "...";
				
			
		$item['li_1_type'] = 'product';
		$item['li_1_name'] = $purpose;
		$item['li_1_product_id'] = '';
		$item['li_1_quantity'] = 1;
		$item['li_1_price'] = $total;
		$item['li_1_tangible'] = $tangible;

        return $item;
    }

    private static function get_donation_total($form, $entry){
        $fields = "";

        //getting all donation fields
        $donations = GFCommon::get_fields_by_type($form, array("donation"));
        $total = 0;
        $purpose = "";
        foreach($donations as $donation){
            $value = RGFormsModel::get_lead_field_value($entry, $donation);
            list($name, $price) = explode("|", $value);
            if(empty($price)){
                $price = $name;
                $name = $donation["label"];
            }
            $purpose .= $name . ", ";
            $price = GFCommon::to_number($price);
            $total += $price;
        }

        //using product fields for donation if there aren't any legacy donation fields in the form
        if($total == 0){
            //getting all product fields
            $products = GFCommon::get_product_fields($form, $entry, true);
            foreach($products["products"] as $product){
                $options = "";
                if(is_array($product["options"]) && !empty($product["options"])){
                    $options = " (";
                    foreach($product["options"] as $option){
                        $options .= $option["option_name"] . ", ";
                    }
                    $options = substr($options, 0, strlen($options)-2) . ")";
                }
                $quantity = GFCommon::to_number($product["quantity"]);
                $quantity_label = $quantity > 1 ? $quantity . " " : "";
                $purpose .= $quantity_label . $product["name"] . $options . ", ";
            }

            $total = GFCommon::get_order_total($form, $entry);
        }

        if(!empty($purpose))
            $purpose = substr($purpose, 0, strlen($purpose)-2);

        //truncating to maximum length allowed by 2Checkout
        if(strlen($purpose) > 127)
            $purpose = substr($purpose, 0, 124) . "...";
				
		//$add_item( $purpose, $total, 1, '' );

        return $total > 0 ? $total : '0.00';
    }

    public static function uninstall(){

        //loading data lib
        require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");

        if(!GFTWOCHECKOUT::has_access("gravityforms_twocheckout_uninstall"))
            die(__("You don't have adequate permission to uninstall the 2Checkout Add-On.", "gravityformstwocheckout"));

        //droping all tables
        GFTWOCHECKOUTData::drop_tables();

        //removing options
        delete_option("gf_twocheckout_site_name");
        delete_option("gf_twocheckout_auth_token");
        delete_option("gf_twocheckout_version");

        //Deactivating plugin
        $plugin = GF_TWOCHECKOUT_PLUGIN;
        deactivate_plugins($plugin);
        update_option('recently_activated', array($plugin => time()) + (array)get_option('recently_activated'));
    }

    private static function is_gravityforms_installed(){
        return class_exists("RGForms");
    }

    private static function is_gravityforms_supported(){
        if(class_exists("GFCommon")){
            $is_correct_version = version_compare(GFCommon::$version, self::$min_gravityforms_version, ">=");
            return $is_correct_version;
        }
        else{
            return false;
        }
    }

    protected static function has_access($required_permission){
        $has_members_plugin = function_exists('members_get_capabilities');
        $has_access = $has_members_plugin ? current_user_can($required_permission) : current_user_can("level_7");
        if($has_access)
            return $has_members_plugin ? $required_permission : "level_7";
        else
            return false;
    }

    private static function get_customer_information($form, $config=null){

        //getting list of all fields for the selected form
        $form_fields = self::get_form_fields($form);

        $str = "<table cellpadding='0' cellspacing='0'><tr><td class='twocheckout_col_heading'>" . __("2Checkout Fields", "gravityformstwocheckout") . "</td><td class='twocheckout_col_heading'>" . __("Form Fields", "gravityformstwocheckout") . "</td></tr>";
        $customer_fields = self::get_customer_fields();
        foreach($customer_fields as $field){
            $selected_field = $config ? $config["meta"]["customer_fields"][$field["name"]] : "";
            $str .= "<tr><td class='twocheckout_field_cell'>" . $field["label"]  . "</td><td class='twocheckout_field_cell'>" . self::get_mapped_field_list($field["name"], $selected_field, $form_fields) . "</td></tr>";
        }
        $str .= "</table>";

        return $str;
    }

    private static function get_customer_fields(){
        return array(
						array("name" => "first_name" , "label" => "First Name"),
						array("name" => "last_name" , "label" =>"Last Name"),
						array("name" => "email" , "label" =>"Email"),
						array("name" => "street_address" , "label" =>"Address"), 
						array("name" => "street_address2" , "label" =>"Address 2"),
						array("name" => "city" , "label" =>"City"), 
						array("name" => "state" , "label" =>"State"), 
						array("name" => "zip" , "label" =>"Zip"), 
						array("name" => "country" , "label" =>"Country")
					);
    }

    private static function get_mapped_field_list($variable_name, $selected_field, $fields){
        $field_name = "twocheckout_customer_field_" . $variable_name;
        $str = "<select name='$field_name' id='$field_name'><option value=''></option>";
        foreach($fields as $field){
            $field_id = $field[0];
            $field_label = esc_html(GFCommon::truncate_middle($field[1], 40));

            $selected = $field_id == $selected_field ? "selected='selected'" : "";
            $str .= "<option value='" . $field_id . "' ". $selected . ">" . $field_label . "</option>";
        }
        $str .= "</select>";
        return $str;
    }

    private static function get_product_options($form, $selected_field){
        $str = "<option value=''>" . __("Select a field", "gravityformstwocheckout") ."</option>";
        $fields = GFCommon::get_fields_by_type($form, array("product"));

        foreach($fields as $field){
            $field_id = $field["id"];
            $field_label = RGFormsModel::get_label($field);

            $selected = $field_id == $selected_field ? "selected='selected'" : "";
            $str .= "<option value='" . $field_id . "' ". $selected . ">" . $field_label . "</option>";
        }

        $selected = $selected_field == 'all' ? "selected='selected'" : "";
        $str .= "<option value='all' " . $selected . ">" . __("Form Total", "gravityformstwocheckout") ."</option>";

        return $str;
    }

    private static function get_form_fields($form){
        $fields = array();

        if(is_array($form["fields"])){
            foreach($form["fields"] as $field){
                if(isset($field["inputs"]) && is_array($field["inputs"])){

                    foreach($field["inputs"] as $input)
                        $fields[] =  array($input["id"], GFCommon::get_label($field, $input["id"]));
                }
                else if(!rgar($field, 'displayOnly')){
                    $fields[] =  array($field["id"], GFCommon::get_label($field));
                }
            }
        }
        return $fields;
    }

    private static function return_url($form_id, $lead_id) {
        $pageURL = GFCommon::is_ssl() ? "https://" : "http://";

        if ($_SERVER["SERVER_PORT"] != "80")
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        else
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

        $ids_query = "ids={$form_id}|{$lead_id}";
        $ids_query .= "&hash=" . wp_hash($ids_query);

        return add_query_arg("gf_twocheckout_return", base64_encode($ids_query), $pageURL);
    }

    private static function is_twocheckout_page(){
        $current_page = trim(strtolower(RGForms::get("page")));
        return in_array($current_page, array("gf_twocheckout"));
    }

    public static function admin_edit_payment_status($payment_status, $form_id, $lead)
    {
		//allow the payment status to be edited when for twocheckout, not set to Approved, and not a subscription
		$payment_gateway = gform_get_meta($lead["id"], "payment_gateway");
		require_once(GF_TWOCHECKOUT_BASE_PATH . "/data.php");
		//get the transaction type out of the feed configuration, do not allow status to be changed when subscription
		$twocheckout_feed_id = gform_get_meta($lead["id"], "twocheckout_feed_id");
		$feed_config = GFTWOCHECKOUTData::get_feed($twocheckout_feed_id);
		$transaction_type = rgars($feed_config, "meta/type");
    	if ($payment_gateway <> "twocheckout" || strtolower(rgpost("save")) <> "edit" || $payment_status == "Approved" || $transaction_type == "subscription")
    		return $payment_status;

		//create drop down for payment status
		$payment_string = gform_tooltip("twocheckout_edit_payment_status","",true);
		$payment_string .= '<select id="payment_status" name="payment_status">';
		$payment_string .= '<option value="' . $payment_status . '" selected>' . $payment_status . '</option>';
		$payment_string .= '<option value="Approved">Approved</option>';
		$payment_string .= '</select>';
		return $payment_string;
    }
    public static function admin_edit_payment_status_details($form_id, $lead)
    {
		//check meta to see if this entry is twocheckout
		$payment_gateway = gform_get_meta($lead["id"], "payment_gateway");
		$form_action = strtolower(rgpost("save"));
		if ($payment_gateway <> "twocheckout" || $form_action <> "edit")
			return;

		//get data from entry to pre-populate fields
		$payment_amount = rgar($lead, "payment_amount");
		if (empty($payment_amount))
		{
			$form = RGFormsModel::get_form_meta($form_id);
			$payment_amount = GFCommon::get_order_total($form,$lead);
		}
	  	$transaction_id = rgar($lead, "transaction_id");
		$payment_date = rgar($lead, "payment_date");
		if (empty($payment_date))
		{
			$payment_date = gmdate("y-m-d H:i:s");
		}

		//display edit fields
		?>
		<div id="edit_payment_status_details" style="display:block">
			<table>
				<tr>
					<td colspan="2"><strong>Payment Information</strong></td>
				</tr>

				<tr>
					<td>Date:<?php gform_tooltip("twocheckout_edit_payment_date") ?></td>
					<td><input type="text" id="payment_date" name="payment_date" value="<?php echo $payment_date?>"></td>
				</tr>
				<tr>
					<td>Amount:<?php gform_tooltip("twocheckout_edit_payment_amount") ?></td>
					<td><input type="text" id="payment_amount" name="payment_amount" value="<?php echo $payment_amount?>"></td>
				</tr>
				<tr>
					<td nowrap>Transaction ID:<?php gform_tooltip("twocheckout_edit_payment_transaction_id") ?></td>
					<td><input type="text" id="twocheckout_transaction_id" name="twocheckout_transaction_id" value="<?php echo $transaction_id?>"></td>
				</tr>
			</table>
		</div>
		<?php
	}

	public static function admin_update_payment($form, $lead_id)
	{
		check_admin_referer('gforms_save_entry', 'gforms_save_entry');
		//update payment information in admin, need to use this function so the lead data is updated before displayed in the sidebar info section
		//check meta to see if this entry is twocheckout
		$payment_gateway = gform_get_meta($lead_id, "payment_gateway");
		$form_action = strtolower(rgpost("save"));
		if ($payment_gateway <> "twocheckout" || $form_action <> "update")
			return;
		//get lead
		$lead = RGFormsModel::get_lead($lead_id);
		//get payment fields to update
		$payment_status = rgpost("payment_status");
		//when updating, payment status may not be editable, if no value in post, set to lead payment status
		if (empty($payment_status))
		{
			$payment_status = $lead["payment_status"];
		}

		$payment_amount = rgpost("payment_amount");
		$payment_transaction = rgpost("twocheckout_transaction_id");
		$payment_date = rgpost("payment_date");
		if (empty($payment_date))
		{
			$payment_date = gmdate("y-m-d H:i:s");
		}
		else
		{
			//format date entered by user
			$payment_date = date("Y-m-d H:i:s", strtotime($payment_date));
		}

		global $current_user;
		$user_id = 0;
        $user_name = "System";
        if($current_user && $user_data = get_userdata($current_user->ID)){
            $user_id = $current_user->ID;
            $user_name = $user_data->display_name;
        }

		$lead["payment_status"] = $payment_status;
		$lead["payment_amount"] = $payment_amount;
		$lead["payment_date"] =   $payment_date;
		$lead["transaction_id"] = $payment_transaction;

		// if payment status does not equal approved or the lead has already been fulfilled, do not continue with fulfillment
        if($payment_status == 'Approved' && !$lead["is_fulfilled"])
        {
        	//call fulfill order, mark lead as fulfilled
        	self::fulfill_order($lead, $payment_transaction, $payment_amount);
        	$lead["is_fulfilled"] = true;
		}
		//update lead, add a note
		RGFormsModel::update_lead($lead);
		RGFormsModel::add_note($lead["id"], $user_id, $user_name, sprintf(__("Payment information was manually updated. Status: %s. Amount: %s. Transaction Id: %s. Date: %s", "gravityforms"), $lead["payment_status"], GFCommon::to_money($lead["payment_amount"], $lead["currency"]), $payment_transaction, $lead["payment_date"]));
	}

	function set_logging_supported($plugins)
	{
		$plugins[self::$slug] = "2Checkout";
		return $plugins;
	}

	private static function log_error($message){
		if(class_exists("GFLogging"))
		{
			GFLogging::include_logger();
			GFLogging::log_message(self::$slug, $message, KLogger::ERROR);
		}
	}

	private static function log_debug($message){
		if(class_exists("GFLogging"))
		{
			GFLogging::include_logger();
			GFLogging::log_message(self::$slug, $message, KLogger::DEBUG);
		}
	}
}

if(!function_exists("rgget")){
function rgget($name, $array=null){
    if(!isset($array))
        $array = $_GET;

    if(isset($array[$name]))
        return $array[$name];

    return "";
}
}

if(!function_exists("rgpost")){
function rgpost($name, $do_stripslashes=true){
    if(isset($_POST[$name]))
        return $do_stripslashes ? stripslashes_deep($_POST[$name]) : $_POST[$name];

    return "";
}
}

if(!function_exists("rgar")){
function rgar($array, $name){
    if(isset($array[$name]))
        return $array[$name];

    return '';
}
}

if(!function_exists("rgars")){
function rgars($array, $name){
    $names = explode("/", $name);
    $val = $array;
    foreach($names as $current_name){
        $val = rgar($val, $current_name);
    }
    return $val;
}
}

if(!function_exists("rgempty")){
function rgempty($name, $array = null){
    if(!$array)
        $array = $_POST;

    $val = rgget($name, $array);
    return empty($val);
}
}

if(!function_exists("rgblank")){
function rgblank($text){
    return empty($text) && strval($text) != "0";
}
}
