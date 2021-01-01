<?php
/**
 * this file is executed on plugin uninstallation
 */
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// drop custom database tables
global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_sheets');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_entries');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_js_files');
?>
