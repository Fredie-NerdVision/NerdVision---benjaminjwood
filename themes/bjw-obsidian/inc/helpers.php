<?php
/**
 * Shared helpers. Kept free of WordPress-only calls where possible so the
 * section partials can also be rendered by the static preview harness in /dev.
 *
 * @package bjw-obsidian
 */

if ( ! function_exists( 'bjw_asset' ) ) {
	/**
	 * URL for a file inside the theme's assets directory.
	 *
	 * @param string $path Relative path, e.g. "img/black_marble.png".
	 * @return string
	 */
	function bjw_asset( $path ) {
		return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'bjw_marble_variables' ) ) {
	/**
	 * CSS custom properties that point at the marble textures.
	 *
	 * @return string
	 */
	function bjw_marble_variables() {
		return sprintf(
			':root{--marble-dark:url(%1$s);--marble-light:url(%2$s);--viz-hot:#ffe7a2;--viz-cool:rgba(200,162,74,0.32);}',
			esc_url( bjw_asset( 'img/black_marble.png' ) ),
			esc_url( bjw_asset( 'img/white_marble.png' ) )
		);
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
