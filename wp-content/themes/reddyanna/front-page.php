<?php
/**
 * Front page. Uses the page builder when a static page is set, otherwise the blog index.
 *
 * @package ReddyAnna
 */

if ( 'page' !== get_option( 'show_on_front' ) ) {
	get_template_part( 'index' );
	return;
}

get_template_part( 'page' );
