<?php
/**
 * This file contains general admin functions like activation and deactication functions
 */

// prevent direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * This function is called, when the plugin is activated.
 */
function jcs_cucj_activate() {

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // create db table for css sheets
    //$table_name = $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_sheets';
    $table_name = $wpdb->prefix . 'jcs_cucj_css_sheets';

    $sql = "CREATE TABLE $table_name (
        id int NOT NULL AUTO_INCREMENT,
		name varchar(1023) NOT NULL,
        description varchar(1023)
		media_query varchar(1023),
		PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

    // create db table for css entries
	$table_name = $wpdb->prefix . 'jcs_cucj_css_entries';

	$sql = "CREATE TABLE $table_name (
		id int(255) NOT NULL AUTO_INCREMENT,
		selector varchar(1023) NOT NULL,
        description varchar(1023)
		custom_code varchar(1023),
		PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

    // create db table for js files
	$table_name = $wpdb->prefix . 'jcs_cucj_js_files';

	$sql = "CREATE TABLE $table_name (
		id int(255) NOT NULL AUTO_INCREMENT,
		name varchar(1023) NOT NULL,
        description varchar(1023)
		custom_code varchar(1023),
		PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
    /*
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // create db table for css sheets
    //$table_name = $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_sheets';
    $table_name = $wpdb->prefix . 'jcs_cucj_css_sheets';

    $sql = "CREATE TABLE $table_name (
        id int NOT NULL AUTO_INCREMENT,
        name varchar(1023) NOT NULL,
        description varchar(8191)
        media_query varchar(8191),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // create db table for css entries
    $table_name = $wpdb->prefix . 'jcs_cucj_css_entries';

    $sql = "CREATE TABLE $table_name (
        id int(255) NOT NULL AUTO_INCREMENT,
        selector varchar(8191) NOT NULL,
        description varchar(8191)
        custom_code varchar(65534),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // create db table for js files
    $table_name = $wpdb->prefix . 'jcs_cucj_js_files';

    $sql = "CREATE TABLE $table_name (
        id int(255) NOT NULL AUTO_INCREMENT,
        name varchar(1023) NOT NULL,
        description varchar(8191)
        custom_code varchar(65534),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    */
}

/**
 * This function is called, when the plugin is deactivated.
 */
function jcs_cucj_deactivate() {

}

?>
