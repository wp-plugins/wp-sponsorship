<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Sponsorship
 *
 * @wordpress-plugin
 * Plugin Name:       WPSponsorship
 * Plugin URI:        http://www.wp-inbound.com/
 * Description:       The core plugin for WPSponsorship, it allows users to invite their friends to your site via E-mail
 * Version:           1.0.1
 * Author:            B5productions
 * Author URI:        http://www.wp-inbound.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sponsorship
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sponsorship-activator.php
 */
function activate_sponsorship() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sponsorship-activator.php';
	Sponsorship_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sponsorship-deactivator.php
 */
function deactivate_sponsorship() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sponsorship-deactivator.php';
	Sponsorship_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sponsorship' );
register_deactivation_hook( __FILE__, 'deactivate_sponsorship' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sponsorship.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sponsorship() {

	$plugin = new Sponsorship();
	$plugin->run();

}
run_sponsorship();
