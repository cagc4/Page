<?php

/**
 * HTML template part.
 *
 * Author box biography markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.17
 * @version    1.3.0
 */
?>

<div id="molongui-author-box-bio" class="molongui-author-box-bio" itemprop="description">
	<p class="text-size-<?php echo $settings['bio_size']; ?> text-align-<?php echo $settings['bio_align']; ?> text-style-<?php echo $settings['bio_style']; ?>" style="color: <?php echo $settings['bio_color']; ?>">
		<?php echo str_replace( array("\n\r", "\r\n", "\n\n", "\r\r"), "<br>", $author['bio'] ); ?>
	</p>
</div>