<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.fahidjavid.com
 * @since      1.0.0
 *
 * @package    Simple_Support_System
 * @subpackage Simple_Support_System/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_Support_System
 * @subpackage Simple_Support_System/includes
 * @author     Fahid Javid <fahidjavid@gmail.com>
 */
class Simple_Support_System {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Simple_Support_System_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'simple-support-system';
		$this->version = '1.1.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

        add_action( 'init', array( $this, 'sss_hide_admin_bar' ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Support_System_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Support_System_i18n. Defines internationalization functionality.
	 * - Simple_Support_System_Admin. Defines all hooks for the admin area.
	 * - Simple_Support_System_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-support-system-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-support-system-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-support-system-admin.php';

        /**
         * The class responsible for envato api integration
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/envato-api-wrapper.php';

        /**
         * The class responsible for handling all the plugin forms
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-support-system-handler.php';

        /**
         * The class responsible for providing plugin options
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-support-system-options.php';

        /**
         * The class responsible for providing WordPress dashboard widget
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wp-dashboard-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-support-system-public.php';

		$this->loader = new Simple_Support_System_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Support_System_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Support_System_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Simple_Support_System_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $sss_handler = new Simple_Support_System_Handler();
        add_shortcode( 'sss_register_verified_user', array( $sss_handler, 'sss_register_verified_user_form') );
        add_shortcode( 'sss_login_user', array( $sss_handler, 'sss_login_user_form') );
        add_shortcode( 'sss_create_ticket', array( $sss_handler, 'sss_create_ticket_form') );
        add_shortcode( 'sss_list_purchases', array( $sss_handler, 'list_user_purchases') );


        $sss_dash_widget = new SSS_WP_Dashboard_Widget();
        $this->loader->add_action('wp_dashboard_setup', $sss_dash_widget, 'add_dashboard_widgets' );

    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_Support_System_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Simple_Support_System_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

    /**
     * Checks if current user is restricted or not
     *
     * @return bool
     */
    public function sss_is_user_restricted() {
        $current_user = wp_get_current_user();
        $sss_optinos = get_option( 'simple_support_system_option' );

        // get restricted level from theme options
        if( isset( $sss_optinos['restrict_admin'] ) && ! empty( $sss_optinos['restrict_admin'] ) ) {
            $restricted_level = intval( $sss_optinos['restrict_admin'] );
        } else {
            $restricted_level = 0;
        }

        // Redirects user below a certain user level to home url
        // Ref: https://codex.wordpress.org/Roles_and_Capabilities#User_Level_to_Role_Conversion
        if ( $current_user->user_level <= $restricted_level ) {
            return true;
        }

        return false;
    }

    /**
     * Hide the admin bar on front end for users with user level equal to or below restricted level
     */
    public function sss_hide_admin_bar() {
        if ( is_user_logged_in() ) {
            if ( $this->sss_is_user_restricted() ) {
                add_filter( 'show_admin_bar', '__return_false' );
            }
        }
    }

}
