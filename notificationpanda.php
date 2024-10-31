<?php
/**
 * Plugin Name: Notification Panda Social Proof
 * Version: 2.0.0
 * Author: Notification Panda
 * Author URI: https://notificationpanda.com/
 * Description: Allows you to install Notification Panda of your WordPress Website and Manage your campaigns directly from your WP Admin area.
 * License: proprietary
 */
class NotificationPanda
{
    public function __construct()
    {
        $file_data = get_file_data(__FILE__, array(
            'Version' => 'Version'
        ));

        $this->plugin = new stdClass;
        $this->plugin->name = 'notification-panda';
        $this->plugin->displayName = 'Notification Panda';
        $this->plugin->version = $file_data['Version'];
        $this->plugin->folder = plugin_dir_path(__FILE__);
        $this->plugin->url = plugin_dir_url(__FILE__);
        $this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';

        add_action('admin_init', array(&$this, 'registerSettings'));
        add_action('admin_enqueue_scripts', array(&$this, 'initCodeMirror'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('admin_notices', array(&$this, 'dashboardNotices'));
        add_action('wp_ajax_' . $this->plugin->name . '_dismiss_dashboard_notices', array(&$this, 'dismissDashboardNotices'));
        add_action('wp_head', array(&$this, 'frontendHeader'));
    }

    function dashboardNotices()
    {
        global $pagenow;

        if (!get_option($this->plugin->db_welcome_dismissed_key))
        {
            if (!('admin.php' === $pagenow && isset($_GET['page']) && 'notification-panda' === $_GET['page']))
            {
                $setting_page = admin_url('admin.php?page=' . $this->plugin->name);
                include_once ($this->plugin->folder . '/views/dashboard-notices.php');
            }
        }
    }

    function dismissDashboardNotices()
    {
        check_ajax_referer($this->plugin->name . '-nonce', 'nonce');
        update_option($this->plugin->db_welcome_dismissed_key, 1);
        exit;
    }

    function registerSettings()
    {
        register_setting($this->plugin->name, 'np_insert_header', 'trim');
    }

    function adminPanelsAndMetaBoxes()
    {
        add_menu_page( $this ->plugin->displayName, 'Notification Panda', 'manage_options', 'notification-panda', array(&$this, 'adminDashboard'), 'data:image/png;base64,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', 80 );
        
        add_submenu_page( 'notification-panda', 'Notification Panda - Install Pixel', 'Install Pixel', 'manage_options', 'notification-panda-install-pixel', array(&$this, 'installPixel') );

    }

    function adminDashboard()
    {
        if (!current_user_can('administrator'))
        {
            echo '<p>' . __('Sorry, you are not allowed to access this page.', 'notification-panda') . '</p>';
            return;
        }

        include_once ($this->plugin->folder . '/views/login.php');
    }

    function installPixel()
    {
        if (!current_user_can('administrator'))
        {
            echo '<p>' . __('Sorry, you are not allowed to access this page.', 'notification-panda-install-pixel') . '</p>';
            return;
        }

        if (isset($_REQUEST['submit']))
        {
            if (!isset($_REQUEST[$this->plugin->name . '_nonce']))
            {
                $this->errorMessage = __('nonce field is missing. Pixel settings NOT saved.', 'notification-panda-install-pixel');
            }
            elseif (!wp_verify_nonce($_REQUEST[$this->plugin->name . '_nonce'], $this->plugin->name))
            {
                $this->errorMessage = __('Invalid nonce specified. Settings NOT saved.', 'notification-panda-install-pixel');
            }
            else
            {
                update_option('np_insert_header', $_REQUEST['np_insert_header']);
                update_option($this->plugin->db_welcome_dismissed_key, 1);
                $this->message = __('Pixel settings saved!', 'notification-panda-install-pixel');
            }
        }

        $this->settings = array(
            'np_insert_header' => esc_html(wp_unslash(get_option('np_insert_header'))) ,
        );

        include_once ($this->plugin->folder . '/views/install-pixel.php');
    }

    function initCodeMirror()
    {
        if (!function_exists('wp_enqueue_code_editor'))
        {
            return;
        }

        global $pagenow;

        if (!('admin.php' === $pagenow && isset($_GET['page']) && 'notification-panda-install-pixel' === $_GET['page']))
        {
            return;
        }

        $settings = wp_enqueue_code_editor(array(
            'type' => 'text/html'
        ));

        if (false === $settings)
        {
            return;
        }

        $styles = '.CodeMirror{ border: 1px solid #ccd0d4; }';
        wp_add_inline_style('code-editor', $styles);
        wp_add_inline_script('code-editor', sprintf('jQuery( function() { wp.codeEditor.initialize( "np_insert_header", %s ); } );', wp_json_encode($settings)));
    }


    function frontendHeader()
    {
        $this->output('np_insert_header');
    }

    function output($setting)
    {
        if (is_admin() || is_feed() || is_robots() || is_trackback())
        {
            return;
        }

        if (apply_filters('disable_np', false))
        {
            return;
        }

        if ('np_insert_header' === $setting && apply_filters('disable_np_header', false))
        {
            return;
        }

        // Get meta
        $meta = get_option($setting);
        if (empty($meta))
        {
            return;
        }
        if (trim($meta) === '')
        {
            return;
        }

        echo wp_unslash($meta);
    }
}

$np = new NotificationPanda();
