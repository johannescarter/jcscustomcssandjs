<?php
/**
 * This file conatains functions for the admin menu
 */

add_action('admin_menu', 'jcs_cucj_setup_menu');

/**
 * Adds a settings page in the backend for admins
 */
function jcs_cucj_setup_menu() {
	// add menu parent page
	add_menu_page(
		JCS_CUCJ_ADMIN_MENU_TITLE,
		JCS_CUCJ_ADMIN_MENU_LABEL,
		JCS_CUCJ_ADMIN_MENU_REQUIRED_CAPABILITY,
		JCS_CUCJ_ADMIN_MENU_PAGE_SLUG,
	);

	// add subpages
	foreach (JCS_CUCJ_ADMIN_SUBMENU_PAGES as $menu_page) {
		add_submenu_page(
			$menu_page['parent_slug'],
			$menu_page['page_title'],
			$menu_page['menu_title'],
			$menu_page['capability'],
			$menu_page['menu_slug'],
			$menu_page['function'],
		);
	}

	// add menu sections
	foreach (JCS_CUCJ_WP_OPTIONS_SECTIONS as $section) {
		add_settings_section(
			$section['id'],
			$section['title'],
			$section['callback'],
			$section['page'],
		);
	}

	//
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
 * --- Settings sections and settings callback functions ---
 */

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
 * --- Menu Page callback Functions ---
 */

/**
 * renders the settingspage for admins in the backend
 */
function jcs_cucj_menu_page_general_settigns_callback() {
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
