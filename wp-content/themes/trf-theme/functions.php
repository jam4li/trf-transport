<?php
/**
 * TRF Transport theme functions.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRF_THEME_VERSION', '1.7.1' );
define( 'TRF_THEME_DIR', get_template_directory() );
define( 'TRF_THEME_URI', get_template_directory_uri() );

$trf_includes = array(
	'helpers.php',
	'setup.php',
	'assets.php',
	'customizer.php',
	'home-data.php',
	'quote.php',
);

foreach ( $trf_includes as $trf_file ) {
	require_once TRF_THEME_DIR . '/inc/' . $trf_file;
}
