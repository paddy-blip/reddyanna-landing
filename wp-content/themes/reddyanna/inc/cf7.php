<?php
/**
 * Contact Form 7 integration: load its assets only where a form is rendered.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

// Don't load CF7 CSS/JS globally.
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

// Remove the <p>/<br> auto-formatting so form markup stays clean.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Enqueue CF7 assets on demand (call before rendering a form).
 */
function ra_cf7_enqueue() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	// Form styling lives in the theme CSS, so only CF7's (dependency-free) JS is needed.
	if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
		wpcf7_enqueue_scripts();
	}
}

/** Also enqueue when a [contact-form-7] shortcode is used inside normal content. */
add_action(
	'wp',
	function () {
		if ( is_singular() ) {
			$post = get_post();
			if ( $post && has_shortcode( $post->post_content, 'contact-form-7' ) ) {
				add_action( 'wp_enqueue_scripts', 'ra_cf7_enqueue', 20 );
			}
		}
	}
);

/**
 * Render a CF7 form by post ID.
 *
 * @param int $form_id wpcf7_contact_form post ID.
 * @return string
 */
function ra_cf7_form( $form_id ) {
	$form_id = (int) $form_id;
	if ( ! $form_id || ! shortcode_exists( 'contact-form-7' ) ) {
		return '';
	}
	ra_cf7_enqueue();
	return do_shortcode( '[contact-form-7 id="' . $form_id . '"]' );
}
