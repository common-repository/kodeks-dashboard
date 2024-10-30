<?php
/*
Plugin Name: Kodeks Dashboard
Description: This plugin customizes WordPress for Kodeks customers.
Version: 3.0.17
Author: Thomas Johannessen & Marius Kaase
Author URI: http://kodeks.no
License: GPLv2
*/


/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

 //
// function detect_plugin_activation( $plugin, $network_activation ) {
// 	$kodeksThemeDir = get_template_directory();
// 	$kodeksDir = $kodeksThemeDir.'/inc/kodeks.php';
// 	if( file_exists( $kodeksDir ) )
// 	    {
// 	        deactivate_plugins(plugin_basename(__FILE__));
// 	    }
// }
// add_action( 'activated_plugin', 'detect_plugin_activation', 10, 2 );


/**
 * custom option and settings
 */

function kodeks_init() {
 // register a new setting for "wporg" page
 register_setting('kodeks', 'kodeks_options');
 
 // register a new section in the "wporg" page
add_settings_section(
'kodeks_instillinger',
__('Her kan du aktivere/deaktivere funksjoner nedenfor.', 'kodeks'),
'kodeks_section_callback',
'kodeks'
);


add_settings_field(
'setting_dashboard', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Kodeks Dashboard', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_dashboard',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_branding', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Branding', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_branding',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_news', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'News from Kodeks.no', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_news',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);



add_settings_field(
'setting_acl', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Editor ACL', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_acl',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

// add_settings_field(
// 'setting_customizer', // as of WP 4.6 this value is used only internally
// // use $args' label_for to populate the id inside the callback
// __( 'Custom fields in customizer', 'kodeks' ),
// 'kodeks_echo_buttons',
// 'kodeks',
// 'kodeks_instillinger',
// [
// 'label_for' => 'setting_customizer',
// 'class' => 'kodeks_row',
// 'kodeks_custom_data' => 'custom',
// ]
// );


add_settings_field(
'setting_fp_widget_disable', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable default dashboards', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_fp_widget_disable',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);



add_settings_field(
'setting_emoji', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable emoji', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_emoji',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_api', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable Rest API', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_api',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_widget', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable widget support', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_widget',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_gutenberg', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable Gutenberg bloat', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_gutenberg',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);


add_settings_field(
'setting_css', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable CSS in customizer', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_css',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);

add_settings_field(
'setting_adminbar', // as of WP 4.6 this value is used only internally
// use $args' label_for to populate the id inside the callback
__( 'Disable admin-bar', 'kodeks' ),
'kodeks_echo_buttons',
'kodeks',
'kodeks_instillinger',
[
'label_for' => 'setting_adminbar',
'class' => 'kodeks_row',
'kodeks_custom_data' => 'custom',
]
);


}

 
/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'kodeks_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function kodeks_section_callback( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'MERK: kodeks.php må fjernes, ellers vil WP kræsje.', 'kodeks' ); ?></p>
 <?php
}
 
// pill field cb
$kodeks_options = get_option('kodeks_options');
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function kodeks_echo_buttons($args) {
 // get the value of the setting we've registered with register_setting()
 //$options = get_option( 'kodeks_options' );
 global $kodeks_options;
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['kodeks_custom_data'] ); ?>"
 name="kodeks_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="1" <?php echo isset( $kodeks_options[$args['label_for']] ) ? ( selected( $kodeks_options[$args['label_for']], '1', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'True', 'kodeks' ); ?>
 </option>
 <option value="0" <?php echo isset( $kodeks_options[$args['label_for']] ) ? ( selected( $kodeks_options[$args['label_for']], '0', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'False', 'kodeks' ); ?>
 </option>
 </select>
 <?php
}
 
/**
 * top level menu
 */
function kodeks_options_page() {
 // add top level menu page
 add_options_page(
 'Kodeks Dashboard',
 'Kodeks Dashboard',
 'manage_options',
 'kodeksdash',
 'kodeksDashOptions'
 );
}
 
/**
 * register our wporg_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'kodeks_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function kodeksDashOptions() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 // if ( isset( $_GET['settings-updated'] ) ) {
 // // add settings saved message with the class of "updated"
 // add_settings_error( 'kodeks_messages', 'kodeks_message', __( 'Innstillinger lagret.', 'kodeks' ), 'updated' );
 // }
 
 // show error/update messages
 settings_errors( 'kodeks_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "wporg"
 settings_fields( 'kodeks' );
 // output setting sections and their fields
 // (sections are registered for "wporg", each field is registered to a specific section)
 do_settings_sections( 'kodeks' );
 // output save settings button
 submit_button( 'Lagre' );
 ?>
 </form>
 </div>
 <?php
}

include_once('functions.php');
