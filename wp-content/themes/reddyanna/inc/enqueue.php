<?php
/**
 * Front-end assets.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset URL + version, preferring the minified build unless SCRIPT_DEBUG.
 *
 * @param string $rel Path relative to theme, without .min (e.g. assets/css/main.css).
 * @return array{0:string,1:string}
 */
function ra_asset( $rel ) {
	$min = preg_replace( '/\.(css|js)$/', '.min.$1', $rel );
	if ( ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) && file_exists( RA_DIR . '/' . $min ) ) {
		$rel = $min;
	}
	$ver = file_exists( RA_DIR . '/' . $rel ) ? (string) filemtime( RA_DIR . '/' . $rel ) : RA_VERSION;
	return array( RA_URI . '/' . $rel, $ver );
}

add_action(
	'wp_enqueue_scripts',
	function () {
		list( $css, $css_ver ) = ra_asset( 'assets/css/main.css' );
		wp_enqueue_style( 'reddyanna', $css, array(), $css_ver );

		// Brand colour overrides from Theme Settings.
		$inline = ra_color_vars_css();
		if ( $inline ) {
			wp_add_inline_style( 'reddyanna', $inline );
		}

		list( $js, $js_ver ) = ra_asset( 'assets/js/main.js' );
		wp_enqueue_script(
			'reddyanna',
			$js,
			array(),
			$js_ver,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

/** Preload the two above-the-fold fonts. */
add_action(
	'wp_head',
	function () {
		foreach ( array( 'josefin-sans-var.woff2', 'poppins-400.woff2' ) as $font ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( RA_URI . '/assets/fonts/' . $font ) );
		}
	},
	1
);

/**
 * Build :root overrides for colours that differ from the defaults.
 *
 * @return string
 */
function ra_color_vars_css() {
	$map  = array(
		'ra_color_primary'       => array( '--gold', '#d4a537' ),
		'ra_color_primary_light' => array( '--gold-light', '#f3d77a' ),
		'ra_color_bg'            => array( '--bg', '#0b0b0b' ),
		'ra_color_surface'       => array( '--surface', '#141414' ),
		'ra_color_text'          => array( '--text', '#f2efe6' ),
	);
	$vars = '';
	foreach ( $map as $field => $def ) {
		$val = sanitize_hex_color( ra_opt( $field, $def[1] ) );
		if ( $val && strtolower( $val ) !== $def[1] ) {
			$vars .= $def[0] . ':' . $val . ';';
		}
	}
	return $vars ? ':root{' . $vars . '}' : '';
}
