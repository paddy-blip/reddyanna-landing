<?php
/**
 * Site header.
 *
 * @package ReddyAnna
 */

$ra_sticky = ra_opt( 'ra_sticky_header', true );
$ra_search = ra_opt( 'ra_header_search', true );
$ra_btn    = ra_opt( 'ra_header_button', array() );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'reddyanna' ); ?></a>

<?php if ( ra_opt( 'ra_topbar_enable', false ) && ra_opt( 'ra_topbar_text' ) ) : ?>
	<div class="topbar"><div class="container"><?php echo esc_html( ra_opt( 'ra_topbar_text' ) ); ?></div></div>
<?php endif; ?>

<header class="site-header<?php echo $ra_sticky ? ' is-sticky' : ''; ?>" id="site-header">
	<div class="container site-header__inner">
		<?php echo ra_logo( 'header' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

		<nav class="nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'reddyanna' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'ra_menu_fallback',
				)
			);
			?>
			<div class="nav__mobile-cta">
				<?php echo ra_button( $ra_btn, 'btn btn--gold btn--block' ); // phpcs:ignore ?>
			</div>
		</nav>

		<div class="site-header__actions">
			<?php if ( $ra_search ) : ?>
				<button class="icon-btn js-search-open" type="button" aria-label="<?php esc_attr_e( 'Open search', 'reddyanna' ); ?>" aria-controls="search-panel" aria-expanded="false"><?php echo ra_icon( 'search', '', 20 ); // phpcs:ignore ?></button>
			<?php endif; ?>
			<?php echo ra_button( $ra_btn, 'btn btn--gold btn--sm header-cta' ); // phpcs:ignore ?>
			<button class="icon-btn nav-toggle js-nav-toggle" type="button" aria-label="<?php esc_attr_e( 'Menu', 'reddyanna' ); ?>" aria-controls="primary-nav" aria-expanded="false">
				<?php echo ra_icon( 'menu', 'nav-toggle__open', 24 ) . ra_icon( 'close', 'nav-toggle__close', 24 ); // phpcs:ignore ?>
			</button>
		</div>
	</div>

	<?php if ( $ra_search ) : ?>
		<div class="search-panel" id="search-panel" hidden>
			<div class="container">
				<?php get_search_form(); ?>
				<button class="icon-btn js-search-close" type="button" aria-label="<?php esc_attr_e( 'Close search', 'reddyanna' ); ?>"><?php echo ra_icon( 'close', '', 22 ); // phpcs:ignore ?></button>
			</div>
		</div>
	<?php endif; ?>
</header>

<main id="main" class="site-main" tabindex="-1">
