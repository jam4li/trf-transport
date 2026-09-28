<?php
/**
 * Footer quote form: nonce + admin-post + wp_mail.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recipient for quote emails.
 *
 * @return string
 */
function trf_quote_recipient() {
	$email = trf_mod( 'trf_quote_email' );
	if ( is_email( $email ) ) {
		return $email;
	}

	return (string) get_option( 'admin_email' );
}

/**
 * Handle quote form POST.
 */
function trf_handle_quote_submit() {
	$referer = wp_get_referer();
	$redirect = $referer ? $referer : home_url( '/' );

	if ( ! isset( $_POST['trf_quote_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trf_quote_nonce'] ) ), 'trf_quote_submit' ) ) {
		trf_quote_redirect( $redirect, 'error' );
	}

	// Honeypot: bots that fill this are discarded silently.
	if ( ! empty( $_POST['trf_website'] ) ) {
		trf_quote_redirect( $redirect, 'sent' );
	}

	$name    = isset( $_POST['trf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trf_name'] ) ) : '';
	$phone   = isset( $_POST['trf_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['trf_phone'] ) ) : '';
	$message = isset( $_POST['trf_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['trf_message'] ) ) : '';

	if ( '' === $name || '' === $phone ) {
		trf_quote_redirect( $redirect, 'error' );
	}

	$to      = trf_quote_recipient();
	$subject = sprintf(
		/* translators: %s: sender name */
		__( 'Quote request from %s', 'trf-theme' ),
		$name
	);
	$body = sprintf(
		"%s: %s\n%s: %s\n%s:\n%s\n",
		__( 'Name', 'trf-theme' ),
		$name,
		__( 'Phone', 'trf-theme' ),
		$phone,
		__( 'Message', 'trf-theme' ),
		$message
	);

	$sent = wp_mail(
		$to,
		$subject,
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);

	trf_quote_redirect( $redirect, $sent ? 'sent' : 'error' );
}
add_action( 'admin_post_nopriv_trf_quote_submit', 'trf_handle_quote_submit' );
add_action( 'admin_post_trf_quote_submit', 'trf_handle_quote_submit' );

/**
 * Redirect back to the form with a status flag.
 *
 * @param string $url    Referer URL.
 * @param string $status sent|error.
 */
function trf_quote_redirect( $url, $status ) {
	$url = strtok( $url, '#' );
	$url = remove_query_arg( 'trf_quote', $url );
	$url = add_query_arg( 'trf_quote', $status, $url );
	wp_safe_redirect( $url . '#quote' );
	exit;
}

/**
 * Status from the current request.
 *
 * @return string sent|error|empty
 */
function trf_quote_status() {
	if ( empty( $_GET['trf_quote'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}

	$status = sanitize_key( wp_unslash( $_GET['trf_quote'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( in_array( $status, array( 'sent', 'error' ), true ) ) {
		return $status;
	}

	return '';
}
