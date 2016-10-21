<?php

namespace Molongui\Authorship\Includes;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
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

class Plugin_Deactivator
{
	/**
	 * Fires all required actions during plugin deactivation.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public static function deactivate()
	{
		// Remove rewrite rules and then recreate rewrite rules.
		flush_rewrite_rules();

		// Deactivate premium license.
		if( is_premium() )
		{
			self::remove_license();
		}

		// Undo "user description" field replacement.
		self::undo_replace_description_field();
	}

	/**
	 * Remove premium plugin license key.
	 *
	 * Deactivates the license on Molongui's server so it can be reused and removes all license key data stored into
	 * the database.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public static function remove_license()
	{
		// Load config.
		$config = include dirname( plugin_dir_path( __FILE__ ) ) . "/premium/config/update.php";
		$settings = get_option( $config['db']['license_key'] );

		// Deactivate license on Molongui's server.
		self::license_key_deactivation( $config );

		// Remove premium license settings if not configured otherwise.
		if ( !$settings[$config['db']['keep_license']] )
		{
			if( is_multisite() )
			{
				global $blog_id;

				switch_to_blog( $blog_id );

				foreach(
					array(
						$config['db']['license_key'],
						$config['db']['product_id_key'],
						$config['db']['instance_key'],
						$config['db']['activated_key'],
					) as $option )
				{
					delete_option( $option );
				}

				restore_current_blog();
			}
			else
			{
				foreach(
					array(
						$config['db']['license_key'],
						$config['db']['product_id_key'],
						$config['db']['instance_key'],
						$config['db']['activated_key'],
					) as $option )
				{
					delete_option( $option );
				}
			}
		}
	}


	/**
	 * Deactivate license key on Molongui's server.
	 *
	 * This function deactivates the license on Molongui's server so it can be reused in another site.
	 *
	 * @access   public
	 * @param    array       $config    Plugin settings.
	 * @return   void
	 * @since    1.0.0
	 * @version  1.2.11
	 */
	public function license_key_deactivation( $config )
	{
		$activation_status = get_option( $config['db']['activated_key'] );
		$options = get_option( $config['db']['license_key'] );

		$api_email = $options[$config['db']['activation_email']];
		$api_key   = $options[$config['db']['activation_key']];

		$args = array(
			'email'         => $api_email,
			'licence_key'   => $api_key,
		);

		if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' )
		{
			// Reset license key activation
			$plugin_key = new Plugin_Key(get_option( $config['db']['product_id_key'] ), get_option( $config['db']['instance_key'] ), site_url(), MOLONGUI_AUTHORSHIP_VERSION, $config['server']['url']);
			$plugin_key->deactivate( $args );
		}
	}


	/**
	 * Undo replacement of WP User Profile "description" field.
	 *
	 * Undo the replacement of WP User Profile "description" field done when activating the plugin.
	 *
	 * @access  public
	 * @see     includes/class-plugin-activator.php
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function undo_replace_description_field()
	{
		$users = get_users();

		foreach ( $users as $user )
		{
			if ( $bio = get_user_meta( $user->ID, 'molongui_author_bio', true ) ) update_user_meta( $user->ID, 'description', $bio );
		}
	}
}