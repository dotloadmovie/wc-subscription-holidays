<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.shiftinteraction.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/includes
 * @author     Dave Tickle <info@shiftinteraction.com>
 */
class Woocommerce_Subscription_Holiday_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-subscription-holiday',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
