<?php
/*
Plugin Name: Multisite Site Reports
Description: List site options by site to allow for easy migration to Gutenberg
Plugin URI: https://github.com/BellevueCollege/multisite-site-reports
Author: Taija Tevia-Clark
Version: 0.0.0-dev1
Author URI: https://www.bellevuecollege.edu
GitHub Plugin URI: BellevueCollege/multisite-site-reports
Text Domain: bcmsr
*/

/**
 * Based on the following sources:
 * Shortcode to list all admins: http://wordpress.stackexchange.com/a/55997
 * Building a settings page:     http://wordpress.stackexchange.com/a/79899
 * Sorting arrays of objects:    http://www.the-art-of-web.com/php/sortarray/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include( 'classes/Admin_Interface.php' );
include( 'classes/User_List.php' );
include( 'classes/Site_List.php' );
include( 'classes/User.php' );
include( 'classes/Site.php' );

add_action( 'wp_loaded', array ( MUBR_Admin_Interface::get_instance(), 'register' ) );

function MUBR_enqueue_admin_scripts() {
    global $pagenow; 
    if ( ( 'users.php' === $pagenow ) && ( 'multisite_users_selected_role' === $_GET['page'] ) ) {
        //checks if page is /users.php?page=multisite_users_selected_role 
        wp_enqueue_style( 'multisite_users_by_role_style', plugin_dir_url( __FILE__ ) . 'css/mubr.css', array('mayflower_dashboard'), '1.0.0' );
        wp_enqueue_script( 'multisite_users_by_role_script', plugin_dir_url( __FILE__ ) . 'js/mubr-script.js', '1.0.0' );
    }
}

add_action( 'admin_enqueue_scripts', 'MUBR_enqueue_admin_scripts');
