<?php
/**
 * BJW Obsidian theme bootstrap.
 *
 * @package bjw-obsidian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BJW_THEME_VERSION', '1.0.0' );
define( 'BJW_THEME_SLUG', 'bjw-obsidian' );

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/tracks.php';
require_once get_template_directory() . '/inc/contact.php';

/**
 * Theme supports and menus.
 */
function bjw_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 64, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'bjw-obsidian' ),
		)
	);
}
add_action( 'after_setup_theme', 'bjw_setup' );

/**
 * Asset URL version. Falls back to the theme version when the file is missing.
 *
 * Uses the file's modification time so a redeploy always busts browser and
 * page caches, which a static version string does not.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function bjw_asset_version( $relative ) {
	$path = get_template_directory() . $relative;
	$time = file_exists( $path ) ? filemtime( $path ) : false;

	return $time ? (string) $time : BJW_THEME_VERSION;
}

/**
 * Front-end assets.
 */
function bjw_assets() {
	$uri = get_template_directory_uri();

	wp_enqueue_style(
		'bjw-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Montserrat:wght@300;400;500;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'bjw-theme', $uri . '/assets/css/theme.css', array( 'bjw-fonts' ), bjw_asset_version( '/assets/css/theme.css' ) );

	wp_add_inline_style( 'bjw-theme', bjw_marble_variables() );

	wp_enqueue_script( 'bjw-site', $uri . '/assets/js/site.js', array(), bjw_asset_version( '/assets/js/site.js' ), true );
	wp_enqueue_script( 'bjw-tandem', $uri . '/assets/js/tandem-player.js', array(), bjw_asset_version( '/assets/js/tandem-player.js' ), true );

	wp_add_inline_script(
		'bjw-tandem',
		'window.BJW_TRACKS = ' . wp_json_encode( bjw_get_tracks() ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'bjw_assets' );
