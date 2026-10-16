<?php
/**
 * Front-end performance clean-up.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

// Emoji scripts/styles.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter(
	'emoji_svg_url',
	'__return_false'
);

// Head clutter.
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );

add_action(
	'wp_enqueue_scripts',
	function () {
		// Embeds script (we don't embed other WP posts).
		wp_deregister_script( 'wp-embed' );

		// Block styles are only needed where block content is rendered (posts, plain pages).
		$needs_blocks = is_singular( 'post' ) || ( is_page() && ! ra_has_sections() );
		if ( ! $needs_blocks ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'global-styles' );
		}
		wp_dequeue_style( 'classic-theme-styles' );
	},
	100
);

// Don't print global-styles SVG filters / duotone for a classic theme.
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/** Lazy-load + async decode for content images (core handles most, ensure decoding). */
add_filter(
	'wp_get_attachment_image_attributes',
	function ( $attr ) {
		if ( empty( $attr['decoding'] ) ) {
			$attr['decoding'] = 'async';
		}
		return $attr;
	}
);

/** Slightly higher JPEG/WebP quality balance for generated sizes. */
add_filter(
	'wp_editor_set_quality',
	function () {
		return 80;
	}
);

/** Disable XML-RPC (not used; common attack surface). */
add_filter( 'xmlrpc_enabled', '__return_false' );

/** Remove ?ver from theme font URLs etc. is unnecessary; keep cache-busting. */

/** Limit heartbeat on the front end. */
add_action(
	'init',
	function () {
		if ( ! is_admin() ) {
			wp_deregister_script( 'heartbeat' );
		}
	},
	1
);
