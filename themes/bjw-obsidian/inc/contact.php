<?php
/**
 * Contact form handling. Works without a form plugin, and steps aside if one
 * (Contact Form 7, Gravity Forms, ...) is used instead via a shortcode.
 *
 * @package bjw-obsidian
 */

if ( ! function_exists( 'add_action' ) ) {
	return;
}

/**
 * Handle a front-end inquiry submission.
 */
function bjw_handle_inquiry() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! isset( $_POST['bjw_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['bjw_contact_nonce'] ) ), 'bjw_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect ) . '#contact' );
		exit;
	}

	// Honeypot: real people leave this empty.
	if ( ! empty( $_POST['bjw_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'sent', $redirect ) . '#contact' );
		exit;
	}

	$name    = isset( $_POST['bjw_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bjw_name'] ) ) : '';
	$email   = isset( $_POST['bjw_email'] ) ? sanitize_email( wp_unslash( $_POST['bjw_email'] ) ) : '';
	$service = isset( $_POST['bjw_service'] ) ? sanitize_text_field( wp_unslash( $_POST['bjw_service'] ) ) : '';
	$message = isset( $_POST['bjw_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bjw_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'invalid', $redirect ) . '#contact' );
		exit;
	}

	$to      = apply_filters( 'bjw_inquiry_recipient', get_option( 'admin_email' ) );
	$subject = sprintf( '[%s] New inquiry from %s', get_bloginfo( 'name' ), $name );
	$body    = "Name: {$name}\nEmail: {$email}\nService: {$service}\n\n{$message}\n";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'inquiry', $sent ? 'sent' : 'error', $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_bjw_inquiry', 'bjw_handle_inquiry' );
add_action( 'admin_post_bjw_inquiry', 'bjw_handle_inquiry' );
