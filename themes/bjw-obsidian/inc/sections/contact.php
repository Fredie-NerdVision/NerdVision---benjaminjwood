<?php
/**
 * Contact.
 *
 * @package bjw-obsidian
 */

$bjw_status = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$bjw_action = function_exists( 'admin_url' ) ? admin_url( 'admin-post.php' ) : '#';
?>
<section class="section surface-marble-light" id="contact">
	<div class="shell">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php esc_html_e( 'Get In Touch', 'bjw-obsidian' ); ?></p>
			<h2><?php esc_html_e( "Let's Collaborate", 'bjw-obsidian' ); ?></h2>
			<div class="rule"></div>
		</div>

		<div class="contact-grid">
			<div class="contact-aside reveal">
				<p><?php esc_html_e( 'Ready to infuse your project with world-class audio? Contact me to discuss your needs.', 'bjw-obsidian' ); ?></p>
				<?php if ( 'sent' === $bjw_status ) : ?>
					<p><strong><?php esc_html_e( 'Thank you — your inquiry is on its way.', 'bjw-obsidian' ); ?></strong></p>
				<?php elseif ( $bjw_status && 'sent' !== $bjw_status ) : ?>
					<p><strong><?php esc_html_e( 'Something went wrong. Please try again.', 'bjw-obsidian' ); ?></strong></p>
				<?php endif; ?>
			</div>

			<form class="form reveal" method="post" action="<?php echo esc_url( $bjw_action ); ?>">
				<input type="hidden" name="action" value="bjw_inquiry">
				<?php if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'bjw_contact', 'bjw_contact_nonce' ); } ?>
				<p class="bjw-hp" style="position:absolute;left:-9999px;" aria-hidden="true">
					<label for="bjw_website"><?php esc_html_e( 'Website', 'bjw-obsidian' ); ?></label>
					<input type="text" id="bjw_website" name="bjw_website" tabindex="-1" autocomplete="off">
				</p>

				<div>
					<label for="bjw_name"><?php esc_html_e( 'Your Name', 'bjw-obsidian' ); ?></label>
					<input type="text" id="bjw_name" name="bjw_name" required>
				</div>

				<div>
					<label for="bjw_email"><?php esc_html_e( 'Your Email', 'bjw-obsidian' ); ?></label>
					<input type="email" id="bjw_email" name="bjw_email" required>
				</div>

				<div>
					<label for="bjw_service"><?php esc_html_e( 'Service of Interest (e.g., Composition, Mixing)', 'bjw-obsidian' ); ?></label>
					<select id="bjw_service" name="bjw_service">
						<option><?php esc_html_e( 'Composition', 'bjw-obsidian' ); ?></option>
						<option><?php esc_html_e( 'Production', 'bjw-obsidian' ); ?></option>
						<option><?php esc_html_e( 'Mixing & Mastering', 'bjw-obsidian' ); ?></option>
						<option><?php esc_html_e( 'Other Inquiry', 'bjw-obsidian' ); ?></option>
					</select>
				</div>

				<div>
					<label for="bjw_message"><?php esc_html_e( 'Tell me about your project...', 'bjw-obsidian' ); ?></label>
					<textarea id="bjw_message" name="bjw_message" rows="5" required></textarea>
				</div>

				<button type="submit" class="btn"><?php esc_html_e( 'Send Inquiry', 'bjw-obsidian' ); ?></button>
			</form>
		</div>
	</div>
</section>
