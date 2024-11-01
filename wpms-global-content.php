<?php
/**
 * Plugin Name: WPMS Global Content
 * Plugin URI: http://www.vareen.co.cc/
 * Description: WPMS Global Content allow wordpress multisite network admin to set common header and footer content.
 * Version: 1.1
 * Author: Neerav Dobaria
 * Author URI: http://www.vareen.co.cc
 * License: GPL2
 */
/*  Copyright 2011  Neerav Dobaria

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

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

// Define certain terms which may be required throughout the plugin
define('WPMSGC_NAME', 'WPMS Global Content');
define('WPMSGC_PATH', WP_PLUGIN_DIR . '/wpms-global-content');
define('WPMSGC_URL', WP_PLUGIN_URL . '/wpms-global-content');
define('WPMSGC_BASENAME', plugin_basename(__FILE__));

if (!class_exists(wpavp)) {
    class wpmsgc
    {

        private $options;

        function wpmsgc()
        {
            global $blog_id;
            switch_to_blog(1);
            $this->options = get_option('wpmsgc');
            restore_current_blog();
            if (is_admin()) {
                register_activation_hook(WPMSGC_BASENAME, array(&$this, 'on_activate'));
                add_action('admin_init', array(&$this, 'on_admin_init'));

                if (1 == $blog_id) {
                    add_action('admin_menu', array(&$this, 'on_admin_menu'));
                }

                add_filter("plugin_action_links_" . WPMSGC_BASENAME, array(&$this, 'action_links'));

                // since wp 3.2 function name has been changed
                if(get_bloginfo('version') >= 3.2){
                    add_action( 'admin_print_footer_scripts', 'wp_preload_dialogs', 30 );
                } else {
                    add_action( 'admin_print_footer_scripts', 'wp_tiny_mce_preload_dialogs', 30 );
                }
            } else {
                $include = $this->options['include'];
                $exclude = explode(',', $this->options['exclude']);

                // return nothing if blog in exclude list
                if ($include and !in_array($blog_id, $exclude))
                    return;

                // return nothing if blog not in include list
                if (!$include and in_array($blog_id, $exclude))
                    return;

                add_action('init', array(&$this, 'load_script'));
                add_filter('wp_footer', array(&$this, 'add_global_content'));
            }
        }

        function on_admin_init()
        {
            register_setting('wpmsgc_options', 'wpmsgc');
        }

        function on_admin_menu()
        {
            $option_page = add_options_page(WPMSGC_NAME . ' Options', WPMSGC_NAME, 'Super Admin', WPMSGC_BASENAME, array(&$this, 'options_page'));
            add_action("admin_print_scripts-$option_page", array(&$this, 'on_admin_print_scripts'));
            add_action("admin_print_styles-$option_page", array(&$this, 'on_admin_print_styles'));
        }

        function load_script()
        {
            wp_enqueue_script('wpmsgc_script', WPMSGC_URL . '/assets/js/script.js', array('jquery'));
        }

        function add_global_content()
        {
            echo '<div id="wpmsgcheader">' . $this->options['header'] . '</div>';
            echo '<div id="wpmsgcfooter">' . $this->options['footer'] . '</div>';
        }

        function options_page()
        {
            // 'a_nice_textarea' is class of textarea which will have TinyMCE
            wp_tiny_mce(true, array('editor_selector' => 'wpmsgctextarea', 'width' => '100%','height' => '300px'));
            include(WPMSGC_PATH . '/wpmsgc-options.php');
        }

        function on_admin_print_styles()
        {
            wp_enqueue_style('wpmsgcadminstyle', WPMSGC_URL . '/assets/css/admin.css', false, '1.0', 'all');
        }

        function on_admin_print_scripts()
        {
            //wp_enqueue_script('farbtastic', TP_URL . '/assets/farbtastic/farbtastic.js', 'jquery');
        }

        function action_links($links)
        {
            $settings_link = '<a href="options-general.php?page=' . WPMSGC_BASENAME . '">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        function on_activate()
        {
            $default = array(
                'include' => '0',
                'exclude' => '1',
                'header' => 'This will be displayed in header.',
                'footer' => 'This will be displayed in footer.',
                'submit' => 'Save Changes',
            );
            if (!get_option('wpmsgc')) {
                add_option('wpmsgc', $default);
            }
        }
    }

    $wpmsgc = new wpmsgc();
}
