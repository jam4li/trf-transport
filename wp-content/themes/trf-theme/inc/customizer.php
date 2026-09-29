<?php
/**
 * Theme Customizer: phone, tracking, footer copy, quote recipient.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function trf_customize_register( $wp_customize ) {
	$defaults = trf_theme_defaults();

	$wp_customize->add_section(
		'trf_site',
		array(
			'title'    => __( 'TRF Site', 'trf-theme' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'trf_phone'           => array(
			'label' => __( 'Phone (display)', 'trf-theme' ),
			'type'  => 'text',
		),
		'trf_phone_tel'       => array(
			'label' => __( 'Phone (tel: link)', 'trf-theme' ),
			'type'  => 'text',
		),
		'trf_tracking_slug'   => array(
			'label'       => __( 'Tracking page slug', 'trf-theme' ),
			'type'        => 'text',
			'description' => __( 'Published page slug, e.g. tracking', 'trf-theme' ),
		),
		'trf_contact_slug'    => array(
			'label'       => __( 'Contact page slug', 'trf-theme' ),
			'type'        => 'text',
			'description' => __( 'Published page slug, e.g. contact-us', 'trf-theme' ),
		),
		'trf_hero_eyebrow'    => array(
			'label' => __( 'Homepage hero eyebrow', 'trf-theme' ),
			'type'  => 'text',
		),
		'trf_hero_title'      => array(
			'label'       => __( 'Homepage hero H1', 'trf-theme' ),
			'type'        => 'text',
			'description' => __( 'Visible homepage headline. Align with Rank Math SEO title.', 'trf-theme' ),
		),
		'trf_hero_lead'       => array(
			'label' => __( 'Homepage hero lead', 'trf-theme' ),
			'type'  => 'textarea',
		),
		'trf_footer_blurb'    => array(
			'label' => __( 'Footer about text', 'trf-theme' ),
			'type'  => 'textarea',
		),
		'trf_quote_email'     => array(
			'label'       => __( 'Quote form recipient email', 'trf-theme' ),
			'type'        => 'email',
			'description' => __( 'Leave empty to use the WordPress admin email.', 'trf-theme' ),
		),
	);

	foreach ( $fields as $id => $field ) {
		$sanitize = 'sanitize_text_field';
		if ( 'url' === $field['type'] ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $field['type'] ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'textarea' === $field['type'] ) {
			$sanitize = 'sanitize_textarea_field';
		}

		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$id,
			array(
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'section'     => 'trf_site',
				'type'        => $field['type'],
			)
		);
	}
}
add_action( 'customize_register', 'trf_customize_register' );
