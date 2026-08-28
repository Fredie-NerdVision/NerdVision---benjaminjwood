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
					<p class="ab__note"><?php echo esc_html( bjw_field( 'listen_intro', __( 'Both renders play in perfect sync. Mute, solo or crossfade between them to hear exactly what changed.', 'bjw-obsidian' ) ) ); ?></p>
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
					<div class="lane" data-lane="<?php echo esc_attr( $bjw_key ); ?>">
						<div class="lane__top">
							<span class="lane__label">
								<span class="lane__tag"><?php echo esc_html( strtoupper( $bjw_key ) ); ?></span>
								<span data-lane-name><?php echo esc_html( $bjw_tracks[0][ $bjw_key ]['label'] ); ?></span>
							</span>
							<span class="lane__buttons">
								<button type="button" class="lane__btn" data-lane-mute aria-pressed="false" title="<?php esc_attr_e( 'Mute', 'bjw-obsidian' ); ?>"><?php esc_html_e( 'M', 'bjw-obsidian' ); ?></button>
								<button type="button" class="lane__btn" data-lane-solo aria-pressed="false" title="<?php esc_attr_e( 'Solo', 'bjw-obsidian' ); ?>"><?php esc_html_e( 'S', 'bjw-obsidian' ); ?></button>
							</span>
						</div>

						<div class="lane__meter" data-lane-meter aria-hidden="true"></div>

						<label class="lane__gain">
							<span><?php esc_html_e( 'Gain', 'bjw-obsidian' ); ?></span>
							<input type="range" min="0" max="100" value="100" data-lane-gain aria-label="<?php echo esc_attr( sprintf( /* translators: %s: channel name. */ __( '%s gain', 'bjw-obsidian' ), $bjw_channel ) ); ?>">
						</label>
					</div>
				<?php endforeach; ?>
			</div>

			<label class="ab__crossfade">
				<span><?php esc_html_e( 'A', 'bjw-obsidian' ); ?></span>
				<input type="range" min="0" max="100" value="50" data-crossfade aria-label="<?php esc_attr_e( 'Crossfade between version A and version B', 'bjw-obsidian' ); ?>">
				<span><?php esc_html_e( 'B', 'bjw-obsidian' ); ?></span>
			</label>
		</div>
	</div>
</section>
