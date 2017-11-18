<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Gaston
 * @author  Christian Hapgood <christian.hapgood@gmail.com>
 * @license GPL-2.0+
 * @link    TODO
 * @version 0.0.2
 */
class Gaston {

	/**
	* Refers to a single instance of this class.
	*
	* @var    object
	*/
	protected static $instance = null;

	/**
	* Refers to the slug of the plugin screen.
	*
	* @var    string
	*/
	protected $plugin_screen_slug = null;

	/**
	* Creates or returns an instance of this class.
	*
	* @since     0.0.2
	* @return    Gaston    A single instance of this class.
	*/
	public function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	* Initializes the plugin by setting localization, filters, and administration functions.
	*
	* @since    0.0.2
	*/
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		/*
		 * Add the options page and menu item.
		 * Uncomment the following line to enable the Settings Page for the plugin:
		 */
		//add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		/*
		 * Register admin styles and scripts
		 * If the Settings page has been activated using the above hook, the scripts and styles
		 * will only be loaded on the settings page. If not, they will be loaded for all
		 * admin pages.
		 *
		 * add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
		 * add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		 */

		// Register site stylesheets and JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		/*
		 *
		 * Define the custom functionality for your plugin. The first parameter of the
		 * add_action/add_filter calls are the hooks into which your code should fire.
		 *
		 */
		add_action( 'init', array( $this, 'include_acf' ) );
		add_action( 'init', array( $this, 'register_gaston_post_types' ) );
		add_action( 'acf/init', array( $this, 'initialize_acf_config' ) );
		add_action( 'rest_api_init', array( $this, 'initialize_rest_routes') );

		// Add plugin admin menu
		add_action('admin_menu', array( $this, 'add_plugin_admin_menu') );
	}

	public function include_acf() {

		if ( !class_exists('acf') ) { // if ACF plugin does not currently exist

			/** Start: Customize ACF path */
			add_filter('acf/settings/path', 'gaston_acf_settings_path');
			function gaston_acf_settings_path( $path ) {
				$path = plugin_dir_path( __FILE__ ) . 'plugins/acf/';
				return $path;
			}
			/** End: Customize ACF path */

			/** Start: Customize ACF dir */
			add_filter('acf/settings/dir', 'gaston_acf_settings_dir');
			function gaston_acf_settings_dir( $path ) {
				$path = plugin_dir_url( __FILE__ ) . 'plugins/acf/';
				return $dir;
			}
			/** End: Customize ACF path */

			/** Start: Hide ACF field group menu item */
			add_filter('acf/settings/show_admin', '__return_false');
			/** End: Hide ACF field group menu item */

			/** Start: Include ACF */
			include_once( plugin_dir_path( __FILE__ ) . 'plugins/acf/acf.php' );
			/** End: Include ACF */

			/** Start: Create JSON save point */
			add_filter('acf/settings/save_json', 'gaston_acf_json_save_point');
			function gaston_acf_json_save_point( $path ) {
				$path = plugin_dir_path( __FILE__ ) . 'plugins/acf-json/';
				return $path;
			}
			/** End: Create JSON save point */

			/** Start: Create JSON load point */
			add_filter('acf/settings/load_json', 'gaston_acf_json_load_point');
			/** End: Create JSON load point */

			/** Start: Stop ACF upgrade notifications */
			add_filter( 'site_transient_update_plugins', 'gaston_stop_acf_update_notifications', 11 );
			function gaston_stop_acf_update_notifications( $value ) {
				unset( $value->response[ plugin_dir_path( __FILE__ ) . 'plugins/acf/acf.php' ] );
				return $value;
			}
			/** End: Stop ACF upgrade notifications */
		} else { // else ACF Pro plugin does exist
			/** Start: Create JSON load point */
			add_filter('acf/settings/load_json', 'gaston_acf_json_load_point');
			/** End: Create JSON load point */
		} // end-if ACF Pro plugin does not currently exist
		/** End: Detect ACF Pro plugin. Include if not present. */

		/** Start: Function to create JSON load point */
		function gaston_acf_json_load_point( $paths ) {
			$paths[] = plugin_dir_path( __FILE__ ) . 'plugins/acf-json-load';
			return $paths;
		}
		/** End: Function to create JSON load point */
	}

	// include functions
  public function register_gaston_post_types() {
    include 'functions/custom-post-types.php';
    include 'functions/rest-api.php';
    include 'functions/register-taxonomy.php';
	}

	// include functions
  public function initialize_acf_config() {
    include 'functions/acf-config.php';
  }

	public function initialize_rest_routes() {
    $problem_controller = new GASTON_ROUTE();
    $problem_controller->register_routes();
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	 public static function activate( $network_wide ) {
		// TODO: Define activation functionality here

		// Add 'climb' CPT with Area Taxonomy - Tags for filtering
		// Add ACF meta boxes for fields w/ conditionals for grades/other info
		// Enable JSON API for post type - Custom endpoint with info

		// Add Community Member user role
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 * @since    0.0.2
	 */
	 public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here

		// Remove CPT

		// Remove user Role
	}

	/**
	 * Loads the plugin text domain for translation
	 */
	public function load_plugin_textdomain() {

		$domain = 'gaston-locale';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	}

	/**
	 * Registers and enqueues admin-specific styles.
	 *
	 * @since    0.0.2
	 */
	public function register_admin_styles() {

		/*
		 * Check if the plugin has registered a settings page
		 * and if it has, make sure only to enqueue the scripts on the relevant screens
		 */

		if ( isset( $this->plugin_screen_slug ) ) {

			/*
			 * Check if current screen is the admin page for this plugin
			 * Don't enqueue stylesheet or JavaScript if it's not
			 */

			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_slug ) {
				wp_enqueue_style( 'gaston-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), GASTON_VERSION );
			}

		}

	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 *
	 * @since    0.0.2
	 */
	public function register_admin_scripts() {

		/*
		 * Check if the plugin has registered a settings page
		 * and if it has, make sure only to enqueue the scripts on the relevant screens
		 */

		if ( isset( $this->plugin_screen_slug ) ) {

			/*
			 * Check if current screen is the admin page for this plugin
			 * Don't enqueue stylesheet or JavaScript if it's not
			 */

			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_slug ) {
				wp_enqueue_script( 'gaston-admin-script', plugins_url('js/admin.js', __FILE__), array( 'jquery' ), GASTON_VERSION );
			}

		}

	}

	/**
	 * Registers and enqueues public-facing stylesheets.
	 *
	 * @since    0.0.2
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'gaston-plugin-styles', plugins_url( 'css/display.css', __FILE__ ), GASTON_VERSION );
	}

	/**
	 * Registers and enqueues public-facing JavaScript.
	 *
	 * @since    0.0.2
	 */
	public function register_plugin_scripts() {
		wp_enqueue_script( 'gaston-plugin-script', plugins_url( 'js/display.js', __FILE__ ), array( 'jquery' ), GASTON_VERSION );
	}

	/**
	 * Registers the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.0.2
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'gaston' to the name of your plugin
		 */
		$this->plugin_screen_slug = add_plugins_page(
			__('Gaston Settings', 'gaston-locale'),
			__('Gaston Settings', 'gaston-locale'),
			'read',
			'gaston',
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Renders the options page for this plugin.
	 *
	 * @since    0.0.2
	 */
	public function display_plugin_admin_page() {
		include_once('views/admin.php');
	}

	/*
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    0.0.2
	 */
	public function action_method_name() {
		// TODO: Define your action method here
	}

	/*
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since       0.0.2
	 */
	public function filter_method_name() {
		// TODO: Define your filter method here
	}

}