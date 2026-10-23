<?php
/**
 * CSV import into wpwv_validations.
 *
 * Format:
 *   code, description, image_url|image_url, event_date
 *
 * The fourth column is optional and may be Jalali (1403/08/01) or Gregorian.
 * The same tracking code inserts another update row instead of being skipped.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Importer.
 */
class TRF_Cargo_Tracking_Import {

	/**
	 * Import a CSV file path.
	 *
	 * @param string $path Temporary file path.
	 * @return array{inserted:int,duplicates:int,skipped:int,error:string}
	 */
	public static function from_file( $path ) {
		$result = array(
			'inserted'   => 0,
			'duplicates' => 0,
			'skipped'    => 0,
			'error'      => '',
		);

		if ( ! $path || ! is_readable( $path ) ) {
			$result['error'] = __( 'فایل قابل خواندن نیست.', 'trf-cargo-tracking' );
			return $result;
		}

		$handle = fopen( $path, 'r' );
		if ( ! $handle ) {
			$result['error'] = __( 'باز کردن فایل ممکن نشد.', 'trf-cargo-tracking' );
			return $result;
		}

		while ( false !== ( $row = fgetcsv( $handle ) ) ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$count = count( $row );
			if ( $count < 2 || $count > 4 ) {
				++$result['skipped'];
				continue;
			}

			$code        = trim( (string) $row[0] );
			$description = trim( (string) $row[1] );
			$images_raw  = isset( $row[2] ) ? trim( (string) $row[2] ) : '';
			$date_raw    = isset( $row[3] ) ? trim( (string) $row[3] ) : '';
			$urls        = TRF_Cargo_Tracking_Gallery::normalize_urls( array_map( 'trim', explode( '|', $images_raw ) ) );
			$event_date  = '' === $date_raw ? null : TRF_Cargo_Tracking_Jalali::to_gregorian_date( $date_raw );

			if ( '' === $code || '' === $description ) {
				++$result['skipped'];
				continue;
			}

			if ( '' !== $date_raw && null === $event_date ) {
				++$result['skipped'];
				continue;
			}

			$insert = TRF_Cargo_Tracking_Repository::insert( $code, $description, $urls, $event_date );
			if ( is_wp_error( $insert ) ) {
				++$result['skipped'];
				continue;
			}

			++$result['inserted'];
		}

		fclose( $handle );

		return $result;
	}
}
