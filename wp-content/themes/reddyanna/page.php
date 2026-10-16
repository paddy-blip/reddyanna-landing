<?php
/**
 * Pages: ACF sections when present, otherwise standard content (legal pages etc.).
 *
 * @package ReddyAnna
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( ! ra_render_sections() ) :
		get_template_part( 'template-parts/content/page-hero' );
		?>
		<div class="section">
			<div class="container container--narrow">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content prose' ); ?>>
					<?php
					the_content();
					wp_link_pages();
					?>
				</article>
			</div>
		</div>
		<?php
	endif;
endwhile;

get_footer();
