<?php
/**
 * This file contains general admin functions like activation and deactication functions
 */

// prevent direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * register css for admin area
 */
function jcs_cucj_register_admin_css() {
    wp_enqueue_style( 'jcs_cucj_css_main', plugin_dir_url( __FILE__ ) . 'assets/css/main.css' );
}

add_action( 'admin_menu', 'jcs_cucj_admin_menu_init' );

function jcs_cucj_admin_menu_init() {
    jcs_cucj_register_admin_css();
}

/**
 * Register our stylesheet.
 */
function wpdocs_plugin_admin_init() {
    wp_register_style( 'wpdocsPluginStylesheet', plugins_url( 'stylesheet.css', __FILE__ ) );
}

/**
 * This function is called, when the plugin is activated.
 */
function jcs_cucj_activate() {

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // create db table for css sheets
    $table_name = $wpdb->prefix . 'jcs_cucj_css_sheets';

    $sql = "CREATE TABLE $table_name (
        id int(255) NOT NULL AUTO_INCREMENT,
        name longtext NOT NULL,
        description longtext,
        media_query longtext,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // create db table for css entries
    $table_name = $wpdb->prefix . 'jcs_cucj_css_entries';

    $sql = "CREATE TABLE $table_name (
        id int(255) NOT NULL AUTO_INCREMENT,
        selector longtext NOT NULL,
        description longtext,
        custom_code longtext,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // create db table for js files
    $table_name = $wpdb->prefix . 'jcs_cucj_js_files';

    $sql = "CREATE TABLE $table_name (
        id int(255) NOT NULL AUTO_INCREMENT,
        name longtext NOT NULL,
        description longtext,
        custom_code longtext,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}

/**
 * This function is called, when the plugin is deactivated.
 */
function jcs_cucj_deactivate() {

}

?>
