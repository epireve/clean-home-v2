<?php
/**
 * WordPress.com-specific functions and definitions
 * This file is centrally included from `wp-content/mu-plugins/wpcom-theme-compat.php`.
 *
 * @package Clean Home
 * @since Clean Home 1.0
 */

/**
 * Adds support for wp.com-specific theme functions
 */
function cleanhome_add_wpcom_support() {
	global $themecolors;

	// Set a default theme color array.
	$options = cleanhome_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme ) {
		$themecolors = array(
			'bg'     => '161616',
			'border' => '222222',
			'text'   => 'd5d5d5',
			'link'   => 'd5d5d5',
			'url'    => 'ad5a00',
		);
	}
	if ( 'snowy' == $color_scheme ) {
		$themecolors = array(
			'bg'     => 'e9f7fb',
			'border' => '9ccedd',
			'text'   => '000000',
			'link'   => '0092be',
			'url'    => '086581',
		);
	}
	if ( 'sunny' == $color_scheme ) {
		$themecolors = array(
			'bg'     => 'f9f7e1',
			'border' => 'd6cd64',
			'text'   => '000000',
			'link'   => '856d0e',
			'url'    => '815303',
		);
	}
	if ( 'light' == $color_scheme ) {
		$themecolors = array(
			'bg'     => 'fafafa',
			'border' => 'cccccc',
			'text'   => '000000',
			'link'   => 'ca1e00',
			'url'    => 'e94325',
		);
	}

	add_theme_support( 'print-style' );
}
add_action( 'after_setup_theme', 'cleanhome_add_wpcom_support' );

//WordPress.com specific styles
function cleanhome_wpcom_styles() {
	wp_enqueue_style( 'cleanhome-wpcom', get_template_directory_uri() . '/inc/style-wpcom.css', '20130005' );
}
add_action( 'wp_enqueue_scripts', 'cleanhome_wpcom_styles' );