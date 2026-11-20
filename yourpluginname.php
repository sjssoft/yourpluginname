<?php
/**
 * Plugin Name:       YourPluginName
 * Plugin URI:        https://github.com/sjssoft/yourpluginname
 * Description:       A clean starter boilerplate for university workshops covering CPTs, Shortcodes, Settings API, Assets, and AJAX.
 * Version:           1.0.0
 * Author:            Er. Satinder Jeet Singh
 * Author URI:        https://itwebsolutions.ca
 * License:           GPL-2.0-or-later
 * Text Domain:       yourpluginname
 */

// 1. Security Check: Prevent direct file access outside WordPress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 2. Define Plugin Constants
define( 'YPN_VERSION', '1.0.0' );
define( 'YPN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YPN_URL', plugin_dir_url( __FILE__ ) );

// 3. Include Core Classes
require_once YPN_PATH . 'includes/class-plugin-core.php';
require_once YPN_PATH . 'includes/class-plugin-admin.php';

// 4. Activation & Deactivation Hooks
function ypn_activate() {
    $core = new YPN_Core();
    $core->register_project_cpt();
    flush_rewrite_rules();

    if ( false === get_option( 'ypn_greeting_text' ) ) {
        update_option( 'ypn_greeting_text', 'Hello from my custom WordPress plugin!' );
    }
}
register_activation_hook( __FILE__, 'ypn_activate' );

function ypn_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ypn_deactivate' );

// 5. Initialize Plugin
function ypn_init() {
    new YPN_Core();
    if ( is_admin() ) {
        new YPN_Admin();
    }
}
add_action( 'plugins_loaded', 'ypn_init' );