<?php
/**
 * Section: Free content.
 *
 * @package ReddyAnna
 */

ra_section_open( 'content', get_sub_field( 'narrow' ) ? 'is-narrow' : '' );
echo '<div class="prose">' . wp_kses_post( get_sub_field( 'content' ) ) . '</div>';
ra_section_close();
