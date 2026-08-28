<?php
/**
 * Portfolio highlights.
 *
 * @package bjw-obsidian
 */

$bjw_works = array(
	array(
		'title' => __( 'The Last Signal', 'bjw-obsidian' ),
		'meta'  => __( 'Film Score - Orchestral & Synth', 'bjw-obsidian' ),
	),
	array(
		'title' => __( "Echoes of K'lar", 'bjw-obsidian' ),
		'meta'  => __( 'Video Game Soundtrack', 'bjw-obsidian' ),
	),
);
?>
<section class="section surface-ink" id="portfolio">
	<div class="shell">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php esc_html_e( 'Selected Work', 'bjw-obsidian' ); ?></p>
			<h2><?php esc_html_e( 'My Portfolio Highlights', 'bjw-obsidian' ); ?></h2>
			<div class="rule"></div>
		</div>

		<div class="work-grid">
			<?php foreach ( $bjw_works as $bjw_work ) : ?>
				<a class="work reveal" href="#">
					<div class="work__body">
						<span class="work__meta"><?php echo esc_html( $bjw_work['meta'] ); ?></span>
						<h4><?php echo esc_html( $bjw_work['title'] ); ?></h4>
						<span class="work__link">
							<?php esc_html_e( 'View Project Details', 'bjw-obsidian' ); ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" /></svg>
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="work-foot reveal">
			<a class="btn" href="#"><?php esc_html_e( 'View Full Portfolio', 'bjw-obsidian' ); ?></a>
		</div>
	</div>
</section>
