<?php

use Molongui\Authorship\Includes\Plugin_Activator;
use Molongui\Authorship\Includes\Plugin_Deactivator;
use Molongui\Authorship\Includes\Plugin_Core;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * Plugin Name:       Molongui Authorship
 * Plugin URI:        https://molongui.amitzy.com/product/authorship
 * Description:       Give credit to the authors and contributors of your blog by showing their profile information in a fancy and minimalistic box within the post page.
 * Text Domain:       molongui-authorship
 * Domain Path:       /i18n/
 * Version:           1.3.0
 * Author:            Amitzy
 * Author URI:        https://molongui.amitzy.com/
 * Plugin Base:       _boilerplate 1.0.0
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see http://www.gnu.org/licenses/.
 *
 * @category   Plugin
 * @package    Molongui_Authorship
 * @since      1.0.0
 * @version    1.0.0
 */

// Deny direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

// Load plugin configuration
require_once( plugin_dir_path( __FILE__ ) . 'config/config.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-activator.php
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function activate_authorship_plugin()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-activator.php';
	Plugin_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_authorship_plugin' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-deactivator.php
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function deactivate_authorship_plugin()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-deactivator.php';
	Plugin_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_authorship_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function run_plugin()
{
	$plugin = new Plugin_Core();
	$plugin->run();
}
run_plugin();