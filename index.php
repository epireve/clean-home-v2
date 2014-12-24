<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
<?php get_header(); ?>

	<div id="content" class="content">
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() );?>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&larr; Older Entries', 'cleanhome' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'cleanhome' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not found', 'cleanhome' ); ?></h2>
		<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'cleanhome' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
