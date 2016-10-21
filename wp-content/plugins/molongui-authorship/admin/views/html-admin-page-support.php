<?php

use Molongui\Authorship\Includes\Plugin_System_Info;
//use Molongui\Authorship\Includes\Plugin_Email;

/**
 * Support tab.
 *
 * This file is used to markup the support tab of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/views
 * @since      1.3.0
 * @version    1.3.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap">

	<h3 class=""><?php _e( 'System information', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></h3>
	<div id="templateside">
		<p class="instructions"><?php _e( 'This page displays information about your WordPress configuration and Server information that may be useful for debugging.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></p>
	</div>
	<div id="template">
		<div>
			<textarea id="molongui-support-report" name="molongui-support-report" readonly="readonly" onclick="this.focus();this.select()" title="<?php _e( 'To copy the System Status, click below then press Ctrl + C (PC) or Cmd + C (Mac).', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>">
				<?php echo esc_html( Plugin_System_Info::display() ); ?>
			</textarea>
		</div>
		<p class="submit">
			<a id="get-support-report" download="molongui_support_report.txt" href="#" class="button"><?php _e( 'Download report as text file', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ?></a>
			<a id="send-support-report" href="#" class="button"><?php _e( 'Send report to Molongui', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ?></a>
		</p>
	</div>

</div>