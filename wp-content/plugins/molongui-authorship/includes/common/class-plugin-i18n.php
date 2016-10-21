<?php

namespace Molongui\Authorship\Includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @author     Amitzy
 * @category   Molongui
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/includes
 * @since      1.0.0
 * @version    1.0.0
 */
class Plugin_i18n
{
	/**
	 * The domain specified for this plugin.
	 *
	 * @access   private
	 * @var      string    $domain    The domain identifier for this plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( dirname( plugin_basename( __FILE__ ) ) ) ) . '/i18n/'
		);
	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @param    string    $domain    The domain that represents the locale of this plugin.
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	public function set_domain( $domain )
	{
		$this->domain = $domain;
	}
}
