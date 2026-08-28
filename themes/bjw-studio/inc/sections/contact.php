<?php
/**
 * Contact.
 *
 * @package bjw-studio
 */

$bjw_status = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$bjw_action = function_exists( 'admin_url' ) ? admin_url( 'admin-post.php' ) : '#';
?>
<section class="section" id="contact">
	<div class="shell">
		<div class="rack reveal">
			<div class="rack__bar">
				<span class="rack__screws"><i></i><i></i></span>
				<span class="rack__name"><?php esc_html_e( 'Get In Touch', 'bjw-studio' ); ?></span>
				<span class="rack__led"><?php esc_html_e( 'Open', 'bjw-studio' ); ?></span>
			</div>

			<div class="rack__body">
				<div class="section-head">
					<h2><?php esc_html_e( "Let's Collaborate", 'bjw-studio' ); ?></h2>
				</div>

				<div class="contact-grid">
					<div class="contact-aside">
						<p><?php esc_html_e( 'Ready to infuse your project with world-class audio? Contact me to discuss your needs.', 'bjw-studio' ); ?></p>
						<?php if ( 'sent' === $bjw_status ) : ?>
							<p class="mono" style="color:var(--moss)"><?php esc_html_e( 'Thank you — your inquiry is on its way.', 'bjw-studio' ); ?></p>
						<?php elseif ( $bjw_status ) : ?>
							<p class="mono" style="color:var(--clay)"><?php esc_html_e( 'Something went wrong. Please try again.', 'bjw-studio' ); ?></p>
						<?php endif; ?>
						<span class="mono"><?php esc_html_e( 'Composition / Production / Mixing & Mastering', 'bjw-studio' ); ?></span>
					</div>

					<form class="form" method="post" action="<?php echo esc_url( $bjw_action ); ?>">
						<input type="hidden" name="action" value="bjw_inquiry">
						<?php if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'bjw_contact', 'bjw_contact_nonce' ); } ?>
						<p class="bjw-hp" style="position:absolute;left:-9999px;" aria-hidden="true">
							<label for="bjw_website"><?php esc_html_e( 'Website', 'bjw-studio' ); ?></label>
							<input type="text" id="bjw_website" name="bjw_website" tabindex="-1" autocomplete="off">
						</p>

						<div>
							<label for="bjw_name"><?php esc_html_e( 'Your Name', 'bjw-studio' ); ?></label>
							<input type="text" id="bjw_name" name="bjw_name" required>
						</div>

						<div>
							<label for="bjw_email"><?php esc_html_e( 'Your Email', 'bjw-studio' ); ?></label>
							<input type="email" id="bjw_email" name="bjw_email" required>
						</div>

						<div>
							<label for="bjw_service"><?php esc_html_e( 'Service of Interest (e.g., Composition, Mixing)', 'bjw-studio' ); ?></label>
							<select id="bjw_service" name="bjw_service">
								<option><?php esc_html_e( 'Composition', 'bjw-studio' ); ?></option>
								<option><?php esc_html_e( 'Production', 'bjw-studio' ); ?></option>
								<option><?php esc_html_e( 'Mixing & Mastering', 'bjw-studio' ); ?></option>
								<option><?php esc_html_e( 'Other Inquiry', 'bjw-studio' ); ?></option>
							</select>
						</div>

						<div>
							<label for="bjw_message"><?php esc_html_e( 'Tell me about your project...', 'bjw-studio' ); ?></label>
							<textarea id="bjw_message" name="bjw_message" rows="5" required></textarea>
						</div>

						<button type="submit" class="btn"><?php esc_html_e( 'Send Inquiry', 'bjw-studio' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
