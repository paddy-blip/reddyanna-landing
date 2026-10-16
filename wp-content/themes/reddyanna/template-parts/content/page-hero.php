<?php
/**
 * Compact page title banner for non-builder views.
 *
 * @package ReddyAnna
 */

$ra_title = isset( $args['title'] ) ? $args['title'] : get_the_title();
$ra_sub   = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
?>
<section class="page-hero">
	<div class="container">
		<?php ra_breadcrumbs(); ?>
		<h1 class="page-hero__title"><?php echo esc_html( $ra_title ); ?></h1>
		<?php if ( $ra_sub ) : ?>
			<p class="page-hero__sub"><?php echo wp_kses_post( $ra_sub ); ?></p>
		<?php endif; ?>
	</div>
</section>
