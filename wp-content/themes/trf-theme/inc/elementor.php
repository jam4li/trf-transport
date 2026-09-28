<?php
/**
 * Elementor Theme Builder compatibility (kept until inner pages are recoded).
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
