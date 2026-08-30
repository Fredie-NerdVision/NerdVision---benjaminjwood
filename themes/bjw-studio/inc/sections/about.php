<?php
/**
 * About.
 *
 * @package bjw-studio
 */

$bjw_portrait = bjw_field( 'about_portrait', function_exists( 'get_theme_mod' ) ? get_theme_mod( 'bjw_portrait' ) : '' );
?>
<section class="section" id="about">
	<div class="shell">
		<div class="rack reveal">
			<div class="rack__bar">
				<span class="rack__screws"><i></i><i></i></span>
				<span class="rack__name"><?php echo esc_html( bjw_field( 'about_eyebrow', __( 'My Story', 'bjw-studio' ) ) ); ?></span>
				<span class="rack__led"><?php esc_html_e( 'Bio', 'bjw-studio' ); ?></span>
			</div>

			<div class="rack__body">
				<div class="section-head">
					<h2><?php echo esc_html( bjw_field( 'about_heading', __( 'The Art of Sound', 'bjw-studio' ) ) ); ?></h2>
				</div>

				<div class="about-grid">
					<div class="portrait">
						<?php if ( $bjw_portrait ) : ?>
							<img src="<?php echo esc_url( $bjw_portrait ); ?>" alt="<?php esc_attr_e( 'Benjamin J. Wood', 'bjw-studio' ); ?>">
						<?php else : ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
								<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						<?php endif; ?>
					</div>

					<div class="about-copy">
						<p><?php echo esc_html( bjw_field( 'about_paragraph_1', __( 'Benjamin J. Wood is a versatile composer, producer, and audio engineer whose work is defined by emotional depth and technical precision. With over a decade of experience, he translates raw creative vision into compelling, polished sonic realities.', 'bjw-studio' ) ) ); ?></p>
						<p><?php echo esc_html( bjw_field( 'about_paragraph_2', __( "From scoring cinematic trailers and feature-length films to mixing and mastering albums for Grammy-nominated artists, Benjamin's dedication to sonic perfection has made him a trusted collaborator across multiple industries.", 'bjw-studio' ) ) ); ?></p>
						<p class="pull"><?php echo esc_html( bjw_field( 'about_quote', __( 'His philosophy is simple: Sound is the unseen emotional bridge between the story and the audience.', 'bjw-studio' ) ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
