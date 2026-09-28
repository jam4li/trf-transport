<?php
/**
 * Plugin Name: TRF Core
 * Description: Site content types for TRF Transport (replaces JetEngine). Always-on must-use plugin.
 * Version: 1.0.0
 * Author: TRF Transport
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package TRF_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_drop_builders = array(
	'elementor/elementor.php',
	'elementor-pro/elementor-pro.php',
	'jet-engine/jet-engine.php',
);
$trf_active_plugins = (array) get_option( 'active_plugins', array() );
$trf_kept_plugins   = array_values( array_diff( $trf_active_plugins, $trf_drop_builders ) );
if ( $trf_kept_plugins !== $trf_active_plugins ) {
	update_option( 'active_plugins', $trf_kept_plugins );
}

/**
 * Post type definitions previously stored in JetEngine's jet_post_types table.
 *
 * @return array<string, array<string, mixed>>
 */
function trf_post_type_defs() {
	return array(
		'services'        => array(
			'labels'      => array(
				'name'          => 'خدمات ما',
				'singular_name' => 'خدمت',
				'menu_name'     => 'خدمات',
				'add_new'       => 'افزودن خدمت',
				'add_new_item'  => 'افزودن خدمت جدید',
				'edit_item'     => 'ویرایش خدمت',
				'view_item'     => 'نمایش خدمت',
				'all_items'     => 'همه خدمات',
				'search_items'  => 'جستجوی خدمات',
			),
			'menu_icon'   => 'dashicons-portfolio',
			'rewrite'     => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'has_archive' => true,
		),
		'domestic-agents' => array(
			'labels'      => array(
				'name'          => 'نمایندگی های داخلی',
				'singular_name' => 'نماینده',
				'menu_name'     => 'نمایندگان داخلی',
				'add_new'       => 'افزودن نماینده',
				'add_new_item'  => 'افزودن نماینده جدید',
				'edit_item'     => 'ویرایش نماینده',
				'view_item'     => 'نمایش نماینده',
				'all_items'     => 'همه نمایندگان',
				'search_items'  => 'جستجوی نمایندگان',
			),
			'menu_icon'   => 'dashicons-location',
			'rewrite'     => array(
				'slug'       => 'domestic-agents',
				'with_front' => false,
			),
			'has_archive' => true,
		),
		'foreign-agents'  => array(
			'labels'      => array(
				'name'          => 'نمایندگی های خارجی',
				'singular_name' => 'نماینده',
				'menu_name'     => 'نمایندگان خارجی',
				'add_new'       => 'افزودن نماینده',
				'add_new_item'  => 'افزودن نماینده جدید',
				'edit_item'     => 'ویرایش نماینده',
				'view_item'     => 'نمایش نماینده',
				'all_items'     => 'همه نمایندگان',
				'search_items'  => 'جستجوی نمایندگان',
			),
			'menu_icon'   => 'dashicons-admin-site-alt3',
			'rewrite'     => array(
				'slug'       => 'foreign-agents',
				'with_front' => false,
			),
			'has_archive' => true,
		),
	);
}

/**
 * Register CPTs previously provided by JetEngine.
 */
function trf_register_post_types() {
	$shared = array(
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'exclude_from_search' => false,
		'hierarchical'        => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	);

	foreach ( trf_post_type_defs() as $slug => $args ) {
		register_post_type( $slug, array_merge( $shared, $args ) );
	}
}
add_action( 'init', 'trf_register_post_types' );

/**
 * Keep old JetEngine pretty URLs (/-/slug) working after the rewrite slug change.
 */
function trf_register_legacy_cpt_rewrites() {
	add_rewrite_rule(
		'^-/([^/]+)/?$',
		'index.php?trf_legacy_slug=$matches[1]',
		'top'
	);
}
add_action( 'init', 'trf_register_legacy_cpt_rewrites', 11 );

/**
 * Query var for legacy /-/slug URLs.
 *
 * @param string[] $vars Query vars.
 * @return string[]
 */
function trf_legacy_query_vars( $vars ) {
	$vars[] = 'trf_legacy_slug';
	return $vars;
}
add_filter( 'query_vars', 'trf_legacy_query_vars' );

/**
 * Resolve /-/slug against the three former JetEngine post types.
 *
 * @param WP_Query $query Query.
 */
function trf_legacy_cpt_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$slug = $query->get( 'trf_legacy_slug' );
	if ( ! $slug ) {
		return;
	}

	$query->set( 'name', $slug );
	$query->set( 'post_type', array_keys( trf_post_type_defs() ) );
	$query->set( 'trf_legacy_slug', '' );
	$query->is_home     = false;
	$query->is_singular = true;
	$query->is_single   = true;
}
add_action( 'pre_get_posts', 'trf_legacy_cpt_pre_get_posts' );

/**
 * Assign a page template by published slug.
 *
 * @param string $slug     Page slug.
 * @param string $template Relative template path.
 */
function trf_assign_page_template( $slug, $template ) {
	$pages = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);
	if ( empty( $pages ) ) {
		return;
	}

	update_post_meta( (int) $pages[0]->ID, '_wp_page_template', $template );
}

/**
 * 301 old JetEngine /-/slug URLs to the new permalink.
 */
function trf_legacy_cpt_canonical_redirect() {
	if ( is_admin() || ! is_singular() ) {
		return;
	}

	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path    = (string) wp_parse_url( $request, PHP_URL_PATH );
	$path    = trim( $path, '/' );
	if ( 0 !== strpos( $path, '-/' ) ) {
		return;
	}

	$permalink = get_permalink();
	if ( $permalink ) {
		wp_safe_redirect( $permalink, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'trf_legacy_cpt_canonical_redirect' );

/**
 * One-time: remap JetEngine leftovers, switch off Hello Elementor, assign native templates.
 */
function trf_migrate_off_page_builders() {
	$done = (string) get_option( 'trf_dropped_page_builders', '0' );
	if ( version_compare( $done, '2', '>=' ) ) {
		return;
	}

	global $wpdb;

	if ( version_compare( $done, '1', '<' ) ) {
		$wpdb->update(
			$wpdb->posts,
			array( 'post_type' => 'services' ),
			array( 'post_type' => '-' )
		);

		$tracking_slug = function_exists( 'trf_mod' ) ? trf_mod( 'trf_tracking_slug' ) : 'tracking';
		$tracking      = get_posts(
			array(
				'name'           => $tracking_slug,
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'no_found_rows'  => true,
			)
		);
		if ( ! empty( $tracking ) && false !== strpos( $tracking[0]->post_content, 'elementor' ) ) {
			$wpdb->update(
				$wpdb->posts,
				array( 'post_content' => '' ),
				array( 'ID' => (int) $tracking[0]->ID )
			);
			clean_post_cache( (int) $tracking[0]->ID );
		}
	}

	$theme = wp_get_theme( 'trf-theme' );
	if ( $theme->exists() && 'trf-theme' !== get_option( 'stylesheet' ) ) {
		switch_theme( 'trf-theme' );
	}

	$tracking_slug = function_exists( 'trf_mod' ) ? trf_mod( 'trf_tracking_slug' ) : 'tracking';
	$contact_slug  = function_exists( 'trf_mod' ) ? trf_mod( 'trf_contact_slug' ) : 'contact-us';
	trf_assign_page_template( $tracking_slug, 'page-templates/tracking.php' );
	trf_assign_page_template( $contact_slug, 'page-templates/contact.php' );

	update_option( 'trf_dropped_page_builders', '2' );
	update_option( 'trf_flush_rewrites', '1' );
}
add_action( 'init', 'trf_migrate_off_page_builders', 1 );

/**
 * Flush rewrite rules once after CPT registration / migration.
 */
function trf_maybe_flush_rewrites() {
	if ( get_option( 'trf_flush_rewrites' ) !== '1' ) {
		return;
	}

	flush_rewrite_rules( false );
	delete_option( 'trf_flush_rewrites' );
}
add_action( 'init', 'trf_maybe_flush_rewrites', 20 );
