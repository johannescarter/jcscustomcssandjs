<?php
/**
 * This file conatains functions for the admin menu
 */

/**
 * == Helper functions ==
 */

/**
 * echos a button with the parameters.
 * @param text  text to be displayed on the button
 * @param name  name of the button (identifier to js)
 * @param onclick   js that is triggert if the button gets clicked
 * @param href  link for the button
 * @param disabled  set true to display the button as disabled in the frontend
 */
function jcs_cucj_echo_button( $text, $name, $onclick = '', $href = '', $disabled = false ) {
    ?>
        <button class="button button-primary" type="button" name="<?= $name; ?>"<?= ($onclick != '')?' onclick="'.$onclick.'"':''; ?><?= ($disabled)?' disabled':''; ?>><?= $text; ?></button>
    <?php
}


/**
 * == Menu functions ==
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
			<?php jcs_cucj_echo_button( 'files list', 'button', "jcs_cucj_menu_get_view('css_files_list_files', 'peter');" ); ?>
            <?php jcs_cucj_echo_button( 'new file', 'button', "jcs_cucj_menu_get_view('css_files_new_file', 'peter');" ); ?>
            <?php jcs_cucj_echo_button( 'edit file', 'button', "jcs_cucj_menu_get_view('css_files_edit_file', 'peter');" ); ?>
            <?php jcs_cucj_echo_button( 'entries list', 'button', "jcs_cucj_menu_get_view('css_files_list_entries', 'peter');" ); ?>
            <?php jcs_cucj_echo_button( 'new entry', 'button', "jcs_cucj_menu_get_view('css_files_new_entry', 'peter');" ); ?>
            <?php jcs_cucj_echo_button( 'edit entry', 'button', "jcs_cucj_menu_get_view('css_files_edit_entry', 'peter');" ); ?>
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
		function jcs_cucj_menu_render(content) {
			jQuery("#jcs_cucj_admin_menu_view_sockel").html(content);
		}

		function jcs_cucj_menu_get_view(viewName, viewData = null){
			var data = {
				'action': 'cs_cucj_admin_menu_render_view',
                'viewName': viewName,
				'view_data': viewData
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				jcs_cucj_menu_render(response);
			});
		}
	</script> <?php
}

/**
 * ajax response function, calls the functions to render a certain view
 */
add_action( 'wp_ajax_cs_cucj_admin_menu_render_view', 'cs_cucj_admin_menu_render_view' );
function cs_cucj_admin_menu_render_view() {
	global $wpdb;

    switch ($_POST['viewName']) {
        case 'css_files_list_files':
            cs_cucj_css_files_list_files_render_view($_POST['viewData']);
            break;
        case 'css_files_new_file':
            cs_cucj_css_files_new_file_render_view($_POST['viewData']);
            break;
        case 'css_files_edit_file':
            cs_cucj_css_files_edit_file_render_view($_POST['viewData']);
            break;
        case 'css_files_list_entries':
            cs_cucj_css_files_list_entries_render_view($_POST['viewData']);
            break;
        case 'css_files_new_entry':
            cs_cucj_css_files_new_entry_render_view($_POST['viewData']);
            break;
        case 'css_files_edit_entry':
            cs_cucj_css_files_edit_entry_render_view($_POST['viewData']);
            break;
        default:
            // TODO echo error msg
            break;
    }

	wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * Renders a css_files_list_files view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_list_files_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1 class="jcs_cucj_view-title">List of all CSS files</h1>
            <div class="jcs_cucj_list">
                <div class="jcs_cucj_list-item">

                </div>
            </div>
        </div>
    <?php
}

/**
 * Renders a css_files_new_file view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_new_file_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1>Create new CSS file</h1>
        </div>
    <?php
}

/**
 * Renders a css_files_edit_file view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_edit_file_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1>Edit CSS file</h1>
        </div>
    <?php
}

/**
 * Renders a css_files_list_entries view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_list_entries_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1>List of all Entries of the CSS file</h1>
        </div>
    <?php
}

/**
 * Renders a css_files_new_entry view and returns the view as a string.
 * @param viewData  mixed Array containing any view parameter
 * @return string
 */
function cs_cucj_css_files_new_entry_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1>Create CSS entry</h1>
        </div>
    <?php
}

/**
 * Renders a css_files_edit_entry view and returns the view as a string.
 * @param viewData  mixed Array containing any view parameter
 * @return string
 */
function cs_cucj_css_files_edit_entry_render_view( $viewData ) {
    ?>
        <div class="wrap">
            <h1>Edit CSS entry</h1>
        </div>
    <?php
}
