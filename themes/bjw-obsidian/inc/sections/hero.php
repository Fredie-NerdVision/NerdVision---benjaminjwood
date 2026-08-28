<?php
/**
 * Hero.
 *
 * @package bjw-obsidian
 */

$bjw_name = trim( bjw_field( 'hero_title', __( 'Benjamin', 'bjw-obsidian' ) ) . ' ' . bjw_field( 'hero_title_accent', __( 'J. Wood', 'bjw-obsidian' ) ) );
?>
<section class="hero surface-ink">
	<div class="shell">
		<div class="hero__frame reveal">
			<p class="hero__kicker"><?php echo esc_html( bjw_field( 'hero_kicker', __( 'Composer | Producer | Audio Engineer', 'bjw-obsidian' ) ) ); ?></p>
			<h1 class="hero__title"><?php echo esc_html( $bjw_name ); ?></h1>
		</div>

		<p class="hero__lede reveal"><?php echo esc_html( bjw_field( 'hero_lede', __( 'Crafting emotional depth and sonic perfection for film, games, and media.', 'bjw-obsidian' ) ) ); ?></p>

		<div class="hero__wave reveal">
			<canvas data-visualizer aria-hidden="true"></canvas>
		</div>

		<div class="hero__cta reveal">
			<a class="btn btn--solid" href="#contact"><?php echo esc_html( bjw_field( 'hero_cta', __( 'Start a Project', 'bjw-obsidian' ) ) ); ?></a>
		</div>
	</div>
</section>
