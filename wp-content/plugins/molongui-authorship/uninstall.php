<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @since      1.0.0
 * @version    1.3.0
 */

// If uninstall not called from WordPress, then exit.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Get plugin options
require_once( plugin_dir_path( __FILE__ ) . 'config/config.php' );
$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

// Remove premium license options
if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . "/premium/config/update.php" ) )
{
	$db_key  = include MOLONGUI_AUTHORSHIP_DIR . "/premium/config/update.php";
	if ( $license = get_option( $db_key['db']['license_key'] ) )
	{
		if( is_multisite() )
		{
			global $blog_id;

			switch_to_blog( $blog_id );

			foreach(
				array(
					$db_key['db']['license_key'],
					$db_key['db']['product_id_key'],
					$db_key['db']['instance_key'],
					$db_key['db']['activated_key'],
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
					$db_key['db']['license_key'],
					$db_key['db']['product_id_key'],
					$db_key['db']['instance_key'],
					$db_key['db']['activated_key'],
				) as $option )
			{
				delete_option( $option );
			}
		}
	}
}

// Delete plugin settings if not configured otherwise
if ( !$settings['keep_config'] )
{
	global $wpdb;

	$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'molongui_authorship_%';");
}

// Delete plugin data if not configured otherwise
if ( !$settings['keep_data'] )
{
	global $wpdb;

	// Get all "molongui_guestauthor" custom-posts
	$ids = $wpdb->get_results(
		"
		SELECT ID
		FROM {$wpdb->prefix}posts
		WHERE post_type LIKE 'molongui_guestauthor'
		",
		ARRAY_A
	);

	// Convert numerically indexed array of associative arrays (ARRAY_A) to comma separated string
	foreach ( $ids as $key => $id )
	{
		if ( $key == 0 ) $postids = $id['ID'];
		else $postids = $postids . ', ' . $id['ID'];
	}

	// Delete all "postmeta" entries related with those custom-posts
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN ( $postids );" );

	// Delete all "molongui_guestauthor" custom-posts
	$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE ID IN ( $postids );" );

	// Delete all "postmeta" entries related with "Molongui Authorship"
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '%_molongui_guest_author%';" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_author_box_display';" );
}