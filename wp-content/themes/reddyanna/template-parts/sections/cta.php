<?php
/**
 * Section: CTA banner.
 *
 * @package ReddyAnna
 */

$ra_style = get_sub_field( 'style' ) === 'dark' ? 'dark' : 'gold';

ra_section_open( 'cta' );
?>
<div class="cta cta--<?php echo esc_attr( $ra_style ); ?>">
	<div class="cta__content">
		<h2 class="cta__title"><?php echo ra_heading( get_sub_field( 'title' ) ); // phpcs:ignore ?></h2>
		<?php if ( get_sub_field( 'text' ) ) : ?>
			<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
		<?php endif; ?>
	</div>
	<div class="btn-row">
		<?php echo ra_button( get_sub_field( 'button_1' ), 'gold' === $ra_style ? 'btn btn--dark btn--lg' : 'btn btn--gold btn--lg' ); // phpcs:ignore ?>
		<?php echo ra_button( get_sub_field( 'button_2' ), 'btn btn--ghost btn--lg' ); // phpcs:ignore ?>
	</div>
</div>
<?php
ra_section_close();
