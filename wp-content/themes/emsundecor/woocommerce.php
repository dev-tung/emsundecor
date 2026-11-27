<?php
/**
 * WooCommerce page wrapper.
 *
 * @package EMSUNDECOR
 */

get_header();
?>
<main id="main-content" class="page-main shop-main">
	<div class="site-container shop-container">
		<?php woocommerce_content(); ?>
	</div>
</main>
<?php
get_footer();
