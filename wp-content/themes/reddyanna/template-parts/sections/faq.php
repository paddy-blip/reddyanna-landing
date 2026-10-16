<?php
/**
 * Section: FAQ accordion (native <details>, works without JS).
 *
 * @package ReddyAnna
 */

$ra_items = get_sub_field( 'items' );

if ( $ra_items && get_sub_field( 'schema' ) ) {
	ra_add_faq_schema( $ra_items );
}

ra_section_open( 'faq' );
ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ) );

if ( $ra_items ) :
	?>
	<div class="faq">
		<?php foreach ( $ra_items as $ra_i => $ra_q ) : ?>
			<details class="faq__item"<?php echo 0 === $ra_i ? ' open' : ''; ?>>
				<summary class="faq__q"><span><?php echo esc_html( $ra_q['question'] ); ?></span><?php echo ra_icon( 'chevron-down', 'faq__chev', 20 ); // phpcs:ignore ?></summary>
				<div class="faq__a prose"><?php echo wp_kses_post( $ra_q['answer'] ); ?></div>
			</details>
		<?php endforeach; ?>
	</div>
	<?php
endif;

ra_section_close();
