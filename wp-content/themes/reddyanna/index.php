<?php
/**
 * Blog index, archives and search results.
 *
 * @package ReddyAnna
 */

get_header();

if ( is_home() && ! is_front_page() ) {
	$ra_title = single_post_title( '', false );
	$ra_sub   = get_the_excerpt( get_option( 'page_for_posts' ) );
} elseif ( is_search() ) {
	/* translators: %s: search query */
	$ra_title = sprintf( __( 'Search results for "%s"', 'reddyanna' ), get_search_query() );
	$ra_sub   = '';
} elseif ( is_archive() ) {
	$ra_title = wp_strip_all_tags( get_the_archive_title() );
	$ra_sub   = get_the_archive_description();
} else {
	$ra_title = __( 'Latest Articles', 'reddyanna' );
	$ra_sub   = '';
}

get_template_part(
	'template-parts/content/page-hero',
	null,
	array(
		'title'    => $ra_title,
		'subtitle' => $ra_sub,
	)
);
?>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/card' );
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => '&larr;<span class="screen-reader-text"> ' . __( 'Previous', 'reddyanna' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'reddyanna' ) . ' </span>&rarr;',
				)
			);
			?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nothing found', 'reddyanna' ); ?></h2>
				<p><?php esc_html_e( 'Try a different search term.', 'reddyanna' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
