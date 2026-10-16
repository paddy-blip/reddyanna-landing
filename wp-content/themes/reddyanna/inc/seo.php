<?php
/**
 * SEO helpers: Yoast sync, fallback Open Graph, theme-color, FAQ schema, favicon fallback.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Development / tunnel hosts (localhost, LAN IPs, *.trycloudflare.com) must never be indexed,
 * so a temporary copy can't compete with the live domain in search results.
 */
function ra_is_dev_host() {
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	return 'localhost' === $host
		|| (bool) filter_var( $host, FILTER_VALIDATE_IP )
		|| (bool) preg_match( '/(\.trycloudflare\.com|\.local|\.test)$/', $host );
}
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( ra_is_dev_host() ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = true;
		}
		return $robots;
	},
	99
);
add_action(
	'send_headers',
	function () {
		if ( ra_is_dev_host() ) {
			header( 'X-Robots-Tag: noindex, nofollow', true );
		}
	}
);

/** Whether Yoast SEO is active. */
function ra_yoast_active() {
	return defined( 'WPSEO_VERSION' );
}

/**
 * Push Theme Settings OG image / twitter handle into Yoast's options.
 */
function ra_sync_yoast_social() {
	$id = (int) ra_opt( 'ra_og_image', 0 );
	if ( ! $id ) {
		return;
	}
	$url     = wp_get_attachment_url( $id );
	$twitter = ltrim( (string) ra_opt( 'ra_twitter_site', '' ), '@' );

	$values = array(
		'wpseo_social' => array(
			'og_default_image'    => $url,
			'og_default_image_id' => $id,
		),
		'wpseo_titles' => array(
			'company_logo'       => $url,
			'company_logo_id'    => $id,
			'company_logo_meta'  => false, // Cached image data; Yoast rebuilds it from the ID.
			'company_or_person'  => 'company',
			'company_name'       => get_bloginfo( 'name' ),
		),
	);
	if ( $twitter ) {
		$values['wpseo_social']['twitter_site'] = $twitter;
	}

	foreach ( $values as $option => $pairs ) {
		if ( class_exists( 'WPSEO_Options' ) ) {
			foreach ( $pairs as $k => $v ) {
				WPSEO_Options::set( $k, $v );
			}
		} else {
			update_option( $option, array_merge( (array) get_option( $option, array() ), $pairs ) );
		}
	}
}
/**
 * After a domain change (e.g. localhost → live), make Yoast rebuild its cached URLs
 * (canonical, og:url, schema, breadcrumbs) on the first request — not only after an admin visit.
 */
add_action(
	'wp_loaded',
	function () {
		// WP-CLI uses the DB URL, not the served domain — never reset from there.
		if ( ! ra_yoast_active() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}
		$wpseo  = get_option( 'wpseo' );
		$stored = is_array( $wpseo ) && ! empty( $wpseo['home_url'] ) ? (string) $wpseo['home_url'] : '';
		if ( $stored && untrailingslashit( $stored ) !== untrailingslashit( get_home_url() ) ) {
			do_action( 'wpseo_permalink_structure_check' );
		}
		ra_maybe_resync_yoast_images();
	}
);

/** After a domain change, re-point Yoast's stored share image / logo to the current domain. */
function ra_maybe_resync_yoast_images() {
		$social = get_option( 'wpseo_social' );
		$stored = is_array( $social ) && ! empty( $social['og_default_image'] ) ? $social['og_default_image'] : '';
		$titles = get_option( 'wpseo_titles' );
		$logo   = is_array( $titles ) && ! empty( $titles['company_logo_meta']['url'] ) ? $titles['company_logo_meta']['url'] : '';
		$host   = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ( $stored && wp_parse_url( $stored, PHP_URL_HOST ) !== $host ) || ( $logo && wp_parse_url( $logo, PHP_URL_HOST ) !== $host ) ) {
			ra_sync_yoast_social();
		}
}

add_action(
	'acf/save_post',
	function ( $post_id ) {
		if ( 'options' === $post_id ) {
			ra_sync_yoast_social();
		}
	},
	20
);

/** <meta name="theme-color"> + fallback OG tags when Yoast is not active. */
add_action(
	'wp_head',
	function () {
		printf( '<meta name="theme-color" content="%s">' . "\n", esc_attr( ra_opt( 'ra_theme_color', '#0b0b0b' ) ) );

		if ( ! get_option( 'site_icon' ) ) {
			printf( '<link rel="icon" href="%s" sizes="32x32">' . "\n", esc_url( RA_URI . '/assets/images/favicon-32.png' ) );
			printf( '<link rel="icon" href="%s" sizes="192x192">' . "\n", esc_url( RA_URI . '/assets/images/favicon-192.png' ) );
			printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( RA_URI . '/assets/images/favicon-180.png' ) );
		}

		if ( ra_yoast_active() ) {
			return;
		}

		$title = is_singular() ? get_the_title() : ra_opt( 'ra_og_title', get_bloginfo( 'name' ) );
		$desc  = is_singular() && has_excerpt() ? get_the_excerpt() : ra_opt( 'ra_og_description', get_bloginfo( 'description' ) );
		$img   = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : '';
		if ( ! $img ) {
			$id  = (int) ra_opt( 'ra_og_image', 0 );
			$img = $id ? wp_get_attachment_url( $id ) : RA_URI . '/assets/images/og-image.jpg';
		}
		$url = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );

		echo '<meta property="og:type" content="' . ( is_single() ? 'article' : 'website' ) . "\">\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . "\">\n";
		echo '<meta property="og:title" content="' . esc_attr( $title ) . "\">\n";
		echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . "\">\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:image" content="' . esc_url( $img ) . "\">\n";
		echo '<meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">' . "\n";
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . "\">\n";
		}
	},
	2
);

/** FAQ items collected while rendering, printed as JSON-LD in the footer. */
$GLOBALS['ra_faq_schema'] = array();

/**
 * Register FAQ entries for schema output.
 *
 * @param array $items Rows with question / answer.
 */
function ra_add_faq_schema( $items ) {
	foreach ( (array) $items as $row ) {
		if ( empty( $row['question'] ) || empty( $row['answer'] ) ) {
			continue;
		}
		$GLOBALS['ra_faq_schema'][] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $row['question'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $row['answer'] ),
			),
		);
	}
}

add_action(
	'wp_footer',
	function () {
		if ( empty( $GLOBALS['ra_faq_schema'] ) ) {
			return;
		}
		$data = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $GLOBALS['ra_faq_schema'],
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	},
	20
);

/** Yoast breadcrumbs helper (only if enabled in Yoast). */
function ra_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'reddyanna' ) . '">', '</nav>' );
	}
}
