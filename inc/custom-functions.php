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
 * This file contains custom functions.
 *
 */


/**
 * Function for printing a button style based on a number of colors.
 *
 * @param string $color_name The name of the color style, will be appended to the class.
 * @param string $color_main The main color in HEX.
 * @param string $color_border_and_hover Color for the border and the hover background, in HEX. Normally the pale one.
 * @param string $color_text_shadow Color for the text shadow, in HEX. Normally the darkest one.
 * @return string The style properly printed.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_print_button_style( $color_name, $color_main, $color_border_and_hover, $color_text_shadow, $should_return_string = false ) {

    $return_string = '.btn-' . $color_name . ' { background-color:' . $color_main . '; border:1px solid ' . $color_border_and_hover . '; text-shadow:0px 0px 3px ' . $color_text_shadow . '; }
    .btn-' . $color_name . ':hover { background-color:' . $color_border_and_hover . '; }';

    if( $should_return_string == true ) return $return_string; else echo $return_string;
}


/**
 * Function for printing a button.
 *
 * @param string $color The name of the color style.
 * @param string $icon The icon from Dashicons, specified as the reference number.
 * @param string $title Title of the button.
 * @param string $subtitle Subtitle/description of the button
 * @param string $url URL for the link.
 * @param bool $target_blank Open in new window or not? Returns 1 or 0.
 * @param bool $should_return_string Return or print the string? Print if false, return if true.
 * @return string The style properly printed.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_print_button( $color, $icon, $title, $subtitle, $url, $target_blank, $should_return_string = false, $container = 'a' ) {

    global $wpbizplugins_eaqm_options;

    // Remove all whitespace from the title and use it as a CSS-identifier to output the icon.
    $css_id = preg_replace('/\s+/', '', $title);
    $css_id .= uniqid();

    // Print the initial style needed
    $return_string = '<style>#' . $css_id . ':before { content: "\f' . $icon . '"; }</style>';

    // Print the actual button
    $return_string .= 
    '<' . $container . ' href="' . $url . '"';

    // Add a target blank if necessary
    if( $target_blank == 1 ) $return_string .= ' target="_blank"';

    $return_string .= ' class="wpbizplugins-eaqm-button btn-' . $color . '">' .
    '<span class="wpbizplugins-eaqm-icon" id="' . $css_id . '"><br /></span>' .
    '<p style="margin: 3px 3px 3px 3px;"><strong style="font-size: ' . $wpbizplugins_eaqm_options['font_size'] . 'pt;">' . $title . '</strong></p>'. $subtitle . '' .
    '</'. $container . '>';

    unset( $wpbizplugins_eaqm_options );

    if( $should_return_string == true ) return $return_string; else echo $return_string;

}


/**
 * Function to print an icon.
 *
 * @param string $icon The reference number for the icon.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_return_icon( $icon ) {

    $css_id = 'wpbizplugins-eaqm-icon-' . $icon;

    // Print the needed style
    $return_string = 
        '<style>#' . $css_id . ':after { content: "\f' . $icon . '"; }</style>' .
        '<span class="wpbizplugins-eaqm-iconmap" id="' . $css_id . '"><br /></span>';

    return $return_string;

}

function wpbizplugins_eaqm_return_icon_array() {

    // Some icons are unecessary to use. Skip these.
    $icons_to_skip = array(
        '114',
        '124',
        '131',
        '137',
        '144',
        '146',
        '149',
        '150',
        '151',
        '152',
        '162',
        '170',
        '202',
        '241',
        '300',
        '447',
        '458',
        '461',
        '474'
    );

    $return_array = array();

    for( $x = 100; $x <= 474; $x++ ) {
        if( ( $x >= 185 ) AND ( $x < 200 ) ) continue;
        if( ( $x >= 242 ) AND ( $x < 300 ) ) continue;
        if( ( $x >= 350 ) AND ( $x < 447 ) ) continue;
        if( in_array( $x, $icons_to_skip ) ) continue;
        $return_array[$x] = wpbizplugins_eaqm_return_icon( $x );
        
    }

    return $return_array;

}

function wpbizplugins_eaqm_return_button_array() {

    $return_array = array();

    $return_array['blue'] = wpbizplugins_eaqm_print_button( 'blue', '103', 'Title', 'Subtitle goes here.', '', true, 'div style="width:80%;"' );
    $return_array['green'] = wpbizplugins_eaqm_print_button( 'green', '103', 'Title', 'Subtitle goes here.', '', true, 'div style="width:80%;"' );
    $return_array['orange'] = wpbizplugins_eaqm_print_button( 'orange', '103', 'Title', 'Subtitle goes here.', '', true, 'div style="width:80%;"' );
    $return_array['red'] = wpbizplugins_eaqm_print_button( 'red', '103', 'Title', 'Subtitle goes here.', '', true, 'div style="width:80%;"' );

    return $return_array;
}
/**
 * Simple function to print the needed style for the icons.
 *
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_return_icon_styles () {

    $return_string = '<style>.wpbizplugins-eaqm-iconmap:after { font: 400 30px/1 dashicons; content: "\f100"; margin:10px 10px 10px 10px;} .wpbizplugins-eaqm-iconmap:hover { color: #2EA2CC; }</style>';

    return $return_string;

}


/**
 * Simple function for checking whether or not we're on a certain post type.
 *
 * @return Returns the current post type.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_return_post_type() {

    global $post, $typenow, $current_screen;

    //we have a post so we can just get the post type from that
    if ( $post && $post->post_type )
        return $post->post_type;

    //check the global $typenow - set in admin.php
    elseif( $typenow )
        return $typenow;

    //check the global $current_screen object - set in sceen.php
    elseif( $current_screen && $current_screen->post_type )
        return $current_screen->post_type;

    //lastly check the post_type querystring
    elseif( isset( $_REQUEST['post_type'] ) )
        return sanitize_key( $_REQUEST['post_type'] );

    elseif( ( isset( $_REQUEST['post'] ) ) && ( get_post_type( $_REQUEST['post'] ) ) )
            return get_post_type($_REQUEST['post']);
    //we do not know the post type!
    return null;

}


/**
 * Simple function for returning the jQuery needed for the custom icon selector.
 *
 * @return Returns the needed jQuery.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_return_jquery_for_icon_select() {

    ob_start();
    ?>
    <script>
    jQuery( document ).ready(function(){
        // Set the jQuery for the icon field
        jQuery('div#acf-icon.field ul.acf-radio-list li [data-checked=""checked""]').parent().parent().addClass( "wpbizplugins-eaqm-element-selected" );
        jQuery('div#acf-icon.field ul.acf-radio-list li label input').click( function(){
            jQuery('div#acf-icon.field ul.acf-radio-list li').removeClass( "wpbizplugins-eaqm-element-selected" );
            jQuery(this).parent().parent().addClass( "wpbizplugins-eaqm-element-selected" );
        });
    });
    </script>
    <?php

    $return_string = ob_get_contents();
    ob_end_clean();

    return $return_string;

}

/**
 * Return array of capabilities for use with restricting access to editing the plugin contents.
 *
 * @return array Returns an array of all available capabilities.
 * @since 1.2
 *
 */

function wpbizplugins_eaqm_return_capabilities_array() {

    global $wp_roles;

    if ( ! isset( $wp_roles ) ) {
        $wp_roles = new WP_Roles();
    }                

    $capabilities_array = array();

    foreach( $wp_roles->roles[ 'administrator' ][ 'capabilities' ] as $capability => $status ) {
        $capabilities_array[ $capability ] = $capability;
    }

    return $capabilities_array;

}