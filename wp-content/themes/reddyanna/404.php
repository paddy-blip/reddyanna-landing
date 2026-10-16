<?php
/**
 * 404.
 *
 * @package ReddyAnna
 */

get_header();
?>
<section class="section section--404">
	<div class="container container--narrow empty-state">
		<p class="big-404" aria-hidden="true">404</p>
		<h1><?php esc_html_e( 'Page not found', 'reddyanna' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for may have moved. Try searching, or head back home.', 'reddyanna' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="btn btn--gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'reddyanna' ); ?></a></p>
	</div>
</section>
<?php
get_footer();
