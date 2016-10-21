<?php

namespace Molongui\Authorship\Admin;

/**
 * Settings page.
 *
 * Displays a settings page at the admin area.
 *
 * @author	Molongui
 * @since	1.3.0
 * @version	1.3.0
 */

if ( !class_exists( 'Settings_Page' ) )
{
	class Settings_Page
	{
		/**
		 * The URI slugs of each tab of the admin settings page.
		 *
		 * @access   private
		 * @var      string
		 * @since    1.3.0
		 * @version  1.3.0
		 */
		private $slug_tab_main    = 'main';
		private $slug_tab_about   = 'about';
		private $slug_tab_license = 'license';


		/**
		 * Class constructor.
		 *
		 * @param   string  $to     Recipient.
		 * @param   string  $from   Sender.
		 * @since   1.3.0
		 * @version 1.3.0
		 */

		function __construct( $slug, $tabs, $default_tab )
		{
			$this->slug         = $slug;
			$this->tabs         = $tabs;
			$this->default_tab  = $default_tab;
		}


		/**
		 * Adds a link to the theme settings page into the 'themes' menu.
		 *
		 * This function registers the menu link to the settings page and the settings page itself.
		 *
		 * @access  public
		 * @see     https://codex.wordpress.org/Function_Reference/add_menu_page
		 * @see     https://codex.wordpress.org/Function_Reference/add_submenu_page
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function add_menu_item()
		{
			if( MOLONGUI_AUTHORSHIP_MENU == "topmenu" )
			{
				add_menu_page( __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ), '', '20' );
				$menu_slug = 'admin.php?page=';
			}
			else
			{
				switch ( MOLONGUI_AUTHORSHIP_SUBMENU )
				{
					case 'dashboard':
						add_submenu_page( 'index.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'index.php?page=' . $this->slug;
					break;

					case 'posts':
						add_submenu_page( 'edit.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'edit.php?page=' . $this->slug;
					break;

					case 'media':
						add_submenu_page( 'upload.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'upload.php?page=' . $this->slug;
					break;

					case 'pages':
						add_submenu_page( 'edit.php?post_type=page', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'edit.php?post_type=page&page=' . $this->slug;
					break;

					case 'comments':
						add_submenu_page( 'edit-comments.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'edit-comments.php?page=' . $this->slug;
					break;

					case 'appearance':
						add_submenu_page( 'themes.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'themes.php?page=' . $this->slug;
					break;

					case 'plugins':
						add_submenu_page( 'plugins.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'plugins.php?page=' . $this->slug;
					break;

					case 'users':
						add_submenu_page( 'users.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'users.php?page=' . $this->slug;
					break;

					case 'tools':
						add_submenu_page( 'tools.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'tools.php?page=' . $this->slug;
					break;

					case 'settings':
					default:
						add_submenu_page( 'options-general.php', __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), __( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ), 'manage_options', $this->slug, array( $this, 'render_page_settings' ) );
						$menu_slug = 'options-general.php?page=' . $this->slug;
					break;
				}
			}

			return $menu_slug;
		}


		/**
		 * Display the Settings Page for the admin area.
		 *
		 * This function renders a tabbed settings page. In order to customize it, edit
		 * 'views/html-admin-page-settings.php' file. This function should not be modified.
		 *
		 * There are two HOOKS enabled within this function:
		 *
		 *      molongui_authorship_settings_before_submit_button
		 *      molongui_authorship_settings_after_submit_button
		 *
		 * @access  public
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function render_page_settings()
		{
			// Define tabs without "Save" button.
			$no_save_button = array( 'support', $this->slug_tab_about );

			// Get current tab.
			$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->default_tab;
			?>

			<div id="molongui-settings" class="wrap molongui license-<?php echo ( is_premium() ? 'premium' : 'free' ); ?>">

				<!-- Page title -->
				<h2><?php _e( 'Molongui Authorship', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ); ?><span class="version"><?php echo MOLONGUI_AUTHORSHIP_VERSION; ?></span></h2>

				<!-- Display "powered by Molongui" -->
				<p class="powered-by">Powered by<a href="//molongui.amitzy.com/" title="Molongui">Molongui</a></p>

				<!-- Display tabs -->
				<h2 class="nav-tab-wrapper">
					<?php
						foreach ( $this->tabs as $tab )
						{
							// Hide "License" tab on free version.
							if ( $tab['slug'] == 'license' and !is_premium() ) continue;

							$active = $current_tab == $tab['slug'] ? 'nav-tab-active' : '';
							echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->slug . '&tab=' . $tab['slug'] . '">' . $tab['label'] . '</a>';
						}
					?>
				</h2>

				<?php do_action( 'molongui_authorship_settings_before_submit_button', $current_tab ); ?>

				<!-- Submit button -->
				<div class="<?php if ( ( !is_premium() ) && ( !in_array( $current_tab, $no_save_button ) ) ) echo 'main'; ?>">
					<form id="molongui-authorship-settings-form" method="post" action="options.php">
						<?php wp_nonce_field( 'update-options' ); ?>
						<?php settings_fields( $current_tab ); ?>
						<?php $this->molongui_do_settings_sections( $current_tab ); ?>
						<?php if( ( !in_array( $current_tab, $no_save_button ) ) ) submit_button(); ?>
					</form>
				</div>

				<?php do_action( 'molongui_authorship_settings_after_submit_button', $current_tab ); ?>

				<!-- Display sidebar -->
				<?php if ( ( !is_premium() ) && ( $current_tab != $this->slug_tab_about ) ) include( MOLONGUI_AUTHORSHIP_DIR . '/admin/views/html-admin-page-sidebar.php' ); ?>

			</div>
			<?php
		}


		/**
		 * Customized 'do_settings_sections' function to be able to call
		 * the customized 'molongui_do_settings_fields' function in order
		 * to add the tip's icon at the end of field's label.
		 *
		 * @access  private
		 * @param   string  $page   Current settings tab.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		private function molongui_do_settings_sections( $page )
		{
			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections[$page] ) ) return;

			foreach ( (array) $wp_settings_sections[$page] as $section )
			{
				if ( $section['title'] ) echo "<h2>{$section['title']}</h2>\n";

				if ( $section['callback'] ) call_user_func( $section['callback'], $section );

				if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) ) continue;

				echo '<table class="form-table">';
				$this->molongui_do_settings_fields( $page, $section['id'] );
				echo '</table>';
			}
		}


		/**
		 * Customized 'do_settings_fields' function to be able to add
		 * the tip's icon at the end of field's label.
		 *
		 * @access  private
		 * @param   string  $page       Current settings tab.
		 * @param   string  $section    Section to fill up.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		private function molongui_do_settings_fields( $page, $section )
		{
			global $wp_settings_fields;

			if ( ! isset( $wp_settings_fields[$page][$section] ) ) return;

			foreach ( (array) $wp_settings_fields[$page][$section] as $field )
			{
				$class = '';

				if ( ! empty( $field['args']['class'] ) )
				{
					$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
				}

				echo "<tr{$class}>";

				if ( ! empty( $field['args']['label_for'] ) )
				{
					echo '<th scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';
				}
				else
				{
					echo '<th scope="row">'
					     . $field['title']
					     . ( isset( $field['args']['field']['tip'] ) ? $this->molongui_help_tip( $field['args']['field']['tip'] ) : "" )
					     . ( ( isset( $field['args']['field']['premium'] ) and !empty( $field['args']['field']['premium'] ) ) ? $this->molongui_premium_setting( $field['args']['field']['premium'] ) : "" )
					     . '</th>';
				}

				echo '<td>';
				call_user_func($field['callback'], $field['args']);
				echo '</td>';
				echo '</tr>';
			}
		}


		/**
		 * Renders the help icon.
		 *
		 * @access  private
		 * @param   string  $tip            Tip's text to display.
		 * @param   boolean $allow_html     Whether to allow HTML.
		 * @return  string                  HTML markup to render tip icon.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		private function molongui_help_tip( $tip, $allow_html = false )
		{
			if ( $allow_html )
			{
				$tip = molongui_sanitize_tooltip( $tip );
			}
			else
			{
				$tip = esc_attr( $tip );
			}
			return '<i class="molongui-authorship-icon-tip molongui-help-tip" data-tip="' . $tip . '"></i>';
		}


		/**
		 * Renders the star icon meaning a premium setting.
		 *
		 * @access  private
		 * @param   string  $tip            Tip's text to display.
		 * @param   boolean $allow_html     Whether to allow HTML.
		 * @return  string                  HTML markup to render star icon.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		private function molongui_premium_setting( $tip, $link = true, $allow_html = false )
		{
			if ( !is_premium() )
			{
				// Handle tip.
				if ( $allow_html )
				{
					$tip = molongui_sanitize_tooltip( $tip );
				}
				else
				{
					$tip = esc_attr( $tip );
				}

				// Return data.
				$html  = '';
				$html .= ( $link ? '<a href="' . MOLONGUI_AUTHORSHIP_WEB . '" target="_blank">' : '' );
				$html .= '<i class="molongui-authorship-icon-star molongui-help-tip molongui-premium-setting" data-tip="' . $tip . '"></i>';
				$html .= ( $link ? '</a>' : '' );

				return $html;
			}
		}


		/**
		 * Sanitize a string destined to be a tooltip.
		 *
		 * Tooltips are encoded with htmlspecialchars to prevent XSS. Should not be used in conjunction with esc_attr().
		 *
		 * @param   string  $var    Tip's text to display.
		 * @return  string          Sanitized tooltip.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		function molongui_sanitize_tooltip( $var )
		{
			return htmlspecialchars( wp_kses( html_entity_decode( $var ), array(
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'small'  => array(),
				'span'   => array(),
				'ul'     => array(),
				'li'     => array(),
				'ol'     => array(),
				'p'      => array(),
			) ) );
		}


		/**
		 * Register settings page tabs.
		 *
		 * @access  public
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function add_page_tabs()
		{
			foreach ( $this->tabs as $tab )
			{
				$this->register_tab( $tab );
			}
		}


		/**
		 * Register tabs.
		 *
		 * @access  private
		 * @param
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		private function register_tab( $tab )
		{
			register_setting( $tab['slug'], $tab['key'], $tab['callback'] );
			foreach ( $tab['sections'] as $section )
			{
				switch ( $section['callback'] )
				{
					case 'render_description':

						add_settings_section( $section['id'], $section['label'], array( $this, 'render_section_description' ), $tab['slug'] );

					break;

					case 'render_page':

						add_settings_section( $section['id'], $section['label'], array( $this, 'render_page' ), $tab['slug'] );

					break;
				}

				if ( !empty ( $section['fields'] ) )
				{
					foreach ( $section['fields'] as $field )
					{
						add_settings_field( $field['id'], $field['label'], array( $this, 'render_field' ), $tab['slug'], $section['id'], array( 'field' => $field, 'option_group' => $tab['key'] ) );
					}
				}
			}
		}

		/**
		 * Function that fills the section with the desired description.
		 *
		 * This function is called as a callback, so no parameters can be passed. Being so, the code is more complicated that
		 * just an echo.
		 *
		 * @see     http://wordpress.stackexchange.com/questions/19156/how-to-pass-variable-to-add-settings-section-callback
		 *
		 * @access  public
		 * @param
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function render_section_description( $args )
		{
			foreach ( $this->tabs as $tab )
			{
				foreach ( $tab['sections'] as $section )
				{
					if ( $section['id'] == $args['id'] ) echo '<p>' . $section['desc'] . '</p>';
				}
			}
		}

		/**
		 * Function that fills the section with the desired content.
		 *
		 * This function is called as a callback, so no parameters can be passed. Being so, the code is more complicated that
		 * just an echo.
		 *
		 * @see     http://wordpress.stackexchange.com/questions/19156/how-to-pass-variable-to-add-settings-section-callback
		 *
		 * @access  public
		 * @param
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function render_page( $args )
		{
			foreach ( $this->tabs as $tab )
			{
				foreach ( $tab['sections'] as $section )
				{
					if ( $section['id'] == $args['id'] ) include( $section['cb_args'] );
				}
			}
		}

		/**
		 * Renders a field.
		 *
		 * @access  public
		 * @param   array   $args  Field to render and option group where to store its value to.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function render_field( $args )
		{
			// Parse parameters
			$field        = $args['field'];
			$option_group = $args['option_group'];

			// Avoid PHP warnings/notices
			if ( ! isset( $field['type'] ) )  return;
			if ( ! isset( $field['icon'] ) )  $field['icon'] = '';
			if ( ! isset( $field['id'] ) )    $field['id'] = '';
			if ( ! isset( $field['label'] ) ) $field['label'] = '';
			if ( ! isset( $field['desc'] ) )  $field['desc'] = '';
			if ( ! isset( $field['tip'] ) )   $field['tip'] = '';
			if ( ! isset( $field['name'] ) )  $field['name'] = '';
			if ( ! isset( $field['placeholder'] ) ) $field['placeholder'] = '';

			// Get saved options
			$options = get_option( $option_group );

			// Render field
			switch ( $field['type'] )
			{
				case 'text':
					if ( $field['icon'] and $field['icon']['position'] == 'left' ) $this->render_icon( $options, $field );
					echo '<input type="text" id="' . $field['id'] . '" name="' . $option_group . '[' . $field['id'] . ']" placeholder="' . $field['placeholder'] . '" value="' . $options[ $field['id'] ] . '" class="regular-text ltr ' . ( ( stripos( $field['id'], "activation_" ) !== false and $options[ $field['id'] ] ) ? 'molongui-field-validated' : '' ) . '" />' . ' ' . $field['desc'];
					if ( $field['icon'] and $field['icon']['position'] == 'right' ) $this->render_icon( $options, $field );
				break;

				case 'textarea':
					echo '<textarea id="' . $field['id'] . '" name="' . $option_group . '[' . $field['id'] . ']" rows="5" cols="50">' . $options[ $field['id'] ] . '</textarea>';
				break;

				case 'select':
					if ( $field['icon'] and $field['icon']['position'] == 'left' ) $this->render_icon( $options, $field );
					echo '<select id="' . $field['id'] . '" name="' . $option_group . '[' . $field['id'] . ']">';
						foreach ( $field['options'] as $option )
						{
							echo '<option value="' . $option['value'] . '"' . selected( $options[$field['id']], $option['value'], false ) . '>' . $option['label'] . '</option>';
						}
					echo '</select>';
					if ( $field['icon'] and $field['icon']['position'] == 'right' ) $this->render_icon( $options, $field );
				break;

				case 'radio':
					foreach ( $field['options'] as $option )
					{
						echo '<input type="radio" id="' . $field['id'] . '" name="' . $option_group . '[' . $field['id'] . ']" value="' . $option['value'] . '"' . checked( $option['value'], $options[$field['id']], false ) . '/>';
						echo '&nbsp;';
						echo '<label for="' . $option_group . '[' . $field['id'] . ']">' . $option['label'] . '</label>';
						echo '<br>';
					}
				break;

				case 'checkbox':
					echo '<input type="checkbox" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '"';
					echo checked( get_option( $field['name'] ), 'on' );
					echo '/>';
					echo '<label for="' . $field['name'] . '">' . $field['desc'] . '</label>';
				break;

				case 'checkboxes':
					echo '<ul id="' . $field['id'] . '">';
					foreach ( $field['options'] as $option )
					{
						echo '<li style="float:left; width:150px;">';
						echo '<input type="checkbox" id="' . $field['id'].'_'.$option['id'] . '" name="' . $option_group . '[' . $field['id'] . '_' . $option['id'] . ']" value="1"' . ( ( isset( $options[$field['id'].'_'.$option['id']] ) && $options[$field['id'].'_'.$option['id']] == 1 ) ? 'checked="checked"' : '')  . '/>';
						echo '<label for="' . $field['id'].'_'.$option['id'] . '">' . $option['label'] . '</label>';
						echo '</li>';
					}
					echo '</ul>';
				break;

				case 'colorpicker':
						echo '<input type="text" class="colorpicker" name="' . $option_group . '[' . $field['id'] . ']" value="' . $options[ $field['id'] ] . '">';
				break;

				case 'button':
						echo '<button id="' . $field['args']['id'] . '" class="' . $field['args']['class'] . '">' . $field['args']['label'] . '</button>';
				break;
			}
		}

		/**
		 * Renders a field's icon.
		 *
		 * @access  public
		 * @param   array   $field  Field to render.
		 * @since   1.3.0
		 * @version 1.3.0
		 */
		public function render_icon( $options, $field )
		{
			switch ( $field['icon']['type'] )
			{
				case 'status':

					if ( $options[ $field['id'] ] )
					{
						echo '<i class="molongui-authorship-icon-checkmark molongui-license-data-ok"></i>';
					}
					else
					{
						echo '<i class="molongui-authorship-icon-notice molongui-license-data-ko"></i>';
					}

				break;

				case 'tip':

					echo '<span class="molongui-help-tip" data-tip="' . $field['icon']['tip'] . '">?</span>';

				break;
			}
		}

	} // End of 'Settings_Page' class
}