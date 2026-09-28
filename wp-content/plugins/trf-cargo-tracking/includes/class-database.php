<?php
/**
 * Schema for the existing Trust validations table.
 *
 * Table name and columns stay as they are so current cargo records keep working:
 *   {prefix}wpwv_validations
 *     id, validation (tracking code), description, thumbnail_url, gallery_urls
 *
 * The unused {prefix}wpwv_serials warranty table is left untouched.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensures the tracking table exists without renaming or dropping data.
 */
class TRF_Cargo_Tracking_Database {

	const SCHEMA_VERSION = '2';

	/**
	 * Physical table used by Trust for "validation" records.
	 *
	 * @return string
	 */
	public static function table() {
		global $wpdb;

		return $wpdb->prefix . 'wpwv_validations';
	}

	/**
	 * Create or update the table in place. Never DROP.
	 */
	public static function ensure_schema() {
		if ( get_option( 'trf_cargo_tracking_schema' ) === self::SCHEMA_VERSION ) {
			return;
		}

		global $wpdb;

		$table           = self::table();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id int(11) NOT NULL AUTO_INCREMENT,
			validation varchar(255) NOT NULL,
			description text NOT NULL,
			thumbnail_url varchar(255) DEFAULT NULL,
			gallery_urls longtext DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY validation (validation)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		if ( $exists !== $table ) {
			return;
		}

		self::ensure_gallery_column();
		update_option( 'trf_cargo_tracking_schema', self::SCHEMA_VERSION );
	}

	/**
	 * Add gallery_urls when an older Trust schema only has thumbnail_url.
	 */
	private static function ensure_gallery_column() {
		global $wpdb;

		$table  = self::table();
		$column = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `{$table}` LIKE %s", 'gallery_urls' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( empty( $column ) ) {
			$wpdb->query( "ALTER TABLE `{$table}` ADD COLUMN `gallery_urls` LONGTEXT NULL AFTER `thumbnail_url`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$rows = $wpdb->get_results( "SELECT id, thumbnail_url FROM `{$table}` WHERE thumbnail_url IS NOT NULL AND thumbnail_url <> '' AND (gallery_urls IS NULL OR gallery_urls = '' OR gallery_urls = '[]')" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		foreach ( $rows as $row ) {
			$wpdb->update(
				$table,
				array( 'gallery_urls' => TRF_Cargo_Tracking_Gallery::encode( array( $row->thumbnail_url ) ) ),
				array( 'id' => (int) $row->id )
			);
		}
	}
}
