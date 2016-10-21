<?php

namespace Molongui\Authorship\Includes;

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * The Molongui plugins class.
 *
 * This is used to get the list of all plugins developed by Molongui.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.0.0
 * @version    1.0.0
 */
class Plugin_Upsell
{
	/**
	 * Handles output of the upsells in admin.
	 *
	 * Data to display is gotten from a JSON file on a remote server. JSON data structure is shown below and can be checked at http://jsoneditoronline.org/.
	 *
	 * {
	 *  "all": {
	 *      "plugin-name": {
	 *          "id": "plugin-name",
	 *          "link": "http://molongui.amitzy.com/plugins/plugin-name",
	 *          "price": "123.00",
	 *          "name": "Plugin Name",
	 *          "image": "http://molongui.amitzy.com/plugins/img/banner_en_US.png",
	 *          "excerpt": "Plugin short description in English.",
	 *          "name_es_ES": "Nombre en castellano",
	 *          "image_es_ES": "http://molongui.amitzy.com/plugins/img/banner_es_ES.png",
	 *          "excerpt_es_ES": "Breve descripci&oacute;n del plugin en castellano."
	 *      }
	 *  },
	 *  "featured": {},
	 *  "popular": {},
	 *  "free": {},
	 *  "premium": {}
	 * }
	 *
	 * Images size must be 300x163px.
	 *
	 * @acess       public
	 * @param       string     $category    The category to show plugins from.
	 * @param       mixed      $num_items   The number of featured plugins to show.
	 * @param       int        $num_words   Number of words to use as plugin description.
	 * @param       string     $more        Text to add when ellipsing plugin description.
	 * @since       1.0.0
	 * @version     1.0.0
	 */
	public static function output( $category = 'all', $num_items = 'all', $num_words = 36, $more = null )
	{
		// Load configuration
		$config = include MOLONGUI_AUTHORSHIP_DIR . "/config/upsell.php";

		// Premium plugins download data from Molongui server
		if ( is_premium() )
		{
			// If cached data, don't download it again
			if ( false === ( $upsells = get_site_transient( 'molongui_sw_data' ) ) )
			{
				// Get data from remote server
				$upsell_json = wp_safe_remote_get( $config['server']['url'], array( 'user-agent' => $config['server']['agent'] ) );

				if ( !is_wp_error( $upsell_json ) )
				{
					// Decode data to a stdClass object
					$upsells = json_decode( wp_remote_retrieve_body( $upsell_json ) );

					// Store data (cache) for future uses (within this week time)
					if ( $upsells ) set_site_transient( 'molongui_sw_data', $upsells, WEEK_IN_SECONDS );
				}
			}
		}
		// Free plugins do not download any data (it is banned). They get it from a local file.
		else
		{
			// Get data from local file
			$upsell_json = file_get_contents( $config['local']['url'] );

			// Set correct local path
			$upsell_json = str_replace( '%%MOLONGUI_PLUGIN_URL%%', MOLONGUI_AUTHORSHIP_URL, $upsell_json );

			// Decode data to a stdClass object
			$upsells = json_decode( $upsell_json );
		}

		// Check there is data to show
		$tmp = (array)$upsells->{$category};
		if ( !empty( $tmp ) )
		// Single line typecasting, as below, does not work in PHP 5.4
		//if ( !empty( (array)$upsells->{$category} ) )
		{
			// Avoid current plugin to be displayed
			if( $upsells->{$category}->{MOLONGUI_AUTHORSHIP_ID}->id ) unset( $upsells->{$category}->{MOLONGUI_AUTHORSHIP_ID} );

			// Slice array so just $num_items are displayed
			if( isset( $num_items ) && ( $num_items != 'all' ) && ( $num_items > 0 ) ) $upsells->{$category} = array_slice( (array)$upsells->{$category}, 0, $num_items );

			// DEBUG: Used to display results for development
			//echo "<pre>"; print_r($upsells); echo "</pre>";

			// Display data
			include_once( MOLONGUI_AUTHORSHIP_DIR . '/admin/views/html-admin-page-upsells.php' );
		}
	}
}