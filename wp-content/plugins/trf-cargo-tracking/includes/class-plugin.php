<?php
/**
 * Plugin bootstrap.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers hooks and deactivates Trust if it is still enabled.
 */
class TRF_Cargo_Tracking_Plugin {

	/**
	 * Singleton.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Activation: keep the existing validations table, never drop it.
	 */
	public static function activate() {
		TRF_Cargo_Tracking_Database::ensure_schema();
		TRF_Cargo_Tracking_Plugin::maybe_seed_options();
		self::deactivate_trust();
	}

	/**
	 * Attach runtime hooks.
	 */
	public function boot() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), 1 );
		add_action( 'init', array( 'TRF_Cargo_Tracking_Database', 'ensure_schema' ) );
		add_action( 'init', array( __CLASS__, 'maybe_seed_options' ) );

		TRF_Cargo_Tracking_Ajax::boot();
		TRF_Cargo_Tracking_Frontend::boot();
		TRF_Cargo_Tracking_Admin::boot();
	}

	/**
	 * Early load: stop Trust from stealing the tracking shortcode.
	 */
	public function on_plugins_loaded() {
		self::deactivate_trust();
		load_plugin_textdomain( 'trf-cargo-tracking', false, dirname( plugin_basename( TRF_TRACK_FILE ) ) . '/languages' );
	}

	/**
	 * Default settings, copying Trust's not-found copy when present.
	 */
	public static function maybe_seed_options() {
		if ( get_option( 'trf_cargo_tracking' ) ) {
			return;
		}

		$legacy     = get_option( 'wpwv_options', array() );
		$not_found  = '';
		if ( is_array( $legacy ) && ! empty( $legacy['invalid_validation_msg'] ) ) {
			$not_found = (string) $legacy['invalid_validation_msg'];
		}

		add_option(
			'trf_cargo_tracking',
			array(
				'not_found_message' => $not_found ? $not_found : 'کد رهگیری یافت نشد.',
				'code_label'        => 'کد رهگیری',
				'button_label'      => 'بررسی وضعیت بار',
			)
		);
	}

	/**
	 * Deactivate the warranty plugin if it is still active.
	 */
	private static function deactivate_trust() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$trust = 'wp-warranty-check/wp-warranty.php';
		if ( is_plugin_active( $trust ) ) {
			deactivate_plugins( $trust, true );
		}
	}
}
