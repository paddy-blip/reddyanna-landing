<?php
/**
 * Section: Stats / achievements (animated counters).
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );

ra_section_open( 'stats' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<div class="stats">
		<?php foreach ( $ra_items as $ra_s ) : ?>
			<div class="stat">
				<p class="stat__num"><span class="js-count" data-to="<?php echo esc_attr( (float) $ra_s['number'] ); ?>"><?php echo esc_html( number_format_i18n( (float) $ra_s['number'] ) ); ?></span><?php echo esc_html( $ra_s['suffix'] ); ?></p>
				<p class="stat__label"><?php echo esc_html( $ra_s['label'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;

ra_section_close();
