<?php

namespace Molongui\Authorship\Includes;

use WP_Query; /* https://roots.io/upping-php-requirements-in-your-wordpress-themes-and-plugins/ */

/**
 * The Guest Author Class.
 *
 * @author     Amitzy
 * @category   Plugin
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/admin/classes
 * @since      1.0.0
 * @version    1.3.0
 */
class Guest_Author
{
	/**
	 * Hook into the appropriate actions when the class is constructed.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.17
	 */
	public function __construct()
	{

	}


	/**
	 * Add columns to the list shown on the Manage {molongui_guestauthor} Posts screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
	 *
	 * @param    array      $columns    An array of column name => label.
	 * @return   array      $columns    Modified array of column name => label.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.2.17
	 */
	public function add_list_columns( $columns )
	{
		// Unset some default columns to display them in a different position
		unset( $columns['title'] );
		unset( $columns['date'] );
		unset( $columns['thumbnail'] );

		return array_merge($columns,
		                   array('guestAuthorPic'  => __( 'Photo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'title'		   => __( 'Name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorJob'  => __( 'Job position', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorCia'  => __( 'Company', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorMail' => __( 'e-mail', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'guestAuthorUrl'  => __( 'Url', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
		                         'date'            => __( 'Date' ),
		                   )
		);
	}


	/**
	 * Fill out custom author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
	 *
	 * @param    array      $column     An array of column name => label.
	 * @param    int        $ID         Post ID.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function fill_list_columns( $column, $ID )
	{
		if ( $column == 'guestAuthorPic' )  echo get_the_post_thumbnail( $ID, array( 60, 60) );
		if ( $column == 'guestAuthorJob' )  echo get_post_meta( $ID, '_molongui_guest_author_job', true );
		if ( $column == 'guestAuthorCia' )  echo get_post_meta( $ID, '_molongui_guest_author_company', true );
		if ( $column == 'guestAuthorMail' ) echo get_post_meta( $ID, '_molongui_guest_author_mail', true );
		if ( $column == 'guestAuthorUrl' )  echo '<a href="' . get_post_meta( $ID, '_molongui_guest_author_link', true ) . '">' . get_post_meta( $ID, '_molongui_guest_author_link', true ) . '</a>';
	}


	/**
	 * Register "Guest Author" custom post-type.
	 *
	 * This functions registers a new post-type called "molongui_guestauthor".
	 * This post-type holds guest authors specific data.
	 *
	 * CPT menu item is placed as per user preference. Default is as a new top level menu.
	 *
	 * @see      https://codex.wordpress.org/Function_Reference/register_post_type
	 *           https://tommcfarlin.com/add-custom-post-type-to-menu/
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function register_guest_author_posttype()
	{
		// Get plugin settings
		$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

		$labels = array(
			'name'					=> _x( 'Guest authors', 'post type general name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'singular_name'			=> _x( 'Guest author', 'post type singular name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'menu_name'				=> __( 'Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'name_admin_bar'		=> __( 'Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'all_items'				=> __( 'All Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'add_new'				=> _x( 'Add New', 'molongui_guestauthor', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'add_new_item'			=> __( 'Add New Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'edit_item'				=> __( 'Edit Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'new_item'				=> __( 'New Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'view_item'				=> __( 'View Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'search_items'			=> __( 'Search Guest authors', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'not_found'				=> __( 'No guest authors found', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'not_found_in_trash'	=> __( 'No guest authors found in the Trash', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ),
			'parent_item_colon'		=> '',
		);

		$args = array(
			'labels'				=> $labels,
			'description'			=> 'Holds our guest author and guest authors specific data',
			'public'				=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'          => ( ( isset( $settings['admin_menu_level'] ) and $settings['admin_menu_level'] != 'true' ) ? $settings['admin_menu_level'] : true ),
			'show_in_admin_bar '	=> true,
			'menu_position'			=> 5, // 5 = Below posts
			'menu_icon'				=> 'dashicons-id',
			'supports'		 		=> array( 'title', 'editor', 'thumbnail' ),
			'register_meta_box_cb'	=> '',
			'has_archive'			=> true,
			'rewrite'				=> array('slug' => 'guest-author'),
		);

		register_post_type( 'molongui_guestauthor', $args );

		// DEBUG: Uncomment below lines to debug on deployment
		// print_r( register_post_type( 'molongui_guest_author', $args ) ); exit;
	}


	/**
	 * Change title placeholder for "guest author" custom post-type.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function change_default_title( $title )
	{
		global $current_screen;

		if ( 'molongui_guestauthor' == $current_screen->post_type ) $title = __( 'Enter guest author name here', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );

		return $title;
	}


	/**
	 * Remove media buttons from "guest author" custom post-type.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function remove_media_buttons()
	{
		global $current_screen;

		if( 'molongui_guestauthor' == $current_screen->post_type ) remove_action( 'media_buttons', 'media_buttons' );
	}


	/**
	 * Modify author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
	 * @see      https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_pages_columns
	 *
	 * @param    array      $columns    An array of column name => label.
	 * @return   array      $columns    Modified array of column name => label.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function change_author_column( $columns )
	{
		global $post;

		$post_type  = get_post_type( $post );
		$post_types = array( 'post', 'page' );

		// Modify author column only at Manage Posts screen
		if ( in_array( $post_type, $post_types ))
		{
			// Remove default author column from the columns list
			unset( $columns['author'] );

			// Add new author column in the same place where default was (after title)
			$new_columns = array();
			foreach ( $columns as $key => $column )
			{
				$new_columns[$key] = $column;
				if ( $key == 'title' ) $new_columns['realAuthor'] = __( 'Author' );
			}

			return $new_columns;
		}
		else
		{
			return $columns;
		}
	}


	/**
	 * Fill out custom author column shown on the Manage Posts/Pages screen.
	 *
	 * @see      https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
	 *
	 * @param    string     $column     Column name.
	 * @param    int        $ID         Post ID.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	function fill_author_column( $column, $ID )
	{
		if ( $column == 'realAuthor' )
		{
			// Get Guest Author ID if one is set
			$guest = get_post_meta( $ID, '_molongui_guest_author', true );
			if ( isset( $guest ) && $guest == 1 )
			{
				$author_id = get_post_meta( $ID, '_molongui_guest_author_id', true );
				echo '<a href="' . admin_url() . 'post.php?post=' . $author_id . '&action=edit">' . get_the_title( $author_id ) . '</a>';
			}
			// If it is not guest author, get the registered author
			else
			{
				$post = get_post( $ID );
				echo '<a href="' . admin_url() . 'user-edit.php?user_id=' . $post->post_author . '">' . get_the_author() . '</a>';
			}
		}
	}


	/**
	 * Remove default "Author" meta box.
	 *
	 * Removes the "Author" meta box from post's and page's edit page.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.2.2
	 */
	public function remove_author_metabox()
	{
		remove_meta_box('authordiv', 'post', 'normal');
		remove_meta_box('authordiv', 'page', 'normal');
	}


	/**
	 * Adds the meta box container.
	 *
	 * @see     https://codex.wordpress.org/Function_Reference/add_meta_box
	 * @access  public
	 * @since   1.0.0
	 * @version 1.2.4
	 */
	public function add_meta_boxes( $post_type )
	{
		// Limit meta box to certain post types
		$post_types = array('post', 'page');

		// Add author meta box to "post" and "page" post-types
		if ( in_array( $post_type, $post_types ))
		{
			add_meta_box(
				'authorboxdiv'
				,__( 'Author' )
				,array( $this, 'render_author_meta_box_content' )
				,$post_type
				,'side'
				,'high'
			);

			// Add selector to choose whether to show authorship box or not
			add_meta_box(
				'showboxdiv'
				,__( 'Authorship box' )
				,array( $this, 'render_showbox_meta_box_content' )
				,$post_type
				,'side'
				,'high'
			);
		}

		// Add custom meta boxes to "Guest Author" custom post-type
		if ( in_array( $post_type, array('molongui_guestauthor') ))
		{
			// Add job profile meta box
			add_meta_box(
				'authorjobdiv'
				,__( 'Job profile', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )
				,array( $this, 'render_job_meta_box_content' )
				,$post_type
				,'side'
				,'core'
			);

			// Add social media meta box
			add_meta_box(
				'authorsocialdiv'
				,__( 'Social Media', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN )
				,array( $this, 'render_social_meta_box_content' )
				,$post_type
				,'normal'
				,'high'
			);
		}
	}


	/**
	 * Render Author Meta Box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function render_author_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest = get_post_meta( $post->ID, '_molongui_guest_author', true );

		// Add some js
		?><script type="text/javascript">
			function showAuthorContent()
			{
				// Get DOM elements
				var radios     = document.getElementsByName("guest-author");
				var registered = document.getElementById("registered_author_data");
				var guest      = document.getElementById("guest_author_data");

				// Show content based on selection
				if ( radios[0].checked )
				{
					registered.style.display = 'block';
					registered.className     = "";
					guest.style.display      = 'none';
				}
				if ( radios[1].checked )
				{
					registered.style.display = 'none';
					guest.style.display      = 'block';
					guest.className          = "";
				}
			}
		</script><?php

		// Display the form, loading stored values if available
		?>
		<div class="molongui-metabox">
			<p class="molongui-description"><?php _e( 'As author, you can choose between a registered user and a guest author:', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></p>
			<div class="molongui-field">
				<label for="registered-author">
					<input type="radio" name="guest-author" id="registered-author" value="0" onclick="showAuthorContent()" <?php if ( $guest != 1 ) echo 'checked'; ?>>
					<span class="registered-author"><?php _e( 'Registered author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
				</label>
			</div>
			<div class="molongui-field">
				<label for="guest-author">
					<input type="radio" name="guest-author" id="guest-author" value="1" onclick="showAuthorContent()" <?php if ( $guest == 1 ) echo 'checked'; ?>>
					<span class="guest-author"><?php _e( 'Guest author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
				</label>
			</div>
			<div class="molongui-field">
				<div id="registered_author_data" class="<?php echo ( $guest != 0 ? 'hidden' : '' ); ?>">
					<?php
					echo '<label class="screen-reader-text" for="post_author_override">' . __('Author') . '</label>';
					wp_dropdown_users( array(
						                   'who' => 'authors',
						                   'name' => 'post_author_override',
						                   'selected' => empty($post->ID) ? $user_ID : $post->post_author,
						                   'include_selected' => true
					                   ) );
					?>
				</div>
				<div id="guest_author_data" class="<?php echo ( $guest != 1 ? 'hidden' : '' ); ?>">
					<?php echo $this->get_guest_authors(); ?>
				</div>
			</div>
		</div>
		<?php
	}


	/**
	 * Render selector to choose to show the authorship box or not.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.1.0
	 * @version  1.3.0
	 */
	public function render_showbox_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Get plugin options
		$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

		// Get current screen
		$screen = get_current_screen();

		// Use get_post_meta to retrieve an existing value from the database.
		$author_box_display = get_post_meta( $post->ID, '_molongui_author_box_display', true );

		// If no existing value, set default as global configuration defines
		if ( empty( $author_box_display ) )
		{
			switch( $settings['display'] )
			{
				case '0':

					$author_box_display = 'hide';

				break;

				case '1':

					$author_box_display = 'show';

				break;

				case 'posts':

					if ( $screen->post_type == 'post' ) $author_box_display = 'show';
					else $author_box_display = 'hide';

				break;

				case 'pages':

					if ( $screen->post_type == 'page' ) $author_box_display = 'show';
					else $author_box_display = 'hide';

				break;
			}
		}

		// Display the form, loading stored values if available
		?>
		<div class="molongui-metabox">
			<p class="molongui-description"><?php _e( 'Show the authorship box for this post?', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></p>
			<div class="molongui-field">
				<select name="_molongui_author_box_display">
					<option value="show" <?php echo ( $author_box_display == 'show' ? 'selected' : '' ); ?>><?php _e( 'Show', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></option>
					<option value="hide" <?php echo ( $author_box_display == 'hide' ? 'selected' : '' ); ?>><?php _e( 'Hide', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></option>
				</select>
			</div>
		</div>
		<?php
	}


	/**
	 * Render job profile meta box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function render_job_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest_author_mail         = get_post_meta( $post->ID, '_molongui_guest_author_mail', true );
		$guest_author_show_mail    = get_post_meta( $post->ID, '_molongui_guest_author_show_mail', true );
		$guest_author_link         = get_post_meta( $post->ID, '_molongui_guest_author_link', true );
		$guest_author_job          = get_post_meta( $post->ID, '_molongui_guest_author_job', true );
		$guest_author_company      = get_post_meta( $post->ID, '_molongui_guest_author_company', true );
		$guest_author_company_link = get_post_meta( $post->ID, '_molongui_guest_author_company_link', true );

		// Display the form, loading stored values if available
		echo '<div class="molongui-metabox">';
			//echo '<div class="molongui-note">' . __( 'Empty fields are not displayed in the front-end.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</div>';
			echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_link">' . __( 'Profile link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
				echo '<i class="tip molongui-authorship-icon-tip molongui-help-tip" data-tip="' . __( 'URL the author name will link to. Leave blank to disable link feature.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></i>';
				echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.example.com/', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_link" name="_molongui_guest_author_link" value="' . ( $guest_author_link ? $guest_author_link : '' ) . '" class="text"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_mail">' . __( 'Email address', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
				echo '<i class="tip molongui-authorship-icon-tip molongui-help-tip" data-tip="' . __( 'Used to retrieve author\'s Gravatar if it exists. This field is not displayed in the front-end unless checkbox below is checked.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></i>';
				echo '<div class="input-wrap"><input type="text" id="_molongui_guest_author_mail" name="_molongui_guest_author_mail" value="' . ( $guest_author_mail ? $guest_author_mail : '' ) . '" class="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></div>';
				echo '<div class="input-wrap"><input type="checkbox" id="_molongui_guest_author_show_mail" name="_molongui_guest_author_show_mail" value="yes"' . ( $guest_author_show_mail == 'yes' ? 'checked=checked' : '' ) . '><label class="checkbox-label" for="_molongui_guest_author_show_mail">' . __( 'Display email in the author box.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_job">' . __( 'Job title', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
				echo '<i class="tip molongui-authorship-icon-tip molongui-help-tip" data-tip="' . __( 'Name used to describe what the author does for a business or another enterprise. Leave blank to prevent to display this field in the front-end.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></i>';
				echo '<div class="input-wrap"><input type="text" id="_molongui_guest_author_job" name="_molongui_guest_author_job" value="' . ( $guest_author_job ? $guest_author_job : '' ) . '" class="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_company">' . __( 'Company', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
				echo '<i class="tip molongui-authorship-icon-tip molongui-help-tip" data-tip="' . __( 'The name of the company the author works for. Leave blank to prevent to display this field in the front-end.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></i>';
				echo '<div class="input-wrap"><input type="text" id="_molongui_guest_author_company" name="_molongui_guest_author_company" value="' . ( $guest_author_company ? $guest_author_company : '' ) . '" class="text" placeholder="' . __( '', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></div>';
			echo '</div>';
			echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_company_link">' . __( 'Company link', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
				echo '<i class="tip molongui-authorship-icon-tip molongui-help-tip" data-tip="' . __( 'URL the company name will link to. Leave blank to disable link feature.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></i>';
				echo '<div class="input-wrap"><input type="text" id="_molongui_guest_author_company_link" name="_molongui_guest_author_company_link" value="' . ( $guest_author_company_link ? $guest_author_company_link : '' ) . '" class="text" placeholder="' . __( '//www.example.com/', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '"></div>';
			echo '</div>';
		echo '</div>';
	}


	/**
	 * Render social media meta box content.
	 *
	 * @param    WP_Post    $post  The post object.
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function render_social_meta_box_content( $post )
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'molongui_authorship', 'molongui_authorship_nonce' );

		// Get plugin config settings
		$main_settings   = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
		$box_settings    = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
		$string_settings = get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS );
		$settings        = array_merge( $main_settings, $box_settings, $string_settings );

		// Use get_post_meta to retrieve an existing value from the database.
		$guest_author_twitter     = get_post_meta( $post->ID, '_molongui_guest_author_twitter', true );
		$guest_author_facebook    = get_post_meta( $post->ID, '_molongui_guest_author_facebook', true );
		$guest_author_linkedin    = get_post_meta( $post->ID, '_molongui_guest_author_linkedin', true );
		$guest_author_googleplus  = get_post_meta( $post->ID, '_molongui_guest_author_gplus', true );
		$guest_author_youtube     = get_post_meta( $post->ID, '_molongui_guest_author_youtube', true );
		$guest_author_pinterest   = get_post_meta( $post->ID, '_molongui_guest_author_pinterest', true );
		$guest_author_tumblr      = get_post_meta( $post->ID, '_molongui_guest_author_tumblr', true );
		$guest_author_instagram   = get_post_meta( $post->ID, '_molongui_guest_author_instagram', true );
		$guest_author_slideshare  = get_post_meta( $post->ID, '_molongui_guest_author_slideshare', true );
		$guest_author_xing        = get_post_meta( $post->ID, '_molongui_guest_author_xing', true );
		$guest_author_renren      = get_post_meta( $post->ID, '_molongui_guest_author_renren', true );
		$guest_author_vk          = get_post_meta( $post->ID, '_molongui_guest_author_vk', true );
		$guest_author_flickr      = get_post_meta( $post->ID, '_molongui_guest_author_flickr', true );
		$guest_author_vine        = get_post_meta( $post->ID, '_molongui_guest_author_vine', true );
		$guest_author_meetup      = get_post_meta( $post->ID, '_molongui_guest_author_meetup', true );
		$guest_author_weibo       = get_post_meta( $post->ID, '_molongui_guest_author_weibo', true );
		$guest_author_deviantart  = get_post_meta( $post->ID, '_molongui_guest_author_deviantart', true );
		$guest_author_stumbleupon = get_post_meta( $post->ID, '_molongui_guest_author_stumbleupon', true );
		$guest_author_myspace     = get_post_meta( $post->ID, '_molongui_guest_author_myspace', true );
		$guest_author_yelp        = get_post_meta( $post->ID, '_molongui_guest_author_yelp', true );
		$guest_author_mixi        = get_post_meta( $post->ID, '_molongui_guest_author_mixi', true );
		$guest_author_soundcloud  = get_post_meta( $post->ID, '_molongui_guest_author_soundcloud', true );
		$guest_author_lastfm      = get_post_meta( $post->ID, '_molongui_guest_author_lastfm', true );
		$guest_author_foursquare  = get_post_meta( $post->ID, '_molongui_guest_author_foursquare', true );
		$guest_author_spotify     = get_post_meta( $post->ID, '_molongui_guest_author_spotify', true );
		$guest_author_vimeo       = get_post_meta( $post->ID, '_molongui_guest_author_vimeo', true );
		$guest_author_dailymotion = get_post_meta( $post->ID, '_molongui_guest_author_dailymotion', true );
		$guest_author_reddit      = get_post_meta( $post->ID, '_molongui_guest_author_reddit', true );


		// Display the form, loading stored values if available
		echo '<div class="molongui molongui-metabox">';

			if ( isset( $settings['show_tw'] ) && $settings['show_tw'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_twitter">' . __( 'Twitter', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.twitter.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_twitter" name="_molongui_guest_author_twitter" value="' . ( $guest_author_twitter ? $guest_author_twitter : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_fb'] ) && $settings['show_fb'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_facebook">' . __( 'Facebook', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.facebook.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_facebook" name="_molongui_guest_author_facebook" value="' . ( $guest_author_facebook ? $guest_author_facebook : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_in'] ) && $settings['show_in'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_linkedin">' . __( 'Linkedin', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
					if ( !is_premium() )
					{
						echo '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">' . '<i class="molongui-authorship-icon-star molongui-help-tip molongui-premium-setting" data-tip="' . $this->premium_option_tip() . '"></i>' . '</a>';
						echo '</label>';
						echo '<div class="input-wrap"><input type="text" disabled placeholder="' . __( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_linkedin" name="_molongui_guest_author_linkedin" value="' . ( $guest_author_linkedin ? $guest_author_linkedin : '' ) . '" class="text"></div>';
					}
					else
					{
						echo '</label>';
						echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.linkedin.com/pub/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_linkedin" name="_molongui_guest_author_linkedin" value="' . ( $guest_author_linkedin ? $guest_author_linkedin : '' ) . '" class="text"></div>';
					}
				echo '</div>';
			}

			if ( isset( $settings['show_gp'] ) && $settings['show_gp'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_gplus">' . __( 'Google +', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//plus.google.com/+user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_gplus" name="_molongui_guest_author_gplus" value="' . ( $guest_author_googleplus ? $guest_author_googleplus : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_yt'] ) && $settings['show_yt'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_youtube">' . __( 'Youtube', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.youtube.com/user/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_youtube" name="_molongui_guest_author_youtube" value="' . ( $guest_author_youtube ? $guest_author_youtube : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_pi'] ) && $settings['show_pi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_pinterest">' . __( 'Pinterest', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.pinterest.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_pinterest" name="_molongui_guest_author_pinterest" value="' . ( $guest_author_pinterest ? $guest_author_pinterest : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_tu'] ) && $settings['show_tu'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_tumblr">' . __( 'Tumblr', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//user_name.tumblr.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_tumblr" name="_molongui_guest_author_tumblr" value="' . ( $guest_author_tumblr ? $guest_author_tumblr : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_ig'] ) && $settings['show_ig'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_instagram">' . __( 'Instagram', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//instagram.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_instagram" name="_molongui_guest_author_instagram" value="' . ( $guest_author_instagram ? $guest_author_instagram : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_ss'] ) && $settings['show_ss'] == 1 )
			{
				echo '<div class="molongui-field">';
				echo '<label class="title" for="_molongui_guest_author_slideshare">' . __( 'Slideshare', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
				if ( !is_premium() )
				{
					echo '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">' . '<i class="molongui-authorship-icon-star molongui-help-tip molongui-premium-setting" data-tip="' . $this->premium_option_tip() . '"></i>' . '</a>';
					echo '</label>';
					echo '<div class="input-wrap"><input type="text" disabled placeholder="' . __( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_slideshare" name="_molongui_guest_author_slideshare" value="' . ( $guest_author_slideshare ? $guest_author_slideshare : '' ) . '" class="text"></div>';
				}
				else
				{
					echo '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.slideshare.net/username', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_slideshare" name="_molongui_guest_author_slideshare" value="' . ( $guest_author_slideshare ? $guest_author_slideshare : '' ) . '" class="text"></div>';
				}
				echo '</div>';
			}

			if ( isset( $settings['show_xi'] ) && $settings['show_xi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_xing">' . __( 'Xing', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN );
					if ( !is_premium() )
					{
						echo '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">' . '<i class="molongui-authorship-icon-star molongui-help-tip molongui-premium-setting" data-tip="' . $this->premium_option_tip() . '"></i>' . '</a>';
						echo '</label>';
						echo '<div class="input-wrap"><input type="text" disabled placeholder="' . __( 'Premium feature', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_xing" name="_molongui_guest_author_xing" value="' . ( $guest_author_xing ? $guest_author_xing : '' ) . '" class="text"></div>';
					}
					else
					{
						echo '</label>';
						echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.xing.com/profile/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_xing" name="_molongui_guest_author_xing" value="' . ( $guest_author_xing ? $guest_author_xing : '' ) . '" class="text"></div>';
					}
				echo '</div>';
			}

			if ( isset( $settings['show_re'] ) && $settings['show_re'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_renren">' . __( 'Renren', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.renren.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_renren" name="_molongui_guest_author_renren" value="' . ( $guest_author_renren ? $guest_author_renren : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_vk'] ) && $settings['show_vk'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_vk">' . __( 'Vk', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.vk.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_vk" name="_molongui_guest_author_vk" value="' . ( $guest_author_vk ? $guest_author_vk : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_fl'] ) && $settings['show_fl'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_flickr">' . __( 'Flickr', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.flickr.com/photos/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_flickr" name="_molongui_guest_author_flickr" value="' . ( $guest_author_flickr ? $guest_author_flickr : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_vi'] ) && $settings['show_vi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_vine">' . __( 'Vine', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//vine.co/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_vine" name="_molongui_guest_author_vine" value="' . ( $guest_author_vine ? $guest_author_vine : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_me'] ) && $settings['show_me'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_meetup">' . __( 'Meetup', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.meetup.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_meetup" name="_molongui_guest_author_meetup" value="' . ( $guest_author_meetup ? $guest_author_meetup : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_we'] ) && $settings['show_we'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_weibo">' . __( 'Weibo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.weibo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_weibo" name="_molongui_guest_author_weibo" value="' . ( $guest_author_weibo ? $guest_author_weibo : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_de'] ) && $settings['show_de'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_deviantart">' . __( 'DeviantArt', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//user_name.deviantart.com', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_deviantart" name="_molongui_guest_author_deviantart" value="' . ( $guest_author_deviantart ? $guest_author_deviantart : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_st'] ) && $settings['show_st'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_stumbleupon">' . __( 'StumbleUpon', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//stumbleupon.com/stumbler/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_stumbleupon" name="_molongui_guest_author_stumbleupon" value="' . ( $guest_author_stumbleupon ? $guest_author_stumbleupon : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_my'] ) && $settings['show_my'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_myspace">' . __( 'MySpace', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//myspace.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_myspace" name="_molongui_guest_author_myspace" value="' . ( $guest_author_myspace ? $guest_author_myspace : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_ye'] ) && $settings['show_ye'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_yelp">' . __( 'Yelp', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.yelp.com/biz/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_yelp" name="_molongui_guest_author_yelp" value="' . ( $guest_author_yelp ? $guest_author_yelp : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_mi'] ) && $settings['show_mi'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_mixi">' . __( 'Mixi', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//mixi.jp/view_community.pl?id=12345', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_mixi" name="_molongui_guest_author_mixi" value="' . ( $guest_author_mixi ? $guest_author_mixi : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_so'] ) && $settings['show_so'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_soundcloud">' . __( 'SoundCloud', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//soundcloud.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_soundcloud" name="_molongui_guest_author_soundcloud" value="' . ( $guest_author_soundcloud ? $guest_author_soundcloud : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_la'] ) && $settings['show_la'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_lastfm">' . __( 'Last.fm', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.last.fm/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_lastfm" name="_molongui_guest_author_lastfm" value="' . ( $guest_author_lastfm ? $guest_author_lastfm : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_fo'] ) && $settings['show_fo'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_foursquare">' . __( 'Foursquare', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//foursquare.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_foursquare" name="_molongui_guest_author_foursquare" value="' . ( $guest_author_foursquare ? $guest_author_foursquare : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_sp'] ) && $settings['show_sp'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_renren">' . __( 'Spotify', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( 'https://play.spotify.com/user/name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_spotify" name="_molongui_guest_author_spotify" value="' . ( $guest_author_spotify ? $guest_author_spotify : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_vm'] ) && $settings['show_vm'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_vimeo">' . __( 'Vimeo', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//vimeo.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_vimeo" name="_molongui_guest_author_vimeo" value="' . ( $guest_author_vimeo ? $guest_author_vimeo : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_dm'] ) && $settings['show_dm'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_dailymotion">' . __( 'Dailymotion', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.dailymotion.com/user_name', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_dailymotion" name="_molongui_guest_author_dailymotion" value="' . ( $guest_author_dailymotion ? $guest_author_dailymotion : '' ) . '" class="text"></div>';
				echo '</div>';
			}

			if ( isset( $settings['show_rd'] ) && $settings['show_rd'] == 1 )
			{
				echo '<div class="molongui-field">';
					echo '<label class="title" for="_molongui_guest_author_reddit">' . __( 'Reddit', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '</label>';
					echo '<div class="input-wrap"><input type="text" placeholder="' . __( '//www.reddit.com/user/username', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . '" id="_molongui_guest_author_reddit" name="_molongui_guest_author_reddit" value="' . ( $guest_author_reddit ? $guest_author_reddit : '' ) . '" class="text"></div>';
				echo '</div>';
			}

		echo '</div>';
	}


	/**
	 *
	 */
	public function premium_option_tip()
	{
		return sprintf( __( '%sPremium feature%s. You are using the free version of this plugin. Consider purchasing the Premium Version to enable this feature.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), '<strong>', '</strong>' );
	}


	/**
	 * Get a list of guest authors.
	 *
	 * @see     https://codex.wordpress.org/Class_Reference/WP_Query#Pagination_Parameters
	 *
	 * @param   boolean         $dropdown   Whether to get an object or an html dropdown list.
	 * @return  object or html
	 * @access  private
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function get_guest_authors( $dropdown = true )
	{
		// Get post
		global $post;

		// Query guest authors
		$args   = array( 'post_type' => 'molongui_guestauthor', 'posts_per_page' => -1 );
		$guests = new WP_Query( $args );

		// Check output format
		if ( !$dropdown ) return $guests;

		// Get current post guest author (if any)
		$guest_author = get_post_meta( $post->ID, '_molongui_guest_author_id', true );

		// Mount html markup
		$output = '';
		if( $guests->have_posts() )
		{
			$output .= '<select name="_molongui_guest_author_id">';
			foreach( $guests->posts as $guest )
			{
				$output .= '<option value="' . $guest->ID . '"' . ( $guest_author == $guest->ID ? 'selected' : '' ) . '>' . $guest->post_title . '</option>';
			}
			$output .= '</select>';
		}

		return ( $output );
	}


	/**
	 * Save the meta when the post is saved.
	 *
	 * @param    int    $post_id  The ID of the post being saved.
	 * @return   void
	 * @access   public
	 * @since    1.0.0
	 * @version  1.3.0
	 */
	public function save( $post_id )
	{
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( !isset( $_POST['molongui_authorship_nonce'] ) ) return $post_id;
		$nonce = $_POST['molongui_authorship_nonce'];

		// Verify that the nonce is valid.
		if ( !wp_verify_nonce( $nonce, 'molongui_authorship' ) ) return $post_id;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] )
		{
			if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		}
		else
		{
			if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		global $current_screen;

		if( 'molongui_guestauthor' == $current_screen->post_type )
		{
			// Update data
			update_post_meta( $post_id, '_molongui_guest_author_mail', sanitize_text_field( $_POST['_molongui_guest_author_mail'] ) );
			update_post_meta( $post_id, '_molongui_guest_author_show_mail', sanitize_text_field( $_POST['_molongui_guest_author_show_mail'] ) );
			update_post_meta( $post_id, '_molongui_guest_author_link', sanitize_text_field( $_POST['_molongui_guest_author_link'] ) );
			update_post_meta( $post_id, '_molongui_guest_author_job', sanitize_text_field( $_POST['_molongui_guest_author_job'] ) );
			update_post_meta( $post_id, '_molongui_guest_author_company', sanitize_text_field( $_POST['_molongui_guest_author_company'] ) );
			update_post_meta( $post_id, '_molongui_guest_author_company_link', sanitize_text_field( $_POST['_molongui_guest_author_company_link'] ) );
			if ( isset( $_POST['_molongui_guest_author_twitter'] ) )     update_post_meta( $post_id, '_molongui_guest_author_twitter', sanitize_text_field( $_POST['_molongui_guest_author_twitter'] ) );
			if ( isset( $_POST['_molongui_guest_author_facebook'] ) )    update_post_meta( $post_id, '_molongui_guest_author_facebook', sanitize_text_field( $_POST['_molongui_guest_author_facebook'] ) );
			if ( isset( $_POST['_molongui_guest_author_linkedin'] ) )    update_post_meta( $post_id, '_molongui_guest_author_linkedin', sanitize_text_field( $_POST['_molongui_guest_author_linkedin'] ) );
			if ( isset( $_POST['_molongui_guest_author_gplus'] ) )       update_post_meta( $post_id, '_molongui_guest_author_gplus', sanitize_text_field( $_POST['_molongui_guest_author_gplus'] ) );
			if ( isset( $_POST['_molongui_guest_author_youtube'] ) )     update_post_meta( $post_id, '_molongui_guest_author_youtube', sanitize_text_field( $_POST['_molongui_guest_author_youtube'] ) );
			if ( isset( $_POST['_molongui_guest_author_pinterest'] ) )   update_post_meta( $post_id, '_molongui_guest_author_pinterest', sanitize_text_field( $_POST['_molongui_guest_author_pinterest'] ) );
			if ( isset( $_POST['_molongui_guest_author_tumblr'] ) )      update_post_meta( $post_id, '_molongui_guest_author_tumblr', sanitize_text_field( $_POST['_molongui_guest_author_tumblr'] ) );
			if ( isset( $_POST['_molongui_guest_author_instagram'] ) )   update_post_meta( $post_id, '_molongui_guest_author_instagram', sanitize_text_field( $_POST['_molongui_guest_author_instagram'] ) );
			if ( isset( $_POST['_molongui_guest_author_slideshare'] ) )  update_post_meta( $post_id, '_molongui_guest_author_slideshare', sanitize_text_field( $_POST['_molongui_guest_author_slideshare'] ) );
			if ( isset( $_POST['_molongui_guest_author_xing'] ) )        update_post_meta( $post_id, '_molongui_guest_author_xing', sanitize_text_field( $_POST['_molongui_guest_author_xing'] ) );
			if ( isset( $_POST['_molongui_guest_author_renren'] ) )      update_post_meta( $post_id, '_molongui_guest_author_renren', sanitize_text_field( $_POST['_molongui_guest_author_renren'] ) );
			if ( isset( $_POST['_molongui_guest_author_vk'] ) )          update_post_meta( $post_id, '_molongui_guest_author_vk', sanitize_text_field( $_POST['_molongui_guest_author_vk'] ) );
			if ( isset( $_POST['_molongui_guest_author_flickr'] ) )      update_post_meta( $post_id, '_molongui_guest_author_flickr', sanitize_text_field( $_POST['_molongui_guest_author_flickr'] ) );
			if ( isset( $_POST['_molongui_guest_author_vine'] ) )        update_post_meta( $post_id, '_molongui_guest_author_vine', sanitize_text_field( $_POST['_molongui_guest_author_vine'] ) );
			if ( isset( $_POST['_molongui_guest_author_meetup'] ) )      update_post_meta( $post_id, '_molongui_guest_author_meetup', sanitize_text_field( $_POST['_molongui_guest_author_meetup'] ) );
			if ( isset( $_POST['_molongui_guest_author_weibo'] ) )       update_post_meta( $post_id, '_molongui_guest_author_weibo', sanitize_text_field( $_POST['_molongui_guest_author_weibo'] ) );
			if ( isset( $_POST['_molongui_guest_author_deviantart'] ) )  update_post_meta( $post_id, '_molongui_guest_author_deviantart', sanitize_text_field( $_POST['_molongui_guest_author_deviantart'] ) );
			if ( isset( $_POST['_molongui_guest_author_stumbleupon'] ) ) update_post_meta( $post_id, '_molongui_guest_author_stumbleupon', sanitize_text_field( $_POST['_molongui_guest_author_stumbleupon'] ) );
			if ( isset( $_POST['_molongui_guest_author_myspace'] ) )     update_post_meta( $post_id, '_molongui_guest_author_myspace', sanitize_text_field( $_POST['_molongui_guest_author_myspace'] ) );
			if ( isset( $_POST['_molongui_guest_author_yelp'] ) )        update_post_meta( $post_id, '_molongui_guest_author_yelp', sanitize_text_field( $_POST['_molongui_guest_author_yelp'] ) );
			if ( isset( $_POST['_molongui_guest_author_mixi'] ) )        update_post_meta( $post_id, '_molongui_guest_author_mixi', sanitize_text_field( $_POST['_molongui_guest_author_mixi'] ) );
			if ( isset( $_POST['_molongui_guest_author_soundcloud'] ) )  update_post_meta( $post_id, '_molongui_guest_author_soundcloud', sanitize_text_field( $_POST['_molongui_guest_author_soundcloud'] ) );
			if ( isset( $_POST['_molongui_guest_author_lastfm'] ) )      update_post_meta( $post_id, '_molongui_guest_author_lastfm', sanitize_text_field( $_POST['_molongui_guest_author_lastfm'] ) );
			if ( isset( $_POST['_molongui_guest_author_foursquare'] ) )  update_post_meta( $post_id, '_molongui_guest_author_foursquare', sanitize_text_field( $_POST['_molongui_guest_author_foursquare'] ) );
			if ( isset( $_POST['_molongui_guest_author_spotify'] ) )     update_post_meta( $post_id, '_molongui_guest_author_spotify', sanitize_text_field( $_POST['_molongui_guest_author_spotify'] ) );
			if ( isset( $_POST['_molongui_guest_author_vimeo'] ) )       update_post_meta( $post_id, '_molongui_guest_author_vimeo', sanitize_text_field( $_POST['_molongui_guest_author_vimeo'] ) );
			if ( isset( $_POST['_molongui_guest_author_dailymotion'] ) ) update_post_meta( $post_id, '_molongui_guest_author_dailymotion', sanitize_text_field( $_POST['_molongui_guest_author_dailymotion'] ) );
			if ( isset( $_POST['_molongui_guest_author_reddit'] ) )      update_post_meta( $post_id, '_molongui_guest_author_reddit', sanitize_text_field( $_POST['_molongui_guest_author_reddit'] ) );
		}
		else
		{
			// Update data
			update_post_meta( $post_id, '_molongui_guest_author', $_POST['guest-author'] );						    // Guest author?
			if ( $_POST['guest-author'] == 0 ) delete_post_meta( $post_id, '_molongui_guest_author_id' );		    // Guest author ID
			else update_post_meta( $post_id, '_molongui_guest_author_id', $_POST['_molongui_guest_author_id'] );	// Guest author ID
			update_post_meta( $post_id, '_molongui_author_box_display', $_POST['_molongui_author_box_display'] );	// Show author box?
		}

	}


	/**
	 * Get author data.
	 *
	 * @param   int     $author_id      The ID of the author to get data from.
	 * @param   string  $author_type    The type of author: {guest | registered}.
	 * @param   array   $settings       The plugin settings.
	 * @return  array   $author         The author data.
	 * @access  public
	 * @since   1.2.14
	 * @version 1.3.0
	 */
	public function get_author_data ( $author_id, $author_type, $settings )
	{
		// Get author data
		if ( $author_type == 'guest' )
		{
			// Prepare archive page URI slug
			$uri_slug = ( ( isset( $settings['guest_archive_permalink'] ) and !empty( $settings['guest_archive_permalink'] ) ) ? '/'.$settings['guest_archive_permalink'] : '' ) .
			            ( ( isset( $settings['guest_archive_slug'] ) and !empty( $settings['guest_archive_slug'] ) ) ? '/'.$settings['guest_archive_slug'] : '/author' );

			// Guest author
			$author_post            = get_post( $author_id );
			$author['name']         = $author_post->post_title;
			$author['slug']         = $author_post->post_name;
			$author['mail']         = get_post_meta( $author_id, '_molongui_guest_author_mail', true );
			$author['show_mail']    = get_post_meta( $author_id, '_molongui_guest_author_show_mail', true );
			$author['link']         = get_post_meta( $author_id, '_molongui_guest_author_link', true );
			$author['url']          = ( $settings[ 'enable_guest_archives' ] ? home_url( $uri_slug . '/' . $author['slug'] ) : '' );
			$author['img']          = ( has_post_thumbnail( $author_id ) ? get_the_post_thumbnail( $author_id, "thumbnail", array( 'class' => 'mabt-radius-' . $settings[ 'img_style' ], 'itemprop' => 'image' ) ) : '' );
			$author['job']          = get_post_meta( $author_id, '_molongui_guest_author_job', true );
			$author['company']      = get_post_meta( $author_id, '_molongui_guest_author_company', true );
			$author['company_link'] = get_post_meta( $author_id, '_molongui_guest_author_company_link', true );
			$author['bio']          = $author_post->post_content;
			$author['tw']           = get_post_meta( $author_id, '_molongui_guest_author_twitter', true );
			$author['fb']           = get_post_meta( $author_id, '_molongui_guest_author_facebook', true );
			$author['in']           = get_post_meta( $author_id, '_molongui_guest_author_linkedin', true );
			$author['gp']           = get_post_meta( $author_id, '_molongui_guest_author_gplus', true );
			$author['yt']           = get_post_meta( $author_id, '_molongui_guest_author_youtube', true );
			$author['pi']           = get_post_meta( $author_id, '_molongui_guest_author_pinterest', true );
			$author['tu']           = get_post_meta( $author_id, '_molongui_guest_author_tumblr', true );
			$author['ig']           = get_post_meta( $author_id, '_molongui_guest_author_instagram', true );
			$author['ss']           = get_post_meta( $author_id, '_molongui_guest_author_slideshare', true );
			$author['xi']           = get_post_meta( $author_id, '_molongui_guest_author_xing', true );
			$author['re']           = get_post_meta( $author_id, '_molongui_guest_author_renren', true );
			$author['vk']           = get_post_meta( $author_id, '_molongui_guest_author_vk', true );
			$author['fl']           = get_post_meta( $author_id, '_molongui_guest_author_flickr', true );
			$author['vi']           = get_post_meta( $author_id, '_molongui_guest_author_vine', true );
			$author['me']           = get_post_meta( $author_id, '_molongui_guest_author_meetup', true );
			$author['we']           = get_post_meta( $author_id, '_molongui_guest_author_weibo', true );
			$author['de']           = get_post_meta( $author_id, '_molongui_guest_author_deviantart', true );
			$author['st']           = get_post_meta( $author_id, '_molongui_guest_author_stubmleupon', true );
			$author['my']           = get_post_meta( $author_id, '_molongui_guest_author_myspace', true );
			$author['ye']           = get_post_meta( $author_id, '_molongui_guest_author_yelp', true );
			$author['mi']           = get_post_meta( $author_id, '_molongui_guest_author_mixi', true );
			$author['so']           = get_post_meta( $author_id, '_molongui_guest_author_soundcloud', true );
			$author['la']           = get_post_meta( $author_id, '_molongui_guest_author_lastfm', true );
			$author['fo']           = get_post_meta( $author_id, '_molongui_guest_author_foursquare', true );
			$author['sp']           = get_post_meta( $author_id, '_molongui_guest_author_spotify', true );
			$author['vm']           = get_post_meta( $author_id, '_molongui_guest_author_vimeo', true );
			$author['dm']           = get_post_meta( $author_id, '_molongui_guest_author_dailymotion', true );
			$author['rd']           = get_post_meta( $author_id, '_molongui_guest_author_reddit', true );
		}
		else
		{
			// Registered author
			$author_post            = get_user_by( 'id', $author_id );
			$author['name']         = $author_post->display_name;
			$author['slug']         = $author_post->user_nicename;
			$author['mail']         = $author_post->user_email;
			$author['show_mail']    = get_the_author_meta( 'molongui_author_show_mail', $author_id );
			$author['link']         = get_the_author_meta( 'molongui_author_link', $author_id );
			$author['url']          = ( $settings[ 'enable_guest_archives' ] ? get_author_posts_url( $author_id ) : '' );
			$author['img']          = ( get_the_author_meta( "molongui_author_image_id", $author_id ) ? wp_get_attachment_image( get_the_author_meta( "molongui_author_image_id", $author_id ), "thumbnail", false, array( 'class' => 'mabt-radius-' . $settings[ 'img_style' ], 'itemprop' => 'image' ) ) : "" );
			$author['job']          = get_the_author_meta( 'molongui_author_job', $author_id );
			$author['company']      = get_the_author_meta( 'molongui_author_company', $author_id );
			$author['company_link'] = get_the_author_meta( 'molongui_author_company_link' );
			$author['bio']          = get_the_author_meta( 'molongui_author_bio', $author_id );
			$author['tw']           = get_the_author_meta( 'molongui_author_twitter', $author_id );
			$author['fb']           = get_the_author_meta( 'molongui_author_facebook', $author_id );
			$author['in']           = get_the_author_meta( 'molongui_author_linkedin', $author_id );
			$author['gp']           = get_the_author_meta( 'molongui_author_gplus', $author_id );
			$author['yt']           = get_the_author_meta( 'molongui_author_youtube', $author_id );
			$author['pi']           = get_the_author_meta( 'molongui_author_pinterest', $author_id );
			$author['tu']           = get_the_author_meta( 'molongui_author_tumblr', $author_id );
			$author['ig']           = get_the_author_meta( 'molongui_author_instagram', $author_id );
			$author['ss']           = get_the_author_meta( 'molongui_author_slideshare', $author_id );
			$author['xi']           = get_the_author_meta( 'molongui_author_xing', $author_id );
			$author['re']           = get_the_author_meta( 'molongui_author_renren', $author_id );
			$author['vk']           = get_the_author_meta( 'molongui_author_vk', $author_id );
			$author['fl']           = get_the_author_meta( 'molongui_author_flickr', $author_id );
			$author['vi']           = get_the_author_meta( 'molongui_author_vine', $author_id );
			$author['me']           = get_the_author_meta( 'molongui_author_meetup', $author_id );
			$author['we']           = get_the_author_meta( 'molongui_author_weibo', $author_id );
			$author['de']           = get_the_author_meta( 'molongui_author_deviantart', $author_id );
			$author['st']           = get_the_author_meta( 'molongui_author_stubmleupon', $author_id );
			$author['my']           = get_the_author_meta( 'molongui_author_myspace', $author_id );
			$author['ye']           = get_the_author_meta( 'molongui_author_yelp', $author_id );
			$author['mi']           = get_the_author_meta( 'molongui_author_mixi', $author_id );
			$author['so']           = get_the_author_meta( 'molongui_author_soundcloud', $author_id );
			$author['la']           = get_the_author_meta( 'molongui_author_lastfm', $author_id );
			$author['fo']           = get_the_author_meta( 'molongui_author_foursquare', $author_id );
			$author['sp']           = get_the_author_meta( 'molongui_author_spotify', $author_id );
			$author['vm']           = get_the_author_meta( 'molongui_author_vimeo', $author_id );
			$author['dm']           = get_the_author_meta( 'molongui_author_dailymotion', $author_id );
			$author['rd']           = get_the_author_meta( 'molongui_author_reddit', $author_id );
		}

		// Handle author profile image if none set.
		if( empty( $author['img'] ) && !empty( $author['mail'] ) )
		{
			if ( $settings[ 'img_default' ] == 'acronym' )
			{
				// Generate "initials" image.
				$author['img'] = $this->get_author_acronym( $author['name'], $settings );
			}
			else
			{
				// Try to load the associated Gravatar (https://codex.wordpress.org/Function_Reference/get_avatar).
				$author['img'] = get_avatar( $author['mail'], '150', $settings[ 'img_default' ], false, array( 'class' => 'mabt-radius-' . $settings[ 'img_style' ] ) );
			}
		}

		// Return data
		return $author;
	}


	/**
	 * Get author acronym.
	 *
	 * @param   string  $name       The author name.
	 * @param   array   $settings   Plugin settings.
	 * @return  string  $acronym    The author acronym.
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function get_author_acronym ( $name, $settings )
	{
		// Get the acronym.
		$acronym = $this->get_acronym( $name );

		// Return styled acronym.
		$html  = '';
		$html .= '<div class="mabt-radius-' . $settings[ 'img_style' ] . ' acronym-container" style="background:' . $settings[ 'acronym_bg_color' ] . '; color:' . $settings[ 'acronym_text_color' ] . ';">';
		$html .= '<div class="vertical-aligned">';
		$html .= $acronym;
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}


	/**
	 * Get the acronym of the given string.
	 *
	 * @param   string  $words      The string.
	 * @param   int     $length     The maximum length of the acronym.
	 * @return  string  $acronym    The acronym.
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function get_acronym ( $words, $length = 3 )
	{
		$acronym = '';
		foreach ( explode( ' ', $words ) as $word ) $acronym .= mb_substr( $word, 0, 1, 'utf-8' );

		return strtoupper( mb_substr( $acronym, 0, $length ) );
	}


	/**
	 * Get author posts.
	 *
	 * @see     http://codex.wordpress.org/Class_Reference/WP_Query
	 *
	 * @param   int     $author_id      The ID of the author to get data from.
	 * @param   string  $author_type    The type of author: {guest | registered}.
	 * @param   array   $settings       The plugin settings.
	 * @param   boolean $get_all        Whether to limit the query or not.
	 * @return  array   $posts          The author posts.
	 * @access  public
	 * @since   1.2.17
	 * @version 1.3.0
	 */
	public function get_author_posts ( $author_id, $author_type, $settings, $get_all = false )
	{
		// Adjust query
		if ( $author_type == 'guest' )
		{
			// Guest author
			$args = array(
				'post_type'      => 'post',
				'orderby'        => $settings[ 'related_order_by' ],
				'order'          => $settings[ 'related_order' ],
				'posts_per_page' => ( $get_all ? '-1' : $settings[ 'related_items' ] ),
				'meta_query'     => array(
					array(
						'key'    => '_molongui_guest_author',
						'value'  => '1',
					),
					array(
						'key'    => '_molongui_guest_author_id',
						'value'  => $author_id,
					),
				),
			);
		}
		else
		{
			// Registered author
			$args = array(
				'post_type'      => 'post',
				'author'         => $author_id,
				'orderby'        => $settings[ 'related_order_by' ],
				'order'          => $settings[ 'related_order' ],
				'posts_per_page' => ( $get_all ? '-1' : $settings[ 'related_items' ] ),
				'meta_query'     => array(
					'relation'    => 'OR',
					array(
						'key'     => '_molongui_guest_author',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation'     => 'AND',
						array(
							'key'      => '_molongui_guest_author',
							'compare'  => 'EXISTS',
						),
						array(
							'key'      => '_molongui_guest_author',
							'value'    => '0',
							'compare'  => '==',
						),
					),
				),
			);
		}

		// Get data
		$data = new WP_Query( $args );

		// Prepare data
		foreach ( $data->posts as $post )
		{
			$posts[] = $post;
		}

		// Return data
		if ( !empty( $posts ) ) return $posts;
		else return;
	}


	/**
	 * Return authorship box HTML markup to the calling JS.
	 *
	 * This function is called via AJAX (admin/js/).
	 *
	 * @access  public
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function authorship_box_preview()
	{
		// Check security (If nonce is invalid, die)
		check_ajax_referer( 'myajax-js-nonce', 'security', true );

		if ( is_admin() and isset( $_POST ) )
		{
			// Parse settings
			foreach ( $_POST['form'] as $input )
			{
				if ( strpos( $input['name'], 'molongui_authorship_box' ) !== false )
				{
					$new_key = str_replace( "molongui_authorship_box", "", $input['name'] );
					$new_key = substr($new_key, 1, -1);
					$box_settings[ $new_key ] = $input['value'];
				}
			}

			// Load other settings and merge them all
			$main_settings   = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
			$string_settings = get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS );
			$settings        = array_merge( $main_settings, $box_settings, $string_settings );

			// Demo data
			$random_id = substr( number_format(time() * mt_rand(), 0, '', ''), 0, 10 );
			$author = array(
				'name'         => 'Author name',
				'link'         => '#',
				'img'          => '<img class="mabt-radius-'.$settings['img_style'].'" height="150" width="150" src="'.MOLONGUI_AUTHORSHIP_URL.'/admin/img/preview_author_img.png">',
				'job'          => 'Writer',
				'company'      => 'Daily Mail',
				'company_link' => '#',
				'web'          => '#',
				'tw'           => '//twitter.com',
				'fb'           => '//facebook.com',
				'yt'           => '//youtube.com',
				'vi'           => '//vine.com',
				'bio'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque risus augue, lacinia feugiat eros eu, fermentum elementum turpis. Aenean vel porta neque. In eget quam pulvinar justo blandit bibendum ac id leo. Duis volutpat sit amet est non porta. Fusce interdum ante sed metus venenatis porta.',
			);
			for ($i = 1; $i <= $settings['related_items']; $i++)
			{
				$author_posts[] = (object) array(
					'ID'         => '#',
					'post_title' => 'Related article '.$i,
				);
			}

			// Set preview flag
			$is_preview = true;

			// Mount preview
			ob_start();
			if ( !isset( $settings['layout'] ) or
			     empty( $settings['layout'] ) or
			     $settings['layout'] == 'default' )
			{
				include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/html-default-author-box.php' );
			}
			elseif ( $settings['layout'] == 'tabbed' )
			{
				include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/html-tabbed-author-box.php' );
			}
			elseif ( is_premium() )
			{
				include( MOLONGUI_AUTHORSHIP_DIR . '/premium/public/views/html-' . $settings['layout'] . '-author-box.php' );
			}

			// Return markup to JS
			echo ob_get_clean();
		}

		// Avoid 'admin-ajax.php' to append the value outputted with a "0"
		wp_die();
	}


	/**
	 * Add preview button to 'box' settings tab.
	 *
	 * The result of this function is hooked into the 'render_page_settings' function.
	 *
	 * @access  public
	 * @param   string  $current_tab    Current tab of the settings page.
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function add_preview_button( $current_tab )
	{
		if ( $current_tab == 'box' )
		{
			?>
			<!-- Preview button -->
			<div class="molongui-modal-preview-button">
				<button id="molongui-authorship-box-preview-button" class="molongui-authorship-icon-preview"><?php _e( 'Preview', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></button>
			</div>

			<!-- Author box preview -->
			<div id="molongui-modal-preview" class="molongui-modal-preview">
				<div id="molongui-authorship-box-preview" class="modal-content">
					<div class="modal-header">
						<h2><?php _e( 'Preview', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></h2>
						<p>
							<span><?php _e( 'Note', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?></span>
							<?php _e( 'Your theme might change how typography, colors and sizes are displayed in this preview.', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?>
						</p>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button id="molongui-authorship-box-preview-close" class="molongui-modal-preview-close" tabindex="2">Close</button>
					</div>
				</div>
			</div>
		<?php
		}
	}
}