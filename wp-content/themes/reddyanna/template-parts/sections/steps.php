<?php
/**
 * Section: Steps.
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );

ra_section_open( 'steps' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<ol class="steps">
		<?php foreach ( $ra_items as $ra_i => $ra_s ) : ?>
			<li class="step">
				<span class="step__num"><?php echo esc_html( str_pad( (string) ( $ra_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<h3 class="step__title"><?php echo esc_html( $ra_s['title'] ); ?></h3>
				<p><?php echo esc_html( $ra_s['text'] ); ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php
endif;

if ( get_sub_field( 'button' ) ) {
	echo '<div class="section-foot">' . ra_button( get_sub_field( 'button' ), 'btn btn--gold' ) . '</div>'; // phpcs:ignore
}

ra_section_close();
