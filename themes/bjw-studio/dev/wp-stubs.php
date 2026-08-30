<?php
/**
 * Minimal WordPress stand-ins so the theme templates can be rendered by the
 * PHP built-in server for design review, without a WordPress install.
 *
 * Never loaded inside WordPress — see dev/README.md.
 *
 * @package bjw-studio
 */

define( 'ABSPATH', dirname( __DIR__ ) . '/' );
define( 'BJW_PREVIEW', true );

$GLOBALS['bjw_theme_dir'] = dirname( __DIR__ );

function get_template_directory() {
	return $GLOBALS['bjw_theme_dir'];
}

function get_template_directory_uri() {
	return '';
}

function bjw_asset( $path ) {
	return '/assets/' . ltrim( $path, '/' );
}

function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $text ) { return esc_html( $text ); }
function esc_url( $url ) { return esc_html( $url ); }
function esc_url_raw( $url ) { return $url; }
function esc_textarea( $text ) { return esc_html( $text ); }
function esc_html_e( $text ) { echo esc_html( $text ); }
function esc_attr_e( $text ) { echo esc_attr( $text ); }
function esc_html__( $text ) { return esc_html( $text ); }
function __( $text ) { return $text; }
function _e( $text ) { echo $text; }
function sanitize_key( $key ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) ); }
function wp_unslash( $value ) { return $value; }
function language_attributes() { echo 'lang="en"'; }
function bloginfo( $what ) { echo 'name' === $what ? 'Benjamin J. Wood' : 'UTF-8'; }
function get_bloginfo( $what ) { return 'name' === $what ? 'Benjamin J. Wood' : 'UTF-8'; }
function body_class() { echo 'class="home bjw-preview"'; }
function home_url( $path = '/' ) { return $path; }
function wp_body_open() {}
function get_theme_mod( $name, $default = false ) { return $default; }

function wp_head() {
	echo '<title>Benjamin J. Wood — Composer, Producer, Audio Engineer</title>' . "\n";
	echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=JetBrains+Mono:wght@400;500&family=Space+Grotesk:wght@500;600;700&display=swap">' . "\n";
	echo '<link rel="stylesheet" href="/assets/css/theme.css">' . "\n";
	echo '<style>' . bjw_inline_variables() . '</style>' . "\n";
}

function wp_footer() {
	echo '<script>window.BJW_TRACKS = ' . wp_json_encode( bjw_get_tracks() ) . ';</script>' . "\n";
	echo '<script src="/assets/js/site.js"></script>' . "\n";
	echo '<script src="/assets/js/tandem-player.js"></script>' . "\n";
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data ) { return json_encode( $data ); }
}

function get_header() { include get_template_directory() . '/header.php'; }
function get_footer() { include get_template_directory() . '/footer.php'; }
