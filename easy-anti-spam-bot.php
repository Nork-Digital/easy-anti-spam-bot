<?php

/**
 * Plugin Name: Easy Anti Spam Bots
 * Plugin URI: https://github.com/cjperes/easy-anti-spam-bot
 * Description: Simple and 100% Plug-n-play - block spam bots, proxy users and more!
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.4
 */

require  __DIR__ . '/vendor/collizo4sky/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action('admin_init', array( 'PAnD', 'init' ));

if (!defined('ABSPATH')) {
    exit; // tela branca caso o plugin for acessado direto
}

// anti spam bot [email]your@email.com[/email]
function easb_hide_email_shortcode($atts, $content = null)
{
    if (!is_email($content)) {
        return;
    }
    return '<a href="mailto:' . antispambot($content) . '">' . antispambot($content) . '</a>';
}
add_shortcode('email', 'easb_hide_email_shortcode');




//msg sucesso plugin ativo
function easb_admin_notice__success()
{
    if (! PAnD::is_admin_notice_active('disable-done-notice-forever')) {
        return;
    }

    ?>
<div data-dismissible="disable-done-notice-forever" class="notice notice-success is-dismissible">
<p><?php _e('Easy Anti Spam Bot Activated! Use the shortcode [email]your@email.com[/email] for protect the e-mail from bad bots. <a href="https://wordpress.org/support/plugin/easy-anti-spam-bots/reviews/#new-post" target="blank">If you like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the future!</a>'); ?></p>
                                </div>
    <?php
}
add_action('admin_init', array( 'PAnD', 'init' ));
add_action('admin_notices', 'easb_admin_notice__success');




//pagina plugin
class AntiSpamBotSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'easb_add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function easb_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Anti Spam Bot', // page_title
            'Anti Spam Bot', // menu_title
            'manage_options', // capability
            'e-anti-spam-bot', // menu_slug
            array( $this, 'easb_create_admin_page' ) // function
        );
    }

    /**
     * Options page callback
     */
    public function easb_create_admin_page()
    {
        // Set class property
        $this->options = get_option('easb_option_name');
        ?>
        <div class="wrap">
            <h1>Easy Anti Spam Bot</h1>

            <h3>How to use?</h3>

            <p>Just insert the <strong>e-mail</strong> into the shortcode [email][/email]</p>
            <p> <strong>Example: [email]your@email.com[/email]</strong> </p>
            <p><a href="https://wordpress.org/support/plugin/easy-anti-spam-bots/reviews/#new-post" target="blank">If you like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the future!</a></p>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'easb_option_group', // Option group
            'easb_option_name', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Anti Spam bot', // Title
            array($this, 'print_section_info'), // Callback
            'easb-setting-admin' // Page
        );

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title
            array($this, 'id_number_callback'), // Callback
            'easb-setting-admin', // Page
            'setting_section_id' // Section
        );
    }
}

if (is_admin()) {
    $easb_settings_page = new AntiSpamBotSetting();
}
