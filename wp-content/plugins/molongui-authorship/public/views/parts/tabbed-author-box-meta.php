<?php

/**
 * HTML template part.
 *
 * Tabbed author box meta markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.18
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
	<?php if ( $author['link'] ) : ?>
		<?php if ( $author['job'] or $author['company'] ) echo ' | '; ?>
		<a href="<?php echo esc_url( $author['link'] ); ?>" target="_blank" itemprop="url"><?php echo ( $settings[ 'web' ] ? $settings[ 'web' ] : __( 'Website', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></a>
	<?php endif; ?>
</div>