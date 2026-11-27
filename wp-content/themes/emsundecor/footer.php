<?php
/**
 * Theme footer.
 *
 * @package EMSUNDECOR
 */

$business_info = emsundecor_get_business_info();
$footer_categories = emsundecor_product_category_links();
?>
<footer class="site-footer">
	<div class="site-container footer-grid">
		<div class="footer-brand">
			<a class="footer-brand__name" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
			<p><?php esc_html_e( 'Trang trí tiệc sinh nhật và decor những khoảnh khắc đáng nhớ.', 'emsundecor' ); ?></p>
			<a class="footer-request-link" href="<?php echo esc_url( home_url( '/dat-dich-vu/' ) ); ?>"><?php esc_html_e( 'Gửi yêu cầu tư vấn', 'emsundecor' ); ?> <span aria-hidden="true">→</span></a>
		</div>
		<div>
			<h2 class="footer-heading"><?php esc_html_e( 'Liên hệ', 'emsundecor' ); ?></h2>
			<p><a href="<?php echo esc_url( emsundecor_phone_href( $business_info['hotline'] ) ); ?>"><?php echo esc_html( $business_info['hotline'] ); ?></a></p>
			<p><a href="<?php echo esc_url( 'https://zalo.me/' . preg_replace( '/\D+/', '', $business_info['zalo'] ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Nhắn Zalo', 'emsundecor' ); ?></a></p>
			<p><?php echo esc_html( $business_info['address'] ); ?></p>
			<a class="footer-map-link" href="<?php echo esc_url( $business_info['maps'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Xem vị trí trên Google Maps', 'emsundecor' ); ?> <span aria-hidden="true">↗</span></a>
		</div>
		<div>
			<h2 class="footer-heading"><?php esc_html_e( 'Khám phá', 'emsundecor' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => has_nav_menu( 'footer' ) ? 'footer' : 'primary',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
		<?php if ( $footer_categories ) : ?>
			<div>
				<h2 class="footer-heading"><?php esc_html_e( 'Danh mục sản phẩm', 'emsundecor' ); ?></h2>
				<ul class="footer-menu">
					<?php foreach ( $footer_categories as $category ) : ?>
						<li><a href="<?php echo esc_url( $category['url'] ); ?>"><?php echo esc_html( $category['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<div class="footer-bottom">
		<div class="site-container footer-bottom__inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> EMSUNDECOR. <?php esc_html_e( 'All rights reserved.', 'emsundecor' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/chinh-sach-bao-mat/' ) ); ?>"><?php esc_html_e( 'Chính sách quyền riêng tư', 'emsundecor' ); ?></a>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
