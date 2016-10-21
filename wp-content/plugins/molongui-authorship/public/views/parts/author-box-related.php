<?php

/**
 * HTML template part.
 *
 * Author box related posts markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.17
 * @version    1.3.0
 */
?>

<div id="molongui-author-box-related" class="molongui-author-box-related" itemprop="creator" style="display: none;">
	<p class="text-size-<?php echo $settings['bio_size']; ?> text-align-<?php echo $settings['bio_align']; ?> text-style-<?php echo $settings['bio_style']; ?>" style="color: <?php echo $settings['bio_color']; ?>">
		<?php
		if ( !empty( $author_posts ) or is_array( $author_posts ) or is_object( $author_posts ) )
		{
			foreach( $author_posts as $related )
			{
				echo '<a href="' . get_permalink( $related->ID ) . '">' . $related->post_title . '</a>' . '<br>';
			}
		}
		else
		{
			_e( 'This author does not have any more posts.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
		}
		?>
	</p>
</div>