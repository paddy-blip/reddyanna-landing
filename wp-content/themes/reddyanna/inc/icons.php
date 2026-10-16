<?php
/**
 * Inline SVG icon set (24x24, stroke based). No icon font = no extra request.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon path data keyed by name.
 *
 * @return array<string,string>
 */
function ra_icon_paths() {
	return array(
		'bolt'         => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
		'shield'       => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'headset'      => '<path d="M3 14v-2a9 9 0 0 1 18 0v2"/><path d="M21 15a2 2 0 0 1-2 2h-1v-6h1a2 2 0 0 1 2 2zM3 15a2 2 0 0 0 2 2h1v-6H5a2 2 0 0 0-2 2z"/><path d="M18 17v1a3 3 0 0 1-3 3h-3"/>',
		'cricket'      => '<path d="m3 21 3-3"/><path d="m5.5 15.5 9-9 3 3-9 9z"/><path d="M14.5 6.5 17 4l3 3-2.5 2.5"/><circle cx="18" cy="18" r="2.5"/>',
		'live'         => '<circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49M7.76 16.24a6 6 0 0 1 0-8.49M19.07 4.93a10 10 0 0 1 0 14.14M4.93 19.07a10 10 0 0 1 0-14.14"/>',
		'lock'         => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
		'wallet'       => '<path d="M20 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h15v14H5a2 2 0 0 1-2-2V5"/><circle cx="16" cy="14" r="1.5"/>',
		'gift'         => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v9H5v-9"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5C10 3 12 8 12 8s2-5 4.5-5a2.5 2.5 0 0 1 0 5"/>',
		'users'        => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
		'star'         => '<path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/>',
		'check'        => '<path d="M20 6 9 17l-5-5"/>',
		'check-circle' => '<circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/>',
		'trophy'       => '<path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0z"/><path d="M17 5h3v2a3 3 0 0 1-3 3M7 5H4v2a3 3 0 0 0 3 3"/>',
		'phone'        => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'mail'         => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'map-pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'devices'      => '<rect x="2" y="4" width="14" height="10" rx="1.5"/><path d="M6 18h6M9 14v4"/><rect x="17" y="8" width="5" height="12" rx="1"/>',
		'clock'        => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'chart'        => '<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 6-6"/>',
		'target'       => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
		'eye'          => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
		'heart'        => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l8.8 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"/>',
		'refresh'      => '<path d="M21 12a9 9 0 0 1-15.5 6.3L3 16M3 12a9 9 0 0 1 15.5-6.3L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/>',
		'id-card'      => '<rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2.5"/><path d="M14 10h5M14 14h3"/>',
		'globe'        => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"/>',
		'search'       => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'menu'         => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'close'        => '<path d="M18 6 6 18M6 6l12 12"/>',
		'arrow-right'  => '<path d="M5 12h14M13 5l7 7-7 7"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'whatsapp'     => '<path d="M3 21l1.65-4.95A9 9 0 1 1 8.4 19.6z"/><path d="M9 9.2c0 3.2 2.6 5.8 5.8 5.8l1-1.4-2-1-1 .9c-1-.5-1.9-1.4-2.4-2.4l.9-1-1-2z"/>',
		'facebook'     => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'instagram'    => '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
		'x'            => '<path d="M4 4h4.5L20 20h-4.5zM20 4l-6.6 7.2M4 20l6.6-7.2"/>',
		'telegram'     => '<path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>',
		'youtube'      => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3z"/>',
	);
}

/**
 * Return an inline SVG icon.
 *
 * @param string $name  Icon key.
 * @param string $class Extra classes.
 * @param int    $size  Pixel size.
 * @return string
 */
function ra_icon( $name, $class = '', $size = 24 ) {
	$paths = ra_icon_paths();
	if ( empty( $paths[ $name ] ) ) {
		$name = 'check-circle';
	}
	return sprintf(
		'<svg class="icon icon-%1$s %2$s" width="%3$d" height="%3$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg>',
		esc_attr( $name ),
		esc_attr( $class ),
		(int) $size,
		$paths[ $name ] // Static, trusted markup.
	);
}

/** Choices array for ACF select fields. */
function ra_icon_choices() {
	$skip    = array( 'search', 'menu', 'close', 'arrow-right', 'chevron-down', 'facebook', 'instagram', 'x', 'telegram', 'youtube' );
	$choices = array();
	foreach ( array_keys( ra_icon_paths() ) as $key ) {
		if ( ! in_array( $key, $skip, true ) ) {
			$choices[ $key ] = ucwords( str_replace( '-', ' ', $key ) );
		}
	}
	return $choices;
}
