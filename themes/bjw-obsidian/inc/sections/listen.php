<?php
/**
 * Tandem A/B listening section.
 *
 * @package bjw-obsidian
 */

$bjw_tracks = bjw_get_tracks();
?>
<section class="section surface-marble-dark" id="listen">
	<div class="shell">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php echo esc_html( bjw_field( 'listen_eyebrow', __( 'A / B Listening Room', 'bjw-obsidian' ) ) ); ?></p>
			<h2><?php echo esc_html( bjw_field( 'listen_heading', __( 'Hear the Difference', 'bjw-obsidian' ) ) ); ?></h2>
			<div class="rule"></div>
		</div>

		<div class="ab reveal" data-tandem>
			<div class="ab__head">
				<div>
					<h3 class="ab__title"><?php esc_html_e( 'Two versions. One playhead.', 'bjw-obsidian' ); ?></h3>
					<p class="ab__note"><?php echo esc_html( bjw_field( 'listen_intro', __( 'Both renders run on one playhead and only one is ever audible. Switch between them mid-phrase to hear exactly what changed.', 'bjw-obsidian' ) ) ); ?></p>
				</div>

				<div class="ab__tracks" role="group" aria-label="<?php esc_attr_e( 'Choose a track', 'bjw-obsidian' ); ?>">
					<?php foreach ( $bjw_tracks as $bjw_i => $bjw_track ) : ?>
						<button type="button" class="ab__track<?php echo 0 === $bjw_i ? ' is-active' : ''; ?>" data-tandem-track="<?php echo esc_attr( $bjw_i ); ?>">
							<?php echo esc_html( $bjw_track['title'] ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="ab__lanes">
				<?php foreach ( array( 'a' => __( 'Channel A', 'bjw-obsidian' ), 'b' => __( 'Channel B', 'bjw-obsidian' ) ) as $bjw_key => $bjw_channel ) : ?>
					<div class="lane<?php echo 'a' === $bjw_key ? ' is-solo' : ' is-muted'; ?>" data-lane="<?php echo esc_attr( $bjw_key ); ?>" role="button" tabindex="0" aria-pressed="<?php echo 'a' === $bjw_key ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: channel name. */ __( 'Hear %s', 'bjw-obsidian' ), $bjw_channel ) ); ?>">
						<div class="lane__top">
							<span class="lane__label">
								<span class="lane__tag"><?php echo esc_html( strtoupper( $bjw_key ) ); ?></span>
								<span data-lane-name><?php echo esc_html( $bjw_tracks[0][ $bjw_key ]['label'] ); ?></span>
							</span>
							<span class="lane__state"><?php esc_html_e( 'On air', 'bjw-obsidian' ); ?></span>
						</div>

						<div class="lane__meter" data-lane-meter aria-hidden="true"></div>

						<label class="lane__gain">
							<span><?php esc_html_e( 'Gain', 'bjw-obsidian' ); ?></span>
							<input type="range" min="0" max="100" value="100" data-lane-gain aria-label="<?php echo esc_attr( sprintf( /* translators: %s: channel name. */ __( '%s gain', 'bjw-obsidian' ), $bjw_channel ) ); ?>">
						</label>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="ab__switch" role="group" aria-label="<?php esc_attr_e( 'Choose the version you hear', 'bjw-obsidian' ); ?>">
				<span class="ab__switch-label"><?php esc_html_e( 'Now hearing', 'bjw-obsidian' ); ?></span>
				<?php foreach ( array( 'a', 'b' ) as $bjw_key ) : ?>
					<button type="button" class="ab__switch-btn<?php echo 'a' === $bjw_key ? ' is-on' : ''; ?>" data-ab-switch="<?php echo esc_attr( $bjw_key ); ?>" aria-pressed="<?php echo 'a' === $bjw_key ? 'true' : 'false'; ?>">
						<?php echo esc_html( $bjw_tracks[0][ $bjw_key ]['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
