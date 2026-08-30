<?php
/**
 * Services, laid out as console channel strips.
 *
 * @package bjw-studio
 */

$bjw_levels = array( 92, 78, 85 );

$bjw_services = bjw_rows(
	'services',
	array(
		array(
			'title' => __( 'Composition', 'bjw-studio' ),
			'copy'  => __( 'Original music composition for film, television, video games, and commercials. Focus on bespoke themes that capture the unique emotional tone of your project.', 'bjw-studio' ),
		),
		array(
			'title' => __( 'Production', 'bjw-studio' ),
			'copy'  => __( 'Full music production services, including arrangement, sound design, recording oversight, and session musician management to bring your tracks to life.', 'bjw-studio' ),
		),
		array(
			'title' => __( 'Mixing & Mastering', 'bjw-studio' ),
			'copy'  => __( 'Expert audio engineering to ensure your music sounds punchy, clear, and perfectly balanced across all playback systems, ready for distribution.', 'bjw-studio' ),
		),
	)
);
?>
<section class="section" id="services">
	<div class="shell">
		<div class="section-head reveal">
			<span class="mono"><?php echo esc_html( bjw_field( 'services_eyebrow', __( 'What I Offer', 'bjw-studio' ) ) ); ?></span>
			<h2><?php echo esc_html( bjw_field( 'services_heading', __( 'Comprehensive Audio Services', 'bjw-studio' ) ) ); ?></h2>
		</div>

		<div class="strips">
			<?php foreach ( $bjw_services as $bjw_i => $bjw_service ) : ?>
				<article class="strip reveal">
					<div class="strip__head">
						<span class="strip__id"><?php echo esc_html( sprintf( 'CH %02d', $bjw_i + 1 ) ); ?></span>
						<span class="strip__knob" aria-hidden="true"></span>
					</div>
					<h4><?php echo esc_html( $bjw_service['title'] ); ?></h4>
					<div class="strip__fader" aria-hidden="true"><i style="width:<?php echo esc_attr( $bjw_levels[ $bjw_i % count( $bjw_levels ) ] ); ?>%"></i></div>
					<p><?php echo esc_html( $bjw_service['copy'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
