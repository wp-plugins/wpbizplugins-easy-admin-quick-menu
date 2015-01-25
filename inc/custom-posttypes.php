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
 * This file contains custom post types.
 *
 */

/**
 * Define the custom post type that holds the menu entries.
 *
 * @since 1.0
 *
 */

function wpbizpluins_eaqm_custom_post_menu() { 
    
    // Abort if we're not in an admin page
    if( !is_admin() ) return false;

    global $wpbizplugins_eaqm_options;

    if( current_user_can( $wpbizplugins_eaqm_options['menu_capability'] ) ) $show_in_menu = true; else $show_in_menu = false;

    $labels = array(
        'name'               => _x( 'Menu buttons', 'post type general name' ),
        'singular_name'      => _x( 'Menu button', 'post type singular name' ),
        'add_new'            => __( 'Add new menu button', 'book' ),
        'add_new_item'       => __( 'Add new menu button'),
        'edit_item'          => __( 'Edit menu button' ),
        'new_item'           => __( 'New menu button' ),
        'all_items'          => __( 'All menu buttons' ),
        'view_item'          => __( 'See menu buttons' ),
        'search_items'       => __( 'Search menu' ),
        'not_found'          => __( 'No menu button found' ),
        'not_found_in_trash' => __( 'No menu buttons found in the trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Easy Admin Quick Menu'
    );
    
    $args = array(
        'labels'                => $labels,
        'description'           => 'The Easy Admin Quick Menu.',
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => $show_in_menu,
        'show_in_admin_bar'     => false,
        'publicly_queryable'    => false,
        'menu_icon'             => plugins_url( '../assets/img/wpbizplugins-eaqm-menuicon.png', __FILE__ ),
        'menu_position'         => 12,
        'supports'              => array( 'title' ),
        'has_archive'           => false
    );
    register_post_type( 'wpbizplugins-eaqm', $args );   
}

add_action( 'init', 'wpbizpluins_eaqm_custom_post_menu' );

/**
 * Function that loads our menu styles.
 *
 * @param bool $should_return_string Returns the string if true, otherwise echoe's it.
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_print_styles( $should_return_string = false ) {

    global $wpbizplugins_eaqm_options;

    // Output our basic styling for the menu icons
    $return_string = '<style>
    .wpbizplugins-eaqm-icon:before { 

        font: 400 ' . $wpbizplugins_eaqm_options['icon_size'] . 'px/1 dashicons; 
        content: "\f100"; 

    }
    ';

    // Output our basic styling for the buttons
    $return_string .= '

    .wpbizplugins-eaqm-button {
        margin: ' . $wpbizplugins_eaqm_options['button_verticalspacing'] . 'px 0px 0px 0px;
        padding: 10px 10px 10px 10px;
        
        background-color:#4ea5fc;
        -webkit-border-top-left-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -moz-border-radius-topleft:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        border-top-left-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -webkit-border-top-right-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -moz-border-radius-topright:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        border-top-right-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -webkit-border-bottom-right-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -moz-border-radius-bottomright:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        border-bottom-right-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -webkit-border-bottom-left-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        -moz-border-radius-bottomleft:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        border-bottom-left-radius:' . $wpbizplugins_eaqm_options['button_borderradius'] . 'px;
        text-indent:0px;
        border:1px solid #469df5;
        display:inline-block;
        color:#FFF;        
        
        width:90%;
        text-decoration:none;
        text-align:center;
        text-shadow:2px 2px 2px #528ecc;
    }

    }.wpbizplugins-eaqm-button:active {
        position:relative;
        top:1px;
    }

    .wpbizplugins-eaqm-button:hover { 
        background-color:#6aaaeb; 
        color: #F6F6F6;
    }

    .wpbizplugins-eaqm-button:active { 
        color: #F6F6F6;
    }

    .wpbizplugins-eaqm-button:focus { 
        color: #F6F6F6;
    }

    .wpbizplugins-eaqm-element-selected {
        background-color: #408099;
        -webkit-border-top-left-radius: 5px;
        -moz-border-radius-topleft: 5px;
        border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topright: 5px;
        border-top-right-radius: 5px;
        -webkit-border-bottom-right-radius: 5px;
        -moz-border-radius-bottomright: 5px;
        border-bottom-right-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-bottom-left-radius: 5px;
        color: orange;
    }

    .wpbizplugins-eaqm-icon-selected {

        color: orange;

    }

    .wpbizplugins-eaqm-button-selected {
        background-color: #BF564B;
        
        -webkit-border-top-left-radius: 5px;
        -moz-border-radius-topleft: 5px;
        border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topright: 5px;
        border-top-right-radius: 5px;
        -webkit-border-bottom-right-radius: 5px;
        -moz-border-radius-bottomright: 5px;
        border-bottom-right-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-bottom-left-radius: 5px;
    }

    ';

    // Output our specific styling for the buttons
    $return_string .= wpbizplugins_eaqm_print_button_style( 'blue', '#2EA0CC', '#408099', '#0F6485', true );
    $return_string .= wpbizplugins_eaqm_print_button_style( 'green', '#26B637', '#42A84F', '#0E921E', true );
    $return_string .= wpbizplugins_eaqm_print_button_style( 'red', '#FF4531', '#BF564B', '#A61E10', true );
    $return_string .= wpbizplugins_eaqm_print_button_style( 'orange', '#FF9C31', '#BF884B', '#A65E10', true );

    // Output the custom CSS configurable in the admin panel
    $return_string .=  $wpbizplugins_eaqm_options['custom_css'];

    // Close the styling div
    $return_string .= '</style>';

    unset( $wpbizplugins_eaqm_options );

    if( $should_return_string == true ) return $return_string; else echo $return_string;
}

/**
 * Update the messages for the custom post type.
 *
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_updated_messages( $messages ) {

    global $post, $post_ID;

    $messages['wpbizplugins-eaqm'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => __('Menu button updated.', 'wpbizplugins-eaqm'),
        2 => __('Menu button updated.', 'wpbizplugins-eaqm'),
        3 => __('Menu button updated.', 'wpbizplugins-eaqm'),
        4 => __('Menu button updated.', 'wpbizplugins-eaqm'),
        /* translators: %s: date and time of the revision */
        5 => '',
        6 => __('Menu button added.', 'wpbizplugins-eaqm'),
        7 => __('Menu button saved.'),
        8 => __('Menu button added.', 'wpbizplugins-eaqm'),
        9 => '',
          // translators: Publish box date format, see http://php.net/date
          //date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => __('Draft of menu button saved.', 'wpbizplugins-eaqm'),
        );

    return $messages;
}
add_filter( 'post_updated_messages', 'wpbizplugins_eaqm_updated_messages' );
 

// Add the print styles to the dashboard head
add_action( 'admin_head-index.php', 'wpbizplugins_eaqm_print_styles' );

// Function for adding our styling to any admin page where the post type is our post type.
function wpbizplugins_eaqm_print_styles_on_post_type() {

    if( wpbizplugins_eaqm_return_post_type() == 'wpbizplugins-eaqm' ) {

        add_action( 'admin_head', 'wpbizplugins_eaqm_print_styles' );

    }

}
add_action( 'admin_init', 'wpbizplugins_eaqm_print_styles_on_post_type' );

/**
 * Adds the custom fields for ACF.
 *
 * @since 1.0
 *
 */

function wpbizplugins_eaqm_load_custom_fields() {
    if(function_exists("register_field_group")) {

        register_field_group(array (
            'id' => 'acf_menu-entry',
            'title' => 'Menu entry',
            'fields' => array (
                array (
                    'key' => 'field_53419512872ee',
                    'label' => 'Subtitle',
                    'name' => 'subtitle',
                    'type' => 'textarea',
                    'instructions' => __('You can add a short description of what the button does here. The contents of this field gets displayed under the title on the button.', 'wpbizplugins-eaqm'),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => 2,
                    'formatting' => 'br',
                ),
                array (
                    'key' => 'field_53419555872ef',
                    'label' => 'URL',
                    'name' => 'url',
                    'type' => 'text',
                    'instructions' => __('Put the URL you want to link to here.', 'wpbizplugins-eaqm') . '<p>' . __('<em>Tip:</em> Right-click and select Copy Link Location or similarly on any link in the admin menu to get the URL for that specific page.', 'wpbizplugins-eaqm'),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'formatting' => 'none',
                    'maxlength' => '',
                ),
                array ( 
                    'key' => 'field_new_window',
                    'label' => __('Open in new window?', 'wpbizplugins-eaqm'),
                    'name' => 'target_blank',
                    'type' => 'true_false',
                    'instructions' => __('Select this if you want the link to open in a new window.', 'wpbizplugins-eaqm'),
                    'message' => '',
                    'default_value' => 0,
            
                ),
                array (
                    'key' => 'field_53419704872f0',
                    'label' => 'Button color',
                    'name' => 'button_color',
                    'type' => 'radio',
                    'instructions' => __('Select the button color that you want for this entry.', 'wpbizplugins-eaqm'),
                    'choices' => array(

                        'blue'      => 'Blue',
                        'green'     => 'Green',
                        'orange'    => 'Orange',
                        'red'       => 'Red'
                        
                        ),
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => '',
                    'layout' => 'horizontal',
                ),
                array (
                    'key' => 'field_534193e6872ed',
                    'label' => 'Icon',
                    'name' => 'icon',
                    'type' => 'radio',
                    'instructions' => __('Choose an icon for your button below.', 'wpbizplugins-eaqm'),
                    'choices' => wpbizplugins_eaqm_return_icon_array(),
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => 100,
                    'layout' => 'horizontal',
                ),
                array (
                    'key' => 'field_53419755872f1',
                    'label' => 'Bottom message',
                    'name' => '',
                    'type' => 'message',
                    'message' => __('All done!', 'wpbizplugins-eaqm') . '<div><a href="http://www.wpbizplugins.com" target="_blank"><img style="margin-top: 20px; margin-bottom: 20px;" src="' . plugins_url( '../assets/img/wpbizplugins-footer-img.png', __FILE__ ) . '"></a></div>',
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wpbizplugins-eaqm',
                        'order_no' => 0,
                        'group_no' => 0,
                    ),
                ),
            ),
            'options' => array (
                'position' => 'normal',
                'layout' => 'no_box',
                'hide_on_screen' => array (
                    0 => 'permalink',
                    1 => 'the_content',
                    2 => 'excerpt',
                    3 => 'custom_fields',
                    4 => 'discussion',
                    5 => 'comments',
                    6 => 'revisions',
                    7 => 'slug',
                    8 => 'author',
                    9 => 'format',
                    10 => 'featured_image',
                    11 => 'categories',
                    12 => 'tags',
                    13 => 'send-trackbacks',
                ),
            ),
            'menu_order' => 0,
        ));
    }
}

if( is_admin() ) add_action( 'init', 'wpbizplugins_eaqm_load_custom_fields' );

/**
 * Adds text before the title input in the edit form for our custom post type.
 *
 * @since 1.0
 *
 */
add_action( 'edit_form_top', 'wpbizplugins_eaqm_before_title_edit' );
function wpbizplugins_eaqm_before_title_edit() {
    if( wpbizplugins_eaqm_return_post_type() == 'wpbizplugins-eaqm' ) {
        echo '<hr />';
        echo wpbizplugins_eaqm_return_icon_styles() . '<div style="margin-bottom:20px;"><img src="' . plugins_url( '../assets/img/wpbizplugins-eaqm-logo.png', __FILE__ ) . '"></div>';
        echo __('Fill in the fields on this page, select your button color and select an icon. Then simply press publish and you have a shiny new button for your quick menu!', 'wpbizplugins-eaqm');
        echo '<hr />';
        echo '<h3>' . __('Title', 'wpbizplugins-eaqm') . '</h3><p class="label">' . __('Enter the title of your button here.', 'wpbizplugins-eaqm') . '</p>';
    }
}


function wpbizplugins_eaqm_admin_notice() {
    $screen = get_current_screen();
    if( $screen->post_type == 'wpbizplugins-eaqm'
        && 'edit' == $screen->base ){
        ?>
        <div class="update-nag">
            <p>Drag and drop the menu items as you please to re-arrange the menu.</p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'wpbizplugins_eaqm_admin_notice' );

