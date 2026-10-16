<?php
/**
 * Section: Latest posts.
 *
 * @package ReddyAnna
 */

$ra_count = (int) get_sub_field( 'count' );
$ra_args  = array(
	'posts_per_page'      => $ra_count ? $ra_count : 3,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( get_sub_field( 'category' ) ) {
	$ra_args['cat'] = (int) get_sub_field( 'category' );
}
$ra_q = new WP_Query( $ra_args );

if ( $ra_q->have_posts() ) :
	ra_section_open( 'posts' );
	ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );
	echo '<div class="post-grid">';
	while ( $ra_q->have_posts() ) {
		$ra_q->the_post();
		get_template_part( 'template-parts/content/card' );
	}
	wp_reset_postdata();
	echo '</div>';
	if ( get_sub_field( 'button' ) ) {
		echo '<div class="section-foot">' . ra_button( get_sub_field( 'button' ), 'btn btn--ghost' ) . '</div>'; // phpcs:ignore
	}
	ra_section_close();
endif;
