<?php
/**
 * Plugin Name: Multisite Site Reports
 * Description: List site options by site to allow for easy migration to Gutenberg
 * Plugin URI: https://github.com/BellevueCollege/multisite-site-reports
 * Author: Taija Tevia-Clark
 * Version: 1.1
 * Author URI: https://www.bellevuecollege.edu
 * GitHub Plugin URI: BellevueCollege/multisite-site-reports
 * Text Domain: bcmsr
 *
 * @package bcmsr
 */

/**
 * Based on the following sources:
 * Shortcode to list all admins: http://wordpress.stackexchange.com/a/55997
 * Building a settings page:     http://wordpress.stackexchange.com/a/79899
 * Sorting arrays of objects:    http://www.the-art-of-web.com/php/sortarray/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Require Classes
 */
require_once 'classes/class-bcmsr-admin-interface.php';
require_once 'classes/class-bcmsr-site-list.php';
require_once 'classes/class-bcmsr-site.php';

/**
 * Enqueue Admin Scripts
 */
function bcmsr_enqueue_admin_scripts() {
	global $pagenow;
	if ( 'admin.php' === $pagenow && ( 'bcmsr' === $_GET['page'] ) ) {
		// checks if page is /users.php?page=multisite_users_selected_role.

		wp_enqueue_style( 'multisite_users_by_role_style', plugin_dir_url( __FILE__ ) . 'css/mubr.css', array( 'mayflower_dashboard' ), '1.0.0' );
		wp_enqueue_script( 'stupidtable', plugin_dir_url( __FILE__ ) . 'js/stupidtable.min.js', array( 'jquery' ), '1.1.3', true );
		wp_add_inline_script( 'stupidtable', 'jQuery("table.bcmsr-table").stupidtable();' );

	}
}
add_action( 'admin_enqueue_scripts', 'bcmsr_enqueue_admin_scripts' );

/**
 * Initiate Classes
 */
add_action( 'wp_loaded', array( BCMSR_Admin_Interface::get_instance(), 'register' ) );
