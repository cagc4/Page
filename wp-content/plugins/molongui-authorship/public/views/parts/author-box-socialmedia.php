<?php

/**
 * HTML template part.
 *
 * Author box social media icons markup.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views/parts
 * @since      1.2.17
 * @version    1.3.0
 */
?>

<?php if ( ( isset( $settings['show_icons'] ) and !empty( $settings['show_icons'] ) and $settings['show_icons'] == 1 ) and
           ( $author['tw'] or $author['fb'] or $author['in'] or $author['gp'] or $author['yt'] or $author['pi'] or $author['tu'] or $author['ig'] or $author['ss'] or $author['xi'] or $author['re'] or $author['vk'] or
             $author['fl'] or $author['vi'] or $author['me'] or $author['we'] or $author['de'] or $author['st'] or $author['my'] or $author['ye'] or $author['mi'] or $author['so'] or $author['la'] or $author['fo'] or
             $author['sp'] or $author['vm'] or $author['dm'] or $author['rd'] )
) :

	// Get social icon style
	if ( isset( $settings['icons_style'] ) )
	{
		$ico_style = $settings['icons_style']; if ( $ico_style && $ico_style != 'default' ) $ico_style = '-' . $ico_style; elseif ( $ico_style == 'default' ) $ico_style = '';
	}

	// Get social icon size
	if ( isset( $settings['icons_size'] ) ) $ico_size = $settings['icons_size'];
	else $ico_size = 'normal';

	// Get social icon color
	$ico_color = '';
	if ( isset( $settings['icons_color'] ) && $settings['icons_color'] != 'inherit' )
	{
		switch ( $settings['icons_style'] )
		{
			case 'squared':
			case 'circled':

				$ico_color = 'border-color: ' . $settings['icons_color'] . '; background-color: ' . $settings['icons_color'] . ';';

			break;

			case 'boxed':

				$ico_color = 'border-color: ' . $settings['icons_color'] . '; color: ' . $settings['icons_color'] . ';';

			break;

			case 'branded':
			case 'branded-squared-reverse':
			case 'branded-circled-reverse':
			case 'branded-boxed':

				$ico_color = '';    // do nothing

			break;

			case 'branded-squared':
			case 'branded-circled':

				$ico_color = 'background-color: ' . $settings['icons_color'];

			break;

			case 'default':
			default:

				$ico_color = 'color: ' . $settings['icons_color'] . ';';

			break;
		}
	}
	?>
	<div class="molongui-table-cell molongui-author-box-social">
		<?php if ( $author['tw'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['tw'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-twitter text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-twitter"></i></a></div><?php endif; ?>
		<?php if ( $author['fb'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['fb'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-facebook text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-facebook"></i></a></div><?php endif; ?>
		<?php if ( $author['in'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['in'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-linkedin text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-linkedin"></i></a></div><?php endif; ?>
		<?php if ( $author['gp'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['gp'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-gplus text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-gplus"></i></a></div><?php endif; ?>
		<?php if ( $author['yt'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['yt'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-youtube text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-youtube"></i></a></div><?php endif; ?>
		<?php if ( $author['pi'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['pi'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-pinterest text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-pinterest"></i></a></div><?php endif; ?>
		<?php if ( $author['tu'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['tu'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-tumblr text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-tumblr"></i></a></div><?php endif; ?>
		<?php if ( $author['ig'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['ig'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-instagram text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-instagram"></i></a></div><?php endif; ?>
		<?php if ( $author['ss'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['ss'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-slideshare text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-slideshare"></i></a></div><?php endif; ?>
		<?php if ( $author['xi'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['xi'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-xing text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-xing"></i></a></div><?php endif; ?>
		<?php if ( $author['re'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['re'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-renren text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-renren"></i></a></div><?php endif; ?>
		<?php if ( $author['vk'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['vk'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-vkontakte text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-vkontakte"></i></a></div><?php endif; ?>
		<?php if ( $author['fl'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['fl'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-flickr text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-flickr"></i></a></div><?php endif; ?>
		<?php if ( $author['vi'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['vi'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-vine text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-vine"></i></a></div><?php endif; ?>
		<?php if ( $author['me'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['me'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-meetup text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-meetup"></i></a></div><?php endif; ?>
		<?php if ( $author['we'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['we'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-sina-weibo text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-sina-weibo"></i></a></div><?php endif; ?>
		<?php if ( $author['de'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['de'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-deviantart text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-deviantart"></i></a></div><?php endif; ?>
		<?php if ( $author['st'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['st'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-stumbleupon text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-stumbleupon"></i></a></div><?php endif; ?>
		<?php if ( $author['my'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['my'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-myspace text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-myspace"></i></a></div><?php endif; ?>
		<?php if ( $author['ye'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['ye'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-yelp text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-yelp"></i></a></div><?php endif; ?>
		<?php if ( $author['mi'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['mi'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-mixi text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-mixi"></i></a></div><?php endif; ?>
		<?php if ( $author['so'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['so'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-soundcloud text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-soundcloud"></i></a></div><?php endif; ?>
		<?php if ( $author['la'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['la'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-lastfm text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-lastfm"></i></a></div><?php endif; ?>
		<?php if ( $author['fo'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['fo'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-foursquare text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-foursquare"></i></a></div><?php endif; ?>
		<?php if ( $author['sp'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['sp'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-spotify text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-spotify"></i></a></div><?php endif; ?>
		<?php if ( $author['vm'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['vm'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-vimeo text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-vimeo"></i></a></div><?php endif; ?>
		<?php if ( $author['dm'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['dm'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-dailymotion text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-dailymotion"></i></a></div><?php endif; ?>
		<?php if ( $author['rd'] ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author['rd'] ); ?>" class="icon-container<?php echo $ico_style; ?> icon-container-reddit text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>" target="_blank"><i class="molongui-authorship-icon-reddit"></i></a></div><?php endif; ?>
	</div>
<?php endif; ?>