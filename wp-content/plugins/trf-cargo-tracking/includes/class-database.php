<?php
/**
 * Schema for the existing Trust validations table.
 *
 * Table name and columns stay as they are so current cargo records keep working:
 *   {prefix}wpwv_validations
 *     id, validation (tracking code), description, thumbnail_url, gallery_urls,
 *     event_date, created_at
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

	const SCHEMA_VERSION = '3';

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
			event_date date DEFAULT NULL,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY validation (validation)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		if ( $exists !== $table ) {
			return;
		}

		self::ensure_gallery_column();
		self::ensure_date_columns();
		self::ensure_non_unique_validation_index();
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

	/**
	 * Add event_date and created_at without rewriting existing row values.
	 */
	private static function ensure_date_columns() {
		global $wpdb;

		$table = self::table();

		$event = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `{$table}` LIKE %s", 'event_date' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( empty( $event ) ) {
			$wpdb->query( "ALTER TABLE `{$table}` ADD COLUMN `event_date` DATE NULL AFTER `gallery_urls`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$created = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `{$table}` LIKE %s", 'created_at' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( empty( $created ) ) {
			$wpdb->query( "ALTER TABLE `{$table}` ADD COLUMN `created_at` DATETIME NULL AFTER `event_date`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

	/**
	 * Allow more than one update row per tracking code.
	 *
	 * dbDelta does not drop UNIQUE KEY validation, so this is done explicitly.
	 */
	private static function ensure_non_unique_validation_index() {
		global $wpdb;

		$table   = self::table();
		$indexes = $wpdb->get_results( "SHOW INDEX FROM `{$table}`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$has_non_unique = false;
		$unique_names   = array();

		foreach ( $indexes as $index ) {
			$name   = isset( $index->Key_name ) ? (string) $index->Key_name : '';
			$column = isset( $index->Column_name ) ? (string) $index->Column_name : '';
			if ( '' === $name || 'PRIMARY' === $name || 'validation' !== $column ) {
				continue;
			}

			$non_unique = isset( $index->Non_unique ) ? (int) $index->Non_unique : 1;
			if ( 0 === $non_unique ) {
				$unique_names[] = $name;
			} else {
				$has_non_unique = true;
			}
		}

		foreach ( array_unique( $unique_names ) as $name ) {
			$wpdb->query( "ALTER TABLE `{$table}` DROP INDEX `{$name}`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		if ( ! $has_non_unique ) {
			$wpdb->query( "ALTER TABLE `{$table}` ADD INDEX `validation` (`validation`)" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}
}
