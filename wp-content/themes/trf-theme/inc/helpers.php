<?php
/**
 * Shared helpers.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme asset URL.
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

/**
 * Default theme_mod values.
 *
 * @return array<string, string>
 */
function trf_theme_defaults() {
	return array(
		'trf_phone'           => '۰۵۱-۳۷۷۶۲۶۲۶',
		'trf_phone_tel'       => '05137762626',
		'trf_social_telegram' => 'https://t.me/',
		'trf_social_twitter'  => 'https://twitter.com/',
		'trf_social_youtube'  => 'https://www.youtube.com/',
		'trf_tracking_slug'   => 'tracking',
		'trf_contact_slug'    => 'contact-us',
		'trf_footer_blurb'    => 'شرکت باربری و حمل‌ونقل تراف با بیش از ۲۰ سال سابقه تخصصی در زمینه لجستیک؛ حمل بار هوایی، زمینی و دریایی فعالیت می‌کند.',
		'trf_quote_email'     => '',
	);
}

/**
 * Get a theme_mod with the theme default.
 *
 * @param string $key Setting key.
 * @return string
 */
function trf_mod( $key ) {
	$defaults = trf_theme_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return (string) get_theme_mod( $key, $default );
}

/**
 * Resolve a published permalink by slug, with a path fallback.
 *
 * @param string          $slug       Post name.
 * @param string[]|string $post_types Post types to search.
 * @param string          $fallback   Path used if no post is found (e.g. "-/emirates").
 * @return string
 */
function trf_permalink_for( $slug, $post_types = array( 'page' ), $fallback = '' ) {
	$slug = sanitize_title( $slug );

	$posts = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);

	if ( ! empty( $posts ) ) {
		return get_permalink( $posts[0] );
	}

	$path = $fallback ? $fallback : $slug;

	return home_url( user_trailingslashit( '/' . ltrim( $path, '/' ) ) );
}

/**
 * Contact page URL.
 *
 * @return string
 */
function trf_contact_url() {
	return trf_permalink_for( trf_mod( 'trf_contact_slug' ) );
}

/**
 * Cargo tracking page URL.
 *
 * @return string
 */
function trf_tracking_url() {
	return trf_permalink_for( trf_mod( 'trf_tracking_slug' ) );
}

/**
 * Quote CTA URL: in-page form on the homepage, contact page elsewhere.
 *
 * @return string
 */
function trf_quote_cta_url() {
	return is_front_page() ? '#quote' : trf_contact_url();
}

/**
 * Print the custom logo or the bundled fallback.
 *
 * @param string $extra_class Extra class on the wrapper (e.g. trf-logo--footer).
 */
function trf_the_logo( $extra_class = '' ) {
	$class = 'trf-logo';
	if ( $extra_class ) {
		$class .= ' ' . $extra_class;
	}

	if ( has_custom_logo() ) {
		echo '<div class="' . esc_attr( $class ) . '">';
		the_custom_logo();
		echo '</div>';
		return;
	}

	printf(
		'<a class="%1$s" href="%2$s"><img src="%3$s" width="180" height="56" alt="%4$s"></a>',
		esc_attr( $class ),
		esc_url( home_url( '/' ) ),
		esc_url( trf_asset( 'img/logo.png' ) ),
		esc_attr( get_bloginfo( 'name' ) )
	);
}

/**
 * Print a footer column menu, or a hardcoded fallback list.
 *
 * @param string               $location Theme location.
 * @param array<int, array{label:string,url:string,external?:bool}> $fallback Items used when the location is empty.
 */
function trf_nav_or_fallback( $location, $fallback ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	echo '<ul>';
	foreach ( $fallback as $item ) {
		$attrs = '';
		if ( ! empty( $item['external'] ) ) {
			$attrs = ' target="_blank" rel="noopener noreferrer"';
		}
		printf(
			'<li><a href="%1$s"%2$s>%3$s</a></li>',
			esc_url( $item['url'] ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute string.
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}
