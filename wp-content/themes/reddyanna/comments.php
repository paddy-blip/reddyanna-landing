<?php
/**
 * Comments.
 *
 * @package ReddyAnna
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title"><?php comments_number( __( 'No comments', 'reddyanna' ), __( '1 Comment', 'reddyanna' ), __( '% Comments', 'reddyanna' ) ); ?></h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</div>
