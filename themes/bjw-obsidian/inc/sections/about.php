<?php
/**
 * About.
 *
 * @package bjw-obsidian
 */
?>
<section class="section surface-ink-2" id="about">
	<div class="shell">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php esc_html_e( 'My Story', 'bjw-obsidian' ); ?></p>
			<h2><?php esc_html_e( 'The Art of Sound', 'bjw-obsidian' ); ?></h2>
			<div class="rule"></div>
		</div>

		<div class="about-grid">
			<div class="portrait reveal">
				<?php if ( function_exists( 'get_theme_mod' ) && get_theme_mod( 'bjw_portrait' ) ) : ?>
					<img src="<?php echo esc_url( get_theme_mod( 'bjw_portrait' ) ); ?>" alt="<?php esc_attr_e( 'Benjamin J. Wood', 'bjw-obsidian' ); ?>">
				<?php else : ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
						<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				<?php endif; ?>
			</div>

			<div class="about-copy reveal">
				<p><?php esc_html_e( 'Benjamin J. Wood is a versatile composer, producer, and audio engineer whose work is defined by emotional depth and technical precision. With over a decade of experience, he translates raw creative vision into compelling, polished sonic realities.', 'bjw-obsidian' ); ?></p>
				<p><?php esc_html_e( "From scoring cinematic trailers and feature-length films to mixing and mastering albums for Grammy-nominated artists, Benjamin's dedication to sonic perfection has made him a trusted collaborator across multiple industries.", 'bjw-obsidian' ); ?></p>
				<p class="pull"><?php esc_html_e( 'His philosophy is simple: Sound is the unseen emotional bridge between the story and the audience.', 'bjw-obsidian' ); ?></p>
			</div>
		</div>
	</div>
</section>
