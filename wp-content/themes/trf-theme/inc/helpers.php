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
 * Default theme_mod values.
 *
 * @return array<string, string>
 */
function trf_theme_defaults() {
	return array(
		'trf_phone'         => '۰۵۱-۳۷۷۶۲۶۲۶',
		'trf_phone_tel'     => '05137762626',
		'trf_tracking_slug' => 'tracking',
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
 * Convert Persian/Arabic-Indic digits to ASCII.
 *
 * @param string $value Mixed-numeral string.
 * @return string
 */
function trf_ascii_digits( $value ) {
	$from = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$to   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

	return str_replace( $from, $to, $value );
}

/**
 * Build a tel: href from a display phone number.
 *
 * @param string $display Phone as shown to the user.
 * @return string Digits only, or empty if none.
 */
function trf_tel_href( $display ) {
	$digits = preg_replace( '/\D+/', '', trf_ascii_digits( $display ) );

	return is_string( $digits ) ? $digits : '';
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
 * Quote CTA URL: opens the site-wide quote modal.
 *
 * @return string
 */
function trf_quote_cta_url() {
	return '#quote';
}

/**
 * Posts / news archive URL.
 *
 * @return string
 */
function trf_news_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	$link = get_post_type_archive_link( 'post' );

	return $link ? $link : home_url( '/' );
}

/**
 * Manual excerpt for article hero lead (never auto-generated).
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function trf_article_lead( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || '' === trim( (string) $post->post_excerpt ) ) {
		return '';
	}

	return wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 36, '…' );
}

/**
 * Fallback image URL when a singular has no featured image.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function trf_article_fallback_image( $post = null ) {
	$post = get_post( $post );
	$slug = $post ? (string) $post->post_name : '';

	$map = array(
		'road-transport' => 'img/service-road.webp',
		'sea-transport'  => 'img/service-sea.webp',
		'rail-transport' => 'img/service-rail.webp',
		'air-transport'  => 'img/service-air.webp',
	);

	if ( isset( $map[ $slug ] ) ) {
		return trf_asset( $map[ $slug ] );
	}

	if ( false !== strpos( $slug, 'road' ) || false !== strpos( $slug, 'truck' ) ) {
		return trf_asset( 'img/service-road.webp' );
	}
	if ( false !== strpos( $slug, 'sea' ) || false !== strpos( $slug, 'ship' ) ) {
		return trf_asset( 'img/service-sea.webp' );
	}
	if ( false !== strpos( $slug, 'rail' ) || false !== strpos( $slug, 'train' ) ) {
		return trf_asset( 'img/service-rail.webp' );
	}
	if ( false !== strpos( $slug, 'air' ) || false !== strpos( $slug, 'plane' ) ) {
		return trf_asset( 'img/service-air.webp' );
	}

	return trf_asset( 'img/articles-1.webp' );
}

/**
 * Print the article hero image (featured, else themed fallback).
 *
 * @param string           $size Image size.
 * @param int|WP_Post|null $post Post.
 */
function trf_the_article_image( $size = 'large', $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	if ( has_post_thumbnail( $post ) ) {
		echo get_the_post_thumbnail(
			$post,
			$size,
			array(
				'class'         => 'trf-article__img',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
			)
		);
		return;
	}

	printf(
		'<img class="trf-article__img" src="%1$s" alt="%2$s" loading="eager" fetchpriority="high" decoding="async" width="1200" height="675">',
		esc_url( trf_article_fallback_image( $post ) ),
		esc_attr( get_the_title( $post ) )
	);
}

/**
 * Print a compact breadcrumb for singular pages/posts.
 *
 * @param int|WP_Post|null $post Post.
 */
function trf_the_article_breadcrumb( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$items = array(
		array(
			'label' => __( 'خانه', 'trf-theme' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular( 'post' ) ) {
		$items[] = array(
			'label' => __( 'دانشنامه', 'trf-theme' ),
			'url'   => trf_news_url(),
		);
	} elseif ( $post->post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post ) );
		foreach ( $ancestors as $ancestor_id ) {
			$items[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
	}

	echo '<nav class="trf-article__breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'trf-theme' ) . '"><ol>';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
	printf(
		'<li aria-current="page"><span>%s</span></li>',
		esc_html( get_the_title( $post ) )
	);
	echo '</ol></nav>';
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
		'<a class="%1$s" href="%2$s"><img src="%3$s" width="260" height="81" alt="%4$s"></a>',
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
				'menu_class'     => 'trf-footer__list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	echo '<ul class="trf-footer__list">';
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
