<?php

namespace Molongui\Authorship;

use Molongui\Authorship\Includes\Guest_Author;
use Molongui\Authorship\Includes\Page;


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public
 * @since      1.0.0
 * @version    1.3.0
 */

class Plugin_Public
{
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name     The ID of this plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access  private
	 * @var     string    $version         The current version of this plugin.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	private $version;

	protected $author;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param   string    $plugin_name     The name of the plugin.
	 * @param   string    $version         The version of this plugin.
	 * @since   1.0.0
	 * @version 1.2.12
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Load the required dependencies
		$this->load_dependencies();

		if( is_premium() )
		{
			// Load premium dependencies
			$this->load_premium_dependencies();
		}
	}


	/**
	 * Load the required dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.2.12
	 * @version  1.2.12
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for defining all Guest Author stuff
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-guest-author.php';

		/**
		 * The library containing general functions.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/includes/functions.php' );
	}


	/**
	 * Load the required premium dependencies for this class.
	 *
	 * Load other classes definitions used by this class.
	 *
	 * @access   private
	 * @since    1.2.2
	 * @version  1.3.0
	 */
	private function load_premium_dependencies()
	{
		// Load plugin settings
		$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

		/**
		 * The library responsible for defining all available shortcodes.
		 */
		require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/shortcodes.php' );

		/**
		 * The library responsible for initiating virtual pages functionality.
		 */
		if ( $settings['enable_guest_archives'] ) require_once( MOLONGUI_AUTHORSHIP_DIR . '/premium/includes/virtualpages.php' );
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( !is_premium() )
		{
			$fpath = '/public/css/molongui-authorship.7bb3.min.css';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_style( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array(), $this->version, 'all' );
		}
		else
		{
			$fpath = '/premium/public/css/molongui-authorship-premium.4d22.min.css';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_style( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array(), $this->version, 'all' );
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( !is_premium() )
		{
			$fpath = '/public/js/molongui-authorship.xxxx.min.js';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_script( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array( 'jquery' ), $this->version, false );
		}
		else
		{
			$fpath = '/premium/public/js/molongui-authorship-premium.xxxx.min.js';
			if ( file_exists( MOLONGUI_AUTHORSHIP_DIR . $fpath ) )
				wp_enqueue_script( $this->plugin_name, MOLONGUI_AUTHORSHIP_URL . $fpath, array( 'jquery' ), $this->version, false );
		}

	}


	/**
	 * Display guest author if any.
	 *
	 * Hook into the_author() function to override author name
	 * if there is a guest author set.
	 *
	 * @access  public
	 * @param   object      $author         The post author.
	 * @return  string
	 * @since   1.0.0
	 * @version 1.2.17
	 */
	public function filter_author_name( $author )
	{
		global $post;

		// Avoid PHP notices when $post is empty
		if ( empty( $post )) return $author;

		// Filter author name on author archive virtual pages
		if ( is_author() and is_virtualpage() ) return $post->guest_author;

		// Filter author name when handling guest post
		if ( $guest = get_post_meta( $post->ID, '_molongui_guest_author_id', true ) ) $author = get_the_title( $guest );

		return $author;
	}


	/**
	 * Modify guest author link.
	 *
	 * Hook into the_author_link() function to override author link
	 * if there is a guest author set.
	 *
	 * @access  public
	 * @param   object      $link       The author link.
	 * @return  string
	 * @since   1.0.0
	 * @version 1.2.18
	 */
	public function filter_author_link( $link  )
	{
		global $post;

		// Avoid PHP notices when $post is empty
		if ( empty( $post )) return $link;

		// If guest author, modify link
		if ( $guest_id = get_post_meta( $post->ID, '_molongui_guest_author_id', true ) )
		{
			if ( is_premium() )
			{
				// Get guest author
				$guest = get_post( $guest_id );

				// Get guest author archive page link
				$guest_link = home_url( '/author/' . $guest->post_name );

				// Return data
				return $guest_link;
			}
			else
			{
				return '#';
			}
		}
		// If registered user, return link untouched
		else return $link;
	}


	/**
	 * Render the author box.
	 *
	 * Display the author box within the content only if a single post or a page is being displayed.
	 * Showing it on author and archive pages was removed on version 1.2.17.
	 *
	 * @access  public
	 * @param   string      $content        The post content.
	 * @return  string      $content        The modified post content.
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function render_author_box( $content )
	{
		if ( ( is_single() or is_page() ) and !is_virtualpage() )
		{
			global $post;

			// Get plugin settings.
			$main_settings   = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
			$box_settings    = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
			$string_settings = get_option( MOLONGUI_AUTHORSHIP_STRING_SETTINGS );
			$settings        = array_merge( $main_settings, $box_settings, $string_settings );

			// Get post authorship box display configuration.
			$author_box_display = get_post_meta( $post->ID, '_molongui_author_box_display', true );

			// If no post configuration and default option is to not display the authorship box, exit and do not display anything.
			if ( empty( $author_box_display ) && $settings['display'] == '0' ) return $content;

			// If no post configuration and default option is to display the authorship box only on posts, exit if not displaying a post.
			if ( empty( $author_box_display ) && $settings['display'] == 'posts' && !is_single() ) return $content;

			// If no post configuration and default option is to display the authorship box only on pages, exit if not displaying a page.
			if ( empty( $author_box_display ) && $settings['display'] == 'pages' && !is_page() ) return $content;

			// If post configured to not display the authorship box, exit and do not display it.
			if ( $author_box_display == 'hide' ) return $content;

			// Get author data.
			$guest_id  = get_post_meta( $post->ID, '_molongui_guest_author_id', true );
			$author_id = ( $guest_id ? $guest_id : $post->post_author );

			// If no ID or 0, exit.
			if ( !$author_id ) return $content;

			$author = new Guest_Author();
			if ( $settings['show_related'] or $settings['layout'] == 'tabbed' ) $author_posts = $author->get_author_posts( $author_id, ( $guest_id ? 'guest': 'registered' ), $settings );
			$author = $author->get_author_data( $author_id, ( $guest_id ? 'guest': 'registered' ), $settings );

			// If there is not significant info to show, do not display the author box.
			if ( !$author_id or ( !$author['bio'] and $settings['hide_if_no_bio'] ) ) return $content;

			// Make up a random id that uniquely identify the box
			$random_id = substr( number_format(time() * mt_rand(), 0, '', ''), 0, 10 );

			// The markup.
			ob_start();
			if ( !isset( $settings['layout'] ) or
			     empty( $settings['layout'] ) or
			     $settings['layout'] == 'default' )
			{
				include( plugin_dir_path( __FILE__ ) . 'views/html-default-author-box.php' );
			}
			elseif ( $settings['layout'] == 'tabbed' )
			{
				include( plugin_dir_path( __FILE__ ) . 'views/html-tabbed-author-box.php' );
			}
			elseif ( is_premium() )
			{
				include( plugin_dir_path( __FILE__ ) . '../premium/public/views/html-' . $settings['layout'] . '-author-box.php' );
			}
			$html = ob_get_clean();

			// Add "Author Box" to the post content.
			switch ( $settings['position'] )
			{
				case "above":
					$content = $html . $content;
					break;

				case "below":
				case "default":
					$content .= $html;
					break;
			}
		}

		// Return markup to render.
		return $content;
	}


	/**
	 * Show author metadata into the html head.
	 *
	 * Adds authorship meta to the head of the HTML document.
	 *
	 * @access  public
	 * @return  string      $meta        The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function add_author_meta()
	{
		global $post;

		// Avoid PHP notices when $post is empty.
		if ( empty( $post )) return;

		// Get author data.
		if ( $author_id = get_post_meta( $post->ID, '_molongui_guest_author_id', true ) )
		{
			// Guest author.
			$author      = get_post( $author_id );
			if ( !isset( $author ) or empty( $author ) ) return; // If no data, exit to avoid errors and warning
			$author_name = $author->post_title;
			$author_link = get_post_meta( $author_id, '_molongui_guest_author_link', true );
			$author_img  = ( has_post_thumbnail( $author_id ) ? get_the_post_thumbnail( $author_id, "thumbnail" ) : '' );
			$author_fb   = get_post_meta( $author_id, '_molongui_guest_author_facebook', true );
			$author_gp   = get_post_meta( $author_id, '_molongui_guest_author_gplus', true );
		}
		else
		{
			// Registered author.
			$author_id   = $post->post_author;
			$author      = get_user_by( 'id', $author_id );
			if ( !isset( $author ) or empty( $author ) ) return; // If no data, exit to avoid errors and warning
			$author_name = $author->user_nicename;
			$author_link = get_the_author_meta( 'molongui_author_link' );
			$author_fb   = get_the_author_meta( 'molongui_author_facebook' );
			$author_gp   = get_the_author_meta( 'molongui_author_gplus' );
		}

		// Get plugin options.
		$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

		if ( !is_premium() ) $meta = "\n<!-- Molongui Authorship " . $this->version . ", visit: https://wordpress.org/plugins/molongui-authorship/ -->\n";
		else $meta = "\n<!-- Molongui Authorship Premium " . $this->version . ", visit: " . MOLONGUI_AUTHORSHIP_WEB . " -->\n";

		// Show the OpenGraph metadata on "Author archive" page if enabled.
		if ( $settings['add_opengraph_meta'] == 1 && is_author() )
		{
			$meta .= $this->add_opengraph_meta( $author_name, $author_link );
		}

		// Show Google author meta.
		if ( $settings['add_google_meta'] == 1 && isset( $author_gp ) && $author_gp <> '' ) $meta .= $this->add_google_author_meta( $author_gp );

		// Show Facebook author meta.
		if ( $settings['add_facebook_meta'] == 1 && isset( $author_fb ) && $author_fb <> '' ) $meta .= $this->add_facebook_author_meta( $author_fb );

		$meta .= "<!-- /Molongui Authorship -->\n\n";

		echo $meta;
	}


	/**
	 * Get the Open Graph for the current (guest) author.
	 *
	 * @access  public
	 * @param   string  $author_name    Author name.
	 * @param   string  $author_link    Author link.
	 * @return  string  $meta           The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_opengraph_meta( $author_name, $author_link )
	{
		$og  = '';
		$og .= sprintf( '<meta property="og:url" content="%s" />', $author_link ) . "\n";
		$og .= sprintf( '<meta property="og:type" content="%s" />', 'profile' ) . "\n";
		$og .= sprintf( '<meta property="profile:username" content="%s" />', $author_name ) . "\n";

		return $og;
	}

	/**
	 * Get the Google author Meta.
	 *
	 * @access  public
	 * @param   string  $author_gp  Google id.
	 * @return  string  $meta       The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_google_author_meta( $author_gp )
	{
		return '<link rel="author" href="' . ( (strpos( $author_gp, 'http' ) === false ) ? 'https://plus.google.com/' : '' ) . $author_gp . '" />' . "\n";
	}


	/**
	 * Get the Facebook author Meta.
	 *
	 * @access  public
	 * @param   string  $author_fb  Facebook id.
	 * @return  string  $meta       The meta tags to include into the html head.
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function add_facebook_author_meta( $author_fb )
	{
		return '<meta property="article:author" content="' . ( (strpos( $author_fb, 'http' ) === false ) ? 'https://www.facebook.com/' : '' ) . $author_fb . '" />' . "\n";
	}

}