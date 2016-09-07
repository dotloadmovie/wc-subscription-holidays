<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.shiftinteraction.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/public
 * @author     Dave Tickle <info@shiftinteraction.com>
 */
class Woocommerce_Subscription_Holiday_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	private function get_subscription_details($order_id){

			$key = WC_Subscriptions_Manager::get_subscription_key($order_id);

			$user_id = WC_Subscriptions_Manager::get_user_id_from_subscription_key($key);

			$meta = get_user_meta($user_id, 'wcsh_resume_date');

			$output = array();

			$output['key'] = $key;
			$output['user_id'] = $user_id;

			if($meta && count($meta) > 0){

				$meta_elements = explode('||', $meta[0]);
				$output['date'] = date('d/m/Y', $meta_elements[0]);

			}

			return $output;

	}


	public function show_date_picker($subscription_id, $input_class = '', $button_class = '', $button_label = 'Submit'){

		$details = self::get_subscription_details($subscription_id);

		$output = '<div class="wcsh-container"><input type="text" class="wcsh-date '.$input_class.'" style="width:50%" placeholder="Choose date" value="'.$details['date'].'" /> <a href="'.plugin_dir_url( __FILE__ ) . 'api/wcsh_set_status.php'.'" data-key="'.$details['key'].'" data-id="'.$details['user_id'].'" class="wcsh-set-holiday-status '.$button_class.'">'.$button_label.'</a>';

		$output .= '<div class="status"></div></div>';

		return $output;

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Subscription_Holiday_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Subscription_Holiday_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-subscription-holiday-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Subscription_Holiday_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Subscription_Holiday_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-subscription-holiday-public.js', array( 'jquery' ), $this->version, false );

	}

}
