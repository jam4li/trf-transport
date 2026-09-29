<?php
/**
 * Plugin Name: TRF Cargo Tracking
 * Description: Cargo tracking for TRF Transport. Reads and writes the existing wpwv_validations table used previously by Trust.
 * Version: 1.1.2
 * Author: TRF Transport
 * Text Domain: trf-cargo-tracking
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRF_TRACK_VERSION', '1.1.2' );
define( 'TRF_TRACK_FILE', __FILE__ );
define( 'TRF_TRACK_DIR', plugin_dir_path( __FILE__ ) );
define( 'TRF_TRACK_URL', plugin_dir_url( __FILE__ ) );

require_once TRF_TRACK_DIR . 'includes/class-gallery.php';
require_once TRF_TRACK_DIR . 'includes/class-database.php';
require_once TRF_TRACK_DIR . 'includes/class-repository.php';
require_once TRF_TRACK_DIR . 'includes/class-ajax.php';
require_once TRF_TRACK_DIR . 'includes/class-frontend.php';
require_once TRF_TRACK_DIR . 'includes/class-import.php';
require_once TRF_TRACK_DIR . 'includes/class-admin.php';
require_once TRF_TRACK_DIR . 'includes/class-plugin.php';

register_activation_hook( __FILE__, array( 'TRF_Cargo_Tracking_Plugin', 'activate' ) );

TRF_Cargo_Tracking_Plugin::instance()->boot();
