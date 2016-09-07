<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.shiftinteraction.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/admin
 * @author     Dave Tickle <info@shiftinteraction.com>
 */
class Woocommerce_Subscription_Holiday_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	public function add_plugin_admin_menu(){

		add_options_page( 'WC Subscriptions Holiday Settings', 'WooCommerce Subscription Holiday', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);

	}

	public function add_action_links( $links ) {
		
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}


	private function get_subscriptions(){

		$subscriptions = WC_Subscriptions_Manager::get_all_users_subscriptions();
		$subscriptions_populated = array();

		foreach($subscriptions as $subscription){

			$user_id = WC_Subscriptions_Manager::get_user_id_from_subscription_key(WC_Subscriptions_Manager::get_subscription_key($subscription['order_id']));

			$subscription['user'] = get_user_by('id', $user_id);
			$subscription['resume_date'] = get_user_meta($user_id, 'wcsh_resume_date');
			$subscription['sub_key'] = WC_Subscriptions_Manager::get_subscription_key($subscription['order_id']);

			array_push($subscriptions_populated, $subscription);

		}

		return $subscriptions_populated;


	}

	private function get_active_users(){

		$users = get_users(array('meta_key'=>'wcsh_resume_date'));

		$date = time();

		foreach($users as $user){

			$meta = get_user_meta($user->id, 'wcsh_resume_date');
			$meta_elements = explode("||", $meta[0]);

			if($date > (int)$meta_elements[0]){

				WC_Subscriptions_Manager::reactivate_subscription($user->id, $meta_elements[1]);

			}


		}

		return $users;

	}



	public function display_plugin_setup_page() {

		$subscriptions = $this->get_subscriptions();
		$api_path = plugin_dir_url( __FILE__ ) . 'api/wcsh_set_status.php';

		include_once( 'partials/woocommerce-subscription-holiday-admin-display.php' );
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-subscription-holiday-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 */
	public function enqueue_scripts() {

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-subscription-holiday-admin.js', array( 'jquery' ), $this->version, false );

	}

}
