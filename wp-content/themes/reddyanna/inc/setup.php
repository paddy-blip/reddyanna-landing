<?php
/**
 * Theme supports, menus, image sizes.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'reddyanna', RA_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 252,
				'width'       => 753,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );

		register_nav_menus(
			array(
				'primary'      => __( 'Primary Menu', 'reddyanna' ),
				'footer_pages' => __( 'Footer – Important Pages', 'reddyanna' ),
				'footer_legal' => __( 'Footer – Legal Pages', 'reddyanna' ),
			)
		);

		// Blog cards (16:9-ish, matches 1200x630 featured images).
		add_image_size( 'ra-card', 640, 336, true );
		add_image_size( 'ra-hero', 1200, 630, true );
	}
);

/** Content width for embeds. */
add_action(
	'after_setup_theme',
	function () {
		$GLOBALS['content_width'] = 820;
	},
	0
);

/** Excerpt tweaks. */
add_filter(
	'excerpt_length',
	function () {
		return 22;
	}
);
add_filter(
	'excerpt_more',
	function () {
		return '&hellip;';
	}
);

/** Fallback for the primary menu before one is assigned. */
function ra_menu_fallback() {
	echo '<ul class="nav__list">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul>';
}

/** Add a class to menu links so they can be styled consistently. */
add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $args ) {
		if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
			$atts['class'] = 'nav__link';
		}
		return $atts;
	},
	10,
	3
);
