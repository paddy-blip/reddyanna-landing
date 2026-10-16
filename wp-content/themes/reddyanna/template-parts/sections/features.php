<?php
/**
 * Section: Features / services grid.
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );
$ra_cols  = (int) get_sub_field( 'columns' );
$ra_cols  = $ra_cols ? $ra_cols : 3;
$ra_style = get_sub_field( 'style' ) ? get_sub_field( 'style' ) : 'card';

ra_section_open( 'features' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<div class="grid grid--<?php echo (int) $ra_cols; ?>">
		<?php foreach ( $ra_items as $ra_item ) : ?>
			<div class="feature feature--<?php echo esc_attr( $ra_style ); ?>">
				<span class="feature__icon"><?php echo ra_icon( $ra_item['icon'], '', 28 ); // phpcs:ignore ?></span>
				<?php if ( $ra_item['title'] ) : ?>
					<h3 class="feature__title"><?php echo esc_html( $ra_item['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( $ra_item['text'] ) : ?>
					<p class="feature__text"><?php echo esc_html( $ra_item['text'] ); ?></p>
				<?php endif; ?>
				<?php
				if ( ! empty( $ra_item['link'] ) ) {
					echo ra_button( $ra_item['link'], 'link-arrow', 'arrow-right' ); // phpcs:ignore
				}
				?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;

if ( get_sub_field( 'button' ) ) {
	echo '<div class="section-foot">' . ra_button( get_sub_field( 'button' ), 'btn btn--gold' ) . '</div>'; // phpcs:ignore
}

ra_section_close();
