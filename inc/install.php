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
function wpbizplugins_eaqm_activation_function() {

    $args = array(
    
        'post_type' => 'wpbizplugins-eaqm'
        
    );

    $menu_elements = new WP_Query( $args );
    if( !$menu_elements->have_posts() ) {

        $post = array(
          'post_title'     => 'New blogpost',
          'post_type'      => 'wpbizplugins-eaqm',
          'post_status'    => 'publish'
        );  

        $default_button[1] = wp_insert_post( $post );

        // Add all of the meta values
        add_post_meta($default_button[1], 'subtitle', 'Create a new blogpost.');
        add_post_meta($default_button[1], 'url', get_admin_url() . 'post-new.php');
        add_post_meta($default_button[1], 'icon', '109');
        add_post_meta($default_button[1], 'button_color', 'green');

        $post = array(
          'post_title'     => 'New user',
          'post_type'      => 'wpbizplugins-eaqm',
          'post_status'    => 'publish'
        );  

        $default_button[2] = wp_insert_post( $post );

        // Add all of the meta values
        add_post_meta($default_button[2], 'subtitle', 'Create a new user.');
        add_post_meta($default_button[2], 'url', get_admin_url() . 'user-new.php');
        add_post_meta($default_button[2], 'icon', '110');
        add_post_meta($default_button[2], 'button_color', 'blue');

        $post = array(
          'post_title'     => 'New page',
          'post_type'      => 'wpbizplugins-eaqm',
          'post_status'    => 'publish'
        );  

        $default_button[3] = wp_insert_post( $post );

        // Add all of the meta values
        add_post_meta($default_button[3], 'subtitle', 'Add a new page to your website.');
        add_post_meta($default_button[3], 'url', get_admin_url() . 'post-new.php?post_type=page');
        add_post_meta($default_button[3], 'icon', '105');
        add_post_meta($default_button[3], 'button_color', 'orange');

        $post = array(
          'post_title'     => 'WPBizPlugins website',
          'post_type'      => 'wpbizplugins-eaqm',
          'post_status'    => 'publish'
        );  

        $default_button[4] = wp_insert_post( $post );

        // Add all of the meta values
        add_post_meta($default_button[4], 'subtitle', 'Find more business related plugins at our website.');
        add_post_meta($default_button[4], 'url', 'http://www.wpbizplugins.com');
        add_post_meta($default_button[4], 'icon', '103');
        add_post_meta($default_button[4], 'button_color', 'red');
    }

}
