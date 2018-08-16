<?php
/**
 * Plugin Name: Easy Anti Spam Bots
 * Plugin URI: https://nork.digital
 * Description: Hide email from Spam Bots using a shortcode [email]your@email.com[/email]
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // tela branca caso o plugin for acessado direto
}

// anti spam bot [email]your@email.com[/email] 
function wpcodex_hide_email_shortcode( $atts , $content = null ) {
	if ( ! is_email( $content ) ) {
		return;
	}
	return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
}
add_shortcode( 'email', 'wpcodex_hide_email_shortcode' );

