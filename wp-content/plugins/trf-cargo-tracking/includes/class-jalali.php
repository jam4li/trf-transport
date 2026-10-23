<?php
/**
 * Gregorian ↔ Jalali conversion for tracking dates.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Jalali calendar helpers.
 */
class TRF_Cargo_Tracking_Jalali {

	/**
	 * Convert a Gregorian Y-m-d string to Jalali Y/m/d.
	 *
	 * @param string $date Gregorian date.
	 * @return string
	 */
	public static function from_gregorian_date( $date ) {
		$parts = self::split_date( $date );
		if ( ! $parts ) {
			return '';
		}

		$j = self::gregorian_to_jalali( $parts[0], $parts[1], $parts[2] );

		return sprintf( '%04d/%02d/%02d', $j[0], $j[1], $j[2] );
	}

	/**
	 * Convert a MySQL datetime to Jalali Y/m/d H:i.
	 *
	 * @param string $datetime MySQL datetime.
	 * @return string
	 */
	public static function from_gregorian_datetime( $datetime ) {
		$datetime = trim( (string) $datetime );
		if ( '' === $datetime || '0000-00-00 00:00:00' === $datetime ) {
			return '';
		}

		if ( ! preg_match( '/^(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2})/', $datetime, $m ) ) {
			return '';
		}

		$date = self::from_gregorian_date( $m[1] );
		if ( '' === $date ) {
			return '';
		}

		return $date . ' ' . $m[2];
	}

	/**
	 * Parse a Jalali or Gregorian date string into Gregorian Y-m-d.
	 *
	 * Years 1600 and above are treated as Gregorian; 1200–1599 as Jalali.
	 *
	 * @param string $value Raw date.
	 * @return string Empty when invalid.
	 */
	public static function to_gregorian_date( $value ) {
		$parts = self::split_date( $value );
		if ( ! $parts ) {
			return '';
		}

		list( $y, $m, $d ) = $parts;

		if ( $y >= 1600 ) {
			if ( ! checkdate( $m, $d, $y ) ) {
				return '';
			}

			return sprintf( '%04d-%02d-%02d', $y, $m, $d );
		}

		if ( $y < 1200 || $m < 1 || $m > 12 || $d < 1 || $d > 31 ) {
			return '';
		}

		$g = self::jalali_to_gregorian( $y, $m, $d );
		if ( ! checkdate( $g[1], $g[2], $g[0] ) ) {
			return '';
		}

		return sprintf( '%04d-%02d-%02d', $g[0], $g[1], $g[2] );
	}

	/**
	 * Convert ASCII digits to Persian digits.
	 *
	 * @param string $value Text.
	 * @return string
	 */
	public static function to_persian_digits( $value ) {
		return strtr(
			(string) $value,
			array(
				'0' => '۰',
				'1' => '۱',
				'2' => '۲',
				'3' => '۳',
				'4' => '۴',
				'5' => '۵',
				'6' => '۶',
				'7' => '۷',
				'8' => '۸',
				'9' => '۹',
			)
		);
	}

	/**
	 * Gregorian date parts to Jalali parts.
	 *
	 * @param int $gy Year.
	 * @param int $gm Month.
	 * @param int $gd Day.
	 * @return int[]
	 */
	public static function gregorian_to_jalali( $gy, $gm, $gd ) {
		$gy = (int) $gy;
		$gm = (int) $gm;
		$gd = (int) $gd;

		$g_d_m = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
		$gy2   = ( $gm > 2 ) ? ( $gy + 1 ) : $gy;
		$days  = 355666 + ( 365 * $gy ) + (int) ( ( $gy2 + 3 ) / 4 ) - (int) ( ( $gy2 + 99 ) / 100 ) + (int) ( ( $gy2 + 399 ) / 400 ) + $gd + $g_d_m[ $gm - 1 ];
		$jy    = -1595 + ( 33 * (int) ( $days / 12053 ) );
		$days %= 12053;
		$jy   += 4 * (int) ( $days / 1461 );
		$days %= 1461;
		if ( $days > 365 ) {
			$jy   += (int) ( ( $days - 1 ) / 365 );
			$days  = ( $days - 1 ) % 365;
		}
		$jm = ( $days < 186 ) ? 1 + (int) ( $days / 31 ) : 7 + (int) ( ( $days - 186 ) / 30 );
		$jd = 1 + ( ( $days < 186 ) ? ( $days % 31 ) : ( ( $days - 186 ) % 30 ) );

		return array( $jy, $jm, $jd );
	}

	/**
	 * Jalali date parts to Gregorian parts.
	 *
	 * @param int $jy Year.
	 * @param int $jm Month.
	 * @param int $jd Day.
	 * @return int[]
	 */
	public static function jalali_to_gregorian( $jy, $jm, $jd ) {
		$jy = (int) $jy;
		$jm = (int) $jm;
		$jd = (int) $jd;

		$jy     += 1595;
		$days    = -355668 + ( 365 * $jy ) + ( (int) ( $jy / 33 ) * 8 ) + (int) ( ( ( $jy % 33 ) + 3 ) / 4 ) + $jd + ( ( $jm < 7 ) ? ( $jm - 1 ) * 31 : ( ( $jm - 7 ) * 30 ) + 186 );
		$gy      = 400 * (int) ( $days / 146097 );
		$days   %= 146097;
		if ( $days > 36524 ) {
			$gy++;
			$days     -= 36525;
			$gy       += 100 * (int) ( $days / 36524 );
			$days     %= 36524;
			if ( $days >= 365 ) {
				$days++;
			}
		}
		$gy   += 4 * (int) ( $days / 1461 );
		$days %= 1461;
		if ( $days > 365 ) {
			$gy   += (int) ( ( $days - 1 ) / 365 );
			$days  = ( $days - 1 ) % 365;
		}
		$gd    = $days + 1;
		$sal_a = array( 0, 31, ( ( $gy % 4 === 0 && $gy % 100 !== 0 ) || ( $gy % 400 === 0 ) ) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
		$gm    = 0;
		while ( $gm < 13 && $gd > $sal_a[ $gm ] ) {
			$gd -= $sal_a[ $gm ];
			$gm++;
		}

		return array( $gy, $gm, $gd );
	}

	/**
	 * Split a date string into Y, M, D integers.
	 *
	 * @param string $value Date.
	 * @return int[]|null
	 */
	private static function split_date( $value ) {
		$value = trim( (string) $value );
		$value = strtr(
			$value,
			array(
				'۰' => '0',
				'۱' => '1',
				'۲' => '2',
				'۳' => '3',
				'۴' => '4',
				'۵' => '5',
				'۶' => '6',
				'۷' => '7',
				'۸' => '8',
				'۹' => '9',
				'٠' => '0',
				'١' => '1',
				'٢' => '2',
				'٣' => '3',
				'٤' => '4',
				'٥' => '5',
				'٦' => '6',
				'٧' => '7',
				'٨' => '8',
				'٩' => '9',
			)
		);

		if ( '' === $value || preg_match( '/^0{4}-0{2}-0{2}/', $value ) ) {
			return null;
		}

		if ( ! preg_match( '/^(\d{3,4})[\/\-.](\d{1,2})[\/\-.](\d{1,2})/', $value, $m ) ) {
			return null;
		}

		return array( (int) $m[1], (int) $m[2], (int) $m[3] );
	}
}
