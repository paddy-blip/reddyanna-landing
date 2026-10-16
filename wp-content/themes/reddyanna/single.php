<?php
/**
 * Single post.
 *
 * @package ReddyAnna
 */

get_header();

while ( have_posts() ) :
	the_post();
	$ra_cats = get_the_category();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
		<header class="page-hero page-hero--post">
			<div class="container container--narrow">
				<?php ra_breadcrumbs(); ?>
				<?php if ( $ra_cats ) : ?>
					<a class="post-card__cat" href="<?php echo esc_url( get_category_link( $ra_cats[0] ) ); ?>"><?php echo esc_html( $ra_cats[0]->name ); ?></a>
				<?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<p class="post-meta">
					<span><?php echo ra_icon( 'calendar', '', 16 ); // phpcs:ignore ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
					<span><?php echo ra_icon( 'clock', '', 16 ); // phpcs:ignore ?><?php /* translators: %d minutes */ printf( esc_html__( '%d min read', 'reddyanna' ), (int) ra_reading_time() ); ?></span>
				</p>
			</div>
		</header>

		<div class="section section--tight">
			<div class="container container--narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-post__thumb">
						<?php
						the_post_thumbnail(
							'ra-hero',
							array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '(max-width: 860px) 100vw, 820px',
							)
						);
						?>
					</figure>
				<?php endif; ?>

				<div class="entry-content prose">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>

				<div class="post-share">
					<span><?php esc_html_e( 'Share:', 'reddyanna' ); ?></span>
					<?php
					$ra_url   = rawurlencode( get_permalink() );
					$ra_title = rawurlencode( get_the_title() );
					?>
					<a href="https://wa.me/?text=<?php echo $ra_title . '%20' . $ra_url; // phpcs:ignore ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><?php echo ra_icon( 'whatsapp', '', 18 ); // phpcs:ignore ?></a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $ra_url; // phpcs:ignore ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><?php echo ra_icon( 'facebook', '', 18 ); // phpcs:ignore ?></a>
					<a href="https://x.com/intent/post?url=<?php echo $ra_url; // phpcs:ignore ?>&amp;text=<?php echo $ra_title; // phpcs:ignore ?>" target="_blank" rel="noopener noreferrer" aria-label="X"><?php echo ra_icon( 'x', '', 18 ); // phpcs:ignore ?></a>
					<a href="https://t.me/share/url?url=<?php echo $ra_url; // phpcs:ignore ?>&amp;text=<?php echo $ra_title; // phpcs:ignore ?>" target="_blank" rel="noopener noreferrer" aria-label="Telegram"><?php echo ra_icon( 'telegram', '', 18 ); // phpcs:ignore ?></a>
				</div>

				<?php the_post_navigation(); ?>
			</div>
		</div>
	</article>

	<?php
	$ra_related = new WP_Query(
		array(
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'category__in'        => wp_list_pluck( $ra_cats, 'term_id' ),
		)
	);
	if ( $ra_related->found_posts || $ra_related->have_posts() ) :
		?>
		<section class="section bg--alt">
			<div class="container">
				<?php ra_section_heading( __( 'Keep Reading', 'reddyanna' ), __( 'Related <span>Articles</span>', 'reddyanna' ) ); ?>
				<div class="post-grid">
					<?php
					while ( $ra_related->have_posts() ) :
						$ra_related->the_post();
						get_template_part( 'template-parts/content/card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

	if ( comments_open() || get_comments_number() ) {
		echo '<div class="section"><div class="container container--narrow">';
		comments_template();
		echo '</div></div>';
	}
endwhile;

get_footer();
