<?php
/**
 * Section: Contact details + Contact Form 7.
 *
 * @package ReddyAnna
 */

$ra_d     = ra_defaults();
$ra_phone = ra_opt( 'ra_phone', $ra_d['phone'] );
$ra_email = ra_opt( 'ra_email', $ra_d['email'] );
$ra_addr  = ra_opt( 'ra_address', '' );
$ra_form  = (int) get_sub_field( 'form' );
$ra_show  = get_sub_field( 'show_details' );

ra_section_open( 'contact' );
?>
<div class="contact">
	<div class="contact__info">
		<?php ra_section_heading( get_sub_field( 'eyebrow' ), get_sub_field( 'title' ), get_sub_field( 'intro' ), 'h2', 'left' ); ?>

		<?php if ( $ra_show ) : ?>
			<ul class="contact-cards">
				<?php if ( $ra_phone ) : ?>
					<li class="contact-card">
						<span class="feature__icon"><?php echo ra_icon( 'phone', '', 22 ); // phpcs:ignore ?></span>
						<div><h3><?php esc_html_e( 'Call Us', 'reddyanna' ); ?></h3><a href="<?php echo esc_attr( ra_tel( $ra_phone ) ); ?>"><?php echo esc_html( $ra_phone ); ?></a></div>
					</li>
				<?php endif; ?>
				<li class="contact-card">
					<span class="feature__icon"><?php echo ra_icon( 'whatsapp', '', 22 ); // phpcs:ignore ?></span>
					<div><h3><?php esc_html_e( 'WhatsApp', 'reddyanna' ); ?></h3><a href="<?php echo esc_url( ra_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Start a chat', 'reddyanna' ); ?></a></div>
				</li>
				<?php if ( $ra_email ) : ?>
					<li class="contact-card">
						<span class="feature__icon"><?php echo ra_icon( 'mail', '', 22 ); // phpcs:ignore ?></span>
						<div><h3><?php esc_html_e( 'Email', 'reddyanna' ); ?></h3><a href="mailto:<?php echo esc_attr( antispambot( $ra_email ) ); ?>"><?php echo esc_html( antispambot( $ra_email ) ); ?></a></div>
					</li>
				<?php endif; ?>
				<?php if ( $ra_addr ) : ?>
					<li class="contact-card">
						<span class="feature__icon"><?php echo ra_icon( 'map-pin', '', 22 ); // phpcs:ignore ?></span>
						<div><h3><?php esc_html_e( 'Service Area', 'reddyanna' ); ?></h3><span><?php echo wp_kses( $ra_addr, array( 'br' => array() ) ); ?></span></div>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( $ra_form ) : ?>
		<div class="contact__form">
			<?php if ( get_sub_field( 'form_title' ) ) : ?>
				<h3 class="contact__form-title"><?php echo esc_html( get_sub_field( 'form_title' ) ); ?></h3>
			<?php endif; ?>
			<?php echo ra_cf7_form( $ra_form ); // phpcs:ignore ?>
		</div>
	<?php endif; ?>
</div>
<?php
ra_section_close();
