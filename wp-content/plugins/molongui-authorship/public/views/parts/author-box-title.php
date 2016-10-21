<?php

/**
 * HTML template part.
 *
 * Author box title markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.17
 * @version    1.3.0
 */
?>

<div class="molongui-author-box-title">
	<h5 class="text-size-<?php echo $settings['name_size']; ?>" style="color: <?php echo $settings['name_color']; ?>" itemprop="name">
		<?php if ( is_premium() and $settings['enable_guest_archives'] ) echo '<a href="' . esc_url( $author['url'] ) .'" itemprop="url">'; ?>
		<?php echo $author['name']; ?>
		<?php if ( is_premium() and $settings['enable_guest_archives'] ) echo '</a>'; ?>
	</h5>
</div>