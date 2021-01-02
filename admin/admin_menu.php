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
function jcs_cucj_echo_button( $text, $name, $onclick = '', $href = '', $disabled = false, $submit_button = false, $css_classes = '' ) {
    if( $css_classes == '' ) {
        $css_classes = 'button button-primary';
    }
    ?>
        <button class="<?= $css_classes; ?>" type="<?= ($submit_button)?'submit':'button';?>" name="<?= $name; ?>"<?= ($onclick != '')?' onclick="'.$onclick.'"':''; ?><?= ($disabled)?' disabled':''; ?>><?= $text; ?></button>
    <?php
}

/**
 * == Menu functions ==
 */

add_action('admin_menu', 'jcs_cucj_setup_menu');

add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');

function codemirror_enqueue_scripts($hook) {
    $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
    wp_localize_script('jquery', 'cm_settings', $cm_settings);

    wp_enqueue_script('wp-theme-plugin-editor');
    wp_enqueue_style('wp-codemirror');
}

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
    /**
     * jcs_cucj_echo_button( 'files list', 'button', "jcs_cucj_menu_get_view('css_files_list_files', 'peter');" );
     * jcs_cucj_echo_button( 'new file', 'button', "jcs_cucj_menu_get_view('css_files_new_file', 'peter');" );
     * jcs_cucj_echo_button( 'edit file', 'button', "jcs_cucj_menu_get_view('css_files_edit_file', 'peter');" );
     * jcs_cucj_echo_button( 'entries list', 'button', "jcs_cucj_menu_get_view('css_files_list_entries', 'peter');" );
     * jcs_cucj_echo_button( 'new entry', 'button', "jcs_cucj_menu_get_view('css_files_new_entry', 'peter');" );
     * jcs_cucj_echo_button( 'edit entry', 'button', "jcs_cucj_menu_get_view('css_files_edit_entry', 'peter');" );
     */
    ?>
        <div id="jcs_cucj_admin_menu_view_sockel">
            <?php cs_cucj_css_files_list_files_render_view(); ?>
        </div>
    <?php
}

/**
 * --- functions to render css files menu ---
 */

 /**
  * adds a js function to select a view in css files menu
  */
add_action( 'admin_footer', 'jcs_cucj_admin_menu_css_files_render_view_js' );
function jcs_cucj_admin_menu_css_files_render_view_js() { ?>
	<script type="text/javascript" >
        var id = null;

        function jcs_cucj_create_css_file_and_close() {
            var formData = jQuery('form').serializeArray();

            var data = {
                'action': 'jcs_cucj_create_css_file',
                'name': formData[0].value,
                'description': formData[1].value,
                'media_query': formData[2].value
            };

            jQuery.post(ajaxurl, data, function(response) {
                jcs_cucj_menu_get_view('css_files_list_files');
            });
        }

        function jcs_cucj_update_css_file(localId) {
            var formData = jQuery('form').serializeArray();

            var data = {
                'action': 'jcs_cucj_update_css_file',
                'name': formData[0].value,
                'description': formData[1].value,
                'media_query': formData[2].value,
                'id' : localId
            };

            jQuery.post(ajaxurl, data, null);
        }

        function jcs_cucj_update_css_file_and_close(localId) {
            var formData = jQuery('form').serializeArray();

            var data = {
                'action': 'jcs_cucj_update_css_file',
                'name': formData[0].value,
                'description': formData[1].value,
                'media_query': formData[2].value,
                'id' : localId
            };

            jQuery.post(ajaxurl, data, function(response) {
				jcs_cucj_menu_get_view('css_files_list_files');
			});
        }

        function jcs_cucj_delete_css_file(localId) {
            var data = {
				'action': 'jcs_cucj_delete_css_file',
                'id': localId
			};

            id = localId;

			jQuery.post(ajaxurl, data, null);
        }

		function jcs_cucj_menu_render(content) {
			jQuery("#jcs_cucj_admin_menu_view_sockel").html(content);
		}

		function jcs_cucj_menu_get_view(viewName, localId = null){
			var data = {
				'action': 'cs_cucj_admin_menu_render_view',
                'viewName': viewName,
				'id': localId
			};

            if(localId != null) {
                id = localId;
            }

			jQuery.post(ajaxurl, data, function(response) {
				jcs_cucj_menu_render(response);
                wp.codeEditor.initialize(jQuery('#fancy-textarea'), cm_settings);
			});
		}
	</script> <?php
}

/**
 * == axaj response functions ==
 */

/**
 * ajax response function, calls the functions to render a certain view
 */
add_action( 'wp_ajax_cs_cucj_admin_menu_render_view', 'cs_cucj_admin_menu_render_view' );
function cs_cucj_admin_menu_render_view() {
	global $wpdb;

    if ( !current_user_can( 'manage_options' ) ) {
        echo "Access denied!";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    switch ($_POST['viewName']) {
        case 'css_files_list_files':
            cs_cucj_css_files_list_files_render_view();
            break;
        case 'css_files_new_file':
            cs_cucj_css_files_new_file_render_view();
            break;
        case 'css_files_edit_file':
            cs_cucj_css_files_edit_file_render_view( $_POST['id'] );
            break;
        case 'css_files_list_entries':
            cs_cucj_css_files_list_entries_render_view( $_POST['id'] );
            break;
        case 'css_files_new_entry':
            cs_cucj_css_files_new_entry_render_view( $_POST['id'] );
            break;
        case 'css_files_edit_entry':
            cs_cucj_css_files_edit_entry_render_view( $_POST['id'] );
            break;
        default:
            // TODO echo error msg
            break;
    }

	wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * ajax function to delete css files from database
 */
add_action( 'wp_ajax_jcs_cucj_delete_css_file', 'jcs_cucj_delete_css_file' );
function jcs_cucj_delete_css_file() {
    global $wpdb;

    if ( !current_user_can( 'manage_options' ) ) {
        echo "Access denied!";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    if( !empty( $_POST[ 'id' ] ) ) {
        $query = "DELETE FROM " . $wpdb->prefix . "jcs_cucj_css_sheets WHERE id LIKE " . esc_sql( $_POST[ 'id' ] ) . ";";
        $wpdb->get_results( $query );
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * ajax function to insert a new css files into the database
 */
add_action( 'wp_ajax_jcs_cucj_create_css_file', 'jcs_cucj_create_css_file' );
function jcs_cucj_create_css_file() {
    global $wpdb;

    if ( !current_user_can( 'manage_options' ) ) {
        echo "Access denied!";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    if( !empty( $_POST[ 'name' ] ) ) {
        $query = "INSERT INTO " . $wpdb->prefix . "jcs_cucj_css_sheets
                  (name, description, media_query)
                  VALUES
                  (
                      '" . esc_sql( $_POST[ 'name' ] ) . "',
                      '" . esc_sql( $_POST[ 'description' ] ) . "',
                      '" . esc_sql( $_POST[ 'media_query' ] ) . "'
                  );";
        $wpdb->get_results( $query );
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * ajax function to update css file informations in the database
 */
add_action( 'wp_ajax_jcs_cucj_update_css_file', 'jcs_cucj_update_css_file' );
function jcs_cucj_update_css_file() {
    global $wpdb;

    if ( !current_user_can( 'manage_options' ) ) {
        echo "Access denied!";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    if( !empty( $_POST[ 'id' ] ) ) {
        $update = '';
        $is_first = true;
        if( $_POST[ 'name' ] ) {
            $update .= "name = '" . esc_sql( $_POST[ 'name' ] ) . "'";

            if( $is_first ) {
                $is_first = false;
            }
        }
        if( $_POST[ 'description' ] ) {
            if( !$is_first ) {
                $update .= ', ';
            }

            $update .= "description = '" . esc_sql( $_POST[ 'description' ] ) . "'";

            if( $is_first ) {
                $is_first = false;
            }
        }
        if( $_POST[ 'media_query' ] ) {
            if( !$is_first ) {
                $update .= ', ';
            }

            $update .= "media_query = '" . esc_sql( $_POST[ 'media_query' ] ) . "'";

            if( $is_first ) {
                $is_first = false;
            }
        }
        $query = "UPDATE " . $wpdb->prefix . "jcs_cucj_css_sheets SET " . $update . " WHERE id LIKE " . $_POST[ 'id' ] . ";";
        $wpdb->get_results( $query );
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * == admin menu render functions ==
 */

/**
 * Renders a css_files_list_files view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_list_files_render_view() {
    ?>
        <div class="wrap">
            <h1 class="jcs_cucj_view-title">List of all CSS files</h1>
            <div class="jcs_cucj_list">
                <?php
                    global $wpdb;

                    $query = "SELECT * FROM " . $wpdb->prefix . "jcs_cucj_css_sheets ORDER BY name";
                    $result = $wpdb->get_results( $query );

                    foreach ( $result as $row ) {
                        ?>
                            <div class="jcs_cucj_list-item row">
                                <div class="col-8">
                                    <?php if( !empty( $row->name ) ) { ?>
                                        <span class="jcs_cucj_list-item-name"><?= esc_html( $row->name ); ?></span>
                                    <?php } ?>
                                    <?php if( !empty( $row->media_query ) ) { ?>
                                        <span class="jcs_cucj_list-item-media_query">@media <?= esc_html( $row->media_query ); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="col-4 justify-content-flex-end">
                                    <?php
                                        jcs_cucj_echo_button(
                                            'edit entries',
                                            'button',
                                            "jcs_cucj_menu_get_view('css_files_list_entries', ".$row->id.");",
                                            '',
                                            false,
                                            false,
                                            'jcs_cucj_button'
                                        );
                                        jcs_cucj_echo_button(
                                            'edit file',
                                            'button',
                                            "jcs_cucj_menu_get_view('css_files_edit_file', ".$row->id.");",
                                            '',
                                            false,
                                            false,
                                            'jcs_cucj_button'
                                        );
                                        jcs_cucj_echo_button(
                                            'delete',
                                            'button',
                                            "jcs_cucj_delete_css_file(".$row->id.");jcs_cucj_menu_get_view('css_files_edit_file', ".$row->id.");",
                                            '',
                                            false,
                                            false,
                                            'jcs_cucj_button'
                                        );
                                    ?>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <div class="jcs_cucj_view-footer">
                <div class="row">
                    <div class="col-12">
                        <?php
                            jcs_cucj_echo_button(
                                'Create new stylesheet',
                                'new-css-file',
                                "jcs_cucj_menu_get_view('css_files_new_file');"
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

/**
 * Renders a css_files_new_file view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_new_file_render_view() {
    ?>
        <div class="wrap">
            <h1 class="jcs_cucj_view-title">Create new CSS file</h1>
            <form class="jcs_cucj_form">
                <table>
                    <tbody>
                        <tr>
                            <td class="label">
                                <label for="name">name</label>
                            </td>
                            <td>
                                <input type="text" id="name" name="name">
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="description">description</label>
                            </td>
                            <td>
                                <textarea id="description" name="description" rows="5" cols="60"></textarea>
                            </td class="label">
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="media_query">media_query</label>
                            </td>
                            <td>
                                <input type="text" id="media_query" name="media_query">
                            </td>
                        </tr>
                        <tr class="vertical-space"></tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <?php jcs_cucj_echo_button(
                                    'Save and close',
                                    'submit',
                                    "jcs_cucj_create_css_file_and_close();"
                                );
                                jcs_cucj_echo_button(
                                    'Cancel',
                                    'cancel',
                                    "jcs_cucj_menu_get_view('css_files_list_files');"
                                ); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    <?php
}

/**
 * Renders a css_files_edit_file view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_edit_file_render_view( $id ) {
    global $wpdb;

    $query = "SELECT * FROM " . $wpdb->prefix . "jcs_cucj_css_sheets WHERE id LIKE " . $id . " LIMIT 1";
    $result = $wpdb->get_results( $query );

    $row = $result[0];

    ?>
        <div class="wrap">
            <h1 class="jcs_cucj_view-title">Edit CSS file</h1>
            <form class="jcs_cucj_form">
                <table>
                    <tbody>
                        <tr>
                            <td class="label">
                                <label for="name">name</label>
                            </td>
                            <td>
                                <input type="text" id="name" name="name" value="<?= esc_attr( $row->name ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="description">description</label>
                            </td>
                            <td>
                                <textarea id="description" name="description" rows="5" cols="60"><?= esc_textarea( $row->description ); ?></textarea>
                            </td class="label">
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="media_query">media_query</label>
                            </td>
                            <td>
                                <input type="text" id="media_query" name="media_query" value="<?= esc_attr( $row->media_query ); ?>">
                            </td>
                        </tr>
                        <tr class="vertical-space"></tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <?php
                                jcs_cucj_echo_button(
                                    'Save',
                                    'save',
                                    "jcs_cucj_update_css_file(".$id.");"
                                );
                                jcs_cucj_echo_button(
                                    'Save and close',
                                    'save-and-close',
                                    "jcs_cucj_update_css_file_and_close(".$id.");"
                                );
                                jcs_cucj_echo_button(
                                    'Close',
                                    'close',
                                    "jcs_cucj_menu_get_view('css_files_list_files');"
                                ); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    <?php
}

/**
 * Renders a css_files_list_entries view
 * @param viewData  mixed Array containing any view parameter
 */
function cs_cucj_css_files_list_entries_render_view( $id ) {
    ?>
        <div class="wrap">
            <h1 class="jcs_cucj_view-title">List of all Entries of the CSS file with id <?= $id; ?></h1>
            <div class="jcs_cucj_list">
                <?php
                    global $wpdb;

                    $query = "SELECT * FROM " . $wpdb->prefix . "jcs_cucj_css_entries ORDER BY name WHERE stylesheet_id LIKE " . esc_sql( $id ) . ";";
                    $result = $wpdb->get_results( $query );

                    foreach ( $result as $row ) {
                        ?>
                            <div class="jcs_cucj_list-item row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-8">
                                            <?php if( !empty( $row->comment ) ) { ?>
                                                <span class="jcs_cucj_list-item-comment"><?= esc_html( $row->comment ); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="col-4 justify-content-flex-end">
                                            <?php
                                                jcs_cucj_echo_button(
                                                    'edit',
                                                    'button',
                                                    "jcs_cucj_menu_get_view('css_files_edit_entry', ".$row->id.");",
                                                    '',
                                                    false,
                                                    false,
                                                    'jcs_cucj_button'
                                                );
                                                jcs_cucj_echo_button(
                                                    'delete',
                                                    'button',
                                                    "",
                                                    '',
                                                    false,
                                                    false,
                                                    'jcs_cucj_button'
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="jcs_cucj_list-item-code">
                                                <?= esc_html( $row->selector ); ?> {
                                                    <?= esc_html( $row->custom_code ); ?>
                                                }
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <div class="jcs_cucj_view-footer">
                <div class="row">
                    <div class="col-12">
                        <?php
                            jcs_cucj_echo_button(
                                'Create new entry',
                                'new-css-file',
                                "jcs_cucj_menu_get_view('css_files_new_entry');"
                            );
                            jcs_cucj_echo_button(
                                'Back to stylesheet list',
                                'new-css-file',
                                "jcs_cucj_menu_get_view('css_files_list_files');"
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

/**
 * Renders a css_files_new_entry view and returns the view as a string.
 * @param viewData  mixed Array containing any view parameter
 * @return string
 */
function cs_cucj_css_files_new_entry_render_view( $fileId ) {
    ?>
    <div class="wrap">
        <h1 class="jcs_cucj_view-title">Create new CSS entry</h1>
        <form class="jcs_cucj_form">
            <table>
                <tbody>
                    <tr>
                        <td class="label">
                            <label for="selector">selector</label>
                        </td>
                        <td>
                            <input type="text" id="selector" name="selector">
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label for="comment">comment</label>
                        </td>
                        <td>
                            <textarea id="comment" name="comment" rows="5" cols="60"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label for="custom_code">custom code</label>
                        </td>
                        <td>
                            <textarea id="fancy-textarea" name="custom_code"></textarea>
                        </td>
                    </tr>
                    <tr class="vertical-space"></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <?php jcs_cucj_echo_button(
                                'Save and close',
                                'submit',
                                "jcs_cucj_create_css_file_and_close();"
                            );
                            jcs_cucj_echo_button(
                                'Cancel',
                                'cancel',
                                "jcs_cucj_menu_get_view('css_files_list_files');"
                            ); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
    <?php
}

/**
 * Renders a css_files_edit_entry view and returns the view as a string.
 * @param viewData  mixed Array containing any view parameter
 * @return string
 */
function cs_cucj_css_files_edit_entry_render_view( $entryId ) {
    ?>
        <div class="wrap">
            <h1>Edit CSS entry</h1>
        </div>
    <?php
}
