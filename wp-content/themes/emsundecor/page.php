<?php
/**
 * Default page template.
 *
 * @package EMSUNDECOR
 */

get_header();
?>
<main id="main-content" class="page-main">
	<div class="site-container page-container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header class="page-heading">
				<p class="eyebrow"><span class="eyebrow__line"></span><?php esc_html_e( 'EMSUNDECOR', 'emsundecor' ); ?></p>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
