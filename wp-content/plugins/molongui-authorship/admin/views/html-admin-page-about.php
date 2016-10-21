<?php

use Molongui\Authorship\Includes\Plugin_Upsell;

/**
 * About page
 *
 * This file is used to markup the about page of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/views
 * @since      1.0.0
 * @version    1.2.13
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

?>

<div class="molongui-about-wrap">

	<h1 class="brand">Molongui
		<span class="by"><?php _e('by', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?> <a href="//www.amitzy.com/" target="_blank" title="Amitzy"><?php _e('Amitzy', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></a></span>
	</h1>

	<ul class="about">
		<li>
			<table class="status-table widefat" cellspacing="0">
				<thead>
				<tr>
					<th colspan="3" data-export-label="Plugin information"><?php _e( 'Plugin information', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td class="heading" data-export-label="Molongui Authorship"><?php _e( 'Name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
					<td><?php echo MOLONGUI_AUTHORSHIP_NAME; ?></td>
				</tr>
				<tr>
					<td class="heading" data-export-label="Plugin License"><?php _e( 'License', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
					<td><?php echo ( is_premium() ? __( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : __( 'Free', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></td>
				</tr>
				<tr>
					<td class="heading" data-export-label="Plugin Version"><?php _e( 'Version', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
					<td><?php echo MOLONGUI_AUTHORSHIP_VERSION; ?></td>
				</tr>
				<tr>
					<td class="heading" data-export-label="Plugin Author"><?php _e( 'Author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
					<td>
						<?php printf( __( '%1$sMolongui%2$s', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
						              '<a href="//molongui.amitzy.com" target="_blank">',
						              '</a>'
						); ?>
					</td>

				</tr>
				<tr>
					<?php if ( !is_premium() && MOLONGUI_AUTHORSHIP_UPGRADABLE == 'yes' ) : ?>
						<td class="heading" data-export-label="Plugin Upgrade"><?php _e( 'Upgrade', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
						<td>
							<a href="<?php echo MOLONGUI_AUTHORSHIP_WEB ?>" class="button button-primary" target="_blank">
								<?php _e('Upgrade now', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</a>
						</td>
					<?php else : ?>
						<td class="heading" data-export-label="Plugin Docs"><?php _e( 'Docs', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>:</td>
						<td>
							<a href="<?php echo MOLONGUI_AUTHORSHIP_WEB ?>/docs" class="button button-primary" target="_blank">
								<?php _e('Check documentation', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</a>
						</td>
					<?php endif; ?>
				</tr>
				</tbody>
			</table>
		</li>
		<li>
			<table class="rate-table widefat" cellspacing="0">
				<thead>
				<tr>
					<th colspan="3" data-export-label="Plugin Feedback"><?php ( !is_premium() ? _e('Rate', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : _e( 'Feedback', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td rowspan="5">
						<p>
							<?php _e( 'We are constantly looking for ways to improve the quality of our products and services. How you rate our product and service is the most important information we can obtain to support our goal.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
						</p>
						<?php if ( !is_premium() ) : ?>
							<p>
								<?php _e( 'We would really appreciate it if you would take a second to rate this plugin at the official directory of Wordpress plugins. A huge thank you from Molongui in advance!', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</p>
							<br>
							<a href="https://wordpress.org/support/view/plugin-reviews/<?php echo MOLONGUI_AUTHORSHIP_ID ?>" class="button button-primary" target="_blank">
								<?php _e('Rate this plugin', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</a>
						<?php else : ?>
							<p>
								<?php _e( 'We would really appreciate any feedback you would like to send us. A huge thank you from Molongui in advance!', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</p>
							<br>
							<a href="<?php echo MOLONGUI_AUTHORSHIP_WEB ?>" class="button button-primary" target="_blank">
								<?php _e('Send feedback', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
				</tbody>
			</table>
		</li>
	</ul>

	<?php
	// Show upsells
	Plugin_Upsell::output( 'all', 'all', 36, null );

	// Show credits
	echo '<p>';
		printf( __( 'Molongui is a trademark of %1$s Amitzy%2$s.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		        '<a href="//www.amitzy.com" target="_blank">',
		        '</a>'
		);
	echo '</p>';

	?>

</div>