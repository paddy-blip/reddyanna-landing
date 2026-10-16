<?php
/**
 * Site footer.
 *
 * @package ReddyAnna
 */

$ra_d     = ra_defaults();
$ra_phone = ra_opt( 'ra_phone', $ra_d['phone'] );
$ra_email = ra_opt( 'ra_email', $ra_d['email'] );
$ra_addr  = ra_opt( 'ra_address', '' );
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="footer-col footer-col--brand">
			<?php echo ra_logo( 'footer' ); // phpcs:ignore ?>
			<?php if ( ra_opt( 'ra_footer_about' ) ) : ?>
				<p class="footer-about"><?php echo esc_html( ra_opt( 'ra_footer_about' ) ); ?></p>
			<?php endif; ?>
			<?php echo ra_social_links(); // phpcs:ignore ?>
		</div>

		<div class="footer-col">
			<h2 class="footer-title"><?php echo esc_html( ra_opt( 'ra_footer_contact_title', __( 'Get in Touch', 'reddyanna' ) ) ); ?></h2>
			<ul class="footer-contact">
				<?php if ( $ra_email ) : ?>
					<li><?php echo ra_icon( 'mail', '', 18 ); // phpcs:ignore ?><a href="mailto:<?php echo esc_attr( antispambot( $ra_email ) ); ?>"><?php echo esc_html( antispambot( $ra_email ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( $ra_phone ) : ?>
					<li><?php echo ra_icon( 'phone', '', 18 ); // phpcs:ignore ?><a href="<?php echo esc_attr( ra_tel( $ra_phone ) ); ?>"><?php echo esc_html( $ra_phone ); ?></a></li>
				<?php endif; ?>
				<li><?php echo ra_icon( 'whatsapp', '', 18 ); // phpcs:ignore ?><a href="<?php echo esc_url( ra_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Chat on WhatsApp', 'reddyanna' ); ?></a></li>
				<?php if ( $ra_addr ) : ?>
					<li><?php echo ra_icon( 'map-pin', '', 18 ); // phpcs:ignore ?><span><?php echo wp_kses( $ra_addr, array( 'br' => array() ) ); ?></span></li>
				<?php endif; ?>
			</ul>
		</div>

		<nav class="footer-col" aria-label="<?php echo esc_attr( ra_opt( 'ra_footer_pages_title', __( 'Important Pages', 'reddyanna' ) ) ); ?>">
			<h2 class="footer-title"><?php echo esc_html( ra_opt( 'ra_footer_pages_title', __( 'Important Pages', 'reddyanna' ) ) ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer_pages',
					'container'      => false,
					'menu_class'     => 'footer-links',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<nav class="footer-col" aria-label="<?php echo esc_attr( ra_opt( 'ra_footer_legal_title', __( 'Legal Pages', 'reddyanna' ) ) ); ?>">
			<h2 class="footer-title"><?php echo esc_html( ra_opt( 'ra_footer_legal_title', __( 'Legal Pages', 'reddyanna' ) ) ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer_legal',
					'container'      => false,
					'menu_class'     => 'footer-links',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>

	<?php if ( ra_opt( 'ra_footer_disclaimer' ) ) : ?>
		<div class="container footer-disclaimer">
			<span class="badge-18" aria-hidden="true">18+</span>
			<p><?php echo esc_html( ra_opt( 'ra_footer_disclaimer' ) ); ?></p>
		</div>
	<?php endif; ?>

	<div class="footer-bottom">
		<div class="container"><p><?php echo ra_copyright(); // phpcs:ignore ?></p></div>
	</div>
</footer>

<?php if ( ra_opt( 'ra_whatsapp_float', true ) ) : ?>
	<a class="wa-float" href="<?php echo esc_url( ra_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'reddyanna' ); ?>">
		<?php echo ra_icon( 'whatsapp', '', 28 ); // phpcs:ignore ?>
		<span class="wa-float__label"><?php esc_html_e( 'Get ID on WhatsApp', 'reddyanna' ); ?></span>
	</a>
<?php endif; ?>

<button class="to-top js-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'reddyanna' ); ?>" hidden><?php echo ra_icon( 'chevron-down', '', 20 ); // phpcs:ignore ?></button>

<?php wp_footer(); ?>
</body>
</html>
