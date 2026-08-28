<?php
/**
 * Hero, presented as an open session window.
 *
 * @package bjw-studio
 */
?>
<section class="hero">
	<div class="shell hero__grid">
		<div class="rack reveal">
			<div class="rack__bar">
				<span class="rack__screws"><i></i><i></i><i></i></span>
				<span class="rack__name"><?php esc_html_e( 'Session — Benjamin J. Wood', 'bjw-studio' ); ?></span>
				<span class="rack__led"><?php esc_html_e( 'Online', 'bjw-studio' ); ?></span>
			</div>

			<div class="rack__body">
				<p class="hero__kicker"><?php echo esc_html( bjw_field( 'hero_kicker', __( 'Composer | Producer | Audio Engineer', 'bjw-studio' ) ) ); ?></p>
				<h1 class="hero__title"><?php echo esc_html( bjw_field( 'hero_title', __( 'Benjamin', 'bjw-studio' ) ) ); ?> <em><?php echo esc_html( bjw_field( 'hero_title_accent', __( 'J. Wood', 'bjw-studio' ) ) ); ?></em></h1>
				<p class="hero__lede"><?php echo esc_html( bjw_field( 'hero_lede', __( 'Crafting emotional depth and sonic perfection for film, games, and media.', 'bjw-studio' ) ) ); ?></p>

				<div class="hero__actions">
					<a class="btn" href="#contact"><?php echo esc_html( bjw_field( 'hero_cta', __( 'Start a Project', 'bjw-studio' ) ) ); ?></a>
					<a class="btn btn--ghost" href="#listen"><?php esc_html_e( 'Open A/B Room', 'bjw-studio' ); ?></a>
				</div>

				<div class="hero__ruler" aria-hidden="true">
					<?php for ( $bjw_i = 0; $bjw_i < 64; $bjw_i++ ) : ?><i></i><?php endfor; ?>
				</div>

				<div class="hero__wave">
					<canvas data-visualizer aria-hidden="true"></canvas>
				</div>
			</div>
		</div>

		<div class="hero__side">
			<div class="rack reveal">
				<div class="rack__bar">
					<span class="rack__screws"><i></i><i></i></span>
					<span class="rack__name"><?php esc_html_e( 'Channel List', 'bjw-studio' ); ?></span>
				</div>
				<div class="rack__body">
					<dl style="margin:0">
						<div class="stat">
							<dt><?php esc_html_e( 'Composition', 'bjw-studio' ); ?></dt>
							<dd><?php esc_html_e( 'Film / TV / Games', 'bjw-studio' ); ?></dd>
						</div>
						<div class="stat">
							<dt><?php esc_html_e( 'Production', 'bjw-studio' ); ?></dt>
							<dd><?php esc_html_e( 'Arrangement / Sound Design', 'bjw-studio' ); ?></dd>
						</div>
						<div class="stat">
							<dt><?php esc_html_e( 'Engineering', 'bjw-studio' ); ?></dt>
							<dd><?php esc_html_e( 'Mixing / Mastering', 'bjw-studio' ); ?></dd>
						</div>
					</dl>
				</div>
			</div>

			<div class="rack reveal">
				<div class="rack__bar">
					<span class="rack__screws"><i></i><i></i></span>
					<span class="rack__name"><?php esc_html_e( 'Now Loaded', 'bjw-studio' ); ?></span>
					<span class="rack__led"><?php esc_html_e( 'Sync', 'bjw-studio' ); ?></span>
				</div>
				<div class="rack__body">
					<p style="margin:0;color:var(--muted);font-size:0.95rem">
						<?php esc_html_e( 'Two versions of the same piece, running on one playhead. Mute either side to hear what changed.', 'bjw-studio' ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>
