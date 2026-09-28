<?php
/**
 * TRF Transport theme functions.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRF_THEME_VERSION', '1.3.0' );
define( 'TRF_THEME_DIR', get_template_directory() );
define( 'TRF_THEME_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function trf_theme_setup() {
	load_theme_textdomain( 'trf-theme', TRF_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 92,
			'width'       => 298,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'trf-theme' ),
			'footer'  => __( 'Footer Menu', 'trf-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'trf_theme_setup' );

/**
 * Assign the existing "main" menu to the primary location once.
 */
function trf_assign_primary_menu() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! empty( $locations['primary'] ) ) {
		return;
	}

	$menu = wp_get_nav_menu_object( 'main' );
	if ( ! $menu ) {
		return;
	}

	$locations['primary'] = (int) $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_setup_theme', 'trf_assign_primary_menu', 20 );

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

/**
 * Register Elementor Theme Builder locations so Pro does not hijack get_header/get_footer.
 *
 * @param object $elementor_theme_manager Locations manager.
 */
function trf_register_elementor_locations( $elementor_theme_manager ) {
	if ( method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
		$elementor_theme_manager->register_all_core_location();
	}
}
add_action( 'elementor/theme/register_locations', 'trf_register_elementor_locations' );

/**
 * Helper: theme asset URL.
 *
 * @param string $path Relative path under assets/.
 * @return string
 */
function trf_asset( $path ) {
	return TRF_THEME_URI . '/assets/' . ltrim( $path, '/' );
}

/**
 * Whether the current singular page is built with Elementor.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function trf_is_elementor_page( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_queried_object_id();
	if ( ! $post_id ) {
		return false;
	}

	return (bool) get_post_meta( $post_id, '_elementor_edit_mode', true );
}
