<?php
/**
 * In class RS_System_Diagnostic
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

$remote_url_key	= self::get_option( 'remote_url_key' );
if( !empty( $remote_url_key ) ) {
	$remote_url	= RSSD_SITE_URL.'/?'.RSSD_GET_VAR.'='.$remote_url_key;
} else {
	$remote_url = self::generate_url( TRUE );
}
$spc_32 = '                          '; /* 32 spaces */
$output	= $header = $data = '';
$last_transient		= 'rssd_'.$remote_url_key;
$last_transient_set	= self::get_option( 'last_transient_set' );
$last_transient_exp	= get_transient( $last_transient_set ) ? FALSE : TRUE;
$cached	= get_transient( $last_transient );
$header .= '// Generated by the RS System Diagnostic Plugin //'.RSSD_EOL.RSSD_EOL;
$header .= '//// Browser of Current Viewer ///////////////////'.RSSD_EOL.RSSD_EOL;
$header .= $browser.RSSD_EOL;
$header .= '//// End Browser of Current Viewer ///////////////'.RSSD_EOL.RSSD_EOL;
if( self::is_remote_view() && $last_transient_set === $last_transient && TRUE === $last_transient_exp ) {
	self::generate_url( TRUE );
	$error = 'ERROR: URL has expired. Please request a new URL. [Code E002]';
	self::wp_die( $error, '404' );
} elseif( self::is_remote_view() && !empty( $cached ) ) {

	/* Use Cached Output*/	
	$output = $header.$cached;
} elseif( is_admin() || ( self::is_remote_view() && empty( $cached ) ) ) {

	/* Generate Fresh Output*/
	$mysql_ver = $wpdb->use_mysqli ? @mysqli_get_server_info( $wpdb->dbh ) : @mysql_get_server_info();
	$wp_memory_limit		= WP_MEMORY_LIMIT;
	$wp_max_memory_limit	= WP_MAX_MEMORY_LIMIT;
	$session_id				= @session_id();
	$session_name_default	= esc_html( ini_get( 'session.name' ) );
	$error_reporting_level	= self::error_level_tostring( error_reporting(), ' | ' );
	$error_reporting_level	= ( strpos( $error_reporting_level, 'E_ALL' ) === 0 ) ? 'E_ALL' : $error_reporting_level;
	$wp_database_size		= self::get_db_size();

	/* Start Output*/
	$data .= 'Website Name:             '. get_bloginfo('name') . RSSD_EOL;
	$data .= RSSD_EOL;

	$data .= 'WordPress Address (URL):  '. site_url() .'  |  site_url() - The location where your WordPress core files reside.'.RSSD_EOL;	/* SITE_URL - site_url() */
	$data .= 'Site Address (URL):       '. home_url() .'  |  home_url() - The URL people should use to get to your site.'.RSSD_EOL;			/* HOME_URL - home_url() */
	if( !is_multisite() && self::get_domain( site_url() ) !== self::get_domain( home_url() ) ) {
		$data .= 'Domain Mismatch:          '. 'WARNING - CONFIGURATION: The web domains in your "WordPress Address" and "Site Address" settings do not match.'. RSSD_EOL;
	}
	$data .= RSSD_EOL;

	$data .= 'WordPress Version:        '. RSSD_WP_VERSION . RSSD_EOL;
	$data .= 'Multisite:                '; $data .= is_multisite() ? 'Yes' : 'No'; $data .= RSSD_EOL;
	$data .= 'Permalink Structure:      '. get_option( 'permalink_structure' ) . RSSD_EOL;
	$data .= 'Active Theme:             '. $theme . RSSD_EOL;
	$data .= RSSD_EOL;

	if( !empty($web_host) ) {
		$data .= 'Web Host:                 '. $web_host . RSSD_EOL;
		$data .= RSSD_EOL;
	}

	$data .= 'PHP Version:              '. RSSD_PHP_VERSION; $data .= ( version_compare( RSSD_PHP_VERSION, '5.4', '<' ) ) ? '  |  WARNING - SECURITY/PERFORMANCE: The version of PHP running on your server is extremely outdated.'.RSSD_EOL.$spc_32.'Please upgrade your PHP Version to 5.5, 5.6, or higher.' : ''; $data .= RSSD_EOL;
	$data .= 'MySQL Version:            '. $mysql_ver; $data .= ( version_compare( $mysql_ver, '5.5', '<' ) ) ? '  |  WARNING - PERFORMANCE: WordPress requires MySQL Version 5.5 or higher. Please upgrade your MySQL Version.' : ''; $data .= RSSD_EOL;
	$data .= 'Server Software:          '. $_SERVER['SERVER_SOFTWARE'] . ' / '. PHP_OS . RSSD_EOL;
	$data .= 'Server Hostname:          '. RSSD_SERVER_HOSTNAME . RSSD_EOL;
	$data .= RSSD_EOL;

	$data .= 'WP Memory Limit:          '; $data .= $wp_memory_limit; $data .= ( $wp_memory_limit < 64 ) ? '  |  WARNING - PERFORMANCE: WordPress Memory Limit too low. Should be at least 64MB. 96-128MB+ is recommended.' : ''; $data .= RSSD_EOL;
	$data .= 'WP Admin Memory Limit:    '. $wp_max_memory_limit.RSSD_EOL;
	$data .= 'Current WP Memory Used:   '. self::wp_memory_used() .RSSD_EOL;
	$data .= 'Max WP Memory Used:       '. self::wp_memory_used( TRUE ) .RSSD_EOL;
	$data .= 'PHP Safe Mode:            '; $data .= ini_get( 'safe_mode' ) ? 'Yes' : 'No'; $data .= RSSD_EOL;
	$data .= 'PHP Memory Limit:         '. ini_get( 'memory_limit' ) . RSSD_EOL;
	$data .= 'PHP Upload Max Size:      '. ini_get( 'upload_max_filesize' ) . RSSD_EOL;
	$data .= 'PHP Post Max Size:        '. ini_get( 'post_max_size' ) . RSSD_EOL;
	$data .= 'PHP Upload Max Filesize:  '. ini_get( 'upload_max_filesize' ) . RSSD_EOL;
	$data .= 'PHP Time Limit:           '. ini_get( 'max_execution_time' ) . RSSD_EOL;
	$data .= 'PHP Max Input Vars:       '. ini_get( 'max_input_vars' ) . RSSD_EOL;
	$data .= 'PHP Arg Separator:        '. ini_get( 'arg_separator.output' ) . RSSD_EOL;
	$data .= 'PHP Allow URL File Open:  '; $data .= ini_get( 'allow_url_fopen' ) ? 'Yes' : 'No'; $data .= RSSD_EOL;
	$data .= 'PHP Short Open Tags:      '; $data .= ini_get( 'short_open_tag' ) ? 'Enabled  |  WARNING - SECURITY: \'short_open_tag\' should be disabled.' : 'Disabled'; $data .= RSSD_EOL;
	$data .= 'Expose PHP:               '; $data .= ini_get( 'expose_php' ) ? 'Yes  |  WARNING - SECURITY: \'expose_php\' should be disabled.' : 'No'; $data .= RSSD_EOL;
	$data .= RSSD_EOL;

	/**
	 * wp-config.php CONSTANTS			https://codex.wordpress.org/Editing_wp-config.php
	 */
	$data .= 'WP_DEBUG:                 '; $data .= defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled': 'Disabled' : 'Not set'; $data .= RSSD_EOL;
	$data .= 'WP_DEBUG_LOG:             '; $data .= defined( 'WP_DEBUG_LOG' ) ? WP_DEBUG_LOG ? 'Enabled' : 'Disabled' : 'Not set'; $data .= RSSD_EOL;
	$data .= 'WP_DEBUG_DISPLAY:         '; $data .= defined( 'WP_DEBUG_DISPLAY' ) ? WP_DEBUG_DISPLAY ? 'Enabled' : 'Disabled' : 'Not set'; $data .= RSSD_EOL;
	$data .= 'SCRIPT_DEBUG:             '; $data .= defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG ? 'Enabled' : 'Disabled' : 'Not set'; $data .= RSSD_EOL;
	$data .= 'WP_CACHE:                 '; $data .= defined( 'WP_CACHE' ) ? WP_CACHE ? 'Enabled' : 'Disabled' : 'Not set'; $data .= RSSD_EOL;
	$data .= 'AUTOSAVE_INTERVAL:        '; $data .= defined( 'AUTOSAVE_INTERVAL' ) ? AUTOSAVE_INTERVAL : 'Not set'; $data .= RSSD_EOL;
	$data .= 'WP_POST_REVISIONS:        '; $data .= defined( 'WP_POST_REVISIONS' ) ? WP_POST_REVISIONS : 'Not set'; $data .= RSSD_EOL;
	$data .= 'EMPTY_TRASH_DAYS:         '; $data .= defined( 'EMPTY_TRASH_DAYS' ) ? EMPTY_TRASH_DAYS : 'Not set'; $data .= RSSD_EOL;
	$data .= 'DISALLOW_FILE_EDIT:       '; $data .= defined( 'DISALLOW_FILE_EDIT' ) ? DISALLOW_FILE_EDIT ? 'Enabled' . RSSD_EOL : 'Disabled' . RSSD_EOL : 'Not set  |  WARNING - SECURITY: This should be enabled.' . RSSD_EOL;
	$data .= RSSD_EOL;

	$data .= 'WP Database Size:         '. $wp_database_size . RSSD_EOL;
	$data .= 'WP Table Prefix Length:   '. strlen( $wpdb->prefix ).'  |  '; if( strlen( $wpdb->prefix ) > 16 ) { $data .= 'ERROR: Too Long'; } elseif( strlen( $wpdb->prefix ) < 8 ) { $data .= 'ERROR: Too Short'; } else { $data .= 'STATUS: ACCEPTABLE'; } $data .= RSSD_EOL;
	$data .= 'WP Table Prefix Default:  '; $data .= $wpdb->prefix !== 'wp_' ? 'No' : 'Yes  |  WARNING - SECURITY: The WP Table Prefix should be changed to a custom value between 8-16 characters long.'; $data .= RSSD_EOL; 
	$data .= RSSD_EOL;

	$data .= 'Show On Front:            '. get_option( 'show_on_front' ) . RSSD_EOL;
	$data .= 'Page On Front:            '; $id = get_option( 'page_on_front' ); $data .= trim( get_the_title( $id ) . ' (#' . $id . ')' ) . RSSD_EOL;
	$data .= 'Page For Posts:           '; $id = get_option( 'page_for_posts' ); $data .= trim( get_the_title( $id ) . ' (#' . $id . ')' ) . RSSD_EOL;
	$data .= 'Registered Post Stati:    '. implode( ', ', get_post_stati() ) . RSSD_EOL; 
	$data .= RSSD_EOL;

	$data .= 'WP Remote Post:           '. $WP_REMOTE_POST.RSSD_EOL;
	$data .= 'PHP Sessions:             '; $data .= ( isset( $_SESSION ) || $session_id ) ? 'Active' : 'Inactive'; $data .= RSSD_EOL;
	$data .= 'Default Session Name:     '; $data .= $session_name_default; $data .= ( $session_name_default === 'PHPSESSID' ) ? '  |  WARNING - SECURITY: \'session.name\' should be set to a custom value, not the PHP default.' : ''; $data .= RSSD_EOL;
	$data .= 'Session Cookie Path:      '. esc_html( ini_get( 'session.cookie_path' ) ) . RSSD_EOL;
	$data .= 'Session Save Path:        '. esc_html( ini_get( 'session.save_path' ) ) . RSSD_EOL;
	$data .= 'Use Session Cookies:      '; $data .= ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; $data .= RSSD_EOL;
	$data .= 'Session Use Cookies Only: '; $data .= ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off  |  WARNING - SECURITY: \'session.use_only_cookies\' should be enabled.'; $data .= RSSD_EOL;
	$data .= 'Session Cookie HTTP Only: '; $data .= ini_get( 'session.cookie_httponly' ) ? 'On' : 'Off  |  WARNING - SECURITY: \'session.cookie_httponly\' should be enabled to prevent JavaScript from accessing Session Cookies.'; $data .= RSSD_EOL;
	$data .= RSSD_EOL;

	$data .= 'DISPLAY ERRORS:           '; $data .= ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')  |  WARNING - SECURITY: \'display_errors\' should be disabled.' : 'N/A'; $data .= RSSD_EOL;
	/**
	 * TO DO: Add note letting users know ways to fix: php.ini, .htaccess, and wp-config.php
	 */
	$data .= 'ERROR LOGGING:            '; $data .= ( ini_get( 'log_errors' ) ) ? 'On' : 'Off  |  NOTICE - DEBUGGING: To properly debug your site, \'log_errors\' should be enabled.'; $data .= RSSD_EOL;
	$data .= 'ERROR LOG LOCATION:       '; $data .= ( ini_get( 'error_log' ) ) ? esc_html( ini_get( 'error_log' ) ) : 'Not Set  |  WARNING - SECURITY: \'error_log\' should be set to a custom location outside the web root.'; $data .= RSSD_EOL;
	$data .= 'ERROR REPORTING LEVEL:    '; $data .= ( error_reporting() ) ? $error_reporting_level : 'N/A  |  NOTICE - DEBUGGING: To properly debug your site, \'error_reporting\' should be set to E_ALL'; $data .= RSSD_EOL; 
	$data .= RSSD_EOL;

	$data .= 'FSOCKOPEN:                '; $data .= ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; $data .= RSSD_EOL;
	$data .= 'cURL:                     '; $data .= ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; $data .= RSSD_EOL;
	$data .= 'SOAP Client:              '; $data .= ( class_exists( 'SoapClient' ) ) ? 'Your server has the SOAP Client enabled.' : 'Your server does not have the SOAP Client enabled.'; $data .= RSSD_EOL;
	$data .= 'SUHOSIN:                  '; $data .= ( extension_loaded( 'suhosin' ) ) ? 'Your server has SUHOSIN installed.' : 'Your server does not have SUHOSIN installed.'; $data .= RSSD_EOL;
	$data .= 'MySQLi:                   '; $data .= ( function_exists( 'mysqli_init' ) || extension_loaded( 'mysqli' ) ) ? 'Your server has the MySQLi extension enabled.' : 'Your server does not have the MySQLi extension enabled.  |  WARNING - PERFORMANCE: MySQLi should be enabled.'; $data .= RSSD_EOL;
	$data .= RSSD_EOL.RSSD_EOL.'ACTIVE PLUGINS:'.RSSD_EOL.RSSD_EOL;

	if ( !function_exists( 'get_plugins' ) ) {
		include ABSPATH . 'wp-admin/includes/plugin.php';
	}
	/* TO DO: Replace with relevant functions */
	$plugins			= get_plugins();
	$active_plugins		= get_option( 'active_plugins', array() );
	$inactive_plugins	= array();
	$i = 0;
	foreach ( $plugins as $plugin_path => $plugin ) {
		if( ! in_array( $plugin_path, $active_plugins ) ) { $inactive_plugins[$plugin_path] = $plugin; continue; }
		$data .= $plugin['Name'] . ': ' . $plugin['Version'] .RSSD_EOL; $i++;
	}
	$n = (int) $i;
	$data .= RSSD_EOL.'TOTAL ACTIVE PLUGINS: '. $n; $data .= ( $n > 30 ) ? '  |  WARNING - PERFORMANCE: Using this many plugins can cause site slowdowns and conflicts. Try to minimize plugins used.' : ''; $data .= RSSD_EOL;
	if( is_multisite() ) {
		$data .= RSSD_EOL.RSSD_EOL.'NETWORK ACTIVE PLUGINS:'.RSSD_EOL.RSSD_EOL;
		$network_plugins			= wp_get_active_network_plugins();
		$network_active_plugins		= get_site_option( 'active_sitewide_plugins', array() );
		$network_inactive_plugins	= array();
		$i = 0;
		foreach ( $network_plugins as $plugin_path ) {
			$plugin_base = plugin_basename( $plugin_path );
			if( ! isset( $network_active_plugins[$plugin_base] ) ) { continue; }
			unset( $inactive_plugins[$plugin_path] );
			$plugin = get_plugin_data( $plugin_path );
			$data .= $plugin['Name'] . ' :' . $plugin['Version'] .RSSD_EOL; $i++;
		}
		$n = (int) $i;
		$data .= RSSD_EOL.'TOTAL NETWORK ACTIVE PLUGINS: '. $n; $data .= ( $n > 30 ) ? '  |  WARNING - PERFORMANCE: Using this many network active plugins can cause site slowdowns and conflicts. Try to minimize plugins used.' : ''; $data .= RSSD_EOL;
	}
	if( !empty( $inactive_plugins ) ) {
		$data .= RSSD_EOL.RSSD_EOL.'INACTIVE PLUGINS:'.RSSD_EOL.RSSD_EOL;
		foreach ( $inactive_plugins as $plugin_path => $plugin ) {
			$data .= $plugin['Name'] . ': ' . $plugin['Version'] .RSSD_EOL;
		}
		$n = (int) count( $inactive_plugins );
		$data .= RSSD_EOL.'TOTAL INACTIVE PLUGINS: '. $n; $data .= ( $n > 10 ) ? '  |  WARNING - PERFORMANCE: Even inactive plugins can slow down your site. If not using a plugin, uninstall it completely.' : ''; $data .= RSSD_EOL;
	}
	$data .= RSSD_EOL;
	if( has_action( 'rssd_extra_info' ) ) {
		$extra_info = apply_filters( 'rssd_extra_info', '' );
		if( !empty( $extra_info ) ) {
			$data .= RSSD_EOL.RSSD_EOL.'EXTRA INFO:'.RSSD_EOL.RSSD_EOL;
			$data .= $extra_info.RSSD_EOL.RSSD_EOL;
		}
	}
	$output = $header.$data;
	$last_transient = 'rssd_'.$remote_url_key;
	set_transient( $last_transient, $data, DAY_IN_SECONDS );
	self::update_option( array( 'last_url_key' => $remote_url_key, 'last_transient_set' => $last_transient, ) );
	if( is_admin() && is_super_admin() ) { /* If in admin and user is super admin ( 'is_admin()' is location, not user capability! */
		if( !empty( $_GET['option'] ) && 'advanced' === $_GET['option'] ) {
			$data_adv = '';
			$data_adv .= RSSD_EOL.RSSD_EOL.'// ADVANCED //////////////////////////////////////'.RSSD_EOL.RSSD_EOL.RSSD_EOL;
			$wp_config = self::detect_wpconfig();
			if( !empty( $wp_config ) ) {
				$obsc_phrase = '***** DATA HIDDEN FOR SECURITY *****';
				$file_contents = file_get_contents( $wp_config );
				$file_contents = str_replace( array( "\r\r", "\r\n", "\n\n\n\n", "\n\n\n", ), array( "\n\n", "\n", "\n\n\n", "\n\n", ), trim( stripslashes( $file_contents ) ) );
				$file_contents = preg_replace( "~(table_prefix\s*\=\s*['\"]).*?(['\"];\s*(?:\n|\r|\rn))~i", "$1".$obsc_phrase."$2", $file_contents );
				$file_contents = preg_replace( "~(define\(\s*['\"](?:DB_NAME|DB_USER|DB_PASSWORD|DB_HOST|AUTH_KEY|SECURE_AUTH_KEY|LOGGED_IN_KEY|NONCE_KEY|AUTH_SALT|SECURE_AUTH_SALT|LOGGED_IN_SALT|NONCE_SALT|NONCE_[A-Z0-9]+|SECURE_AUTH_[A-Z0-9]+|LOGGED_IN_[A-Z0-9]+|SECURE_AUTH_[A-Z0-9]+)\s*['\"],\s*).*?(\);\s*(?:\n|\r|\rn))~", "$1".$obsc_phrase."$2", $file_contents );
				$file_contents = trim( $file_contents );
				$data_adv .= '// File Location: '.$wp_config.RSSD_EOL;
				$data_adv .= '// File Contents: wp-config.php //////////////////'.RSSD_EOL.RSSD_EOL;
				$data_adv .= $file_contents.RSSD_EOL;
				$data_adv .= RSSD_EOL.'// End File Contents: wp-config.php //////////////'.RSSD_EOL.RSSD_EOL.RSSD_EOL;
			}
			$htaccess_files = self::detect_htaccess();
			if( !empty( $htaccess_files ) && is_array( $htaccess_files ) ) {
				foreach( $htaccess_files as $i => $file ) {
					$file_contents = file_get_contents( $file );
					$file_contents = str_replace( array( "\r\r", "\r\n", "\n\n\n\n", "\n\n\n", ), array( "\n\n", "\n", "\n\n\n", "\n\n", ), trim( stripslashes( $file_contents ) ) );
					/* Custom replace rule goes here */
					
					$file_contents = preg_replace( "~\n+~", "\n", $file_contents );
					$file_contents = trim( $file_contents );
					$data_adv .= '// File Location: '.$file.RSSD_EOL;
					$data_adv .= '// File Contents: .htaccess //////////////////////'.RSSD_EOL.RSSD_EOL;
					$data_adv .= $file_contents.RSSD_EOL;
					$data_adv .= RSSD_EOL.'// End File Contents: .htaccess //////////////////'.RSSD_EOL.RSSD_EOL.RSSD_EOL;
				}
			}
			$php_ini_files = self::detect_php_ini();
			if( !empty( $php_ini_files ) && is_array( $php_ini_files ) ) {
				foreach( $php_ini_files as $i => $file ) {
					$file_contents = file_get_contents( $file );
					$file_contents = str_replace(  array( "\r\r", "\r\n", "\n\n\n\n", "\n\n\n", ), array( "\n\n", "\n", "\n\n\n", "\n\n", ), trim( stripslashes( $file_contents ) ) );
					$file_contents = preg_replace( "~;.*?\n~", "\n", $file_contents."\n" );
					$file_contents = preg_replace( "~\n+~", "\n", $file_contents );
					$file_contents = trim( $file_contents );
					$data_adv .= '// File Location: '.$file.RSSD_EOL;
					$data_adv .= '// File Contents: php.ini ////////////////////////'.RSSD_EOL.RSSD_EOL;
					$data_adv .= $file_contents.RSSD_EOL;
					$data_adv .= RSSD_EOL.'// End File Contents: php.ini ////////////////////'.RSSD_EOL.RSSD_EOL.RSSD_EOL;
				}
			}

			/* TO DO: Scan for and pull in debug.log and error_log files - Add self::detect_debug_log() & self::detect_error_log() */

			$output .= $data_adv;
		}
	}
}
else {
	$error = 'ERROR: Nothing to see here. [Code E003]';
	self::wp_die( $error, '404' );
}

echo $output;

