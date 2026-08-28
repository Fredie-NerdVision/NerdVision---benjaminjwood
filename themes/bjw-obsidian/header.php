<?php
/**
 * Document head, masthead and navigation.
 *
 * @package bjw-obsidian
 */

$bjw_links = array(
	'#about'     => __( 'About', 'bjw-obsidian' ),
	'#services'  => __( 'Services', 'bjw-obsidian' ),
	'#listen'    => __( 'Listen', 'bjw-obsidian' ),
	'#portfolio' => __( 'Portfolio', 'bjw-obsidian' ),
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'bjw-obsidian' ); ?></a>

<header class="site-header" data-header>
	<div class="shell site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="brand__mark">BJW</span>
			<span class="brand__sub"><?php esc_html_e( 'Benjamin J. Wood', 'bjw-obsidian' ); ?></span>
		</a>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'bjw-obsidian' ); ?>">
			<?php foreach ( $bjw_links as $bjw_href => $bjw_label ) : ?>
				<a href="<?php echo esc_attr( $bjw_href ); ?>"><?php echo esc_html( $bjw_label ); ?></a>
			<?php endforeach; ?>
			<a class="btn btn--sm" href="#contact"><?php esc_html_e( 'Contact', 'bjw-obsidian' ); ?></a>
		</nav>

		<button class="nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="bjw-mobile-nav" aria-label="<?php esc_attr_e( 'Toggle menu', 'bjw-obsidian' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
				<path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
			</svg>
		</button>
	</div>

	<div class="mobile-nav" id="bjw-mobile-nav" data-mobile-nav>
		<?php foreach ( $bjw_links as $bjw_href => $bjw_label ) : ?>
			<a href="<?php echo esc_attr( $bjw_href ); ?>"><?php echo esc_html( $bjw_label ); ?></a>
		<?php endforeach; ?>
		<a class="btn btn--sm" href="#contact"><?php esc_html_e( 'Contact', 'bjw-obsidian' ); ?></a>
	</div>
</header>

<main id="main">
