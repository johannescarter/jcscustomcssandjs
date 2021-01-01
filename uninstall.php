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
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_cucj_css_sheets');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_cucj_css_entries');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_cucj_js_files');
?>
