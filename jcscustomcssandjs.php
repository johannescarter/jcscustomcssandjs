<?php
/*
Plugin Name: JohannesCarters Custom CSS and JavaScript
Description:
Author: JohannesCarter
Author URI: https://johanenscarter.de
Version: 1.0.0
*/

// prevent direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * == import plugin files ==
 */
require plugin_dir_path( __FILE__ ) . '/config/config.php';
require plugin_dir_path( __FILE__ ) . '/css/css_main.php';
require plugin_dir_path( __FILE__ ) . '/css/css_mobile.php';
require plugin_dir_path( __FILE__ ) . '/admin/admin_functions.php';
require plugin_dir_path( __FILE__ ) . '/public/public_functions.php';
require plugin_dir_path( __FILE__ ) . '/public/public_shortcodes.php';

// register plugin activation function
register_activation_hook( __FILE__, 'jcscustomcssandjs_install' );
// register plugin deactivation function
register_deactivation_hook( __FILE__, 'jcscustomcssandjs_uninstall' );
?>
