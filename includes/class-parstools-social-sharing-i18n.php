<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://parstools.com/?p=14751
 * @since      1.0.0
 *
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/includes
 * @author     Parstools <parsgateco@gmail.com>
 */
class Parstools_Social_Sharing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'parstools-social-sharing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
