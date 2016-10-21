<?php

namespace Molongui\Authorship\Admin;

use Molongui\Authorship\Includes\Plugin_Key;
use Molongui\Authorship\Includes\Plugin_Update;
use Molongui\Authorship\Includes\Plugin_Password;
//use Molongui\Authorship\Includes\Plugin_Upsell;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin
 * @since      1.0.0
 * @version    1.3.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

class Plugin_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $version;

	/**
	 * The URI slug of this plugin.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_slug;

	/**
	 * The link to the main settings page of the software.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $menu_slug;

	/**
	 * Holds all the configuration set into /premium/config/update.php configuration file.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $config;

	/**
	 * TABS
	 */

	/**
	 * The URI slugs of each tab of the admin settings page.
	 *
	 * @access   private
	 * @var      string
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $tab_activate_slug = 'license';

	/**
	 * LICENSE and UPDATE
	 */

	/**
	 * Used to hold an instance of "Plugin_Key" class
	 *
	 * @access   private
	 * @var      Plugin_Key
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_key;

	/**
	 * Update data used across this class
	 *
	 * @access   public
	 * @var      mixed
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public $update_license;
	public $update_basename;
	public $update_product_id;
	public $update_renew_license_url;
	public $update_instance_id;
	public $update_domain;
	public $update_sw_version;
	public $update_plugin_or_theme;
	public $update_extra;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @access   public
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function __construct( $plugin_name, $version )
	{
		// Init vars
		$this->plugin_name = $plugin_name;
		$this->plugin_slug = $plugin_name;
		$this->version     = $version;

		// Load the required dependencies
		$this->load_dependencies();

		if( is_premium() )
		{
			// Load update configuration
			$this->config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";

			// Load premium dependencies
			$this->load_premium_dependencies();

			// Handle license stuff
			$this->manage_license();
		}
	}


	/**
	 * Load the required dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for getting a list with all the plugins developed by Molongui.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/common/class-plugin-upsell.php' );
	}


	/**
	 * Load the required premium dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	private function load_premium_dependencies()
	{
		/**
		 * The class responsible for defining all actions related with the license handling.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-key.php' );

		/**
		 * The class responsible for defining update functionality of the plugin.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-update.php' );

		/**
		 * The class responsible for creating the instance key of the plugin installation.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-password.php' );
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function enqueue_styles()
	{
		// Enqueue color-picker styles
		wp_enqueue_style( 'wp-color-picker' );

		// Enqueue plugin styles
		if( !is_premium() )
		{
			$fpath = '/admin/css/molongui-authorship-admin.1709.min.css';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_style( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array(), $this->version, 'all' );
		}
		else
		{
			$fpath = '/premium/admin/css/molongui-authorship-premium-admin.e729.min.css';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_style( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function enqueue_scripts()
	{
		// Enqueue plugin scripts
		if( !is_premium() )
		{
			$fpath = '/admin/js/molongui-authorship-admin.a200.min.js';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_script( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array( 'jquery', 'wp-color-picker' ), $this->version , true );
		}
		else
		{
			$fpath = '/premium/admin/js/molongui-authorship-premium-admin.a200.min.js';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_script( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array( 'jquery', 'wp-color-picker' ), $this->version, true );
		}

		// Instantiate 'admin-ajax.php' so Ajax calls can be made from the frontend
		wp_localize_script( 'molongui-authorship', 'myAjax', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),			// URL to 'wp-admin/admin-ajax.php' to process the request
			'ajaxnonce'		=> wp_create_nonce( 'myajax-js-nonce' ),	// Nonce that will be used to enforce security with several requests
		));
	}


	/**
	 * Displays an upgrade notice.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function display_go_premium_notice()
	{
		global $current_screen;

		// Check to make sure we're on a Molongui Authorship settings page
		if ( $current_screen->id != MOLONGUI_AUTHORSHIP_SUBMENU . '_page_' . MOLONGUI_AUTHORSHIP_SLUG ) return;

		echo '<div id="message" class="notice premium">';
			echo '<p>';
				printf( __( 'There is a premium version of this plugin. Grab a %spremium licence%s to unlock all features and have direct support.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
						'<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank" >',
				        '</a>' );
			echo '</p>';
		echo '</div>';
	}


	/**
	 * Handle license stuff for premium plugins.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function manage_license()
	{
		// Set all software update data here
		$this->update_license           = get_option( $this->config['db']['license_key'] );
		$this->update_basename          = MOLONGUI_AUTHORSHIP_BASE_NAME; // same as plugin slug. if a theme use a theme name like 'twentyeleven'
		$this->update_product_id        = get_option( $this->config['db']['product_id_key'] ); // Software Title
		$this->update_renew_license_url = '//molongui.amitzy.com/my-account'; // URL to renew a license
		$this->update_instance_id       = get_option( $this->config['db']['instance_key'] ); // Instance ID (unique to each blog activation)
		$this->update_domain            = site_url(); // blog domain name
		$this->update_sw_version        = $this->version; // The software version
		$this->update_plugin_or_theme   = $this->config['sw']['type'];

		// Displays an inactive message if no license has been activated
		add_action( 'admin_notices', array( &$this, 'display_inactive_notice' ) );

		// Instantiate the class that handles the license key (it is used by other functions in this class)
		$this->plugin_key = new Plugin_Key($this->update_product_id, $this->update_instance_id, $this->update_domain, $this->update_sw_version, $this->config['server']['url']);

		// Check for software updates
		$options = get_option( $this->config['db']['license_key'] );

		if( !empty( $options ) && $options !== false )
		{
			new Plugin_Update(
				$this->config['server']['url'],
				$this->update_basename,
				$this->update_product_id,
				$this->update_license[ $this->config['db']['activation_key'] ],
				$this->update_license[ $this->config['db']['activation_email'] ],
				$this->update_renew_license_url,
				$this->update_instance_id,
				$this->update_domain,
				$this->update_sw_version,
				$this->update_plugin_or_theme,
				MOLONGUI_AUTHORSHIP_TEXT_DOMAIN
			);
		}
	}

	/**
	 * Displays an inactive notice when the software is inactive.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function display_inactive_notice()
	{
		if ( !current_user_can( 'manage_options' ) ) return;
		if ( get_option( $this->config['db']['activated_key'] ) != 'Activated' )
		{
			echo '<div id="message" class="error">';
				echo '<p>';
					printf( __( 'Molongui Authorship license has not been activated, so the plugin is inactive! %sClick here%s to activate the license key and
					the plugin.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="' . esc_url( admin_url( $this->menu_slug . '&tab=' . $this->tab_activate_slug ) ) . '">', '</a>' );
				echo '</p>';
			echo '</div>';
		}
	}

	/**
	 * Change the admin footer text on Molongui Authorship admin pages.
	 *
	 * @access  public
	 * @param   string  $footer_text
	 * @return  string
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function admin_footer_text( $footer_text )
	{
		global $current_screen;

		// Check to make sure we're on a Molongui Authorship settings page
		if ( $current_screen->id != MOLONGUI_AUTHORSHIP_SUBMENU . '_page_' . MOLONGUI_AUTHORSHIP_SLUG ) return;

		// Change the footer text
		$footer_text = sprintf( __( 'If you like <strong>Molongui Authorship</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thank you from Molongui in advance!', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="https://wordpress.org/support/view/plugin-reviews/molongui-authorship?filter=5#postform" target="_blank" class="molongui-rating-link" data-rated="' . esc_attr__( 'Thanks :)', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '">', '</a>' );

		return $footer_text;
	}

	/**
	 * Add extra "action links" to the admin plugins page.
	 *
	 * @see      http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_%28plugin_file_name%29
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.11
	 */
	public function add_action_links( $links )
	{
		$more_links = array(
			'settings' => '<a href="' . admin_url( $this->menu_slug ) . '">' . __( 'Settings', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>',
			'docs'     => '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '/docs" target="blank" >' . __( 'Docs', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>'
		);

		if( !is_premium() )
		{
			$more_links['gopro'] = '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '/" target="blank" style="font-weight:bold;">' . __( 'Go Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</a>';
		}

		return array_merge(
			$more_links,
			$links
		);
	}

	/**
	 * Add menu link to the admin menu at the admin area.
	 *
	 * This function registers the menu link to the settings page and the settings page itself.
	 *
	 * @access   public
	 * @see      https://codex.wordpress.org/Function_Reference/add_menu_page
	 * @see      https://codex.wordpress.org/Function_Reference/add_submenu_page
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function add_menu_item()
	{
		// Variables
		include( MOLONGUI_AUTHORSHIP_DIR . '/config/admin-settings.php' );

		// Instantiate class
		if( !class_exists( 'Settings_Page' ) ) require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/common/class.settings-page.php' );
		$settings = new Settings_Page( $slug, $tabs, $default_tab );

		// Add menu
		$this->menu_slug = $settings->add_menu_item();
	}

	/**
	 * Load all the plugin settings.
	 *
	 * This function loads the plugin settings from the database and then merges them with some
	 * default values for when they are not set.
	 *
	 * As settings page is divided into tabs, each tab has its own options group.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	function load_plugin_settings()
	{
		// Load settings
		$tab_main    = (array)get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
		$tab_box     = (array)get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
		$tab_strings = (array)get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS );

		// Default settings values
		$default_main_settings = array(
			'show_related'            => '0',
			'related_order_by'        => 'date',
			'related_order'           => 'asc',
			'related_items'           => '4',
			'enable_guest_archives'   => '1',
			'guest_archive_permalink' => '',
			'guest_archive_slug'      => 'author',
			'guest_archive_tmpl'      => '',
			'show_tw'                 => '1',
			'show_fb'                 => '1',
			'show_in'                 => '1',
			'show_gp'                 => '1',
			'show_yt'                 => '1',
			'show_pi'                 => '1',
			'show_tu'                 => '0',
			'show_ig'                 => '1',
			'show_xi'                 => '1',
			'show_re'                 => '0',
			'show_vk'                 => '0',
			'show_fl'                 => '0',
			'show_vi'                 => '0',
			'show_me'                 => '0',
			'show_we'                 => '0',
			'show_de'                 => '0',
			'show_st'                 => '0',
			'show_my'                 => '0',
			'show_ye'                 => '0',
			'show_mi'                 => '0',
			'show_so'                 => '0',
			'show_la'                 => '0',
			'show_fo'                 => '0',
			'show_sp'                 => '0',
			'show_vm'                 => '0',
			'enable_sc_text_widgets'  => '1',
			'add_opengraph_meta'      => '1',
			'add_google_meta'         => '1',
			'add_facebook_meta'       => '1',
			'admin_menu_level'        => 'true',
			'keep_config'             => '1',
			'keep_data'               => '1',
		);

		$default_box_settings = array(
			'display'             => '1',
			'position'            => 'below',
			'hide_if_no_bio'      => 'no',
			'layout'              => 'default',
			'box_shadow'          => 'left',
			'box_border'          => 'none',
			'box_border_color'    => 'inherit',
			'box_background'      => 'inherit',
			'img_style'           => 'none',
			'img_default'         => 'mm',
			'acronym_text_color'  => 'inherit',
			'acronym_bg_color'    => 'inherit',
			'name_size'           => 'normal',
			'name_color'          => 'inherit',
			'meta_size'           => 'smaller',
			'meta_color'          => 'inherit',
			'bio_size'            => 'smaller',
			'bio_color'           => 'inherit',
			'bio_align'           => 'justify',
			'bio_style'           => 'normal',
			'show_icons'          => '1',
			'icons_size'          => 'normal',
			'icons_color'         => 'inherit',
			'icons_style'         => 'default',
			'bottom_bg'           => 'inherit',
			'bottom_border'       => 'none',
			'bottom_border_color' => '#B6B6B6',
		);

		$default_string_settings = array(
			'at'               => 'at',
			'web'              => 'Website',
			'more_posts'       => '+ posts',
			'bio'              => 'Bio',
			'about_the_author' => 'About the author',
			'related_posts'    => 'Related posts',
		);

		// Merge settings and update db
		$update = array_merge( $default_main_settings, $tab_main );
		update_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS, $update );

		$update = array_merge( $default_box_settings, $tab_box );
		update_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS, $update );

		$update = array_merge( $default_string_settings, $tab_strings );
		update_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS, $update );
	}

	/**
	 * Register settings page tabs.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	function add_page_tabs()
	{
		// Variables
		include( MOLONGUI_AUTHORSHIP_DIR . '/config/admin-settings.php' );

		// Instantiate class
		if( !class_exists( 'Settings_Page' ) ) require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/common/class.settings-page.php' );
		$settings = new Settings_Page( $slug, $tabs, $default_tab );

		// Add menu
		$settings->add_page_tabs();
	}


	/**
	 * Display a star icon to indicate it is a Premium setting.
	 *
	 * This function adds a star icon to the end of the filed label to mark a Premium setting on free plugins.
	 *
	 * @access  public
	 * @param   string  $type       Whether it is a a premium setting or a setting with premium options.
	 * @param   string  $default    Default value for the setting.
	 * @return  string  $tip        Premium tip.
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	function premium_setting_tip( $type = 'full', $default = '' )
	{
		switch ( $type )
		{
			case 'full':

				$tip = sprintf( __( '%sPremium setting%s. You are using the free version of this plugin, so changing this setting will have no effect and default value will be used. Consider purchasing the %sPremium Version%s.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<strong>', '</strong>', '<a href="'.MOLONGUI_AUTHORSHIP_WEB.'" target="_blank">', '</a>' );

			break;

			case 'part':

				$tip = sprintf( __( '%sPremium setting%s. You are using the free version of this plugin, so selecting any option marked as "PREMIUM" will have no effect and default value will be used. Consider purchasing the %sPremium Version%s.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<strong>', '</strong>', '<a href="'.MOLONGUI_AUTHORSHIP_WEB.'" target="_blank">', '</a>' );

			break;
		}

		return $tip;
	}


	/**
	 * COMMON LICENSE RELATED FUNCTIONS
	 */

	/**
	 * License tab data validation.
	 *
	 * Sanitizes and validates data submitted using license tab form.
	 *
	 * @access   public
	 * @since    1.3.0
	 * @version  1.3.0
	 */
	public function validate_license_tab( $input )
	{
		// Load existing settings
		$activation_status = get_option( $this->config['db']['activated_key'] );
		$current_api_key   = $this->update_license[$this->config['db']['activation_key']];

		// Sanitize submitted input
		$api_key   = trim( $input[$this->config['db']['activation_key']] );
		$api_email = trim( $input[$this->config['db']['activation_email']] );

		// Update settings with submitted input
		$input[$this->config['db']['activation_key']]   = $api_key;
		$input[$this->config['db']['activation_email']] = $api_email;

		// DEBUG: For testing activation status_extra data
		// molongui_debug( array( $_REQUEST, $input, $activation_status, $current_api_key, $api_key, $api_email ), true );

		// Plugin Activation (run it only on "activate" license tab)
		if ( $_REQUEST['option_page'] != $this->tab_deactivate_slug )
		{
			if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $current_api_key != $api_key )
			{
				// If this is a new key, and an existing key already exists in the database,
				// deactivate the existing key before activating the new key.
				if ( !empty($current_api_key) && ($current_api_key != $api_key) ) $this->replace_license_key( $current_api_key );

				$args = array(
					'licence_key' => $api_key,
					'email'       => $api_email,
				);

				$activate_results = json_decode( $this->plugin_key->activate( $args ), true );

				// Activation successful
				if ( $activate_results['activated'] == true && !isset( $activate_results['code'] ) && !isset( $activate_results['error'] ) )
				{
					add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated. ', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . "{$activate_results['message']}.", 'updated' );
					update_option($this->config['db']['activated_key'], 'Activated' );
				}

				// Activation failure
				if ( $activate_results == false )
				{
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later.',MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'error' );
					$input[$this->config['db']['activation_key']]   = '';
					$input[$this->config['db']['activation_email']] = '';
					update_option($this->update_license[$this->config['db']['activated_key']], 'Deactivated' );
				}

				// Handle error and show message
				if ( isset( $activate_results['code'] ) )
				{
					switch ( $activate_results['code'] )
					{
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '103':
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '104':
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '105':
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
						case '106':
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							break;
					}

					// Clear license settings in database
					$input[ $this->config['db']['activation_key'] ]   = '';
					$input[ $this->config['db']['activation_email'] ] = '';
					update_option( $this->config['db']['activated_key'], 'Deactivated' );
				}
			}
		}

		// DEBUG: For testing activation status_extra data
		// molongui_debug( array( $_REQUEST, $input, $args, $activate_results ), true );

		// Save plugin version (useful on plugin updates)
		$input['plugin_version'] = MOLONGUI_AUTHORSHIP_VERSION;

		// Return sanitized and validated data (this will update database data)
		return $input;
	}

	/**
	 * Deactivate the current license key before activating the new license key.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function replace_license_key( $current_api_key )
	{
		$args = array(
			'email'       => $this->update_license[$this->config['db']['activation_email']],
			'licence_key' => $current_api_key,
		);

		// Reset license key activation
		$reset = $this->plugin_key->deactivate( $args );

		if ( $reset == true ) return true;

		return add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The license could not be
		deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new
		license.',MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'updated' );
	}

	/**
	 * Deactivates the license key to allow key to be used on another blog.
	 *
	 * @access   public
	 * @since    1.3.0
	 * @version  1.3.0
	 */
	public function deactivate_license_key()
	{
		// Check security (If nonce is invalid, die)
		check_ajax_referer( 'myajax-js-nonce', 'security', true );

		// Check activation status
		$activation_status = get_option( $this->config['db']['activated_key'] );

		$args = array(
			'licence_key' => $this->update_license[ $this->config['db']['activation_key'] ],
			'email'       => $this->update_license[ $this->config['db']['activation_email'] ],
		);

		if( $activation_status == 'Activated' && $this->update_license[ $this->config['db']['activation_key'] ] != '' &&
		    $this->update_license[ $this->config['db']['activation_email'] ] != ''
		)
		{
			// Generate a new unique installation $instance id
			$plugin_password = new Plugin_Password();
			$instance = $plugin_password->generate_password( 12, false );

			// Deactivates license key activation on Molongui's server
			$activate_results = json_decode( $this->plugin_key->deactivate( $args ), true );

			if( $activate_results['deactivated'] == true )
			{
				$update = array(
					$this->config['db']['activation_key']   => '',
					$this->config['db']['activation_email'] => ''
				);

				$merge_options = array_merge( $this->update_license, $update );

				update_option( $this->config['db']['license_key'], $merge_options );
				update_option( $this->config['db']['activated_key'], 'Deactivated' );
				update_option( $this->config['db']['instance_key'], $instance );

				// Return result
				$result = "success";
				$msg = sprintf( __( "Your license has been deactivated. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['activations_remaining'] );
				echo json_encode( array( $result, $msg ) );

				// Avoid 'admin-ajax.php' to append the value outputted with a "0"
				wp_die();
			}

			if( isset( $activate_results['code'] ) )
			{
				$msg = __( 'UNDEFINED ERROR. Please, try again or contact Molongui', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

				// Show error message
				switch( $activate_results['code'] )
				{
					case '100':
						$msg = sprintf( __( "EMAIL ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '101':
						$msg = sprintf( __( "KEY ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '102':
						$msg = sprintf( __( "PURCHASE INCOMPLETE ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '103':
						$msg = sprintf( __( "EXEEDED ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '104':
						$msg = sprintf( __( "KEY NOT ACTIVATED ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '105':
						$msg = sprintf( __( "INVALID KEY ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
					case '106':
						$msg = sprintf( __( "NOT ACTIVE ERROR: %s. %s", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $activate_results['error'], $activate_results['additional info'] );
					break;
				}

				// Clear license settings because it cannot be deactivated
				$clear = array(
					$this->config['db']['activation_key']   => '',
					$this->config['db']['activation_email'] => ''
				);

				update_option( $this->config['db']['license_key'], $clear );
				update_option( $this->config['db']['activated_key'], 'Deactivated' );
				update_option( $this->config['db']['instance_key'], $instance );

				// Return result
				$result = "error";
				echo json_encode( array( $result, $msg ) );

				// Avoid 'admin-ajax.php' to append the value outputted with a "0"
				wp_die();
			}
		}
		else
		{
			// Return result
			$result = "error";
			$msg    = __( "There is no active license to deactivate...", MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
			echo json_encode( array( $result, $msg ) );

			// Avoid 'admin-ajax.php' to append the value outputted with a "0"
			wp_die();
		}
	}


	/**
	 * PLUGIN SPECIFIC FUNCTIONS
	 */

	/**
	 * Main tab data validation.
	 *
	 * Sanitizes and validates data submitted using main tab form.
	 *
	 * @access   public
	 * @since    1.3.0
	 * @version  1.3.0
	 */
	function validate_main_tab( $input )
	{
		// DEBUG: Check involved data
		// molongui_debug( array( $_REQUEST, $input ), true );

		// Save plugin version (useful on plugin updates)
		$input['plugin_version'] = MOLONGUI_AUTHORSHIP_VERSION;

		// Force default settings for premium options
		if( !is_premium() )
		{
			$input['related_order_by']        = 'date';
			$input['related_order']           = 'asc';
			$input['admin_menu_level']        = 'true';
			$input['enable_guest_archives']   = '0';
			$input['guest_archive_permalink'] = '';
			$input['guest_archive_slug']      = 'author';
			$input['guest_archive_tmpl']      = '';
			$input['enable_sc_text_widgets']  = '0';
		}

		// Handle mandatory fields
		if ( !isset( $input['guest_archive_slug'] ) ) $input['guest_archive_slug'] = 'author';

		// Handle unchecked checkboxes
		// Unchecked checkboxes doesn't have any value, so it must be equaled to 0 in order to override default values
		if ( !isset( $input['show_tw'] ) ) $input['show_tw'] = '0';
		if ( !isset( $input['show_fb'] ) ) $input['show_fb'] = '0';
		if ( !isset( $input['show_in'] ) ) $input['show_in'] = '0';
		if ( !isset( $input['show_gp'] ) ) $input['show_gp'] = '0';
		if ( !isset( $input['show_yt'] ) ) $input['show_yt'] = '0';
		if ( !isset( $input['show_pi'] ) ) $input['show_pi'] = '0';
		if ( !isset( $input['show_tu'] ) ) $input['show_tu'] = '0';
		if ( !isset( $input['show_ig'] ) ) $input['show_ig'] = '0';
		if ( !isset( $input['show_ss'] ) ) $input['show_ss'] = '0';
		if ( !isset( $input['show_xi'] ) ) $input['show_xi'] = '0';
		if ( !isset( $input['show_re'] ) ) $input['show_re'] = '0';
		if ( !isset( $input['show_vk'] ) ) $input['show_vk'] = '0';
		if ( !isset( $input['show_fl'] ) ) $input['show_fl'] = '0';
		if ( !isset( $input['show_vi'] ) ) $input['show_vi'] = '0';
		if ( !isset( $input['show_me'] ) ) $input['show_me'] = '0';
		if ( !isset( $input['show_we'] ) ) $input['show_we'] = '0';
		if ( !isset( $input['show_de'] ) ) $input['show_de'] = '0';
		if ( !isset( $input['show_st'] ) ) $input['show_st'] = '0';
		if ( !isset( $input['show_my'] ) ) $input['show_my'] = '0';
		if ( !isset( $input['show_ye'] ) ) $input['show_ye'] = '0';
		if ( !isset( $input['show_mi'] ) ) $input['show_mi'] = '0';
		if ( !isset( $input['show_so'] ) ) $input['show_so'] = '0';
		if ( !isset( $input['show_la'] ) ) $input['show_la'] = '0';
		if ( !isset( $input['show_fo'] ) ) $input['show_fo'] = '0';
		if ( !isset( $input['show_sp'] ) ) $input['show_sp'] = '0';
		if ( !isset( $input['show_vm'] ) ) $input['show_vm'] = '0';
		if ( !isset( $input['show_dm'] ) ) $input['show_dm'] = '0';
		if ( !isset( $input['show_rd'] ) ) $input['show_rd'] = '0';

		return $input;
	}


	/**
	 * Box tab data validation.
	 *
	 * Sanitizes and validates data submitted using box tab form.
	 *
	 * @access   public
	 * @since    1.3.0
	 * @version  1.3.0
	 */
	function validate_box_tab( $input )
	{
		// DEBUG: Check involved data.
		// molongui_debug( array( $_REQUEST, $input ), true );

		// Load saved settings.
		$box_settings = (array)get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );

		// Save plugin version (useful on plugin updates).
		$input['plugin_version'] = MOLONGUI_AUTHORSHIP_VERSION;

		// Force default settings for premium options.
		if( !is_premium() )
		{
			if ( $input['display'] != '0' and $input['display'] != '1' ) $input['display'] = '1';
			if ( $input['layout']  != 'default' and $input['layout'] != 'tabbed' ) $input['layout'] = 'default';
			if ( $input['img_default'] == 'acronym' ) $input['img_default'] = $box_settings['img_default'];
			$input['name_color']          = 'inherit';
			$input['meta_color']          = 'inherit';
			$input['bio_color']           = 'inherit';
			$input['bio_align']           = 'justify';
			$input['bio_style']           = 'normal';
			$input['icons_style']         = 'default';
			$input['icons_color']         = 'inherit';
			$input['bottom_bg']           = 'transparent';
			$input['bottom_border']       = 'thin';
			$input['bottom_border_color'] = '#B6B6B6';
		}

		return $input;
	}


	/**
	 * Labels tab data validation.
	 *
	 * Sanitizes and validates data submitted using labels tab form.
	 *
	 * @access   public
	 * @since    1.3.0
	 * @version  1.3.0
	 */
	function validate_strings_tab( $input )
	{
		// DEBUG: Check involved data
		// molongui_debug( array( $_REQUEST, $input ), true );

		// Save plugin version (useful on plugin updates)
		$input['plugin_version'] = MOLONGUI_AUTHORSHIP_VERSION;

		return $input;
	}

}