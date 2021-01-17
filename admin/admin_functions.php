<?php
/**
 * This file contains general admin functions like activation and deactication functions
 */

// prevent direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Register our stylesheet.
 */
function jcs_cucj_render_css() {
    //wp_register_style( 'wpdocsPluginStylesheet', plugins_url( 'stylesheet.css', __FILE__ ) );
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
        stylesheet_id int(255) NOT NULL,
        selector longtext NOT NULL,
        comment longtext,
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

    // create db table for css and js files to pages link
    $table_name = $wpdb->prefix . 'jcs_cucj_files_pages_rel';

    $sql = "CREATE TABLE $table_name (
        page_id bigint(20) NOT NULL,
        file_id int(255) NOT NULL,
        file_type varchar(3) NOT NULL
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
