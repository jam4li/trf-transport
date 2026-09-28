<?php
/**
 * CRUD against {prefix}wpwv_validations.
 *
 * Column `validation` is the public tracking code.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tracking record repository.
 */
class TRF_Cargo_Tracking_Repository {

	/**
	 * Table name.
	 *
	 * @return string
	 */
	public static function table() {
		return TRF_Cargo_Tracking_Database::table();
	}

	/**
	 * Find by tracking code.
	 *
	 * @param string $code Tracking code.
	 * @return object|null
	 */
	public static function find_by_code( $code ) {
		global $wpdb;

		$code = trim( (string) $code );
		if ( '' === $code ) {
			return null;
		}

		$table = self::table();
		$row   = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE validation = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$code
			)
		);

		return $row ? $row : null;
	}

	/**
	 * Find by primary key.
	 *
	 * @param int $id Row ID.
	 * @return object|null
	 */
	public static function find( $id ) {
		global $wpdb;

		$id = absint( $id );
		if ( ! $id ) {
			return null;
		}

		$table = self::table();
		$row   = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE id = %d LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$id
			)
		);

		return $row ? $row : null;
	}

	/**
	 * Paginated list with optional search.
	 *
	 * @param array $args Query args.
	 * @return array{items:array,total:int}
	 */
	public static function query( array $args = array() ) {
		global $wpdb;

		$defaults = array(
			'search'   => '',
			'filter'   => 'validation',
			'per_page' => 20,
			'paged'    => 1,
		);
		$args     = wp_parse_args( $args, $defaults );

		$table    = self::table();
		$per_page = max( 1, (int) $args['per_page'] );
		$paged    = max( 1, (int) $args['paged'] );
		$offset   = ( $paged - 1 ) * $per_page;
		$search   = trim( (string) $args['search'] );
		$filter   = in_array( $args['filter'], array( 'validation', 'description' ), true ) ? $args['filter'] : 'validation';

		$where = '1=1';
		$params = array();

		if ( '' !== $search ) {
			$like    = '%' . $wpdb->esc_like( $search ) . '%';
			$where  .= " AND {$filter} LIKE %s";
			$params[] = $like;
		}

		$count_sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
		if ( $params ) {
			$total = (int) $wpdb->get_var( $wpdb->prepare( $count_sql, $params ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		} else {
			$total = (int) $wpdb->get_var( $count_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		$list_sql = "SELECT id, validation, description, thumbnail_url, gallery_urls FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d";
		$params[] = $per_page;
		$params[] = $offset;

		$items = $wpdb->get_results( $wpdb->prepare( $list_sql, $params ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return array(
			'items' => $items ? $items : array(),
			'total' => $total,
		);
	}

	/**
	 * Insert a tracking record.
	 *
	 * @param string   $code        Tracking code.
	 * @param string   $description Status / notes.
	 * @param string[] $urls        Gallery URLs.
	 * @return int|WP_Error New ID.
	 */
	public static function insert( $code, $description, array $urls = array() ) {
		global $wpdb;

		$code        = sanitize_text_field( $code );
		$description = sanitize_textarea_field( $description );

		if ( '' === $code || '' === $description ) {
			return new WP_Error( 'trf_track_required', __( 'کد رهگیری و توضیحات الزامی است.', 'trf-cargo-tracking' ) );
		}

		if ( self::find_by_code( $code ) ) {
			return new WP_Error( 'trf_track_duplicate', __( 'این کد رهگیری از قبل وجود دارد.', 'trf-cargo-tracking' ) );
		}

		$result = $wpdb->insert(
			self::table(),
			array_merge(
				array(
					'validation'  => $code,
					'description' => $description,
				),
				TRF_Cargo_Tracking_Gallery::db_fields_from_urls( $urls )
			)
		);

		if ( false === $result ) {
			return new WP_Error( 'trf_track_insert', __( 'ذخیره رکورد انجام نشد.', 'trf-cargo-tracking' ) );
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update a tracking record. The tracking code itself is not changed.
	 *
	 * @param int      $id          Row ID.
	 * @param string   $description Status / notes.
	 * @param string[] $urls        Gallery URLs.
	 * @return true|WP_Error
	 */
	public static function update( $id, $description, array $urls = array() ) {
		global $wpdb;

		$id          = absint( $id );
		$description = sanitize_textarea_field( $description );

		if ( ! $id || '' === $description ) {
			return new WP_Error( 'trf_track_required', __( 'توضیحات الزامی است.', 'trf-cargo-tracking' ) );
		}

		$result = $wpdb->update(
			self::table(),
			array_merge(
				array( 'description' => $description ),
				TRF_Cargo_Tracking_Gallery::db_fields_from_urls( $urls )
			),
			array( 'id' => $id )
		);

		if ( false === $result ) {
			return new WP_Error( 'trf_track_update', __( 'به‌روزرسانی انجام نشد.', 'trf-cargo-tracking' ) );
		}

		return true;
	}

	/**
	 * Delete one row.
	 *
	 * @param int $id Row ID.
	 * @return bool
	 */
	public static function delete( $id ) {
		global $wpdb;

		$id = absint( $id );
		if ( ! $id ) {
			return false;
		}

		return false !== $wpdb->delete( self::table(), array( 'id' => $id ), array( '%d' ) );
	}

	/**
	 * Delete many rows.
	 *
	 * @param int[] $ids IDs.
	 * @return int Deleted count.
	 */
	public static function delete_many( array $ids ) {
		$deleted = 0;
		foreach ( $ids as $id ) {
			if ( self::delete( $id ) ) {
				++$deleted;
			}
		}

		return $deleted;
	}

	/**
	 * Public lookup payload (same shape Trust used on vck_lookup).
	 *
	 * @param string $code Tracking code.
	 * @return array
	 */
	public static function lookup_payload( $code ) {
		$row = self::find_by_code( $code );
		if ( ! $row ) {
			$options = get_option( 'trf_cargo_tracking', array() );
			$message = isset( $options['not_found_message'] ) ? $options['not_found_message'] : __( 'کد رهگیری یافت نشد.', 'trf-cargo-tracking' );

			return array(
				'status'      => 'fail',
				'description' => $message,
			);
		}

		$gallery = array_map(
			array( 'TRF_Cargo_Tracking_Gallery', 'public_url' ),
			TRF_Cargo_Tracking_Gallery::from_row( $row )
		);
		$gallery = array_values( array_filter( $gallery ) );

		return array(
			'status'        => 'ok',
			'description'   => $row->description,
			'thumbnail_url' => isset( $gallery[0] ) ? $gallery[0] : '',
			'gallery'       => $gallery,
			'validation'    => $row->validation,
		);
	}
}
