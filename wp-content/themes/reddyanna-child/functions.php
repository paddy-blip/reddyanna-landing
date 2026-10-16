<?php
/**
 * Reddy Anna Child theme.
 *
 * The parent stylesheet (assets/css/main.css) is enqueued by the parent theme.
 * This only adds the child style.css after it, and only when it contains rules.
 *
 * @package ReddyAnnaChild
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		$file = get_stylesheet_directory() . '/style.css';
		$css  = (string) file_get_contents( $file );
		// Skip the request when style.css holds only the header comment.
		if ( '' === trim( preg_replace( '#/\*.*?\*/#s', '', $css ) ) ) {
			return;
		}
		wp_enqueue_style( 'reddyanna-child', get_stylesheet_uri(), array( 'reddyanna' ), (string) filemtime( $file ) );
	},
	20
);

// Add your custom functions below.
