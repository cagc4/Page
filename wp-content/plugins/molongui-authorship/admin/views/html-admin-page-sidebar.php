<?php

use Molongui\Authorship\Includes\Plugin_Upsell;

/**
 * Sidebar
 *
 * Shows a sidebar with upsells in the settings page.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/views
 * @since      1.0.0
 * @version    1.0.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

?>

<div class="sidebar">
	<div class="upsells">
		<?php Plugin_Upsell::output( 'featured', 2, 36, null ); ?>
	</div>
</div>