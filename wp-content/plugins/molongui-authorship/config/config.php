<?php

/**
 * Plugin configuration.
 *
 * Contains the plugin main configuration parameters and declares them as global constants.
 *
 * This file is loaded like:
 *      require_once( plugin_dir_path( __FILE__ ) . 'config/config.php' );
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/config
 * @since      1.0.0
 * @version    1.3.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Constants.
 *
 * Add as many as needed by the plugin.
 *
 *  NAME                // The human readable plugin name.
 *  VERSION             // The plugin version.
 *  DB_VERSION          // The plugin database schema version.
 *  ID                  // The plugin slug
 *  SLUG                // The URI slug.
 *  TEXT_DOMAIN         // The plugin text-domain used for localisation. If changed, change it also at "/molongui-authorship.php".
 *  WEB                 // The plugin author's web.
 *  UPGRADABLE          // Whether the plugin has a premium version or not {yes | no}.
 *  MENU                // Admin page (backend) menu type: {topmenu | submenu}.
 *  SUBMENU             // Admin page submenu where to place plugin settings page: {dashboard | posts | media | pages | comments | appearance | plugins | users | tools | settings}.
 *  MAIN_SETTINGS       // DB key used to store main plugin settings.
 *  BOX_SETTINGS        // DB key used to store authorship box settings.
 *  STRING_SETTINGS     // DB key used to store frontend label customizations.
 *  SUPPORT_EMAIL       // Email address used to send support reports to.
 *
 * @since   1.0.0
 * @version 1.3.0
 */

$config = array(
	'NAME'            => 'Molongui Authorship',
	'VERSION'         => '1.3.0',
	'DB_VERSION'      => '2',
	'ID'              => 'molongui-authorship',
	'SLUG'            => 'molongui_authorship',
	'TEXT_DOMAIN'     => 'molongui-authorship',
	'WEB'             => '//molongui.amitzy.com/product/authorship',
	'UPGRADABLE'      => 'yes',
	'MENU'            => 'submenu',
	'SUBMENU'         => 'settings',
	'DB_SETTINGS'     => 'molongui_authorship_db_version',
	'MAIN_SETTINGS'   => 'molongui_authorship_main',
	'BOX_SETTINGS'    => 'molongui_authorship_box',
	'STRING_SETTINGS' => 'molongui_authorship_strings',
	'SUPPORT_EMAIL'   => 'molongui@amitzy.com',
);

/**
 * Global constant namespace.
 *
 * String added before each constant to avoid collisions in the global PHP namespace.
 *
 * @var     string
 */
$constant_prefix = 'MOLONGUI_AUTHORSHIP_';


//DO NOT EDIT FROM HERE ON...

/**
 * Define each constant if not already set
 *
 * @since   1.0.0
 * @version 1.0.0
 */
foreach( $config as $param => $value )
{
	$param = $constant_prefix . $param;
	if( !defined( $param ) ) define( $param, $value );
}

/**
 * Define paths.
 *
 * Defines plugin paths.
 *
 *  DIR                 // The plugin's local path. Something like: /var/www/hmtl/wp-content/plugins/plugin-name
 *  URL                 // The plugin's public path. Something like: http://domain.com/wp-content/plugins/plugin-name
 *  BASE_NAME           // The plugin's basename. Something like: plugin-name/plugin-name.php
 *
 * @since   1.0.0
 * @version 1.0.0
 */

if( !defined( $constant_prefix . 'DIR' ) )       define( $constant_prefix . 'DIR'       , dirname( plugin_dir_path( __FILE__ ) ) );
if( !defined( $constant_prefix . 'URL' ) )       define( $constant_prefix . 'URL'       , plugins_url( '', plugin_dir_path( __FILE__ ) ) );
if( !defined( $constant_prefix . 'BASE_NAME' ) ) define( $constant_prefix . 'BASE_NAME' , plugin_basename( dirname( plugin_dir_path( __FILE__ ) ) ). '/' . $config['ID'] . '.php' );