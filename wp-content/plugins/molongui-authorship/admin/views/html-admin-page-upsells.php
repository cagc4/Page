<?php

/**
 * Display upsells.
 *
 * Display other available software from Molongui.
 *
 * When available, contents are localised.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/views
 * @since      1.0.0
 * @version    1.0.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

// Get locale
$lang = '_' . get_locale();

?>

<p class="intro">
	<?php _e( 'As part of our ongoing effort to provide high quality, eye-catching Wordpress plugins, here you have some you might find useful for your site:', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
</p>

<ul class="products">
	<?php foreach( $upsells->{$category} as $upsell_id => $upsell ) : ?>
		<li class="product">
			<a href="<?php echo $upsell->link; ?>">
				<h3>
					<?php echo ( $upsell->{'name'.$lang} ? $upsell->{'name'.$lang} : $upsell->name ); ?>
					<span class="price"><?php echo ( empty( $upsell->price ) ? __('Free', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : $upsell->price . '&euro;' ); ?></span>
				</h3>
				<?php if( ( $lang == '_en_US' && !empty( $upsell->image ) ) || ( $lang != '_en_US' && !empty( $upsell->{'image'.$lang} ) ) ) : ?>
					<div class="image">
						<img src="<?php echo ( $upsell->{'image'.$lang} ? $upsell->{'image'.$lang} : $upsell->image ); ?>"/>
					</div>
				<?php else : ?>
					<p class="excerpt">
						<?php echo wp_trim_words( ( $upsell->{'excerpt'.$lang} ? $upsell->{'excerpt'.$lang} : $upsell->excerpt ), $num_words, $more ); ?>
					</p>
				<?php endif; ?>

				<!--
				<?php if( !empty( $upsell->image ) ) : ?>
					<img src="<?php echo $upsell->image; ?>"/>
				<?php else : ?>
					<h3>
						<?php echo $upsell->name; ?>
						<span class="price"><?php echo ( empty( $upsell->price ) ? __('Free', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) : $upsell->price . '&euro;' ); ?></span>
					</h3>
				<?php endif; ?>
				<p class="excerpt"><?php echo wp_trim_words( $upsell->excerpt, $num_words, $more ); ?></p>
				-->
			</a>
		</li>
	<?php endforeach; ?>
</ul>