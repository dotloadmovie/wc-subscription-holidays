<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.shiftinteraction.com/
 * @since             1.0.0
 * @package           Woocommerce_Subscription_Holiday
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Subscription Holiday
 * Plugin URI:        http://www.shiftinteraction.com/plugins
 * Description:       Pause a WooCommerce subscription for a user defined period of time.
 * Version:           1.0.0
 * Author:            Dave Tickle
 * Author URI:        http://www.shiftinteraction.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-subscription-holiday
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-subscription-holiday-activator.php
 */
function activate_woocommerce_subscription_holiday() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-subscription-holiday-activator.php';
	Woocommerce_Subscription_Holiday_Activator::activate();

	add_log_entry('Activating plugin...');

	//Use wp_next_scheduled to check if the event is already scheduled
	$timestamp = wp_next_scheduled( 'wcsh_check_holidays' );

	//If $timestamp == false schedule daily backups since it hasn't been done previously
	if( $timestamp == false ){
		//Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
		wp_schedule_event( time(), 'hourly', 'wcsh_check_holidays' );
	}

}


//Hook our function , wi_create_backup(), into the action wi_create_daily_backup
add_action( 'wcsh_check_holidays', 'wcsh_check' );
function wcsh_check(){

	$users = get_users(array('meta_key'=>'wcsh_resume_date'));

	$date = time();

	foreach($users as $user){

		$meta = get_user_meta($user->id, 'wcsh_resume_date');
		$meta_elements = explode("||", $meta[0]);

		if($date > (int)$meta_elements[0]){


			WC_Subscriptions_Manager::reactivate_subscription($user->id, $meta_elements[1]);

			add_log_entry('Reactivating sub number '.$meta_elements[1]);
		}


	}

	add_log_entry('Finished checking holiday schedule');

}


add_action('woocommerce_subscription_status_active', 'handle_status_active');
function handle_status_active($subscription){

	$key = WC_Subscriptions_Manager::get_subscription_key($subscription->order->id);

	$user_id = WC_Subscriptions_Manager::get_user_id_from_subscription_key($key);

	delete_user_meta($user_id, 'wcsh_resume_date');

	add_log_entry('Clearing WCSH meta for user id '.$user_id);

}

add_action('woocommerce_subscription_status_cancel', 'handle_status_cancel');
function handle_status_cancel($subscription){

	$key = WC_Subscriptions_Manager::get_subscription_key($subscription->order->id);

	$user_id = WC_Subscriptions_Manager::get_user_id_from_subscription_key($key);

	delete_user_meta($user_id, 'wcsh_resume_date');

	add_log_entry('Clearing WCSH meta for user id '.$user_id);

}


function add_log_entry($entry){

	$file = fopen(plugin_dir_path( __FILE__ ) ."logs/testlog.txt", "a") or die("Unable to open file!");
	$txt = "$entry\n";
	fwrite($file, $txt);
	fclose($file);

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-subscription-holiday-deactivator.php
 */
function deactivate_woocommerce_subscription_holiday() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-subscription-holiday-deactivator.php';
	Woocommerce_Subscription_Holiday_Deactivator::deactivate();

	wp_clear_scheduled_hook("wcsh_check_holidays");

}

register_activation_hook( __FILE__, 'activate_woocommerce_subscription_holiday' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_subscription_holiday' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-subscription-holiday.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_subscription_holiday() {

	$plugin = new Woocommerce_Subscription_Holiday();
	$plugin->run();

}
run_woocommerce_subscription_holiday();
