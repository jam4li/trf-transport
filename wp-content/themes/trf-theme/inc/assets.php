<?php
/**
 * Front-end assets.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles.
 */
function trf_theme_assets() {
	wp_enqueue_style(
		'trf-theme-base',
		TRF_THEME_URI . '/assets/css/base.css',
		array(),
		TRF_THEME_VERSION
	);

	wp_enqueue_script(
		'trf-theme-nav',
		TRF_THEME_URI . '/assets/js/nav.js',
		array(),
		TRF_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'trf-theme-quote-modal',
		TRF_THEME_URI . '/assets/js/quote-modal.js',
		array(),
		TRF_THEME_VERSION,
		true
	);

	if ( is_page_template( 'page-templates/contact.php' ) ) {
		wp_enqueue_style(
			'trf-theme-contact',
			TRF_THEME_URI . '/assets/css/contact.css',
			array( 'trf-theme-base' ),
			TRF_THEME_VERSION
		);
	}

	if ( is_page_template( 'page-templates/tracking.php' ) ) {
		wp_enqueue_style(
			'trf-theme-tracking',
			TRF_THEME_URI . '/assets/css/tracking.css',
			array( 'trf-theme-base' ),
			TRF_THEME_VERSION
		);
	}

	$trf_is_article = is_singular()
		&& ! is_front_page()
		&& ! is_page_template( 'page-templates/contact.php' )
		&& ! is_page_template( 'page-templates/tracking.php' );

	if ( $trf_is_article ) {
		wp_enqueue_style(
			'trf-theme-article',
			TRF_THEME_URI . '/assets/css/article.css',
			array( 'trf-theme-base' ),
			TRF_THEME_VERSION
		);
	}

	if ( is_front_page() ) {
		wp_enqueue_style(
			'trf-theme-home',
			TRF_THEME_URI . '/assets/css/home.css',
			array( 'trf-theme-base' ),
			TRF_THEME_VERSION
		);
		wp_enqueue_script(
			'trf-theme-home',
			TRF_THEME_URI . '/assets/js/home.js',
			array(),
			TRF_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'trf_theme_assets' );
