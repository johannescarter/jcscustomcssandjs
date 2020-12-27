<?php
/*
@wordpress
Plugin Name: JohannesCarters Custom CSS and JavaScript
Description: Wordpress Plugin to easily add custom CSS and JavaScript code in a simple and organized way.
Author: JohannesCarter
Author URI: https://johanenscarter.de
Version: 1.0.0

@jcs
PluginHex: 00
PluginShort: 'cucj' for CUstom Css and Js
FunctionPrefix: 'jcs_cucj_' for JohannesCarterS CUstom Css and Javascript
ConstantsPrefix: 'JCS_CUCJ_'
*/

// prevent direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * == import plugin files ==
 */
require plugin_dir_path( __FILE__ ) . '/config/config.php';
if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . '/admin/admin_functions.php';
}
require plugin_dir_path( __FILE__ ) . '/public/public_functions.php';
require plugin_dir_path( __FILE__ ) . '/public/public_shortcodes.php';

// register plugin activation function
register_activation_hook( __FILE__, 'jcs_cucj_activate' );
// register plugin deactivation function
register_deactivation_hook( __FILE__, 'jcs_cucj_deactivate' );
?>
