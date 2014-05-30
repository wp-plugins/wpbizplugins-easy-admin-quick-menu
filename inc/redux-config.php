<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('WPBizPlugins_EasyAdminQuickMenu_Config')) {

    class WPBizPlugins_EasyAdminQuickMenu_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs. ;)
            if ( true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            // add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {

            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            // ACTUAL DECLARATION OF SECTIONS
            
            $this->sections[] = array(
                'title' => __('Menu configuration', 'wpbizplugins-eaqm'),
                'desc' => __('Configure the menu here.', 'wpbizplugins-eaqm'),
                'icon' => 'el-icon-th-list',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Easy Admin Quick Menu configuration
                     *
                     */

                    array(
                        'id'       => 'menu_capability',
                        'type'     => 'select',
                        'title'    => __('Capability required', 'wpbizplugins-eaqm'),
                        'subtitle' => __('The capability required to edit the menu', 'wpbizplugins-eaqm'),
                        'desc'     => __('Set the capability required for editing the menu contents here. Use to restrict access for your clients.', 'wpbizplugins-eaqm'),
                        // Must provide key => value pairs for select options
                        'options'  => wpbizplugins_eaqm_return_capabilities_array(),
                        'default'  => 'delete_plugins',
                    ),
                    
                    array(
                        'id'       => 'menu_title',
                        'type'     => 'text',
                        'title'    => __('Menu title', 'wpbizplugins-eaqm'),
                        'subtitle'     => __('The title of the dashboard widget containing the menu.', 'wpbizplugins-eaqm'),
                        'default'  => 'Easy Admin Quick Menu'
                    ), 

                    array(
                        'id'               => 'menu_welcometext',
                        'type'             => 'editor',
                        'title'            => __('Welcome text', 'wpbizplugins-eaqm'),
                        'subtitle'         => __('The welcome text for the menu. Appears before the actual menu.', 'wpbizplugins-eaqm'),
                        'default'          => '<p>Below are default buttons intended to show you how this menu works.</p><p><strong>You should edit this description, plus remove, re-arrange and add new buttons as you please in the Easy Admin Quick Menu entry in the menu to your left.</strong></p><p>Enjoy!</p>',
                        'desc'             => '<p>' . __('Keep your descriptive texts short and informative. Example of a welcome text for a client:', 'wpbizplugins-eaqm') . ' <em>' . __('Use the menu below to administer your website.', 'wpbizplugins-eaqm') . '</em>',
                        'editor_options'   => array(
                            'teeny'            => false,
                            'textarea_rows'    => 10,
                            'tinymce'          => true
                        )
                    )           

                )
                
            );

            $this->sections[] = array(
                'title' => __('Visual configuration', 'wpbizplugins-eaqm'),
                'desc' => __('Configure how the menu and menu entries look.', 'wpbizplugins-eaqm'),
                'icon' => 'el-icon-eye-open',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Easy Admin Quick Menu, visual configuration
                     *
                     */
                    
                    array(
                        'id'        => 'icon_size',
                        'type'      => 'slider',
                        'title'     => __('Size of the icons', 'wpbizplugins-eaqm'),
                        'subtitle'  => __('Control the size of the icons displayed on the menu buttons.', 'wpbizplugins-eaqm'),
                        'desc'      => __('Default:', 'wpbizplugins-eaqm') . ' 30px',
                        "default"   => 30,
                        "min"       => 20,
                        "step"      => 1,
                        "max"       => 80,
                        'display_value' => 'text'
                    ),

                    array(
                        'id'        => 'font_size',
                        'type'      => 'slider',
                        'title'     => __('Size of the title text', 'wpbizplugins-eaqm'),
                        'subtitle'  => __('Control the size of the title text displayed on the menu buttons.', 'wpbizplugins-eaqm'),
                        'desc'      => __('Default:', 'wpbizplugins-eaqm') . ' 14pt',
                        "default"   => 14,
                        "min"       => 10,
                        "step"      => 1,
                        "max"       => 24,
                        'display_value' => 'text'
                    ),

                    array(
                        'id'        => 'button_verticalspacing',
                        'type'      => 'slider',
                        'title'     => __('Vertical space between buttons', 'wpbizplugins-eaqm'),
                        'subtitle'  => __('Control the vertical space between the buttons.', 'wpbizplugins-eaqm'),
                        'desc'      => __('Default:', 'wpbizplugins-eaqm') . ' 5px',
                        "default"   => 5,
                        "min"       => 0,
                        "step"      => 1,
                        "max"       => 50,
                        'display_value' => 'text'
                    ),

                    /*
                    array(
                        'id'             => 'button_padding',
                        'type'           => 'spacing',
                        'output'         => array('.wpbizplugins-eaqm-button'),
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => __('Button padding', 'wpbizplugins-eaqm'),
                        'subtitle'       => __('Control the amount of padding for the button.', 'wpbizplugins-eaqm'),
                        'desc'           => __('Default:', 'wpbizplugins-eaqm') . ' 10px 10px 10px 10px',
                        'default'             => array(
                            'padding-top'     => '10px',
                            'padding-right'   => '10px',
                            'padding-bottom'  => '10px',
                            'padding-left'    => '10px',
                            'units'           => 'px',
                        )
                    ),*/

                    array(
                        'id'        => 'button_borderradius',
                        'type'      => 'slider',
                        'title'     => __('Rounding of button corners', 'wpbizplugins-eaqm'),
                        'subtitle'  => __('Control the rounding of the button corners.', 'wpbizplugins-eaqm'),
                        'desc'      => __('Default:', 'wpbizplugins-eaqm') . ' 10px',
                        "default"   => 10,
                        "min"       => 0,
                        "step"      => 1,
                        "max"       => 40,
                        'display_value' => 'text'
                    ),    

                    array(
                        'id' => 'info_custom_css',
                        'type' => 'info',
                        'style' => 'warning',
                        'icon' => 'el-icon-info-sign',
                        'title' => __('Warning', 'wpbizplugins-eaqm'),
                        'desc' => __('Only edit the custom CSS if you know what you are doing.', 'wpbizplugins-eaqm')
                    ), 

                    array(
                        'id'       => 'custom_css',
                        'type'     => 'ace_editor',
                        'title'    => __('Custom CSS Code', 'wpbizplugins-eaqm'),
                        'subtitle' => __('Put your custom CSS code here.', 'wpbizplugins-eaqm'),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'desc'     => '
                            <strong>' . __('Available CSS selectors', 'wpbizplugins-eaqm') . '</strong>' .
                            '<p><code>#wpbizplugins-eaqm-widget</code> - ' . __('The dashboard widget holding the entire menu.', 'wpbizplugins-eaqm') . '</p>' .
                            '<p><code>.wpbizplugins-eaqm-button</code> - ' . __('All the buttons have this class.', 'wpbizplugins-eaqm') . '</p>' .
                            '<p><code>.wpbizplugins-eaqm-icon</code> - ' . __('All the icons have this class.', 'wpbizplugins-eaqm') . '</p>' .
                            ''

                        ,
                        'default'  => ''
                    )

                )
                
            );


            $this->sections[] = array(
                'title'     => __('Import / Export', 'wpbizplugins-eaqm'),
                'desc'      => __('Import and Export the menu settings from file, text or URL.', 'wpbizplugins-eaqm'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your menu options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
           

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            /*$this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        */
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            //$theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'wpbizplugins_eaqm_options',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => 'Configuration',            // Name that appears at the top of your panel
                'display_version'   => '1.0',  // Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title' => __('Configuration', 'wpbizplugins-eaqm'),
                'page_title' => __('Easy Admin Quick Menu Options', 'wpbizplugins-eaqm'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => false,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'edit.php?post_type=wpbizplugins-eaqm',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'install_plugins',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                       // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'wpbizplugins_eaqm_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/wpbizplugins',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/wpbizplugins',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            $this->args['intro_text'] = '<img src="' . plugins_url( '../assets/img/wpbizplugins-eaqm-logo.png', __FILE__ ) . '"><p>' . __('Welcome to the Easy Admin Quick Menu configuration. Configure everything needed for the display of the menu here.', 'wpbizplugins-eaqm') . '</p>';

            // Add content after the form.
            $this->args['footer_text'] = '<a href="http://www.wpbizplugins.com?utm_source=eaqm&utm_medium=plugin&utm_campaign=eaqm" target="_blank"><img style="margin-top: 20px; margin-bottom: 20px;" src="' . plugins_url( '../assets/img/wpbizplugins-footer-img.png', __FILE__ ) . '"></a>';
        }

    }
    
    global $wpbizplugins_eaqm_options_config;
    $wpbizplugins_eaqm_options_config = new WPBizPlugins_EasyAdminQuickMenu_Config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
