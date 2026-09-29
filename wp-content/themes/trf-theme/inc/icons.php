<?php
/**
 * Inline SVG icons (trusted markup, currentColor).
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return an icon SVG by name.
 *
 * @param string $name Icon key.
 * @return string
 */
function trf_icon( $name ) {
	$svg = '<svg class="trf-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

	$paths = array(
		'ship'     => '<path d="M4 16 8 8h8l4 8"/><path d="M3 20h18"/><path d="M6 8V6h12v2"/><path d="M3 20c1.6-1.2 3.4-1.2 5 0s3.4 1.2 5 0 3.4-1.2 5 0 3.4 1.2 5 0"/>',
		'truck'    => '<path d="M3 7h11v10H3z"/><path d="M14 11h4l3 3v3h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/>',
		'train'    => '<path d="M7 4h10v12H7z"/><path d="M7 12h10"/><path d="M9 20l-2 2"/><path d="M17 20l2 2"/><circle cx="9.5" cy="16" r="1"/><circle cx="14.5" cy="16" r="1"/><path d="M7 4a4 4 0 0 1 10 0"/>',
		'plane'    => '<path d="M3 12l18-8-8 18-2-7z"/>',
		'shield'   => '<path d="M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6z"/><path d="M9.5 12.5 11.5 14.5 15 10"/>',
		'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18"/>',
		'badge'    => '<path d="M12 3 14.5 8.5 20.5 9.5 16 13.8 17.2 20 12 17.2 6.8 20 8 13.8 3.5 9.5 9.5 8.5z"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'phone'    => '<path d="M6.5 3.5h3L11 7.5 8.8 9.2a12 12 0 0 0 6 6L16.5 13l4 1.5v3A2 2 0 0 1 18.6 20 16 16 0 0 1 4 5.4a2 2 0 0 1 2.5-1.9z"/>',
		'arrow'    => '<path d="M19 12H5"/><path d="M10 7l-5 5 5 5"/>',
		'package'  => '<path d="M3 8.5 12 4l9 4.5v9L12 22 3 17.5z"/><path d="M12 22V13"/><path d="M3 8.5 12 13l9-4.5"/>',
		'file'     => '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M10 13h6"/><path d="M10 17h4"/>',
		'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5 10.8 15.2 16 9.5"/>',
		'pin'      => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.2"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return $svg . $paths[ $name ] . '</svg>';
}
