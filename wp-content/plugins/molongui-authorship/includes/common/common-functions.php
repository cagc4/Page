<?php

/**
 * Common functions.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes/common
 * @since      1.2.11
 * @version    1.2.18
 */


/**
 * Whether it is a premium plugin or not.
 *
 * This conditional tag checks if the plugin license is premium.
 * This is a boolean function, meaning it returns either TRUE or FALSE.
 *
 * @return   boolean    True if premium plugin, false otherwise.
 * @since    1.2.11
 * @version  1.2.11
 */
function is_premium()
{
	$path = MOLONGUI_AUTHORSHIP_DIR . '/premium';

	if ( file_exists( $path ) ) return ( TRUE );
	else return ( FALSE );
}


/**
 * Whether the plugin license has been activated.
 *
 * This conditional tag checks if the plugin license is active.
 * This is a boolean function, meaning it returns either TRUE or FALSE.
 *
 * @return   boolean    True if license activated, false otherwise.
 * @since    1.2.11
 * @version  1.2.11
 */
function is_active()
{

}


/**
 * Disables default Wordpress update for this plugin.
 *
 * @return   object     Plugins with an update.
 * @since    1.2.11
 * @version  1.2.11
 */
function disable_default_wp_updates( $value )
{
	if ( isset( $value ) && is_object( $value ) )
	{
		unset( $value->response[MOLONGUI_AUTHORSHIP_BASE_NAME] );
	}

	return $value;
}


/**
 * Prints human-readable information about a variable.
 *
 * This function is used in code development to debug.
 *
 * To output more than one variable at once, call the
 * function like: molongui_debug(array($var1, $var2)).
 *
 * @see     http://php.net/manual/en/function.print-r.php
 *
 * @return  void
 * @since   1.2.18
 * @version 1.2.18
 */
if ( !function_exists( 'molongui_debug' ) )
{
	function molongui_debug( $vars, $die = true )
	{
		echo "<pre>";
		print_r( $vars );
		echo "</pre>";
		if ( $die ) die;
	}
}