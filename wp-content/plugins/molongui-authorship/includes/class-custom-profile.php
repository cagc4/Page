<?php

namespace Molongui\Authorship\Includes;

/**
 * The Class used to add custom fields to a Wordpress User Profile.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/Includes
 * @since      1.0.0
 * @version    1.3.0
 */
class Custom_Profile
{
	/**
	 * Hook into the appropriate actions when the class is constructed.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function __construct()
	{

	}


	/**
	 * Add author box fields form to a user profile.
	 *
	 * @param   array      $user    The Wordpress User.
	 * @return  mixed
	 * @access  public
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function add_authorship_fields( $user )
	{
		if( !current_user_can( 'upload_files' ) ) return false;

		// Get plugin config settings
		$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

		// Get stored data
		$id       = get_the_author_meta( 'molongui_author_image_id',   $user->ID );
		$url      = get_the_author_meta( 'molongui_author_image_url',  $user->ID );
		$edit_url = get_the_author_meta( 'molongui_author_image_edit', $user->ID );

		if( !$id ) $btn_text = __( 'Upload New Image', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
		else $btn_text = __( 'Change Current Image', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		// Enqueue the WordPress Media Uploader
		wp_enqueue_media();

		?>

		<div id="molongui-author-box-container">

			<h3><?php _e( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></h3>

			<table class="form-table">

				<tr>
					<th><label for="molongui_author_image"><?php _e( 'Profile image', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td>
						<!-- Outputs the image after save -->
						<div id="current_img">
							<?php if( $url ): ?>
							<img src="<?php echo esc_url( $url ); ?>" class="molongui_current_img">
							<div class="edit_options uploaded">
								<a class="remove_img"><span><?php _e( 'Remove', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span></a>
								<a href="<?php echo $edit_url; ?>" class="edit_img" target="_blank"><span><?php _e( 'Edit', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span></a>
							</div>
							<?php else : ?>
								<img src="<?php echo plugins_url( 'img/placeholder.gif' ); ?>" class="molongui_current_img placeholder">
							<?php endif; ?>
						</div>

						<!-- Hold the value here of WPMU image -->
						<div id="molongui_image_upload">
							<input type="hidden" name="molongui_placeholder_meta" id="molongui_placeholder_meta" value="<?php echo plugins_url( 'custom-user-profile-photo/img/placeholder.gif' ); ?>" class="hidden" />
							<input type="hidden" name="molongui_author_image_id" id="molongui_author_image_id" value="<?php echo $id; ?>" class="hidden" />
							<input type="hidden" name="molongui_author_image_url" id="molongui_author_image_url" value="<?php echo esc_url_raw( $url ); ?>" class="hidden" />
							<input type="hidden" name="molongui_author_image_edit" id="molongui_author_image_edit" value="<?php echo $edit_url; ?>" class="hidden" />
							<input type='button' class="molongui_wpmu_button button-primary" value="<?php _e( $btn_text, MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" id="uploadimage"/><br />
						</div>
					</td>
				</tr>

				<tr>
					<th><label for="molongui_author_link"><?php _e( 'Profile link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_link" id="" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_link', $user->ID ) ); ?>" class="regular-text" placeholder="//www.example.com" /></td>
				</tr>

				<tr>
					<th><label for="molongui_author_job"><?php _e( 'Job title', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_job" id="molongui_author_job" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_job', $user->ID ) ); ?>" class="regular-text" /></td>
				</tr>

				<tr>
					<th><label for="molongui_author_company"><?php _e( 'Company', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_company" id="molongui_author_company" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_company', $user->ID ) ); ?>" class="regular-text" /></td>
				</tr>

				<tr>
					<th><label for="molongui_author_company_link"><?php _e( 'Company link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_company_link" id="molongui_author_company_link" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_company_link', $user->ID ) ); ?>" class="regular-text" placeholder="//www.example.com" /></td>
				</tr>

				<tr>
					<th><label for="molongui_author_show_mail"><?php _e( 'Email', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="checkbox" name="molongui_author_show_mail" id="molongui_author_show_mail" value="yes" <?php echo ( get_the_author_meta( 'molongui_author_show_mail', $user->ID ) == 'yes' ? 'checked=checked' : '' ); ?>><label for="molongui_author_show_mail"><?php _e( 'Display email publicly in the author box.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></td>
				</tr>

				<tr>
					<th><label for="molongui_author_bio"><?php _e( 'Biographical info', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><textarea name="molongui_author_bio" id="molongui_author_bio" rows="5" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'molongui_author_bio', $user->ID ) ); ?></textarea></td>
				</tr>

				<?php if ( isset( $settings['show_tw'] ) && $settings['show_tw'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_twitter"><?php _e( 'Twitter profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_twitter" id="molongui_author_twitter" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_twitter', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.twitter.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_fb'] ) && $settings['show_fb'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_facebook"><?php _e( 'Facebook profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_facebook" id="molongui_author_facebook" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_facebook', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.facebook.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_in'] ) && $settings['show_in'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_linkedin"><?php _e( 'Linkedin profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td>
						<?php if ( !is_premium() ) : ?>
							<input disabled placeholder="<?php _e( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" type="text" name="molongui_author_linkedin" id="molongui_author_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_in', $user->ID ) ); ?>" class="regular-text" />
							<span class="description"><span class="premium"><?php _e( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span><?php printf( __( 'This option is available only in the %spremium version%s of the plugin.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">', '</a>' ); ?></span>
						<?php else : ?>
							<input type="text" name="molongui_author_linkedin" id="molongui_author_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_linkedin', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.linkedin.com/pub/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" />
						<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_gp'] ) && $settings['show_gp'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_gplus"><?php _e( 'Google+ profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_gplus" id="molongui_author_gplus" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_gplus', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//plus.google.com/+user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_yt'] ) && $settings['show_yt'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_youtube"><?php _e( 'Youtube profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_youtube" id="molongui_author_youtube" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_youtube', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.youtube.com/user/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_pi'] ) && $settings['show_pi'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_pinterest"><?php _e( 'Pinterest profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_pinterest" id="molongui_author_pinterest" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_pinterest', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.pinterest.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_tu'] ) && $settings['show_tu'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_tumblr"><?php _e( 'Tumblr profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_tumblr" id="molongui_author_tumblr" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_tumblr', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//user_name.tumblr.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_ig'] ) && $settings['show_ig'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_instagram"><?php _e( 'Instagram profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_instagram" id="molongui_author_instagram" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_instagram', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//instagram.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_ss'] ) && $settings['show_ss'] == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_slideshare"><?php _e( 'Slideshare profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td>
							<?php if ( !is_premium() ) : ?>
								<input disabled placeholder="<?php _e( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" type="text" name="molongui_author_xing" id="molongui_author_slideshare" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_slideshare', $user->ID ) ); ?>" class="regular-text" />
								<span class="description"><span class="premium"><?php _e( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span><?php printf( __( 'This option is available only in the %spremium version%s of the plugin.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">', '</a>' ); ?></span>
							<?php else : ?>
								<input type="text" name="molongui_author_slideshare" id="molongui_author_slideshare" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_xing', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.slideshare.net/username', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" />
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_xi'] ) && $settings['show_xi'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_xing"><?php _e( 'Xing profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td>
						<?php if ( !is_premium() ) : ?>
							<input disabled placeholder="<?php _e( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" type="text" name="molongui_author_xing" id="molongui_author_xing" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_xing', $user->ID ) ); ?>" class="regular-text" />
							<span class="description"><span class="premium"><?php _e( 'Premium', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span><?php printf( __( 'This option is available only in the %spremium version%s of the plugin.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">', '</a>' ); ?></span>
						<?php else : ?>
						<input type="text" name="molongui_author_xing" id="molongui_author_xing" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_xing', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.xing.com/profile/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" />
						<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_re'] ) && $settings['show_re'] == 1 ) : ?>
				<tr>
					<th><label for="molongui_author_renren"><?php _e( 'Renren profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
					<td><input type="text" name="molongui_author_renren" id="molongui_author_renren" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_renren', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.renren.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
				</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_vk'] ) && $settings['show_vk']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_vk"><?php _e( 'Vk profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_vk" id="molongui_author_vk" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_vk', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.vk.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_fl'] ) && $settings['show_fl']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_flickr"><?php _e( 'Flickr profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_flickr" id="molongui_author_flickr" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_flickr', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.flickr.com/photos/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_vi'] ) && $settings['show_vi']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_vine"><?php _e( 'Vine profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_vine" id="molongui_author_vine" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_vine', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//vine.co/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_me'] ) && $settings['show_me']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_meetup"><?php _e( 'Meetup profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_meetup" id="molongui_author_meetup" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_meetup', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.meetup.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_we'] ) && $settings['show_we']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_weibo"><?php _e( 'Weibo profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_weibo" id="molongui_author_weibo" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_weibo', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.weibo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_de'] ) && $settings['show_de']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_deviantart"><?php _e( 'DeviantArt profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_deviantart" id="molongui_author_deviantart" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_deviantart', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//user_name.deviantart.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_st'] ) && $settings['show_st']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_stumbleupon"><?php _e( 'StumbleUpon profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_stumbleupon" id="molongui_author_stumbleupon" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_stumbleupon', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//stumbleupon.com/stumbler/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_my'] ) && $settings['show_my']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_myspace"><?php _e( 'MySpace profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_myspace" id="molongui_author_myspace" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_myspace', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//myspace.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_ye'] ) && $settings['show_ye']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_yelp"><?php _e( 'Yelp profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_yelp" id="molongui_author_yelp" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_yelp', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.yelp.com/biz/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_mi'] ) && $settings['show_mi']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_mixi"><?php _e( 'Mixi profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_mixi" id="molongui_author_mixi" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_mixi', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//mixi.jp/view_community.pl?id=12345', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_so'] ) && $settings['show_so']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_soundcloud"><?php _e( 'SoundCloud profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_soundcloud" id="molongui_author_soundcloud" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_soundcloud', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//soundcloud.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_la'] ) && $settings['show_la']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_lastfm"><?php _e( 'Last.fm profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_lastfm" id="molongui_author_lastfm" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_lastfm', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.last.fm/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_fo'] ) && $settings['show_fo']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_foursquare"><?php _e( 'Foursquare profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_foursquare" id="molongui_author_foursquare" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_foursquare', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//foursquare.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_sp'] ) && $settings['show_sp']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_spotify"><?php _e( 'Spotify profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_spotify" id="molongui_author_spotify" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_spotify', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//play.spotify.com/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_vm'] ) && $settings['show_vm']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_vimeo"><?php _e( 'Vimeo profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_vimeo" id="molongui_author_vimeo" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_vimeo', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//vimeo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_dm'] ) && $settings['show_dm']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_dailymotion"><?php _e( 'Dailymotion profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_dailymotion" id="molongui_author_dailymotion" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_dailymotion', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.dailymotion.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

				<?php if ( isset( $settings['show_rd'] ) && $settings['show_rd']  == 1 ) : ?>
					<tr>
						<th><label for="molongui_author_reddit"><?php _e( 'Reddit profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></label></th>
						<td><input type="text" name="molongui_author_reddit" id="molongui_author_reddit" value="<?php echo esc_attr( get_the_author_meta( 'molongui_author_reddit', $user->ID ) ); ?>" class="regular-text" placeholder="<?php _e( '//www.reddit.com/user/username', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>" /></td>
					</tr>
				<?php endif; ?>

			</table><!-- end form-table -->

		</div> <!-- end #cupp_container -->

		<?php
	}


	/**
	 * Save added author box fields.
	 *
	 * @param   int     $user_id    The User ID.
	 * @return  mixed   false if no edit permissions
	 * @access  public
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	function save_authorship_fields( $user_id )
	{
		if ( !current_user_can( 'edit_user', $user_id ) ) return false;

		// If the current user can edit Users, save image data.
		update_user_meta( $user_id, 'molongui_author_link', $_POST['molongui_author_link'] );
		update_user_meta( $user_id, 'molongui_author_job', $_POST['molongui_author_job'] );
		update_user_meta( $user_id, 'molongui_author_company', $_POST['molongui_author_company'] );
		update_user_meta( $user_id, 'molongui_author_company_link', $_POST['molongui_author_company_link'] );
		update_user_meta( $user_id, 'molongui_author_show_mail', $_POST['molongui_author_show_mail'] );
		update_user_meta( $user_id, 'molongui_author_bio', $_POST['molongui_author_bio'] );
		if ( isset( $_POST['molongui_author_twitter'] ) )     update_user_meta( $user_id, 'molongui_author_twitter', $_POST['molongui_author_twitter'] );
		if ( isset( $_POST['molongui_author_facebook'] ) )    update_user_meta( $user_id, 'molongui_author_facebook', $_POST['molongui_author_facebook'] );
		if ( isset( $_POST['molongui_author_linkedin'] ) )    update_user_meta( $user_id, 'molongui_author_linkedin', $_POST['molongui_author_linkedin'] );
		if ( isset( $_POST['molongui_author_gplus'] ) )       update_user_meta( $user_id, 'molongui_author_gplus', $_POST['molongui_author_gplus'] );
		if ( isset( $_POST['molongui_author_youtube'] ) )     update_user_meta( $user_id, 'molongui_author_youtube', $_POST['molongui_author_youtube'] );
		if ( isset( $_POST['molongui_author_pinterest'] ) )   update_user_meta( $user_id, 'molongui_author_pinterest', $_POST['molongui_author_pinterest'] );
		if ( isset( $_POST['molongui_author_tumblr'] ) )      update_user_meta( $user_id, 'molongui_author_tumblr', $_POST['molongui_author_tumblr'] );
		if ( isset( $_POST['molongui_author_instagram'] ) )   update_user_meta( $user_id, 'molongui_author_instagram', $_POST['molongui_author_instagram'] );
		if ( isset( $_POST['molongui_author_slideshare'] ) )  update_user_meta( $user_id, 'molongui_author_slideshare', $_POST['molongui_author_slideshare'] );
		if ( isset( $_POST['molongui_author_xing'] ) )        update_user_meta( $user_id, 'molongui_author_xing', $_POST['molongui_author_xing'] );
		if ( isset( $_POST['molongui_author_renren'] ) )      update_user_meta( $user_id, 'molongui_author_renren', $_POST['molongui_author_renren'] );
		if ( isset( $_POST['molongui_author_vk'] ) )          update_user_meta( $user_id, 'molongui_author_vk', $_POST['molongui_author_vk'] );
		if ( isset( $_POST['molongui_author_flickr'] ) )      update_user_meta( $user_id, 'molongui_author_flickr', $_POST['molongui_author_flickr'] );
		if ( isset( $_POST['molongui_author_vine'] ) )        update_user_meta( $user_id, 'molongui_author_vine', $_POST['molongui_author_vine'] );
		if ( isset( $_POST['molongui_author_meetup'] ) )      update_user_meta( $user_id, 'molongui_author_meetup', $_POST['molongui_author_meetup'] );
		if ( isset( $_POST['molongui_author_weibo'] ) )       update_user_meta( $user_id, 'molongui_author_weibo', $_POST['molongui_author_weibo'] );
		if ( isset( $_POST['molongui_author_deviantart'] ) )  update_user_meta( $user_id, 'molongui_author_deviantart', $_POST['molongui_author_deviantart'] );
		if ( isset( $_POST['molongui_author_stumbleupon'] ) ) update_user_meta( $user_id, 'molongui_author_stumbleupon', $_POST['molongui_author_stumbleupon'] );
		if ( isset( $_POST['molongui_author_myspace'] ) )     update_user_meta( $user_id, 'molongui_author_myspace', $_POST['molongui_author_myspace'] );
		if ( isset( $_POST['molongui_author_yelp'] ) )        update_user_meta( $user_id, 'molongui_author_yelp', $_POST['molongui_author_yelp'] );
		if ( isset( $_POST['molongui_author_mixi'] ) )        update_user_meta( $user_id, 'molongui_author_mixi', $_POST['molongui_author_mixi'] );
		if ( isset( $_POST['molongui_author_soundcloud'] ) )  update_user_meta( $user_id, 'molongui_author_soundcloud', $_POST['molongui_author_soundcloud'] );
		if ( isset( $_POST['molongui_author_lastfm'] ) )      update_user_meta( $user_id, 'molongui_author_lastfm', $_POST['molongui_author_lastfm'] );
		if ( isset( $_POST['molongui_author_foursquare'] ) )  update_user_meta( $user_id, 'molongui_author_foursquare', $_POST['molongui_author_foursquare'] );
		if ( isset( $_POST['molongui_author_spotify'] ) )     update_user_meta( $user_id, 'molongui_author_spotify', $_POST['molongui_author_spotify'] );
		if ( isset( $_POST['molongui_author_vimeo'] ) )       update_user_meta( $user_id, 'molongui_author_vimeo', $_POST['molongui_author_vimeo'] );
		if ( isset( $_POST['molongui_author_dailymotion'] ) ) update_user_meta( $user_id, 'molongui_author_dailymotion', $_POST['molongui_author_dailymotion'] );
		if ( isset( $_POST['molongui_author_reddit'] ) )      update_user_meta( $user_id, 'molongui_author_reddit', $_POST['molongui_author_reddit'] );

		if ( !current_user_can( 'upload_files', $user_id ) ) return false;

		// If the current user can upload files, save image data.
		update_user_meta( $user_id, 'molongui_author_image_id',   $_POST['molongui_author_image_id'] );
		update_user_meta( $user_id, 'molongui_author_image_url',  $_POST['molongui_author_image_url'] );
		update_user_meta( $user_id, 'molongui_author_image_edit', $_POST['molongui_author_image_edit'] );
	}


}