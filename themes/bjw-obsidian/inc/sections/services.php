<?php
/**
 * Services.
 *
 * @package bjw-obsidian
 */

$bjw_services = bjw_rows(
	'services',
	array(
		array(
			'title' => __( 'Composition', 'bjw-obsidian' ),
			'copy'  => __( 'Original music composition for film, television, video games, and commercials. Focus on bespoke themes that capture the unique emotional tone of your project.', 'bjw-obsidian' ),
		),
		array(
			'title' => __( 'Production', 'bjw-obsidian' ),
			'copy'  => __( 'Full music production services, including arrangement, sound design, recording oversight, and session musician management to bring your tracks to life.', 'bjw-obsidian' ),
		),
		array(
			'title' => __( 'Mixing & Mastering', 'bjw-obsidian' ),
			'copy'  => __( 'Expert audio engineering to ensure your music sounds punchy, clear, and perfectly balanced across all playback systems, ready for distribution.', 'bjw-obsidian' ),
		),
	)
);
?>
<section class="section surface-marble-light" id="services">
	<div class="shell">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php echo esc_html( bjw_field( 'services_eyebrow', __( 'What I Offer', 'bjw-obsidian' ) ) ); ?></p>
			<h2><?php echo esc_html( bjw_field( 'services_heading', __( 'Comprehensive Audio Services', 'bjw-obsidian' ) ) ); ?></h2>
			<div class="rule"></div>
		</div>

		<div class="card-grid">
			<?php foreach ( $bjw_services as $bjw_i => $bjw_service ) : ?>
				<article class="card reveal">
					<span class="card__index"><?php echo esc_html( sprintf( '%02d', $bjw_i + 1 ) ); ?></span>
					<h4><?php echo esc_html( $bjw_service['title'] ); ?></h4>
					<p><?php echo esc_html( $bjw_service['copy'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
