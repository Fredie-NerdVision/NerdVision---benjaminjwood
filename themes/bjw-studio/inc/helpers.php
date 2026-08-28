<?php
/**
 * Shared helpers. Kept free of WordPress-only calls where possible so the
 * section partials can also be rendered by the static preview harness in /dev.
 *
 * @package bjw-studio
 */

if ( ! function_exists( 'bjw_asset' ) ) {
	/**
	 * URL for a file inside the theme's assets directory.
	 *
	 * @param string $path Relative path, e.g. "js/site.js".
	 * @return string
	 */
	function bjw_asset( $path ) {
		return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'bjw_inline_variables' ) ) {
	/**
	 * Runtime CSS custom properties (visualizer palette).
	 *
	 * @return string
	 */
	function bjw_inline_variables() {
		return ':root{--viz-hot:#f0b070;--viz-cool:rgba(200,118,60,0.42);}';
	}
}

if ( ! function_exists( 'bjw_section' ) ) {
	/**
	 * Render a section partial.
	 *
	 * @param string $name Partial name inside inc/sections.
	 */
	function bjw_section( $name ) {
		$file = dirname( __DIR__ ) . '/inc/sections/' . $name . '.php';
		if ( file_exists( $file ) ) {
			include $file;
		}
	}
}
