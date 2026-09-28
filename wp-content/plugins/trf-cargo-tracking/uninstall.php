<?php
/**
 * Uninstall handler.
 *
 * Does not drop wpwv_validations or wpwv_serials. Those tables hold production
 * cargo records (and leftover Trust warranty rows) and must be removed only by
 * an explicit database operation.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'trf_cargo_tracking' );
delete_option( 'trf_cargo_tracking_schema' );
