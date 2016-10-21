<?php

namespace Molongui\Authorship\Includes;

use Molongui\Authorship\Includes\Plugin_Loader;
use Molongui\Authorship\Admin\Plugin_Admin;
use Molongui\Authorship\Plugin_Public;
use Molongui\Authorship\Includes\Plugin_i18n;
use Molongui\Authorship\Includes\Guest_Author as Plugin_Aim;
use Molongui\Authorship\Includes\Custom_Profile;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.0.0
 * @version    1.3.0
 */
class Plugin_Core
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Plugin_Loader    $loader        Maintains and registers all hooks for the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string           $plugin_name   The string used to uniquely identify this plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string           $version       The current version of the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected $version;

	/**
	 * The license type of the plugin.
	 *
	 * @access   protected
	 * @var      string           $license       The license type of the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	protected $license;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function __construct()
	{
		// Initialize variables.
		$this->plugin_name = MOLONGUI_AUTHORSHIP_ID;
		$this->version     = MOLONGUI_AUTHORSHIP_VERSION;

		// Load code dependencies.
		$this->load_dependencies();

		// Define the locale for internationalization.
		$this->set_locale();

		// Update database schema if needed.
		$this->update_db();

		// Define admin hooks.
		if ( is_admin() ) $this->define_admin_hooks();

		// Define public hooks.
		if ( $this->check_license() ) $this->define_public_hooks();
	}

	/**
	 * .
	 *
	 * @access  private
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	private function update_db()
	{
		$update_db = new DB_Update( MOLONGUI_AUTHORSHIP_DB_VERSION );
		if ( $update_db->db_update_needed() ) $update_db->run_update();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Loader.  Orchestrates the hooks of the plugin.
	 * - Plugin_i18n.    Defines internationalization functionality.
	 * - Plugin_Admin.   Defines all hooks for the admin area.
	 * - Plugin_Public.  Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for handling database schema update to keep backwards compatibility.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/plugin-class-db-update.php' );

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common/class-plugin-loader.php';
		$this->loader = new Plugin_Loader();

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common/class-plugin-i18n.php';

		/**
		 * The class responsible for getting all system information.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common/class-plugin-system-info.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common/class.browser.php';

		/**
		 * The file holding common functions.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/common/common-functions.php' );

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-public.php';

		/**
		 * The class responsible for defining all Guest Author stuff
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-guest-author.php';

		/**
		 * The class responsible for customizing Wordpress user profile
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-profile.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function set_locale()
	{
		$plugin_i18n = new Plugin_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Check license status.
	 *
	 * This function checks whether a required license is set or not. Free plugins are not checked.
	 *
	 * @access  private
	 * @return  bool
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	private function check_license()
	{
		// Check activation for premium plugins
		if ( is_premium() )
		{
			// Load update configuration data
			$config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";

			if ( get_option( $config['db']['activated_key'] ) == 'Activated' )
			{
				return ( true );
			}
			else
			{
				return ( false );
			}
		}
		else
		{
			return ( true );
		}
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	private function define_admin_hooks()
	{
		/**
		 * Common stuff
		 */

		$plugin_admin = new Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Register plugin admin menu item, plugin settings page and tabs
		$this->loader->add_action( 'init', $plugin_admin, 'load_plugin_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_item');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_page_tabs' );

		// Register additional action links at "Plugins" page
		$this->loader->add_filter( 'plugin_action_links_' . MOLONGUI_AUTHORSHIP_BASE_NAME, $plugin_admin, 'add_action_links' );

		// Customize admin footer text
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'admin_footer_text', 1 );

		// Register the "go premium" notice
		if( !is_premium() && MOLONGUI_AUTHORSHIP_UPGRADABLE == 'yes' ) $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_go_premium_notice' );

		// Enable license deactivation for logged in users
		$this->loader->add_action( 'wp_ajax_deactivate_license_key', $plugin_admin, 'deactivate_license_key' );

		// Enable the sent of support reports
		$plugin_info = new Plugin_System_Info();
		$this->loader->add_action( 'wp_ajax_send_support_report', $plugin_info, 'send_support_report' );

		/**
		 * Molongui Authorship specific
		 */

		$plugin_aim = new Plugin_Aim();

		// Register custom post-type
		$this->loader->add_action( 'init', $plugin_aim, 'register_guest_author_posttype' );
		$this->loader->add_filter( 'enter_title_here', $plugin_aim, 'change_default_title' );
		$this->loader->add_action( 'admin_head', $plugin_aim, 'remove_media_buttons' );

		// Customize columns shown on the Manage Guest Authors screen
		$this->loader->add_filter( 'manage_molongui_guestauthor_posts_columns', $plugin_aim, 'add_list_columns' );
		$this->loader->add_action( 'manage_molongui_guestauthor_posts_custom_column', $plugin_aim, 'fill_list_columns', 5, 2 );

		// Modify author column shown on the Manage Posts screen
		$this->loader->add_filter( 'manage_posts_columns', $plugin_aim, 'change_author_column', 5, 2 );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_aim, 'fill_author_column', 5, 2 );

		// Modify author column shown on the Manage Pages screen
		$this->loader->add_filter( 'manage_pages_columns', $plugin_aim, 'change_author_column', 5, 2 );
		$this->loader->add_action( 'manage_pages_custom_column', $plugin_aim, 'fill_author_column', 5, 2 );

		// Replace default WP author meta box
		$this->loader->add_action( 'admin_menu', $plugin_aim, 'remove_author_metabox' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_aim, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_aim, 'save' );

		// Enable authorship box preview for logged in users
		$this->loader->add_action( 'wp_ajax_authorship_box_preview', $plugin_aim, 'authorship_box_preview' );

		// Include authorship box preview
		$this->loader->add_action( 'molongui_authorship_settings_before_submit_button', $plugin_aim, 'add_preview_button' );

		// Add custom profile fields to WordPress user form
		$custom_profile = new Custom_Profile();
		$this->loader->add_action( 'show_user_profile', $custom_profile, 'add_authorship_fields' );
		$this->loader->add_action( 'edit_user_profile', $custom_profile, 'add_authorship_fields' );
		$this->loader->add_action( 'personal_options_update', $custom_profile, 'save_authorship_fields' );
		$this->loader->add_action( 'edit_user_profile_update', $custom_profile, 'save_authorship_fields' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function define_public_hooks()
	{
		/**
		 * Common stuff
		 */

		$plugin_public = new Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		/**
		 * Molongui Authorship specific
		 */

		// OpenGraph, Google and Facebook authorship head meta tags
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_author_meta' );

		// Display guest author credits if set
		$this->loader->add_filter( 'the_author', $plugin_public, 'filter_author_name', 10, 1 );
		$this->loader->add_filter( 'author_link', $plugin_public, 'filter_author_link', 10, 1 );

		// Display author box on single posts
		$this->loader->add_filter( 'the_content', $plugin_public, 'render_author_box', 10, 1 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @access   public
	 * @return   string           The name of the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @access   public
	 * @return   string          The version number of the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @access   public
	 * @return   Plugin_Loader    Orchestrates the hooks of the plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function get_loader()
	{
		return $this->loader;
	}
}