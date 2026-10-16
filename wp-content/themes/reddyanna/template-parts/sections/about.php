<?php
/**
 * Section: Text + image (About).
 *
 * @package ReddyAnna
 */

$ra_img    = (int) get_sub_field( 'image' );
$ra_pos    = get_sub_field( 'image_position' ) === 'left' ? 'left' : 'right';
$ra_points = get_sub_field( 'points' );
$ra_years  = (int) gmdate( 'Y' ) - 2010;

ra_section_open( 'about', 'about--img-' . $ra_pos );
?>
<div class="split">
	<div class="split__content">
		<?php ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), '', 'h2', 'left' ); ?>
		<div class="prose"><?php echo wp_kses_post( get_sub_field( 'content' ) ); ?></div>

		<?php if ( $ra_points ) : ?>
			<ul class="points">
				<?php foreach ( $ra_points as $ra_p ) : ?>
					<li class="point">
						<span class="point__icon"><?php echo ra_icon( $ra_p['icon'], '', 22 ); // phpcs:ignore ?></span>
						<div>
							<h3 class="point__title"><?php echo esc_html( $ra_p['title'] ); ?></h3>
							<?php if ( $ra_p['text'] ) : ?>
								<p><?php echo esc_html( $ra_p['text'] ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( get_sub_field( 'button' ) ) : ?>
			<div class="btn-row"><?php echo ra_button( get_sub_field( 'button' ), 'btn btn--gold' ); // phpcs:ignore ?></div>
		<?php endif; ?>
	</div>

	<div class="split__media">
		<?php if ( $ra_img ) : ?>
			<?php
			echo wp_get_attachment_image(
				$ra_img,
				'large',
				false,
				array(
					'class'   => 'split__img',
					'loading' => 'lazy',
					'sizes'   => '(max-width: 900px) 90vw, 520px',
				)
			);
			?>
		<?php else : ?>
			<div class="trust-visual" aria-hidden="true">
				<div class="trust-visual__badge">
					<span class="trust-visual__num"><?php echo (int) $ra_years; ?>+</span>
					<span class="trust-visual__txt">Years of Trust</span>
				</div>
				<div class="trust-visual__since">Since 2010</div>
				<div class="trust-visual__icons">
					<span><?php echo ra_icon( 'shield', '', 26 ); // phpcs:ignore ?></span>
					<span><?php echo ra_icon( 'cricket', '', 26 ); // phpcs:ignore ?></span>
					<span><?php echo ra_icon( 'trophy', '', 26 ); // phpcs:ignore ?></span>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
ra_section_close();
