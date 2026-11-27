<?php
/**
 * Fallback template.
 *
 * @package EMSUNDECOR
 */

get_header();
?>
<main id="main-content" class="page-main">
	<div class="site-container page-container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'content-card' ); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<div class="entry-content"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Chưa có nội dung để hiển thị.', 'emsundecor' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
