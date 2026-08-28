<?php
/**
 * Front page: the full one-page site.
 *
 * @package bjw-studio
 */

get_header();

bjw_section( 'hero' );
bjw_section( 'about' );
bjw_section( 'services' );
bjw_section( 'listen' );
bjw_section( 'portfolio' );
bjw_section( 'contact' );

get_footer();
