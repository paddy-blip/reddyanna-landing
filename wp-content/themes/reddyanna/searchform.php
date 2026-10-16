<?php
/**
 * Search form.
 *
 * @package ReddyAnna
 */

$ra_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $ra_id ); ?>"><?php esc_html_e( 'Search for:', 'reddyanna' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $ra_id ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Search articles…', 'reddyanna' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="search-form__btn" aria-label="<?php esc_attr_e( 'Search', 'reddyanna' ); ?>"><?php echo ra_icon( 'search', '', 20 ); // phpcs:ignore ?></button>
</form>
