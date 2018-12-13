<?php
/*
Plugin Name: Booster Plus for WooCommerce
Plugin URI: http://booster.io/plus/
Description: Unlock all Booster for WooCommerce features and supercharge your WooCommerce site even more.
Version: 1.1.0
Author: Algoritmika Ltd
Author URI: http://booster.io
Copyright: © 2016 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Jetpack_Plus' ) ) :

/**
 * Main WC_Jetpack_Plus Class
 *
 * @class   WC_Jetpack_Plus
 * @version 1.1.0
 */
final class WC_Jetpack_Plus {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.1.0
	 */
	public $version = '1.1.0';

	/**
	 * @var WC_Jetpack_Plus The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main WC_Jetpack_Plus Instance
	 *
	 * Ensures only one instance of WC_Jetpack_Plus is loaded or can be loaded.
	 *
	 * @static
	 * @see WCJP()
	 * @return WC_Jetpack_Plus - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WC_Jetpack_Plus Constructor.
	 *
	 * @access  public
	 * @version 1.1.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			if ( get_option( 'booster_plus_version', false ) !== $this->version ) {
				update_option( 'booster_plus_version', $this->version );
			}
		}
		add_filter( 'booster_get_message', array( $this, 'booster_get_message' ), 101 );
		add_filter( 'booster_get_option',  array( $this, 'booster_get_option' ), 101, 2 );
	}

	/**
	 * booster_get_option.
	 *
	 * @version 1.1.0
	 */
	public function booster_get_option( $value1, $value2 ) {
		return $value2;
	}

	/**
	 * booster_get_message.
	 *
	 * @version 1.1.0
	 */
	public function booster_get_message() {
		return '';
	}
}

endif;

if ( ! function_exists( 'WCJP' ) ) {
	/**
	 * Returns the main instance of WCJP to prevent the need to use globals.
	 *
	 * @return  WC_Jetpack_Plus
	 * @version 1.1.0
	 */
	function WCJP() {
		return WC_Jetpack_Plus::instance();
	}
}

WCJP();
