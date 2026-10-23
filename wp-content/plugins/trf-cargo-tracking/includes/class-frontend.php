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
		add_shortcode( 'trf_cargo_tracking', array( __CLASS__, 'shortcode' ) );
		add_shortcode( 'validation_check_lookup', array( __CLASS__, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_filter( 'the_content', array( __CLASS__, 'maybe_append_to_tracking_page' ) );
	}

	/**
	 * Register (not enqueue) front-end assets.
	 */
	public static function register_assets() {
		$style  = TRF_TRACK_URL . 'assets/css/frontend.css';
		$script = TRF_TRACK_URL . 'assets/js/frontend.js';
		wp_register_style( 'trf-cargo-tracking', $style, array(), TRF_TRACK_VERSION );
		wp_register_script( 'trf-cargo-tracking', $script, array(), TRF_TRACK_VERSION, true );
	}

	/**
	 * Front-end copy used by PHP and JS.
	 *
	 * @return array
	 */
	public static function i18n() {
		return array(
			'busy'      => __( 'در حال بررسی…', 'trf-cargo-tracking' ),
			'loading'   => __( 'در حال دریافت وضعیت بار…', 'trf-cargo-tracking' ),
			'notFound'  => __( 'کد رهگیری یافت نشد.', 'trf-cargo-tracking' ),
			'error'     => __( 'خطایی رخ داد. دوباره تلاش کنید.', 'trf-cargo-tracking' ),
			'kicker'    => __( 'وضعیت محموله', 'trf-cargo-tracking' ),
			'found'     => __( 'محموله یافت شد', 'trf-cargo-tracking' ),
			'latest'    => __( 'آخرین وضعیت', 'trf-cargo-tracking' ),
			'path'      => __( 'مسیر وضعیت', 'trf-cargo-tracking' ),
			'updates'   => __( 'به‌روزرسانی', 'trf-cargo-tracking' ),
			'documents' => __( 'مدارک', 'trf-cargo-tracking' ),
			'viewFile'  => __( 'مشاهده / دانلود', 'trf-cargo-tracking' ),
			'copy'      => __( 'کپی کد', 'trf-cargo-tracking' ),
			'copied'    => __( 'کپی شد', 'trf-cargo-tracking' ),
			'idleTitle' => __( 'آماده استعلام', 'trf-cargo-tracking' ),
			'idle'      => __( 'کد رهگیری را وارد کنید تا آخرین وضعیت، تاریخ‌ها و مدارک محموله اینجا نمایش داده شود.', 'trf-cargo-tracking' ),
			'errorHint' => __( 'کد را بدون فاصله و دقیقاً مطابق بارنامه وارد کنید.', 'trf-cargo-tracking' ),
		);
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
			$options = array();
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
				'i18n'      => self::i18n(),
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

	/**
	 * Short headline from a status description.
	 *
	 * @param string $description Status text.
	 * @return string
	 */
	public static function headline_from_description( $description ) {
		$text = trim( wp_strip_all_tags( (string) $description ) );
		if ( '' === $text ) {
			return __( 'محموله یافت شد', 'trf-cargo-tracking' );
		}

		$lines = preg_split( '/\R/u', $text );
		$line  = trim( (string) $lines[0] );
		if ( function_exists( 'mb_strlen' ) && mb_strlen( $line ) > 88 ) {
			return mb_substr( $line, 0, 88 ) . '…';
		}
		if ( strlen( $line ) > 88 ) {
			return substr( $line, 0, 88 ) . '…';
		}

		return $line;
	}

	/**
	 * Persian label for the number of updates.
	 *
	 * @param int $count Count.
	 * @return string
	 */
	public static function updates_label( $count ) {
		$count  = max( 1, (int) $count );
		$digits = TRF_Cargo_Tracking_Jalali::to_persian_digits( (string) $count );

		return sprintf(
			/* translators: %s: number of status rows */
			_n( '%s به‌روزرسانی', '%s به‌روزرسانی', $count, 'trf-cargo-tracking' ),
			$digits
		);
	}

	/**
	 * Normalize lookup entries for display.
	 *
	 * @param array $result Lookup payload.
	 * @return array
	 */
	public static function entries_from_result( array $result ) {
		if ( ! empty( $result['entries'] ) && is_array( $result['entries'] ) ) {
			return $result['entries'];
		}

		return array(
			array(
				'description' => isset( $result['description'] ) ? $result['description'] : '',
				'gallery'     => ( ! empty( $result['gallery'] ) && is_array( $result['gallery'] ) ) ? $result['gallery'] : array(),
				'event_date'  => isset( $result['event_date'] ) ? $result['event_date'] : '',
			),
		);
	}
}
