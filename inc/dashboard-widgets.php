<?php
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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  021101301  USA
*/

/**
 *
 * This file contains the dashboard widget used.
 *
 */

function wpbizplugins_eaqm_register_dashboard_widget() {

    global $wpbizplugins_eaqm_options;

    add_meta_box(
     'wpbizplugins-eaqm-widget'
    ,$wpbizplugins_eaqm_options['menu_title']
    ,'wpbizplugins_eaqm_widget_function'
    ,'dashboard' // Take a look at the output of `get_current_screen()` on your dashboard page
    ,'normal' // Valid: 'side', 'advanced'
    ,'high' // Valid: 'default', 'high', 'low'
    );

    unset($wpbizplugins_eaqm_options);

}

if( is_admin() ) add_action('wp_dashboard_setup', 'wpbizplugins_eaqm_register_dashboard_widget');

function wpbizplugins_eaqm_widget_function() {

    global $wpbizplugins_eaqm_options;

    echo apply_filters( 'the_content', $wpbizplugins_eaqm_options['menu_welcometext'] );
    echo '<hr />';
    
    $args = array(
    
        'post_type'         => 'wpbizplugins-eaqm',
        'posts_per_page'    => -1
        
    );

    $menu_elements = new WP_Query( $args );
    if( $menu_elements->have_posts() ) {

        while( $menu_elements->have_posts() ) {
            $menu_elements->the_post();
            
            $menu_element_id = get_the_ID();
            
            // Get the various needed fields
            $title = get_the_title($menu_element_id);
            $subtitle = get_post_meta( $menu_element_id, 'subtitle', true );
            $url = get_post_meta( $menu_element_id, 'url', true );
            $target_blank = get_post_meta( $menu_element_id, 'target_blank', true );
            $icon = get_post_meta( $menu_element_id, 'icon', true );
            $button_color = get_post_meta( $menu_element_id, 'button_color', true );

            // Print the button
            wpbizplugins_eaqm_print_button( $button_color, $icon, $title, $subtitle, $url, $target_blank );
        }

    }

    wp_reset_postdata();

    unset($wpbizplugins_eaqm_options);
}
