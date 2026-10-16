<?php
/**
 * Blog post card.
 *
 * @package ReddyAnna
 */

$ra_cats = get_the_category();
?>
<article <?php post_class( 'post-card' ); ?>>
	<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'ra-card',
				array(
					'loading' => 'lazy',
					'sizes'   => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 380px',
				)
			);
		} else {
			echo '<span class="post-card__placeholder">' . ra_icon( 'cricket', '', 48 ) . '</span>'; // phpcs:ignore
		}
		?>
	</a>
	<div class="post-card__body">
		<?php if ( $ra_cats ) : ?>
			<a class="post-card__cat" href="<?php echo esc_url( get_category_link( $ra_cats[0] ) ); ?>"><?php echo esc_html( $ra_cats[0]->name ); ?></a>
		<?php endif; ?>
		<h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="post-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<div class="post-card__meta">
			<span><?php echo ra_icon( 'calendar', '', 16 ); // phpcs:ignore ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
			<a class="link-arrow" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'reddyanna' ); ?><span class="screen-reader-text"> – <?php the_title(); ?></span><?php echo ra_icon( 'arrow-right', '', 16 ); // phpcs:ignore ?></a>
		</div>
	</div>
</article>
