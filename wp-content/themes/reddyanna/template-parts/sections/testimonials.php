<?php
/**
 * Section: Testimonials (CSS scroll-snap carousel on mobile, grid on desktop).
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );

ra_section_open( 'testimonials' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<div class="testimonials" tabindex="0" aria-label="<?php esc_attr_e( 'Customer reviews', 'reddyanna' ); ?>">
		<?php foreach ( $ra_items as $ra_t ) : ?>
			<?php $ra_rating = max( 1, min( 5, (int) $ra_t['rating'] ) ); ?>
			<figure class="testimonial">
				<div class="testimonial__stars" role="img" aria-label="<?php /* translators: %d rating */ printf( esc_attr__( 'Rated %d out of 5', 'reddyanna' ), (int) $ra_rating ); ?>">
					<?php echo str_repeat( ra_icon( 'star', 'is-filled', 16 ), $ra_rating ); // phpcs:ignore ?>
				</div>
				<blockquote><p><?php echo esc_html( $ra_t['text'] ); ?></p></blockquote>
				<figcaption>
					<span class="testimonial__avatar" aria-hidden="true"><?php echo esc_html( mb_substr( $ra_t['name'], 0, 1 ) ); ?></span>
					<span><strong><?php echo esc_html( $ra_t['name'] ); ?></strong><?php echo $ra_t['location'] ? '<small>' . esc_html( $ra_t['location'] ) . '</small>' : ''; ?></span>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
	<?php
endif;

ra_section_close();
