<?php
/**
 * Contact page form: nonce + admin-post + wp_mail.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle contact form POST.
 */
function trf_handle_contact_submit() {
	$referer  = wp_get_referer();
	$redirect = $referer ? $referer : home_url( '/' );

	if ( ! isset( $_POST['trf_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trf_contact_nonce'] ) ), 'trf_contact_submit' ) ) {
		trf_contact_redirect( $redirect, 'error' );
	}

	if ( ! empty( $_POST['trf_contact_website'] ) ) {
		trf_contact_redirect( $redirect, 'sent' );
	}

	$first   = isset( $_POST['trf_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trf_first_name'] ) ) : '';
	$last    = isset( $_POST['trf_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trf_last_name'] ) ) : '';
	$email   = isset( $_POST['trf_email'] ) ? sanitize_email( wp_unslash( $_POST['trf_email'] ) ) : '';
	$phone   = isset( $_POST['trf_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['trf_phone'] ) ) : '';
	$message = isset( $_POST['trf_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['trf_message'] ) ) : '';

	if ( '' === $first || '' === $last || '' === $phone || '' === $message || ! is_email( $email ) ) {
		trf_contact_redirect( $redirect, 'error' );
	}

	$full_name = trim( $first . ' ' . $last );
	$to        = trf_quote_recipient();
	$subject   = sprintf(
		/* translators: %s: sender name */
		__( 'Contact message from %s', 'trf-theme' ),
		$full_name
	);
	$body = sprintf(
		"%s: %s\n%s: %s\n%s: %s\n%s: %s\n%s:\n%s\n",
		__( 'First name', 'trf-theme' ),
		$first,
		__( 'Last name', 'trf-theme' ),
		$last,
		__( 'Email', 'trf-theme' ),
		$email,
		__( 'Phone', 'trf-theme' ),
		$phone,
		__( 'Message', 'trf-theme' ),
		$message
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $email,
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	trf_contact_redirect( $redirect, $sent ? 'sent' : 'error' );
}
add_action( 'admin_post_nopriv_trf_contact_submit', 'trf_handle_contact_submit' );
add_action( 'admin_post_trf_contact_submit', 'trf_handle_contact_submit' );

/**
 * Redirect back to the contact form with a status flag.
 *
 * @param string $url    Referer URL.
 * @param string $status sent|error.
 */
function trf_contact_redirect( $url, $status ) {
	$url = strtok( $url, '#' );
	$url = remove_query_arg( array( 'trf_contact', 'trf_quote', 'trf_quote_at' ), $url );
	$url = add_query_arg( 'trf_contact', $status, $url );

	wp_safe_redirect( $url . '#contact-form' );
	exit;
}

/**
 * Status from the current request for the contact form.
 *
 * @return string sent|error|empty
 */
function trf_contact_status() {
	if ( empty( $_GET['trf_contact'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}

	$status = sanitize_key( wp_unslash( $_GET['trf_contact'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( in_array( $status, array( 'sent', 'error' ), true ) ) {
		return $status;
	}

	return '';
}

/**
 * Parse contact-page HTML into offices and a company email.
 *
 * Expected list items look like:
 * "دفتر مشهد: …", "تلفن: ۰۵۱…", "ایمیل: info@…"
 *
 * @param string $html Raw or filtered post content.
 * @return array{offices: array<int, array{title:string,address:string,phone:string}>, email: string}
 */
function trf_contact_parse_details( $html ) {
	$offices = array();
	$email   = '';
	$current = null;

	if ( '' === trim( wp_strip_all_tags( (string) $html ) ) ) {
		return array(
			'offices' => $offices,
			'email'   => $email,
		);
	}

	preg_match_all( '/<li\b[^>]*>(.*?)<\/li>/is', $html, $matches );

	foreach ( $matches[1] as $item ) {
		$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $item ) ) );
		if ( '' === $text || 'خانه' === $text ) {
			continue;
		}

		if ( preg_match( '/^ایمیل\s*[:：]\s*(.+)$/u', $text, $found ) ) {
			$candidate = sanitize_email( trf_ascii_digits( trim( $found[1] ) ) );
			if ( is_email( $candidate ) ) {
				$email = $candidate;
			}
			continue;
		}

		if ( preg_match( '/^تلفن\s*[:：]\s*(.+)$/u', $text, $found ) ) {
			if ( is_array( $current ) ) {
				$current['phone'] = trim( $found[1] );
			}
			continue;
		}

		if ( preg_match( '/^(دفتر\s+[^:：]+)[:：]\s*(.+)$/u', $text, $found ) ) {
			if ( is_array( $current ) ) {
				$offices[] = $current;
			}
			$current = array(
				'title'   => trim( $found[1] ),
				'address' => trim( $found[2] ),
				'phone'   => '',
			);
		}
	}

	if ( is_array( $current ) ) {
		$offices[] = $current;
	}

	return array(
		'offices' => $offices,
		'email'   => $email,
	);
}
