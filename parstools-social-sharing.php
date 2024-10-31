<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://parstools.com/?p=14751
 * @since             1.0.0
 * @package           Parstools_Social_Sharing
 *
 * @wordpress-plugin
 * Plugin Name:       Parstools Social Sharing
 * Plugin URI:        http://parstools.com/?p=14751
 * Description:       With Social Sharing buttons you can grow your traffic
 * Version:           1.0.0
 * Author:            Parstools
 * Author URI:        http://parstools.com/?p=14751
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       parstools-social-sharing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parstools-social-sharing-activator.php
 *
function activate_parstools_social_sharing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parstools-social-sharing-activator.php';
	Parstools_Social_Sharing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parstools-social-sharing-deactivator.php
 *
function deactivate_parstools_social_sharing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parstools-social-sharing-deactivator.php';
	Parstools_Social_Sharing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parstools_social_sharing' );
register_deactivation_hook( __FILE__, 'deactivate_parstools_social_sharing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-parstools-social-sharing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_parstools_social_sharing() {

	$plugin = new Parstools_Social_Sharing();
	$plugin->run();

}
run_parstools_social_sharing();
