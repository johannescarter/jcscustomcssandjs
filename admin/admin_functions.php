<?php
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
    // drop custom database tables
	global $wpdb;
	$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_sheets');
	$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_css_entries');
    $wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'jcs_' . JCS_PLUGIN_HEX . '_js_files');
}

/**
 * Adds a settings page in the backend for admins
 */
function jcs_cucj_setup_menu() {
	add_menu_page(
		JCS_CUCJ_ADMIN_MENU_TITLE,
		JCS_CUCJ_ADMIN_MENU_LABEL,
		JCS_CUCJ_ADMIN_MENU_REQUIRED_CAPABILITY,
		JCS_CUCJ_ADMIN_MENU_PAGE_SLUG,
		'jcs_cucj_render_menu'
	);

	foreach (JCS_CUCJ_WP_OPTIONS_SECTIONS as $section) {
		add_settings_section(
			$section['id'],
			$section['title'],
			$section['callback'],
			$section['page'],
		);
	}

	foreach (JCS_CUCJ_WP_OPTIONS_SETTINGS as $setting) {
		$tmp_section = ($setting['section'] != '')?$setting['section']:'default';
		add_settings_field(
			$setting['id'],
			$setting['title'],
			$setting['callback'],
			$setting['page'],
			$tmp_section,
			array_merge(
				$setting,
				[
					'label_for' => $setting['id'],
				],
			),
		);
		register_setting( $setting['page'], $setting['id'], array(
			'type' => $setting['wp_type'],
			'default' => $setting['default'],
		) );
	}
}

/**
 * callback function for setting sections
 */
function jcs_cucj_settings_section_callback( $section ) {
	echo '<p>' . JCS_CUCJ_WP_OPTIONS_SECTIONS[ $section[ 'id' ] ][ 'description' ] . '</p>';
}

/**
 * callback function for settings
 */
function jcs_cucj_settings_callback( $setting ) {
	$setting_value = get_option( $setting['id'] );
	if ( $setting['intern_type'] == 'simple' ) {
		if ( !empty( $setting['description']) ) {
			?><p><?php
		}
		if ($setting['html_type'] == 'checkbox') {
			?>
			    <input type="<?= $setting['html_type']; ?>" id="<?= $setting['id']; ?>" name="<?= $setting['id']; ?>" value="1" <?php checked(1, $setting_value, true); ?>>
			<?php
		} else {
			?>
			    <input type="<?= $setting['html_type']; ?>" id="<?= $setting['id']; ?>" name="<?= $setting['id']; ?>" value="<?= $setting_value; ?>">
			<?php
		}
		if ( !empty( $setting['description']) ) {
			?><?= $setting['description']; ?></p><?php
		}
	}
}

/**
 * renders the settingspage for admins in the backend
 */
function jcs_cucj_render_menu() {
	?>
		<div class="wrap">
			<h1><?= JCS_CUCJ_ADMIN_MENU_TITLE; ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( JCS_CUCJ_ADMIN_MENU_PAGE_SLUG );
					do_settings_sections( JCS_CUCJ_ADMIN_MENU_PAGE_SLUG );
					submit_button();
				?>
			</form>
		</div>
	<?php
}

?>
