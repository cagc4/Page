<?php

namespace Molongui\Authorship\Includes;

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

class Plugin_System_Info
{
	/**
	 * Render plugin page title, information and info textarea.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	static function render_info()
	{
		include( MOLONGUI_AUTHORSHIP_DIR . '/admin/views/html-admin-page-support.php' );
	}


	/**
	 * Gather data and generate system status report.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	static function display()
	{
		$browser = new Plugin_Browser();

		if ( get_bloginfo( 'version' ) < '3.4' ) {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
			$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
		} else {
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version;
		}
		// Try to identify the hosting provider
		$host = false;
		if ( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif ( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		}
		$request['cmd'] = '_notify-validate';
		$params = array(
			'sslverify' => false,
			'timeout'   => 60,
			'body'      => $request,
		);
		$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$WP_REMOTE_POST = 'wp_remote_post() works' . "\n";
		} else {
			$WP_REMOTE_POST = 'wp_remote_post() does not work' . "\n";
		}
		return self::display_output( $browser, $theme, $host, $WP_REMOTE_POST );
	}


	/**
	 * Render System Status.
	 *
	 * @param   string  Browser information
	 * @param   string  Theme Data
	 * @param   string  Theme name
	 * @param   string  Host
	 * @param   string  WP Remote Host
	 * @return  string  Output of System Status display
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	static function display_output( $browser, $theme, $host, $WP_REMOTE_POST )
	{
		global $wpdb;

		ob_start();
		//Render Info Display
		include( MOLONGUI_AUTHORSHIP_DIR . '/admin/views/output.php' );
		return ob_get_clean();
	}


	/**
	 * Letter to number converter.
	 *
	 * @param   string       $v
	 * @return  int|string
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	static function let_to_num( $v )
	{
		$l   = substr( $v, -1 );
		$ret = substr( $v, 0, -1 );

		switch ( strtoupper( $l ) )
		{
			case 'P': // fall-through
			case 'T': // fall-through
			case 'G': // fall-through
			case 'M': // fall-through
			case 'K': // fall-through
				$ret *= 1024;
			break;

			default:
			break;
		}

		return $ret;
	}


	/**
	 * Sends plain-text system info report to Molongui.
	 *
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function send_support_report()
	{
		// Check security (If nonce is invalid, die)
		//check_ajax_referer( 'myajax-js-nonce', 'security', true );

		// Leave if no data to send.
		if ( !is_admin() and !isset( $_POST['report'] ) ) return;

		// Get sender info.
		global $current_user;
		get_currentuserinfo();

		// Prepare headers.
		$subject = sprintf( __( 'MOLONGUI - Support report for %s', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), $_POST['domain'] );
		$headers = array(
			'From: '         . $current_user->display_name . ' <' . 'noreply@'.$_POST['domain'] . '>',
			'Reply-To: '     . $current_user->display_name . ' <' . $current_user->user_email   . '>',
			'Content-Type: ' . 'text/plain; charset=UTF-8',
		);

		// Sent report.
		$sent = wp_mail( MOLONGUI_AUTHORSHIP_SUPPORT_EMAIL, $subject, $_POST['report'], $headers );

		// Return result.
		echo ( $sent ? 'sent' : 'error' );

		// Avoid 'admin-ajax.php' to append the value outputted with a "0"
		wp_die();
	}

}