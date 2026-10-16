<?php
/**
 * Built-in hero visual: a branded "Cricket ID" card (pure HTML/CSS, no image request).
 *
 * @package ReddyAnna
 */

?>
<div class="id-visual" aria-hidden="true">
	<div class="id-visual__ring"></div>
	<div class="id-card">
		<div class="id-card__top">
			<img src="<?php echo esc_url( RA_URI . '/assets/images/logo-440.webp' ); ?>" alt="" width="440" height="147" class="id-card__logo">
			<span class="id-card__status"><span class="dot"></span>Active</span>
		</div>
		<p class="id-card__label">Cricket ID</p>
		<p class="id-card__id">RA &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 2010</p>
		<div class="id-card__rows">
			<div><?php echo ra_icon( 'live', '', 18 ); // phpcs:ignore ?><span>Live Matches</span><strong>24/7</strong></div>
			<div><?php echo ra_icon( 'bolt', '', 18 ); // phpcs:ignore ?><span>ID Activation</span><strong>Instant</strong></div>
			<div><?php echo ra_icon( 'shield', '', 18 ); // phpcs:ignore ?><span>Account Security</span><strong>Verified</strong></div>
		</div>
	</div>
	<div class="id-visual__chip id-visual__chip--a"><?php echo ra_icon( 'headset', '', 18 ); // phpcs:ignore ?>24/7 Support</div>
	<div class="id-visual__chip id-visual__chip--b"><?php echo ra_icon( 'wallet', '', 18 ); // phpcs:ignore ?>Fast Withdrawals</div>
	<div class="id-visual__ball"></div>
</div>
