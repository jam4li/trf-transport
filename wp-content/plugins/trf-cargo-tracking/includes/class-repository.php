<?php
/**
 * CRUD against {prefix}wpwv_validations.
 *
 * Column `validation` is the public tracking code. Multiple rows per code
 * are update entries (description, documents, public date, recorded-at).
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
	 * Latest row for a tracking code.
	 *
	 * @param string $code Tracking code.
	 * @return object|null
	 */
	public static function find_by_code( $code ) {
		$rows = self::find_all_by_code( $code );

		return $rows ? $rows[0] : null;
	}

	/**
	 * All update rows for a tracking code, newest public date first.
	 *
	 * @param string $code Tracking code.
	 * @return object[]
	 */
	public static function find_all_by_code( $code ) {
		global $wpdb;

		$code = trim( (string) $code );
		if ( '' === $code ) {
			return array();
		}

		$table = self::table();
		$rows  = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE validation = %s ORDER BY (event_date IS NULL) ASC, event_date DESC, id DESC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$code
			)
		);

		return $rows ? $rows : array();
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
	 * Paginated list grouped by tracking code.
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

		$where  = '1=1';
		$params = array();

		if ( '' !== $search ) {
			$like     = '%' . $wpdb->esc_like( $search ) . '%';
			$where   .= " AND {$filter} LIKE %s";
			$params[] = $like;
		}

		$count_sql = "SELECT COUNT(DISTINCT validation) FROM {$table} WHERE {$where}";
		if ( $params ) {
			$total = (int) $wpdb->get_var( $wpdb->prepare( $count_sql, $params ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		} else {
			$total = (int) $wpdb->get_var( $count_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		$list_sql = "SELECT validation, COUNT(*) AS update_count, MAX(id) AS sort_id FROM {$table} WHERE {$where} GROUP BY validation ORDER BY sort_id DESC LIMIT %d OFFSET %d";
		$list_params = $params;
		$list_params[] = $per_page;
		$list_params[] = $offset;

		$groups = $wpdb->get_results( $wpdb->prepare( $list_sql, $list_params ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $groups ) {
			return array(
				'items' => array(),
				'total' => $total,
			);
		}

		$codes = array();
		$counts = array();
		foreach ( $groups as $group ) {
			$codes[] = $group->validation;
			$counts[ $group->validation ] = (int) $group->update_count;
		}

		$placeholders = implode( ',', array_fill( 0, count( $codes ), '%s' ) );
		$latest_sql   = "SELECT v.* FROM {$table} v
			INNER JOIN (
				SELECT validation, SUBSTRING_INDEX(GROUP_CONCAT(id ORDER BY (event_date IS NULL) ASC, event_date DESC, id DESC), ',', 1) AS latest_id
				FROM {$table}
				WHERE validation IN ({$placeholders})
				GROUP BY validation
			) latest ON v.id = latest.latest_id";

		$items = $wpdb->get_results( $wpdb->prepare( $latest_sql, $codes ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$items = $items ? $items : array();

		$by_code = array();
		foreach ( $items as $item ) {
			$item->update_count = isset( $counts[ $item->validation ] ) ? $counts[ $item->validation ] : 1;
			$by_code[ $item->validation ] = $item;
		}

		$ordered = array();
		foreach ( $codes as $code ) {
			if ( isset( $by_code[ $code ] ) ) {
				$ordered[] = $by_code[ $code ];
			}
		}

		return array(
			'items' => $ordered,
			'total' => $total,
		);
	}

	/**
	 * Insert one update row (CSV and new entries). Duplicate codes are allowed.
	 *
	 * @param string      $code        Tracking code.
	 * @param string      $description Status / notes.
	 * @param string[]    $urls        Gallery URLs.
	 * @param string|null $event_date  Gregorian Y-m-d or null.
	 * @return int|WP_Error New ID.
	 */
	public static function insert( $code, $description, array $urls = array(), $event_date = null ) {
		global $wpdb;

		$code        = sanitize_text_field( $code );
		$description = sanitize_textarea_field( $description );
		$event_date  = self::normalize_event_date( $event_date );

		if ( '' === $code || '' === $description ) {
			return new WP_Error( 'trf_track_required', __( 'کد رهگیری و توضیحات الزامی است.', 'trf-cargo-tracking' ) );
		}

		$data = array_merge(
			array(
				'validation'  => $code,
				'description' => $description,
				'created_at'  => current_time( 'mysql' ),
			),
			TRF_Cargo_Tracking_Gallery::db_fields_from_urls( $urls )
		);
		if ( null !== $event_date ) {
			$data['event_date'] = $event_date;
		}

		$result = $wpdb->insert( self::table(), $data );

		if ( false === $result ) {
			return new WP_Error( 'trf_track_insert', __( 'ذخیره رکورد انجام نشد.', 'trf-cargo-tracking' ) );
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update one update row. Tracking code and created_at are not changed.
	 *
	 * @param int         $id          Row ID.
	 * @param string      $description Status / notes.
	 * @param string[]    $urls        Gallery URLs.
	 * @param string|null $event_date  Gregorian Y-m-d or null.
	 * @return true|WP_Error
	 */
	public static function update( $id, $description, array $urls = array(), $event_date = null ) {
		global $wpdb;

		$id          = absint( $id );
		$description = sanitize_textarea_field( $description );
		$event_date  = self::normalize_event_date( $event_date );

		if ( ! $id || '' === $description ) {
			return new WP_Error( 'trf_track_required', __( 'توضیحات الزامی است.', 'trf-cargo-tracking' ) );
		}

		$fields = array_merge(
			array( 'description' => $description ),
			TRF_Cargo_Tracking_Gallery::db_fields_from_urls( $urls )
		);
		if ( null !== $event_date ) {
			$fields['event_date'] = $event_date;
		}

		$result = $wpdb->update(
			self::table(),
			$fields,
			array( 'id' => $id )
		);

		if ( false === $result ) {
			return new WP_Error( 'trf_track_update', __( 'به‌روزرسانی انجام نشد.', 'trf-cargo-tracking' ) );
		}

		return true;
	}

	/**
	 * Create or update every row of a tracking code.
	 *
	 * @param string $code     Tracking code.
	 * @param array  $entries  List of id/description/urls/event_date.
	 * @param bool   $is_new   Whether this is a new tracking code.
	 * @return string|WP_Error Tracking code on success.
	 */
	public static function sync_shipment( $code, array $entries, $is_new = false ) {
		$code = sanitize_text_field( $code );
		if ( '' === $code ) {
			return new WP_Error( 'trf_track_required', __( 'کد رهگیری الزامی است.', 'trf-cargo-tracking' ) );
		}

		$existing = self::find_all_by_code( $code );
		if ( $is_new && $existing ) {
			return new WP_Error( 'trf_track_duplicate', __( 'این کد رهگیری از قبل وجود دارد.', 'trf-cargo-tracking' ) );
		}
		if ( ! $is_new && ! $existing ) {
			return new WP_Error( 'trf_track_missing', __( 'این محموله یافت نشد.', 'trf-cargo-tracking' ) );
		}

		$normalized = array();
		foreach ( $entries as $entry ) {
			if ( ! is_array( $entry ) ) {
				continue;
			}

			$id          = isset( $entry['id'] ) ? absint( $entry['id'] ) : 0;
			$description = isset( $entry['description'] ) ? sanitize_textarea_field( $entry['description'] ) : '';
			$urls        = isset( $entry['urls'] ) && is_array( $entry['urls'] ) ? $entry['urls'] : array();
			$raw_date    = isset( $entry['event_date'] ) ? trim( (string) $entry['event_date'] ) : '';
			$event_date  = self::normalize_event_date( $raw_date );

			if ( '' === $description && empty( $urls ) && '' === $raw_date && ! $id ) {
				continue;
			}

			if ( '' === $description ) {
				return new WP_Error( 'trf_track_required', __( 'توضیحات هر ردیف الزامی است.', 'trf-cargo-tracking' ) );
			}
			if ( '' === $raw_date ) {
				return new WP_Error( 'trf_track_required', __( 'تاریخ نمایش هر ردیف الزامی است.', 'trf-cargo-tracking' ) );
			}
			if ( null === $event_date ) {
				return new WP_Error( 'trf_track_invalid_date', __( 'تاریخ نمایش نامعتبر است.', 'trf-cargo-tracking' ) );
			}

			$normalized[] = array(
				'id'          => $id,
				'description' => $description,
				'urls'        => $urls,
				'event_date'  => $event_date,
			);
		}

		if ( empty( $normalized ) ) {
			return new WP_Error( 'trf_track_required', __( 'حداقل یک ردیف با توضیحات لازم است.', 'trf-cargo-tracking' ) );
		}

		$existing_ids = array();
		foreach ( $existing as $row ) {
			$existing_ids[ (int) $row->id ] = true;
		}

		$keep_ids = array();
		foreach ( $normalized as $entry ) {
			$id = $entry['id'];
			if ( $id && isset( $existing_ids[ $id ] ) ) {
				$updated = self::update( $id, $entry['description'], $entry['urls'], $entry['event_date'] );
				if ( is_wp_error( $updated ) ) {
					return $updated;
				}
				$keep_ids[] = $id;
				continue;
			}

			$inserted = self::insert( $code, $entry['description'], $entry['urls'], $entry['event_date'] );
			if ( is_wp_error( $inserted ) ) {
				return $inserted;
			}
			$keep_ids[] = (int) $inserted;
		}

		foreach ( $existing as $row ) {
			$row_id = (int) $row->id;
			if ( ! in_array( $row_id, $keep_ids, true ) ) {
				self::delete( $row_id );
			}
		}

		return $code;
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
	 * Delete every row for a tracking code.
	 *
	 * @param string $code Tracking code.
	 * @return int Deleted count.
	 */
	public static function delete_by_code( $code ) {
		global $wpdb;

		$code = sanitize_text_field( $code );
		if ( '' === $code ) {
			return 0;
		}

		$deleted = $wpdb->delete( self::table(), array( 'validation' => $code ), array( '%s' ) );

		return false === $deleted ? 0 : (int) $deleted;
	}

	/**
	 * Delete many tracking codes (all of their rows).
	 *
	 * @param string[] $codes Codes.
	 * @return int Deleted shipment count.
	 */
	public static function delete_many_codes( array $codes ) {
		$deleted = 0;
		foreach ( $codes as $code ) {
			if ( self::delete_by_code( $code ) ) {
				++$deleted;
			}
		}

		return $deleted;
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
	 * Public lookup payload (same shape Trust used on vck_lookup, plus entries).
	 *
	 * @param string $code Tracking code.
	 * @return array
	 */
	public static function lookup_payload( $code ) {
		$rows = self::find_all_by_code( $code );
		if ( ! $rows ) {
			$options = get_option( 'trf_cargo_tracking', array() );
			$message = isset( $options['not_found_message'] ) ? $options['not_found_message'] : __( 'کد رهگیری یافت نشد.', 'trf-cargo-tracking' );

			return array(
				'status'      => 'fail',
				'description' => $message,
			);
		}

		$entries = array();
		foreach ( $rows as $row ) {
			$entries[] = self::entry_payload( $row );
		}

		$latest  = $entries[0];
		$gallery = $latest['gallery'];

		return array(
			'status'        => 'ok',
			'description'   => $latest['description'],
			'thumbnail_url' => isset( $gallery[0] ) ? $gallery[0] : '',
			'gallery'       => $gallery,
			'validation'    => $rows[0]->validation,
			'event_date'    => $latest['event_date'],
			'entries'       => $entries,
		);
	}

	/**
	 * Public fields for one update row.
	 *
	 * @param object $row Row.
	 * @return array
	 */
	private static function entry_payload( $row ) {
		$gallery = array_map(
			array( 'TRF_Cargo_Tracking_Gallery', 'public_url' ),
			TRF_Cargo_Tracking_Gallery::from_row( $row )
		);
		$gallery = array_values( array_filter( $gallery ) );

		$event = '';
		if ( ! empty( $row->event_date ) ) {
			$event = TRF_Cargo_Tracking_Jalali::from_gregorian_date( $row->event_date );
		}

		return array(
			'description' => $row->description,
			'gallery'     => $gallery,
			'event_date'  => $event,
		);
	}

	/**
	 * Normalize a Gregorian date string or null.
	 *
	 * @param mixed $event_date Date.
	 * @return string|null
	 */
	private static function normalize_event_date( $event_date ) {
		if ( null === $event_date ) {
			return null;
		}

		$event_date = trim( (string) $event_date );
		if ( '' === $event_date ) {
			return null;
		}

		$parsed = TRF_Cargo_Tracking_Jalali::to_gregorian_date( $event_date );

		return '' === $parsed ? null : $parsed;
	}
}
