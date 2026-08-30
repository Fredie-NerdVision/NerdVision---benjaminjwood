<?php
/**
 * Router for `php -S`. Serves theme assets directly and renders the front page
 * through the WordPress stubs for everything else.
 *
 * Usage: php -S localhost:8081 -t . dev/router.php   (from the theme root)
 *
 * @package bjw-studio
 */

$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$file = __DIR__ . '/..' . $path;

if ( '/' !== $path && file_exists( $file ) && ! is_dir( $file ) && 'php' !== pathinfo( $file, PATHINFO_EXTENSION ) ) {
	return false;
}

require __DIR__ . '/wp-stubs.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/tracks.php';
require get_template_directory() . '/front-page.php';
