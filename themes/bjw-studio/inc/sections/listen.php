<?php
/**
 * Tandem A/B mixer.
 *
 * @package bjw-studio
 */

$bjw_tracks = bjw_get_tracks();
?>
<section class="section" id="listen">
	<div class="shell">
		<div class="section-head reveal">
			<span class="mono"><?php echo esc_html( bjw_field( 'listen_eyebrow', __( 'A / B Listening Room', 'bjw-studio' ) ) ); ?></span>
			<h2><?php echo esc_html( bjw_field( 'listen_heading', __( 'Hear the Difference', 'bjw-studio' ) ) ); ?></h2>
			<p><?php echo esc_html( bjw_field( 'listen_intro', __( 'Both renders run on one playhead and only one is ever audible. Switch between them mid-phrase to hear exactly what changed.', 'bjw-studio' ) ) ); ?></p>
		</div>

		<div class="rack reveal" data-tandem>
			<div class="rack__bar">
				<span class="rack__screws"><i></i><i></i><i></i></span>
				<span class="rack__name"><?php esc_html_e( 'Tandem Mixer — 2 CH', 'bjw-studio' ); ?></span>
				<span class="rack__led"><?php esc_html_e( 'Locked', 'bjw-studio' ); ?></span>
			</div>

			<div class="rack__body">
				<div class="mixer__top">
					<span class="mono" style="color:var(--dim)"><?php esc_html_e( 'Load a track', 'bjw-studio' ); ?></span>
					<div class="mixer__tracks" role="group" aria-label="<?php esc_attr_e( 'Choose a track', 'bjw-studio' ); ?>">
						<?php foreach ( $bjw_tracks as $bjw_i => $bjw_track ) : ?>
							<button type="button" class="mixer__track<?php echo 0 === $bjw_i ? ' is-active' : ''; ?>" data-tandem-track="<?php echo esc_attr( $bjw_i ); ?>">
								<?php echo esc_html( $bjw_track['title'] ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="mixer__lanes">
					<?php foreach ( array( 'a' => __( 'Channel A', 'bjw-studio' ), 'b' => __( 'Channel B', 'bjw-studio' ) ) as $bjw_key => $bjw_channel ) : ?>
						<div class="lane" data-lane="<?php echo esc_attr( $bjw_key ); ?>">
							<div class="lane__top">
								<span class="lane__label">
									<span class="lane__id"><?php echo esc_html( strtoupper( $bjw_key ) ); ?></span>
									<span class="lane__name" data-lane-name><?php echo esc_html( $bjw_tracks[0][ $bjw_key ]['label'] ); ?></span>
								</span>
								<span class="lane__buttons">
									<button type="button" class="lane__btn<?php echo 'a' === $bjw_key ? ' is-on' : ''; ?>" data-lane-solo aria-pressed="<?php echo 'a' === $bjw_key ? 'true' : 'false'; ?>" title="<?php echo esc_attr( sprintf( /* translators: %s: channel name. */ __( 'Hear %s', 'bjw-studio' ), $bjw_channel ) ); ?>"><?php esc_html_e( 'Listen', 'bjw-studio' ); ?></button>
								</span>
							</div>

							<div class="lane__meter" data-lane-meter aria-hidden="true"></div>

							<label class="lane__gain">
								<span><?php esc_html_e( 'Gain', 'bjw-studio' ); ?></span>
								<input type="range" min="0" max="100" value="100" data-lane-gain aria-label="<?php echo esc_attr( sprintf( /* translators: %s: channel name. */ __( '%s gain', 'bjw-studio' ), $bjw_channel ) ); ?>">
							</label>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mixer__switch" role="group" aria-label="<?php esc_attr_e( 'Choose the version you hear', 'bjw-studio' ); ?>">
					<span class="mono"><?php esc_html_e( 'Now hearing', 'bjw-studio' ); ?></span>
					<?php foreach ( array( 'a', 'b' ) as $bjw_key ) : ?>
						<button type="button" class="mixer__switch-btn<?php echo 'a' === $bjw_key ? ' is-on' : ''; ?>" data-ab-switch="<?php echo esc_attr( $bjw_key ); ?>" aria-pressed="<?php echo 'a' === $bjw_key ? 'true' : 'false'; ?>">
							<?php echo esc_html( $bjw_tracks[0][ $bjw_key ]['label'] ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
