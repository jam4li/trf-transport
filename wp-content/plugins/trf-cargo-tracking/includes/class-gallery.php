<?php
/**
 * Image gallery helpers for tracking records.
 *
 * Stores JSON in gallery_urls and keeps thumbnail_url as the first image
 * for compatibility with the original Trust frontend payload.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize, encode, and decode gallery URLs.
 */
class TRF_Cargo_Tracking_Gallery {

	/**
	 * Sanitize a list of URLs.
	 *
	 * @param mixed $urls Raw URLs.
	 * @return string[]
	 */
	public static function normalize_urls( $urls ) {
		if ( ! is_array( $urls ) ) {
			return array();
		}

		$clean = array();
		foreach ( $urls as $url ) {
			$url = self::canonicalize_url( $url );
			if ( '' === $url ) {
				continue;
			}
			$clean[] = $url;
		}

		return array_values( array_unique( $clean ) );
	}

	/**
	 * Turn a stored URL into a site-relative uploads path when possible.
	 *
	 * @param mixed $url Raw URL.
	 * @return string
	 */
	public static function canonicalize_url( $url ) {
		$url = trim( (string) $url );
		if ( '' === $url ) {
			return '';
		}

		$url  = esc_url_raw( $url );
		$path = wp_parse_url( $url, PHP_URL_PATH );
		if ( is_string( $path ) && false !== strpos( $path, '/wp-content/uploads/' ) ) {
			return $path;
		}

		return $url;
	}

	/**
	 * Absolute URL for display on the current site.
	 *
	 * @param string $url Relative path or absolute URL.
	 * @return string
	 */
	public static function public_url( $url ) {
		$url = trim( (string) $url );
		if ( '' === $url ) {
			return '';
		}

		if ( isset( $url[0] ) && '/' === $url[0] ) {
			return home_url( $url );
		}

		$path = wp_parse_url( $url, PHP_URL_PATH );
		if ( is_string( $path ) && false !== strpos( $path, '/wp-content/uploads/' ) ) {
			return home_url( $path );
		}

		return $url;
	}

	/**
	 * URLs from an admin POST.
	 *
	 * @return string[]
	 */
	public static function urls_from_request() {
		$urls = array();

		if ( isset( $_POST['gallery_urls'] ) && is_array( $_POST['gallery_urls'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$urls = wp_unslash( $_POST['gallery_urls'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} elseif ( ! empty( $_POST['gallery_urls_json'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$decoded = json_decode( wp_unslash( $_POST['gallery_urls_json'] ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( is_array( $decoded ) ) {
				$urls = $decoded;
			}
		} elseif ( ! empty( $_POST['thumbnail_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$urls = array( wp_unslash( $_POST['thumbnail_url'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		return self::normalize_urls( $urls );
	}

	/**
	 * JSON-encode URLs for gallery_urls.
	 *
	 * @param string[] $urls URLs.
	 * @return string
	 */
	public static function encode( array $urls ) {
		return wp_json_encode( array_values( self::normalize_urls( $urls ) ) );
	}

	/**
	 * Decode gallery_urls from the database.
	 *
	 * @param mixed $value JSON, array, or single URL.
	 * @return string[]
	 */
	public static function decode( $value ) {
		if ( empty( $value ) ) {
			return array();
		}
		if ( is_array( $value ) ) {
			return self::normalize_urls( $value );
		}

		$decoded = json_decode( (string) $value, true );
		if ( is_string( $decoded ) ) {
			$decoded = json_decode( $decoded, true );
		}
		if ( ! is_array( $decoded ) ) {
			return self::normalize_urls( array( (string) $value ) );
		}

		return self::normalize_urls( $decoded );
	}

	/**
	 * Gallery from a table row, falling back to thumbnail_url.
	 *
	 * @param object|array $row Row.
	 * @return string[]
	 */
	public static function from_row( $row ) {
		$gallery = array();

		if ( is_object( $row ) && isset( $row->gallery_urls ) ) {
			$gallery = self::decode( $row->gallery_urls );
		} elseif ( is_array( $row ) && isset( $row['gallery_urls'] ) ) {
			$gallery = self::decode( $row['gallery_urls'] );
		}

		if ( ! empty( $gallery ) ) {
			return $gallery;
		}

		$thumb = '';
		if ( is_object( $row ) && ! empty( $row->thumbnail_url ) ) {
			$thumb = $row->thumbnail_url;
		} elseif ( is_array( $row ) && ! empty( $row['thumbnail_url'] ) ) {
			$thumb = $row['thumbnail_url'];
		}

		return self::normalize_urls( array( $thumb ) );
	}

	/**
	 * Columns to persist for a set of URLs.
	 *
	 * @param string[] $urls URLs.
	 * @return array{thumbnail_url:string,gallery_urls:string}
	 */
	public static function db_fields_from_urls( array $urls ) {
		$urls = self::normalize_urls( $urls );

		return array(
			'thumbnail_url' => isset( $urls[0] ) ? $urls[0] : '',
			'gallery_urls'  => self::encode( $urls ),
		);
	}

	/**
	 * Media-library picker used on add/edit screens.
	 *
	 * @param string[] $urls Current URLs.
	 */
	public static function render_admin_field( array $urls = array() ) {
		$urls = self::normalize_urls( $urls );
		?>
		<div class="trf-track-gallery-field" data-trf-gallery>
			<label><?php esc_html_e( 'تصاویر بارنامه', 'trf-cargo-tracking' ); ?></label>
			<div class="trf-track-gallery-preview" data-trf-gallery-preview>
				<?php foreach ( $urls as $url ) : ?>
					<?php $src = self::public_url( $url ); ?>
					<div class="trf-track-gallery-item" data-url="<?php echo esc_attr( $src ); ?>">
						<img src="<?php echo esc_url( $src ); ?>" alt="">
						<button type="button" class="button-link trf-track-gallery-remove" aria-label="<?php esc_attr_e( 'حذف', 'trf-cargo-tracking' ); ?>">&times;</button>
						<input type="hidden" name="gallery_urls[]" value="<?php echo esc_attr( $src ); ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<p>
				<button type="button" class="button" data-trf-gallery-add><?php esc_html_e( 'افزودن / انتخاب تصاویر', 'trf-cargo-tracking' ); ?></button>
			</p>
			<p class="description"><?php esc_html_e( 'از کتابخانه رسانه وردپرس چند تصویر انتخاب کنید. ترتیب نمایش همان ترتیب انتخاب است.', 'trf-cargo-tracking' ); ?></p>
		</div>
		<?php
	}
}
