<?php
/**
 * Portfolio highlights, laid out as timeline clips.
 *
 * @package bjw-studio
 */

$bjw_peaks = array(
	array( 12, 26, 18, 34, 22, 38, 16, 30, 24, 14, 32, 20, 28, 18, 36, 22, 12, 26 ),
	array( 20, 14, 30, 22, 36, 18, 28, 12, 34, 24, 16, 32, 20, 38, 14, 26, 22, 18 ),
);

$bjw_works = bjw_rows(
	'works',
	array(
		array(
			'title' => __( 'The Last Signal', 'bjw-studio' ),
			'meta'  => __( 'Film Score - Orchestral & Synth', 'bjw-studio' ),
			'link'  => '',
		),
		array(
			'title' => __( "Echoes of K'lar", 'bjw-studio' ),
			'meta'  => __( 'Video Game Soundtrack', 'bjw-studio' ),
			'link'  => '',
		),
	)
);
?>
<section class="section" id="portfolio">
	<div class="shell">
		<div class="section-head reveal">
			<span class="mono"><?php echo esc_html( bjw_field( 'portfolio_eyebrow', __( 'Selected Work', 'bjw-studio' ) ) ); ?></span>
			<h2><?php echo esc_html( bjw_field( 'portfolio_heading', __( 'My Portfolio Highlights', 'bjw-studio' ) ) ); ?></h2>
		</div>

		<div class="clips">
			<?php foreach ( $bjw_works as $bjw_i => $bjw_work ) : ?>
				<a class="clip reveal" href="<?php echo esc_url( empty( $bjw_work['link'] ) ? '#' : $bjw_work['link'] ); ?>">
					<span>
						<span class="clip__tag"><?php echo esc_html( sprintf( 'Clip %02d', $bjw_i + 1 ) ); ?></span>
						<span class="clip__meta" style="display:block"><?php echo esc_html( $bjw_work['meta'] ); ?></span>
					</span>
					<span>
						<h4><?php echo esc_html( $bjw_work['title'] ); ?></h4>
						<span class="clip__bars" aria-hidden="true">
							<?php foreach ( $bjw_peaks[ $bjw_i % count( $bjw_peaks ) ] as $bjw_peak ) : ?>
								<i style="height:<?php echo esc_attr( $bjw_peak ); ?>px"></i>
							<?php endforeach; ?>
						</span>
					</span>
					<span class="clip__go">
						<?php esc_html_e( 'View Project Details', 'bjw-studio' ); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" /></svg>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="clips-foot reveal">
			<a class="btn btn--ghost" href="#"><?php esc_html_e( 'View Full Portfolio', 'bjw-studio' ); ?></a>
		</div>
	</div>
</section>
