<?php
/**
 * Public tracking form.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes and assets.
 */
class TRF_Cargo_Tracking_Frontend {

	/**
	 * Register shortcodes and assets.
	 */
	public static function boot() {
		add_shortcode( 'trf_cargo_tracking', [ __CLASS__, 'shortcode' ] );
		add_shortcode( 'validation_check_lookup', [ __CLASS__, 'shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'register_assets' ] );
		add_filter( 'the_content', [ __CLASS__, 'maybe_append_to_tracking_page' ] );
	}

	/**
	 * Register (not enqueue) front-end assets.
	 */
	public static function register_assets() {
		$style  = TRF_TRACK_URL . 'assets/css/frontend.css';
		$script = TRF_TRACK_URL . 'assets/js/frontend.js';
		wp_register_style( 'trf-cargo-tracking', $style, [], TRF_TRACK_VERSION );
		wp_register_script( 'trf-cargo-tracking', $script, [], TRF_TRACK_VERSION, true );
	}

	/**
	 * Tracking form markup.
	 *
	 * @param mixed $atts Shortcode atts.
	 * @return string
	 */
	public static function shortcode( $atts = null ) {
		wp_enqueue_style( 'trf-cargo-tracking' );
		wp_enqueue_script( 'trf-cargo-tracking' );

		$options = get_option( 'trf_cargo_tracking' );
		if ( ! is_array( $options ) ) {
			$options = [];
		}

		$label  = '';
		$button = '';
		if ( isset( $options['code_label'] ) ) {
			$label = (string) $options['code_label'];
		}
		if ( isset( $options['button_label'] ) ) {
			$button = (string) $options['button_label'];
		}
		if ( '' === $label ) {
			$label = __( 'کد رهگیری', 'trf-cargo-tracking' );
		}
		if ( '' === $button ) {
			$button = __( 'بررسی وضعیت بار', 'trf-cargo-tracking' );
		}

		wp_localize_script(
			'trf-cargo-tracking',
			'trfCargoTracking',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'trf_track_lookup' ),
				'action'    => 'trf_track_lookup',
				'codeLabel' => $label,
			)
		);

		$code   = '';
		$result = null;
		if ( isset( $_GET['trf_track'] ) ) {
			$code   = sanitize_text_field( wp_unslash( $_GET['trf_track'] ) );
			$result = TRF_Cargo_Tracking_Repository::lookup_payload( $code );
		}

		ob_start();
		include TRF_TRACK_DIR . 'templates/frontend/form.php';
		return (string) ob_get_clean();
	}

	/**
	 * On the published tracking page, show the form when the shortcode is missing.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function maybe_append_to_tracking_page( $content ) {
		if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( has_shortcode( $content, 'trf_cargo_tracking' ) || has_shortcode( $content, 'validation_check_lookup' ) ) {
			return $content;
		}

		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$want = 'tracking';
		if ( function_exists( 'trf_mod' ) ) {
			$want = trf_mod( 'trf_tracking_slug' );
		}
		if ( $slug !== $want ) {
			return $content;
		}

		return $content . self::shortcode();
	}
}
