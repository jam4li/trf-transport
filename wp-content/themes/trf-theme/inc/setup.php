<?php
/**
 * Theme setup.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register supports and menus.
 */
function trf_theme_setup() {
	load_theme_textdomain( 'trf-theme', TRF_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
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
			'primary'      => __( 'Primary Menu', 'trf-theme' ),
			'footer'       => __( 'Footer Services', 'trf-theme' ),
			'footer-extra' => __( 'Footer Quick Links', 'trf-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'trf_theme_setup' );

/**
 * Drop retired Customizer social URL mods (Telegram, Twitter, YouTube).
 */
function trf_remove_retired_social_mods() {
	$retired = array( 'trf_social_telegram', 'trf_social_twitter', 'trf_social_youtube' );
	$mods    = get_theme_mods();
	if ( ! is_array( $mods ) ) {
		return;
	}

	foreach ( $retired as $key ) {
		if ( isset( $mods[ $key ] ) ) {
			remove_theme_mod( $key );
		}
	}
}
add_action( 'after_setup_theme', 'trf_remove_retired_social_mods' );

/**
 * Assign the existing "main" menu to the primary location once, on theme switch.
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
add_action( 'after_switch_theme', 'trf_assign_primary_menu' );

/**
 * Drop the default "Archives:" prefix on archive headings.
 *
 * @return string
 */
function trf_archive_title_prefix() {
	return '';
}
add_filter( 'get_the_archive_title_prefix', 'trf_archive_title_prefix' );

/**
 * Add a disclosure button next to parent items in the primary menu (mobile).
 *
 * @param string   $item_output Item HTML.
 * @param WP_Post  $item        Menu item.
 * @param int      $depth       Depth.
 * @param stdClass $args        Walker args.
 * @return string
 */
function trf_nav_parent_toggle( $item_output, $item, $depth, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $item_output;
	}

	$classes = is_array( $item->classes ) ? $item->classes : array();
	if ( ! in_array( 'menu-item-has-children', $classes, true ) ) {
		return $item_output;
	}

	$label = sprintf(
		/* translators: %s: parent menu item title */
		__( 'Toggle submenu: %s', 'trf-theme' ),
		$item->title
	);

	$item_output .= sprintf(
		'<button type="button" class="trf-nav__sub-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span><span aria-hidden="true">▾</span></button>',
		esc_html( $label )
	);

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'trf_nav_parent_toggle', 10, 4 );
