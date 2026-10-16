<?php
/**
 * Section: Hero.
 *
 * @package ReddyAnna
 */

$ra_tag   = get_sub_field( 'heading_tag' ) === 'h2' ? 'h2' : 'h1';
$ra_size  = get_sub_field( 'size' ) ? get_sub_field( 'size' ) : 'large';
$ra_img   = (int) get_sub_field( 'image' );
$ra_hls   = get_sub_field( 'highlights' );
$ra_first = empty( $GLOBALS['ra_section_index'] );

ra_section_open( 'hero', 'hero--' . $ra_size );
?>
<div class="hero__grid">
	<div class="hero__content">
		<?php
		if ( $ra_first && ! is_front_page() ) {
			ra_breadcrumbs();
		}
		?>
		<?php if ( get_sub_field( 'eyebrow' ) ) : ?>
			<p class="eyebrow"><?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
		<?php endif; ?>
		<?php printf( '<%1$s class="hero__title">%2$s</%1$s>', $ra_tag, ra_heading( get_sub_field( 'title' ) ) ); // phpcs:ignore ?>
		<?php if ( get_sub_field( 'text' ) ) : ?>
			<p class="hero__text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
		<?php endif; ?>

		<?php if ( $ra_hls ) : ?>
			<ul class="hero__highlights">
				<?php foreach ( $ra_hls as $ra_hl ) : ?>
					<li><?php echo ra_icon( 'check', '', 18 ); // phpcs:ignore ?><?php echo esc_html( $ra_hl['text'] ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="btn-row">
			<?php echo ra_button( get_sub_field( 'button_1' ), 'btn btn--gold btn--lg' ); // phpcs:ignore ?>
			<?php echo ra_button( get_sub_field( 'button_2' ), 'btn btn--ghost btn--lg' ); // phpcs:ignore ?>
		</div>
	</div>

	<?php if ( 'large' === $ra_size ) : ?>
		<div class="hero__visual">
			<?php
			if ( $ra_img ) {
				echo wp_get_attachment_image(
					$ra_img,
					'large',
					false,
					array(
						'class'         => 'hero__img',
						'loading'       => $ra_first ? 'eager' : 'lazy',
						'fetchpriority' => $ra_first ? 'high' : 'auto',
						'sizes'         => '(max-width: 900px) 90vw, 520px',
					)
				);
			} else {
				get_template_part( 'template-parts/content/visual-id-card' );
			}
			?>
		</div>
	<?php endif; ?>
</div>
<?php
ra_section_close();
