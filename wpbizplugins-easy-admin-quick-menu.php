<?php
/*
Plugin Name: WPBizPlugins Easy Admin Quick Menu
Plugin URI: http://www.wpbizplugins.com
Description: Add a quick menu as a dashboard widget to your WordPress admin section.
Version: 1.2.8
Author: Gabriel Nordeborn
Author URI: http://www.wpbizplugins.com
Text Domain: wpbizplugins-eaqm
*/

/*  WPBizPlugins Easy Admin Quick Menu
    Copyright 2014  Gabriel Nordeborn  (email : gabriel@wpbizplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 *
 * Register activation hook for the plugin, that adds the default menu settings if necessary.
 *
 */

register_activation_hook( __FILE__, 'wpbizplugins_eaqm_activation_function' );


/**
 *
 * START BY INCLUDING THE VARIOUS EMBEDDED PLUGINS. CURRENTLY:
 *  - ACF for custom fields
 *  - ReduxFramework for options
 *  - Intuitive Custom Post Order for easily changing the order of the menu
 *
 */

if( is_admin() ) {

    // If ACF isn't active, load ACF.
    if ( ! class_exists( 'Acf' ) && file_exists( dirname( __FILE__ ) . '/assets/acf/acf.php' ) ) {

        define( 'ACF_LITE' , true );
        require_once( dirname( __FILE__ ) . '/assets/acf/acf.php' );
        
    }
    

    // Include Redux if plugin isn't available
    if ( ! class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' ) ) {

        require_once( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' );

    }

    require_once( dirname( __FILE__ ) . '/inc/redux-config.php' );

    
    function wpbizplugins_eaqm_load_hicpo() {

        require_once( dirname( __FILE__ ) . '/assets/icpo/intuitive-custom-post-order.php' );

    }

    add_action( 'plugins_loaded', 'wpbizplugins_eaqm_load_hicpo' );

}

require_once( dirname( __FILE__ ) . '/inc/install.php' );                  // Import the installation functions
require_once( dirname( __FILE__ ) . '/inc/custom-functions.php' );         // Import our custom functions first
require_once( dirname( __FILE__ ) . '/inc/custom-posttypes.php' );         // Import our custom post types
require_once( dirname( __FILE__ ) . '/inc/dashboard-widgets.php' );        // Import our dashboard widget

// Load localization
function wpbizplugins_eaqm_init_plugin() {

    load_plugin_textdomain( 'wpbizplugins-eaqm', false, dirname( __FILE__ ) . '/lang' );

}

add_action( 'init', 'wpbizplugins_eaqm_init_plugin' );
