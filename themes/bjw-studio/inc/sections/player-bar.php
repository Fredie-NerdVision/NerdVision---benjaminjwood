<?php
/**
 * Sticky transport bar for the tandem player.
 *
 * @package bjw-studio
 */
?>
<div class="player" role="region" aria-label="<?php esc_attr_e( 'Audio player', 'bjw-studio' ); ?>">
	<div class="player__inner">
		<div class="player__meta">
			<p class="player__title" data-player-title>&nbsp;</p>
			<p class="player__artist" data-player-artist>&nbsp;</p>
		</div>

		<div class="player__controls">
			<button type="button" class="icon-btn" data-player-prev aria-label="<?php esc_attr_e( 'Previous track', 'bjw-studio' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M18 19V5l-9 7 9 7zM6 5v14" stroke-linecap="round" stroke-linejoin="round" /></svg>
			</button>

			<button type="button" class="icon-btn icon-btn--play" data-player-play aria-label="<?php esc_attr_e( 'Play', 'bjw-studio' ); ?>">
				<svg data-icon="play" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5z" /></svg>
				<svg data-icon="pause" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="display:none"><path d="M8 5h3v14H8zM13 5h3v14h-3z" /></svg>
			</button>

			<button type="button" class="icon-btn" data-player-next aria-label="<?php esc_attr_e( 'Next track', 'bjw-studio' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M6 5v14l9-7-9-7zM18 5v14" stroke-linecap="round" stroke-linejoin="round" /></svg>
			</button>

			<span class="player__ab">
				<button type="button" class="is-on" data-player-ab="a" aria-pressed="true"><?php esc_html_e( 'A', 'bjw-studio' ); ?></button>
				<button type="button" data-player-ab="b" aria-pressed="false"><?php esc_html_e( 'B', 'bjw-studio' ); ?></button>
			</span>
		</div>

		<div class="player__volume">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 9v6h4l5 4V5L8 9H4zM17 9.2a4 4 0 0 1 0 5.6M19.6 6.6a7.5 7.5 0 0 1 0 10.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
			<input type="range" min="0" max="100" value="100" data-player-volume aria-label="<?php esc_attr_e( 'Volume', 'bjw-studio' ); ?>">
		</div>

		<div class="player__timeline">
			<span class="player__time" data-player-current>0:00</span>
			<div class="scrub" data-player-scrub role="slider" tabindex="0" aria-label="<?php esc_attr_e( 'Seek', 'bjw-studio' ); ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="scrub__track"><div class="scrub__fill" data-player-fill></div></div>
			</div>
			<span class="player__time" data-player-duration>--:--</span>
		</div>
	</div>
</div>
