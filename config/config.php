<?php
/**
 * In this file all configuration is made.
 */
 // prevent direct access
 if ( ! defined( 'WPINC' ) ) {
 	die;
 }

/**
 * == JCs Plugin Meta
 */

// hex value from '00' to 'ff' unique to every of myy plugins, in order to have
// unique names
define('JCS_PLUGIN_HEX', '00');

/**
 * == Admin Menu ==
 */

// Legt den Titel des Adminmenüs fest
define( 'JCS_CUCJ_ADMIN_MENU_TITLE', 'Öffnungszeiten Einstellungsseite' );

// Legt das Label des Adminmenüs fest (wird in der Menüleiste angezeigt)
define( 'JCS_CUCJ_ADMIN_MENU_LABEL', 'Öffnungszeiten' );

// Legt die Berechtigung fest, die ein Benutzer haben muss, damit ihm das Menü angezeigt wird
// Übersicht aller Berechtigungen (capabilities) siehe https://wordpress.org/support/article/roles-and-capabilities/
define( 'JCS_CUCJ_ADMIN_MENU_REQUIRED_CAPABILITY', 'edit_plugins' );

// plugin option page slug
define( 'JCS_CUCJ_ADMIN_MENU_PAGE_SLUG', 'jcs_cucj_admin_menu' );

// plugin option section callback function
define( 'JCS_CUCJ_ADMIN_MENU_SECTION_CALLBACK', 'jcs_cucj_settings_section_callback' );

// plugin option sections
define( 'JCS_CUCJ_WP_OPTIONS_SECTIONS', [
    'jcs_cucj_settings_general_section' => [
        'id' => 'jcs_cucj_settings_general_section',
        'title' => 'Allgemeine Einstellungen',
        'callback' => JCS_CUCJ_ADMIN_MENU_SECTION_CALLBACK,
        'page' => JCS_CUCJ_ADMIN_MENU_PAGE_SLUG,
        'description' => 'Hier können allgemeine Einstellungen getroffen werden.',
    ],
]);

// plugin option settings callback function
define( 'JCS_CUCJ_ADMIN_MENU_SETTINGS_CALLBACK', 'jcs_cucj_settings_callback' );

// plugin option group
define( 'JCS_CUCJ_WP_OPTIONS_SETTINGS', [
    [
        'id' => 'jcs_cucj_example_setting',
        'title' => 'Beispiel Einstellung',
        'description' => 'Beschreibung der Einstellung',
        'callback' => JCS_CUCJ_ADMIN_MENU_SETTINGS_CALLBACK,
        'page' => JCS_CUCJ_ADMIN_MENU_PAGE_SLUG,
        'section' => 'jcs_cucj_settings_general_section',
        'intern_type' => 'simple',
        'html_type' => 'text',
        'wp_type' => 'string',
        'default' => '',
    ]
]);
?>
