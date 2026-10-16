<?php
/**
 * Section: Split cards (vision / mission).
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );

ra_section_open( 'split-cards' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<div class="grid grid--2">
		<?php foreach ( $ra_items as $ra_c ) : ?>
			<div class="vm-card">
				<span class="feature__icon"><?php echo ra_icon( $ra_c['icon'], '', 28 ); // phpcs:ignore ?></span>
				<h3 class="vm-card__title"><?php echo esc_html( $ra_c['title'] ); ?></h3>
				<div class="prose"><?php echo wp_kses_post( $ra_c['text'] ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;

ra_section_close();
