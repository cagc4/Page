<?php

/**
 * HTML template part.
 *
 * Author box meta markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.17
 * @version    1.3.0
 */
?>

<div class="molongui-author-box-job text-size-<?php echo $settings['meta_size']; ?>" style="color: <?php echo $settings['meta_color']; ?>">

	<span itemprop="jobTitle"><?php echo $author['job']; ?></span>
	<?php if ( $author['job'] && $author['company'] ) echo ' ' . ( $settings[ 'at' ] ? $settings[ 'at' ] : __('at', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ) . ' '; ?>
	<span itemprop="worksFor" itemscope itemtype="https://schema.org/Organization">
				<?php if ( $author['company_link'] ) echo '<a href="' . esc_url( $author['company_link'] ) . '" target="_blank" itemprop="url">'; ?>
		<span itemprop="name"><?php echo $author['company']; ?></span>
		<?php if ( $author['company_link'] ) echo '</a>'; ?>
	</span>

	<?php if ( $author['mail'] and $author['show_mail'] ) : ?>
		<?php if ( $author['job'] or $author['company'] ) echo ' | '; ?>
		<a href="mailto:<?php echo $author['mail']; ?>" target="_top" itemprop="email"><?php echo $author['mail']; ?></a>
	<?php endif; ?>

	<?php if ( $author['link'] ) : ?>
		<?php if ( $author['job'] or $author['company'] or ( $author['mail'] and $author['show_mail'] ) ) echo ' | '; ?>
		<a href="<?php echo esc_url( $author['link'] ); ?>" target="_blank" itemprop="url"><?php echo ( $settings[ 'web' ] ? $settings[ 'web' ] : __( 'Website', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></a>
	<?php endif; ?>

	<?php if ( $settings[ 'show_related' ] && isset( $author_posts ) && !empty( $author_posts ) ) : ?>
		<?php if ( $author['job'] or $author['company'] or $author['link'] ) echo ' | '; ?>
		<script type="text/javascript" language="JavaScript">
			if (typeof window.ToggleAuthorshipData === 'undefined')
			{
				function ToggleAuthorshipData(id)
				{
					// Toggle link label
					var label = document.querySelector('#molongui-author-box-' + id + ' ' + '.molongui-author-box-data-toggle');
					label.innerHTML = ( label.text == '<?php echo ( $settings[ 'more_posts' ] ? $settings[ 'more_posts' ] : __( '+ posts', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?>' ? '<?php echo ( $settings[ 'bio' ] ? $settings[ 'bio' ] : __( 'Bio', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?>' : '<?php echo ( $settings[ 'more_posts' ] ? $settings[ 'more_posts' ] : __( '+ posts', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?>' );

					// Toggle data to display
					var bio     = document.querySelector('#molongui-author-box-' + id + ' ' + '.molongui-author-box-bio');
					var related = document.querySelector('#molongui-author-box-' + id + ' ' + '.molongui-author-box-related');

					if( related.style.display == "none")
					{
						related.style.display = "block";
						bio.style.display     = "none";
					}
					else
					{
						related.style.display = "none";
						bio.style.display     = "block";
					}
				}
			}
		</script>
		<a href="javascript:ToggleAuthorshipData(<?php echo $random_id; ?>)" class="molongui-author-box-data-toggle"><?php echo ( $settings[ 'more_posts' ] ? $settings[ 'more_posts' ] : __( '+ posts', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></a>
	<?php endif; ?>
</div>