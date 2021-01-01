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
    echo "Hello, World!";

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // create db table for css sheets
    $table_name = $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_sheets';

    $sql = "CREATE TABLE $table_name (
        id int NOT NULL AUTO_INCREMENT,
		name varchar(1024) NOT NULL,
        description varchar(8192)
		media_query varchar(8192),
		PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

    // create db table for css entries
	$table_name = $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_entries';

	$sql = "CREATE TABLE $table_name (
		id int(255) NOT NULL AUTO_INCREMENT,
		selector varchar(8192) NOT NULL,
        description varchar(8192)
		custom_code varchar(65535),
		PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

    // create db table for js files
	$table_name = $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_js_files';

	$sql = "CREATE TABLE $table_name (
		id int(255) NOT NULL AUTO_INCREMENT,
		name varchar(1024) NOT NULL,
        description varchar(8192)
		custom_code varchar(65535),
		PRIMARY KEY  (id)
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
