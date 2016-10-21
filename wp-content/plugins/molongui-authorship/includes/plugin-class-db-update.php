<?php

namespace Molongui\Authorship\Includes;

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Class DB_Update.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.3.0
 * @version    1.3.0
 */
class DB_Update
{
	/**
	 * The target version of the plugin's database schema to reach.
	 *
	 * @access  protected
	 * @var     integer    $db_version     The current version of the plugin database schema.
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	protected $target_version;

	/**
	 * Class constructor.
	 *
	 * @access  public
	 * @param   integer     $target_version     Current database schema version.
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function __construct( $target_version )
	{
		$this->target_version = $target_version;
	}

	/**
	 * Checks whether a database update is needed.
	 *
	 * @access  private
	 * @return  bool
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function db_update_needed()
	{
		// Get current database schema version.
		$current_version = get_option( MOLONGUI_AUTHORSHIP_DB_SETTINGS );

		// If no version set, assume it to be 1.
		if ( empty( $current_version ) ) $current_version = 1;

		// Check update need.
		if ( $current_version >= $this->target_version ) return false;
		return true;
	}

	/**
	 * Runs updates to get database schema to the latest version.
	 *
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function run_update()
	{
		// Get current database schema version.
		$current_db_ver = get_option( MOLONGUI_AUTHORSHIP_DB_SETTINGS, 1 );

		// Set the target version to reach.
		$target_db_ver = $this->target_version;

		// Run update routines one by one until the current version reaches the target version.
		while ( $current_db_ver < $target_db_ver )
		{
			// Increment the current db_ver by one.
			$current_db_ver ++;

			// Each db version will require a separate update function, for example, for db_ver 3, the function name should be db_update_3.
			$func = "db_update_{$current_db_ver}";
			if ( method_exists( $this, $func ) )
			{
				$this->{$func}();
			}

			// Update the option in the database, so that this process can always pick up where it left off.
			update_option( MOLONGUI_AUTHORSHIP_DB_SETTINGS, $current_db_ver );
		}
	}

	/**
	 * Update data to be compatible with version 1.3.0 and higher.
	 *
	 * Changes made on version 1.3.0:
	 *
	 *  - removed db key named 'molongui_authorship_config' (aka MOLONGUI_AUTHORSHIP_CONFIG_KEY)
	 *  - removed db key named 'molongui_authorship_deactivate_checkbox'
	 *  + added db key name 'molongui_authorship_main' (aka MOLONGUI_AUTHORSHIP_MAIN_SETTINGS)
	 *  + added db key name 'molongui_authorship_box' (aka MOLONGUI_AUTHORSHIP_BOX_SETTINGS)
	 *  + added db key name 'molongui_authorship_strings' (aka MOLONGUI_AUTHORSHIP_STRING_SETTINGS)
	 *  - removed 'molongui_authorship' prefix from the array indexes names
	 *  ~ changed 'molongui_authorship_related_show' setting name to 'show_related'
	 *  ~ changed 'layout-1' layout name to 'ribbon'
	 *  ~ changed 'layout-1-rtl' layout name to 'ribbon-rtl'
	 *  ~ changed 'molongui_guest_author_link' array index name to 'molongui_guest_author_blog'
	 *  ~ changed 'molongui_guest_author_xxxxxx' metadata option names to '_molongui_guest_author_xxxxxx' (to hide them from the backend)
	 *  ~ changed 'molongui_author_box_display' metadata option names to '_molongui_author_box_display' (to hide it from the backend)
	 *
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function db_update_2()
	{
		global $wpdb;

		// Get current settings.
		// 'molongui_authorship_config' was the db key used to store the plugin settings prior to version 1.3.0.
		$settings = get_option( 'molongui_authorship_config' );

		// Convert plugin settings.
		$main_settings = array(
			'show_related'       => $settings['molongui_authorship_related_show'],
			'related_order_by'   => $settings['molongui_authorship_related_order_by'],
			'related_order'      => $settings['molongui_authorship_related_order'],
			'related_items'      => $settings['molongui_authorship_related_items'],
			'show_tw'            => $settings['molongui_authorship_show_social_networks_tw'],
			'show_fb'            => $settings['molongui_authorship_show_social_networks_fb'],
			'show_in'            => $settings['molongui_authorship_show_social_networks_in'],
			'show_gp'            => $settings['molongui_authorship_show_social_networks_gp'],
			'show_yt'            => $settings['molongui_authorship_show_social_networks_yt'],
			'show_pi'            => $settings['molongui_authorship_show_social_networks_pi'],
			'show_tu'            => $settings['molongui_authorship_show_social_networks_tu'],
			'show_ig'            => $settings['molongui_authorship_show_social_networks_ig'],
			'show_xi'            => $settings['molongui_authorship_show_social_networks_xi'],
			'show_re'            => $settings['molongui_authorship_show_social_networks_re'],
			'show_vk'            => $settings['molongui_authorship_show_social_networks_vk'],
			'show_fl'            => $settings['molongui_authorship_show_social_networks_fl'],
			'show_vi'            => $settings['molongui_authorship_show_social_networks_vi'],
			'show_me'            => $settings['molongui_authorship_show_social_networks_me'],
			'show_we'            => $settings['molongui_authorship_show_social_networks_we'],
			'show_de'            => $settings['molongui_authorship_show_social_networks_de'],
			'show_st'            => $settings['molongui_authorship_show_social_networks_st'],
			'show_my'            => $settings['molongui_authorship_show_social_networks_my'],
			'show_ye'            => $settings['molongui_authorship_show_social_networks_ye'],
			'show_mi'            => $settings['molongui_authorship_show_social_networks_mi'],
			'show_so'            => $settings['molongui_authorship_show_social_networks_so'],
			'show_la'            => $settings['molongui_authorship_show_social_networks_la'],
			'show_fo'            => $settings['molongui_authorship_show_social_networks_fo'],
			'show_sp'            => $settings['molongui_authorship_show_social_networks_sp'],
			'show_vm'            => $settings['molongui_authorship_show_social_networks_vm'],
			'add_opengraph_meta' => $settings['molongui_authorship_add_opengraph_meta'],
			'add_google_meta'    => $settings['molongui_authorship_add_google_meta'],
			'add_facebook_meta'  => $settings['molongui_authorship_add_facebook_meta'],
			'admin_menu_level'   => $settings['molongui_authorship_admin_menu_level'],
			'keep_config'        => $settings['molongui_authorship_keep_config'],
			'keep_data'          => $settings['molongui_authorship_keep_data'],
		);

		$box_settings = array(
			'display'             => $settings['molongui_authorship_display'],
			'position'            => $settings['molongui_authorship_position'],
			'hide_if_no_bio'      => $settings['molongui_authorship_hide_if_no_bio'],
			'layout'              => ( $settings['molongui_authorship_layout'] == 'layout-1' ? 'ribbon' : ( $settings['molongui_authorship_layout'] == 'layout-1-rtl' ? 'ribbon-rtl' : $settings['molongui_authorship_layout'] ) ),
			'box_shadow'          => $settings['molongui_authorship_box_shadow'],
			'box_border'          => $settings['molongui_authorship_box_border'],
			'box_border_color'    => $settings['molongui_authorship_box_border_color'],
			'box_background'      => $settings['molongui_authorship_box_background'],
			'img_style'           => $settings['molongui_authorship_img_style'],
			'img_default'         => $settings['molongui_authorship_img_default'],
			'name_size'           => $settings['molongui_authorship_name_size'],
			'name_color'          => $settings['molongui_authorship_name_color'],
			'meta_size'           => $settings['molongui_authorship_meta_size'],
			'meta_color'          => $settings['molongui_authorship_meta_color'],
			'bio_size'            => $settings['molongui_authorship_bio_size'],
			'bio_color'           => $settings['molongui_authorship_bio_color'],
			'bio_align'           => $settings['molongui_authorship_bio_align'],
			'bio_style'           => $settings['molongui_authorship_bio_style'],
			'show_icons'          => $settings['molongui_authorship_icons_show'],
			'icons_size'          => $settings['molongui_authorship_icons_size'],
			'icons_color'         => $settings['molongui_authorship_icons_color'],
			'icons_style'         => $settings['molongui_authorship_icons_style'],
			'bottom_bg'           => $settings['molongui_authorship_bottom_bg'],
			'bottom_border'       => $settings['molongui_authorship_bottom_border'],
			'bottom_border_color' => $settings['molongui_authorship_bottom_border_color'],
		);

		// Insert new settings.
		add_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS, $main_settings );
		add_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS, $box_settings );

		// Remove old entries.
		delete_option( 'molongui_authorship_config' );
		delete_option( 'molongui_authorship_deactivate_checkbox' );

		// Change 'molongui_guest_author_link' to 'molongui_guest_author_blog'.
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = 'molongui_guest_author_blog' WHERE meta_key = 'molongui_guest_author_link';" );

		// Change 'molongui_guest_author_xxxxx' to '_molongui_guest_author_xxxxx'.
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_id' WHERE meta_key = 'molongui_guest_author_id';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_mail' WHERE meta_key = 'molongui_guest_author_mail';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_link' WHERE meta_key = 'molongui_guest_author_blog';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_job' WHERE meta_key = 'molongui_guest_author_job';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_company' WHERE meta_key = 'molongui_guest_author_company';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_company_link' WHERE meta_key = 'molongui_guest_author_company_link';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_twitter' WHERE meta_key = 'molongui_guest_author_twitter';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_facebook' WHERE meta_key = 'molongui_guest_author_facebook';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_linkedin' WHERE meta_key = 'molongui_guest_author_linkedin';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_gplus' WHERE meta_key = 'molongui_guest_author_gplus';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_youtube' WHERE meta_key = 'molongui_guest_author_youtube';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_pinterest' WHERE meta_key = 'molongui_guest_author_pinterest';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_tumblr' WHERE meta_key = 'molongui_guest_author_tumblr';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_instagram' WHERE meta_key = 'molongui_guest_author_instagram';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_xing' WHERE meta_key = 'molongui_guest_author_xing';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_renren' WHERE meta_key = 'molongui_guest_author_renren';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_vkg' WHERE meta_key = 'molongui_guest_author_vk';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_flickr' WHERE meta_key = 'molongui_guest_author_flickr';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_vine' WHERE meta_key = 'molongui_guest_author_vine';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_meetup' WHERE meta_key = 'molongui_guest_author_meetup';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_weibo' WHERE meta_key = 'molongui_guest_author_weibo';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_deviantart' WHERE meta_key = 'molongui_guest_author_deviantart';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_stumbleupon' WHERE meta_key = 'molongui_guest_author_stumbleupon';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_myspace' WHERE meta_key = 'molongui_guest_author_myspace';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_yelp' WHERE meta_key = 'molongui_guest_author_yelp';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_mixi' WHERE meta_key = 'molongui_guest_author_mixi';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_soundcloud' WHERE meta_key = 'molongui_guest_author_soundcloud';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_lastfm' WHERE meta_key = 'molongui_guest_author_lastfm';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_foursquare' WHERE meta_key = 'molongui_guest_author_foursquare';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_spotify' WHERE meta_key = 'molongui_guest_author_spotify';" );
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_guest_author_vimeo' WHERE meta_key = 'molongui_guest_author_vimeo';" );

		// Change 'molongui_author_box_display' to '_molongui_author_box_display'.
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key = '_molongui_author_box_display' WHERE meta_key = 'molongui_author_box_display';" );
	}

}