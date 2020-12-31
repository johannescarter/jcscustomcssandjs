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
 * callback function for the settingspage for admins in the backend
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

/**
 * callback function for the css files list page
 */
function jcs_cucj_menu_page_css_files_callback( $submenu_page ) {
 	?>
		<div id="jcs_cucj_admin_menu_view_sockel">
			<p>Hello, World!</p>
			<button type="button" name="button" onclick="jcs_cucj_admin_menu_css_files_render_view_js('css_files_list_files', 'peter');">Klick mich!</button>
		</div>
	<?php
}

/**
 * --- functions to render css files menu ---
 */

 /**
  * adds a js function to select a view in css files menu
  */
add_action( 'admin_footer', 'jcs_cucj_admin_menu_css_files_render_view_js' ); // Write our JS below here
function jcs_cucj_admin_menu_css_files_render_view_js() { ?>
	<script type="text/javascript" >
		function jcs_cucj_menu_render(string content) {
			$("#jcs_cucj_admin_menu_view_sockel").html(content);
		}

		function jcs_cucj_menu_get_view(string viewName, viewData = null){

			string viewActionName = '';

			switch (viewName) {
				case 'css_files_list_files':
					viewActionName = 'cs_cucj_render_view_css_files_list_files';
					break;
				case 'css_files_new_file':
					viewActionName = 'cs_cucj_render_view_css_files_new_file';
					break;
				case 'css_files_edit_file':
					viewActionName = 'cs_cucj_render_view_css_files_edit_file';
					break;
				case 'css_files_list_entries':
					viewActionName = 'cs_cucj_render_view_css_files_list_entries';
					break;
				case 'css_files_new_entry':
					viewActionName = 'cs_cucj_render_view_css_files_new_entry';
					break;
				case 'css_files_edit_entry':
					viewActionName = 'cs_cucj_render_view_css_files_edit_entry';
					break;
				default:
					// TODO default value
					viewActionName = '';
			}

			var data = {
				'action': viewActionName,
				'view_data': viewData
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				alert('Got this from the server: ' + response);
			});
		});
	</script> <?php
}

/**
 * render view css_files_list_files
 */
add_action( 'wp_ajax_cs_cucj_render_view_css_files_list_files', 'cs_cucj_render_view_css_files_list_files' );
function cs_cucj_render_view_css_files_list_files() {
	global $wpdb; // this is how you get access to the database

	$view_data = serialize( $_POST['view_data'] );

    echo $view_data;

	wp_die(); // this is required to terminate immediately and return a proper response
}
