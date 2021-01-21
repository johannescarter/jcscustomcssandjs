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
	require plugin_dir_path( __FILE__ ) . '/admin/admin_menu.php';
    add_action( 'current_screen', 'enqueue_jcs_cucj_css_main' );
}
require plugin_dir_path( __FILE__ ) . '/public/public_functions.php';
require plugin_dir_path( __FILE__ ) . '/public/public_shortcodes.php';

/**
 * Run code on the plugins admin menu page
 */
function enqueue_jcs_cucj_css_main() {
    $currentScreen = get_current_screen();
    if( strpos( $currentScreen->id, 'jcs_cucj_' ) !== false ) {
        wp_enqueue_style( 'jcs_cucj_css_main', plugin_dir_url( __FILE__ ) . '/assets/css/admin_menu_main.css' );
    }
}

// register plugin activation function
register_activation_hook( __FILE__, 'jcs_cucj_activate' );
// register plugin deactivation function
register_deactivation_hook( __FILE__, 'jcs_cucj_deactivate' );
?>
