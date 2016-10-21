<?php

/**
 * Upsell ads configuration
 *
 * This file holds all the configuration parameters needed to get upsell information from Molongui's server.
 *
 * This file is read like:
 *      $config = include dirname( plugin_dir_path( __FILE__ ) ) . "/config/upsell.php";
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/config
 * @since      1.0.0
 * @version    1.0.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Parameters explanation
 *
 * server
 *  url         // URI to the JSON file on the remote Molongui's server {http://molongui.amitzy.com/upsells/molongui-local-upsells.json}.
 *  agent       // User agent to identify requester on server's log.
 * local
 *  url         // Local path to the JSON file.
 */

return array(
	'server'    => array(
		'url'       => '',
		'agent'     => 'Molongui Upsell Ads',
	),
	'local'     => array(
		'url'       => dirname ( plugin_dir_path( __FILE__ ) ) . '/admin/upsells/molongui-local-upsells.json',
	),
);