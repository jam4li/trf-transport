<?php
/**
 * Public and admin AJAX.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX handlers.
 */
class TRF_Cargo_Tracking_Ajax {

	/**
	 * Register actions. vck_lookup is kept so old Trust frontends still resolve.
	 */
	public static function boot() {
		add_action( 'wp_ajax_trf_track_lookup', array( __CLASS__, 'lookup' ) );
		add_action( 'wp_ajax_nopriv_trf_track_lookup', array( __CLASS__, 'lookup' ) );

		add_action( 'wp_ajax_vck_lookup', array( __CLASS__, 'lookup' ) );
		add_action( 'wp_ajax_nopriv_vck_lookup', array( __CLASS__, 'lookup' ) );
	}

	/**
	 * Public tracking lookup.
	 */
	public static function lookup() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( $nonce && ! wp_verify_nonce( $nonce, 'trf_track_lookup' ) ) {
			wp_send_json(
				array(
					'status'      => 'fail',
					'description' => __( 'درخواست نامعتبر است. صفحه را تازه‌سازی کنید.', 'trf-cargo-tracking' ),
				),
				403
			);
		}

		$code = '';
		if ( isset( $_POST['code'] ) ) {
			$code = sanitize_text_field( wp_unslash( $_POST['code'] ) );
		} elseif ( isset( $_POST['validation'] ) ) {
			$code = sanitize_text_field( wp_unslash( $_POST['validation'] ) );
		}

		wp_send_json( TRF_Cargo_Tracking_Repository::lookup_payload( $code ) );
	}
}
