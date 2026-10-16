<?php
/**
 * Template helpers.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get a Theme Settings value (ACF options page) with a default.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback when empty / ACF inactive.
 * @return mixed
 */
function ra_opt( $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	if ( null === $value || '' === $value || false === $value && ! is_bool( $default ) ) {
		return $default;
	}
	return $value;
}

/** Brand defaults (also used as placeholders in Theme Settings). */
function ra_defaults() {
	return array(
		'phone'      => '+91 90000 00000',
		'email'      => 'support@reddyanna.com',
		'whatsapp'   => '919000000000',
		'wa_message' => 'Hi Reddy Anna, I would like to get my Cricket ID.',
	);
}

/**
 * WhatsApp click-to-chat URL.
 *
 * @param string|null $message Optional pre-filled message.
 * @return string
 */
function ra_whatsapp_url( $message = null ) {
	$d      = ra_defaults();
	$number = preg_replace( '/\D+/', '', (string) ra_opt( 'ra_whatsapp_number', $d['whatsapp'] ) );
	$text   = null === $message ? ra_opt( 'ra_whatsapp_message', $d['wa_message'] ) : $message;
	return 'https://wa.me/' . $number . ( $text ? '?text=' . rawurlencode( $text ) : '' );
}

/**
 * Resolve special URL tokens: "#whatsapp" -> WhatsApp chat link.
 *
 * @param string $url URL.
 * @return string
 */
function ra_resolve_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url || '#whatsapp' === $url || false !== strpos( $url, '#whatsapp' ) ) {
		return ra_whatsapp_url();
	}
	return ra_absolute_url( $url );
}

/**
 * Site-relative paths ("/about-us/") resolve against the current home URL,
 * so stored links keep working when the site moves to its live domain.
 *
 * @param string $url URL or path.
 * @return string
 */
function ra_absolute_url( $url ) {
	if ( isset( $url[0] ) && '/' === $url[0] && ( ! isset( $url[1] ) || '/' !== $url[1] ) ) {
		return home_url( $url );
	}
	return $url;
}

/**
 * Rewrite href="/path" in HTML content to the current home URL.
 *
 * @param string $html HTML.
 * @return string
 */
function ra_absolute_links( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, 'href="/' ) ) {
		return $html;
	}
	return preg_replace_callback(
		'#href="(/(?!/)[^"]*)"#',
		function ( $m ) {
			return 'href="' . esc_url( home_url( $m[1] ) ) . '"';
		},
		$html
	);
}
add_filter( 'the_content', 'ra_absolute_links', 20 );
add_filter( 'acf/format_value/type=wysiwyg', 'ra_absolute_links', 20 );
add_filter( 'acf/format_value/type=textarea', 'ra_absolute_links', 20 );

/** Whether a URL leaves the site. */
function ra_is_external( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host;
}

/**
 * Render a button from an ACF link array.
 *
 * @param array|string $link  ACF link array (url,title,target) or URL.
 * @param string       $class Button classes.
 * @param string       $icon  Optional icon name.
 * @param string       $label Fallback label.
 * @return string
 */
function ra_button( $link, $class = 'btn btn--gold', $icon = '', $label = '' ) {
	if ( empty( $link ) ) {
		return '';
	}
	if ( is_string( $link ) ) {
		$link = array(
			'url'    => $link,
			'title'  => $label,
			'target' => '',
		);
	}
	$url   = ra_resolve_url( isset( $link['url'] ) ? $link['url'] : '' );
	$title = ! empty( $link['title'] ) ? $link['title'] : $label;
	if ( ! $title ) {
		return '';
	}
	$is_wa  = false !== strpos( $url, 'wa.me' );
	$target = ( ! empty( $link['target'] ) || ra_is_external( $url ) ) ? ' target="_blank" rel="noopener noreferrer"' : '';
	if ( ! $icon && $is_wa ) {
		$icon = 'whatsapp';
	}
	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s<span>%5$s</span></a>',
		esc_attr( $class ),
		esc_url( $url ),
		$target,
		$icon ? ra_icon( $icon, 'btn__icon', 20 ) : '',
		esc_html( $title )
	);
}

/**
 * Allowed tags for headings (gold highlight via <span>).
 *
 * @param string $text Heading text.
 * @return string
 */
function ra_heading( $text ) {
	return wp_kses(
		$text,
		array(
			'span'   => array( 'class' => true ),
			'br'     => array(),
			'strong' => array(),
			'em'     => array(),
		)
	);
}

/**
 * Section heading block.
 *
 * @param string $eyebrow Small label.
 * @param string $title   Heading.
 * @param string $intro   Intro text.
 * @param string $tag     Heading tag.
 * @param string $align   center|left.
 */
function ra_section_heading( $eyebrow, $title, $intro = '', $tag = 'h2', $align = 'center' ) {
	if ( ! $eyebrow && ! $title && ! $intro ) {
		return;
	}
	$tag = in_array( $tag, array( 'h1', 'h2', 'h3' ), true ) ? $tag : 'h2';
	echo '<header class="section-head section-head--' . esc_attr( $align ) . '">';
	if ( $eyebrow ) {
		echo '<p class="eyebrow">' . esc_html( $eyebrow ) . '</p>';
	}
	if ( $title ) {
		printf( '<%1$s class="section-title">%2$s</%1$s>', $tag, ra_heading( $title ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
	if ( $intro ) {
		echo '<p class="section-intro">' . esc_html( $intro ) . '</p>';
	}
	echo '</header>';
}

/**
 * Opening wrapper for a builder section.
 *
 * @param string $layout Layout slug.
 * @param string $extra  Extra classes.
 */
function ra_section_open( $layout, $extra = '' ) {
	$id  = function_exists( 'get_sub_field' ) ? get_sub_field( 'section_id' ) : '';
	$bg  = function_exists( 'get_sub_field' ) ? get_sub_field( 'background' ) : '';
	$cls = trim( 'section section--' . $layout . ' ' . ( $bg ? 'bg--' . $bg : '' ) . ' ' . $extra );
	printf( '<section class="%s"%s>', esc_attr( $cls ), $id ? ' id="' . esc_attr( sanitize_title( $id ) ) . '"' : '' );
	echo '<div class="container">';
}

/** Closing wrapper for a builder section. */
function ra_section_close() {
	echo '</div></section>';
}

/**
 * Logo markup.
 *
 * @param string $context header|footer.
 * @return string
 */
function ra_logo( $context = 'header' ) {
	$field = 'footer' === $context ? 'ra_footer_logo' : 'ra_header_logo';
	$id    = (int) ra_opt( $field, 0 );
	if ( ! $id && 'footer' === $context ) {
		$id = (int) ra_opt( 'ra_header_logo', 0 );
	}
	if ( ! $id ) {
		$id = (int) get_theme_mod( 'custom_logo' );
	}
	$name  = get_bloginfo( 'name' );
	$width = (int) ra_opt( 'footer' === $context ? 'ra_footer_logo_width' : 'ra_logo_width', 'footer' === $context ? 200 : 170 );

	if ( $id ) {
		$img = wp_get_attachment_image(
			$id,
			'medium_large',
			false,
			array(
				'class'         => 'logo__img',
				'alt'           => $name,
				'loading'       => 'header' === $context ? 'eager' : 'lazy',
				'fetchpriority' => 'header' === $context ? 'high' : 'auto',
				'sizes'         => $width . 'px',
				'style'         => 'width:' . $width . 'px',
			)
		);
	} else {
		$img = sprintf(
			'<img class="logo__img" src="%1$s" alt="%2$s" width="440" height="147" style="width:%3$dpx" %4$s>',
			esc_url( RA_URI . '/assets/images/logo-440.webp' ),
			esc_attr( $name ),
			$width,
			'header' === $context ? 'fetchpriority="high"' : 'loading="lazy"'
		);
	}
	return sprintf( '<a class="logo logo--%1$s" href="%2$s" rel="home" aria-label="%3$s">%4$s</a>', esc_attr( $context ), esc_url( home_url( '/' ) ), esc_attr( $name . ' – ' . __( 'Home', 'reddyanna' ) ), $img );
}

/** Social links list. */
function ra_social_links( $class = 'social' ) {
	$items = ra_opt( 'ra_social', array() );
	if ( empty( $items ) || ! is_array( $items ) ) {
		return '';
	}
	$out = '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		if ( empty( $item['url'] ) ) {
			continue;
		}
		$net  = ! empty( $item['network'] ) ? $item['network'] : 'globe';
		$out .= sprintf(
			'<li><a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a></li>',
			esc_url( $item['url'] ),
			esc_attr( ucfirst( $net ) ),
			ra_icon( $net, '', 18 )
		);
	}
	return $out . '</ul>';
}

/** Copyright line with {year} and {site} tokens. */
function ra_copyright() {
	$text = ra_opt( 'ra_copyright', 'Copyright © {year} {site}. All Rights Reserved.' );
	return esc_html( str_replace( array( '{year}', '{site}' ), array( gmdate( 'Y' ), get_bloginfo( 'name' ) ), $text ) );
}

/** tel: href from a display phone number. */
function ra_tel( $phone ) {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $phone );
}

/** Estimated reading time in minutes. */
function ra_reading_time( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

/** Render builder sections for the current post. Returns true if any were output. */
function ra_render_sections() {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'ra_sections' ) ) {
		return false;
	}
	$GLOBALS['ra_section_index'] = 0;
	while ( have_rows( 'ra_sections' ) ) {
		the_row();
		$layout = get_row_layout();
		get_template_part( 'template-parts/sections/' . $layout );
		$GLOBALS['ra_section_index']++;
	}
	return true;
}

/** Whether the current singular page uses builder sections. */
function ra_has_sections( $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}
	$post_id = $post_id ? $post_id : get_queried_object_id();
	$rows    = get_post_meta( $post_id, 'ra_sections', true );
	return ! empty( $rows );
}
