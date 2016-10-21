<?php

namespace Molongui\Authorship\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.0.0
 * @version    1.3.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

class Plugin_Activator
{
	/**
	 * Fires all required actions during plugin activation.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public static function activate()
	{
		// Remove rewrite rules and then recreate rewrite rules.
		flush_rewrite_rules();

		// Update database schema if needed.
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/plugin-class-db-update.php' );
		$update_db = new DB_Update( MOLONGUI_AUTHORSHIP_DB_VERSION );
		if ( $update_db->db_update_needed() ) $update_db->run_update();

		// Insert default plugin options into database.
		self::add_default_options();

		// If premium plugin, add license options into database.
		if ( is_premium() ) self::add_license_options();

		// Replace WP User Profile "description" field.
		self::replace_description_field();
	}


	/**
	 * Initialize default option values.
	 *
	 * This functions stores default plugin settings into options table at Wordpress database.
	 *
	 * @access   public
	 * @since    1.2.0
	 * @version  1.3.0
	 */
	public static function add_default_options()
	{
		// Default main settings.
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
			'show_ss'                 => '1',
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
			'show_dm'                 => '0',
			'show_rd'                 => '0',
			'enable_sc_text_widgets'  => '1',
			'add_opengraph_meta'      => '1',
			'add_google_meta'         => '1',
			'add_facebook_meta'       => '1',
			'admin_menu_level'        => 'true',
			'keep_config'             => '1',
			'keep_data'               => '1',
		);

		// Insert default main settings on first install.
		if ( !get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS ) )
		{
			add_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS, $default_main_settings );
		}
		else
		{
			// Get existing settings.
			$config = (array)get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

			// Insert new settings.
			$update = array_merge( $default_main_settings, $config );

			// Fix possible wrong values: "keep_config" and "keep_data".
			if ( $update['keep_config'] == 'yes' or $update['keep_config'] == '1' ) $update['keep_config'] = '1'; else $update['keep_config'] = '0';
			if ( $update['keep_data']   == 'yes' or $update['keep_data']   == '1' ) $update['keep_data']   = '1'; else $update['keep_data']   = '0';

			// Update settings into database.
			update_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS, $update );
		}

		// Default box settings.
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

		// Insert default box settings on first install.
		if ( !get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS ) )
		{
			add_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS, $default_box_settings );
		}
		else
		{
			// Get existing settings.
			$config = (array)get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );

			// Insert new settings.
			$update = array_merge( $default_box_settings, $config );

			// Update settings into database.
			update_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS, $update );
		}

		// Default string settings.
		$default_string_settings = array(
			'at'               => 'at',
			'web'              => 'Website',
			'more_posts'       => '+ posts',
			'bio'              => 'Bio',
			'about_the_author' => 'About the author',
			'related_posts'    => 'Related posts',
		);

		// Insert default string settings on first install.
		if ( !get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS ) )
		{
			add_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS, $default_string_settings );
		}
		else
		{
			// Get existing settings.
			$config = (array)get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS );

			// Insert new settings.
			$update = array_merge( $default_string_settings, $config );

			// Update settings into database.
			update_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS, $update );
		}
	}


	/**
	 * Set default plugin license options.
	 *
	 * This functions stores default plugin license settings into options table at Wordpress database.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public static function add_license_options()
	{
		// Load db keys.
		$config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";

		// Leave if there is an existing license.
		$license = get_option( $config['db']['license_key'] );
		if ( !empty( $license ) and $license[$config['db']['activation_key']] and $license[$config['db']['activation_email']] ) return;

		// Generate a unique installation $instance id
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/update/class-plugin-password.php' );
		$plugin_password = new Plugin_Password();
		$instance = $plugin_password->generate_password( 12, false );

		// Initialize database entries.
		$global_options = array(
			$config['db']['activation_key']   => '',
			$config['db']['activation_email'] => '',
			$config['db']['keep_license']     => '1',
		);
		update_option( $config['db']['license_key'], $global_options );

		$single_options = array(
			$config['db']['product_id_key'] => $config['sw']['id'],
			$config['db']['instance_key']   => $instance,
			$config['db']['activated_key']  => 'Deactivated',
		);

		foreach ( $single_options as $key => $value )
		{
			update_option( $key, $value );
		}

		// Check if the current plugin version is lower than the version being installed.
		$curr_ver = get_option( $config['db']['version_key'] );
		if ( version_compare( MOLONGUI_AUTHORSHIP_VERSION, $curr_ver, '>' ) )
		{
			// Update the version into database.
			update_option( $config['db']['version_key'], MOLONGUI_AUTHORSHIP_VERSION );
		}
	}


	/**
	 * Replace WP User Profile "description" field.
	 *
	 * Some themes display its own "author box" if "description" field is not empty. This function avoids that by
	 * copying "description" field contents to custom "molongui_author_bio" user meta field and emptying the first
	 * one.
	 *
	 * As long as "description" field cannot be removed from profile page without hacking into WP code, it is hidden
	 * using CSS styling.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public static function replace_description_field()
	{
		$users = get_users();

		foreach ( $users as $user )
		{
			if ( $user->description ) update_user_meta( $user->ID, 'molongui_author_bio', $user->description );
			update_user_meta( $user->ID, 'description', '' );
		}
	}

}
