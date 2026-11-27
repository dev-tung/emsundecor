<?php
/**
 * Front page.
 *
 * @package EMSUNDECOR
 */

get_header();

$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'orderby'    => 'term_id',
		'order'      => 'ASC',
		'exclude'    => array( get_option( 'default_product_cat' ) ),
	)
);

$vip_slug = 'sinh-nhat-vip';
?>
<main id="main-content">
	<section class="hero-section">
		<div class="site-container hero-grid">
			<div class="hero-copy">
				<p class="eyebrow"><span class="eyebrow__line"></span><?php esc_html_e( 'EMSUNDECOR · DECOR YOUR HAPPY MOMENTS', 'emsundecor' ); ?></p>
				<h1><?php esc_html_e( 'Sinh nhật thêm rực rỡ, khoảnh khắc thêm đáng nhớ', 'emsundecor' ); ?></h1>
				<p class="hero-description"><?php esc_html_e( 'Khám phá các mẫu trang trí tiệc sinh nhật và lựa chọn phong cách phù hợp với ngày vui của bạn.', 'emsundecor' ); ?></p>
				<div class="hero-actions">
					<a class="button" href="<?php echo esc_url( home_url( '/san-pham/' ) ); ?>"><?php esc_html_e( 'Khám phá mẫu decor', 'emsundecor' ); ?><span aria-hidden="true">↗</span></a>
					<a class="button button--outline" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php esc_html_e( 'Liên hệ tư vấn', 'emsundecor' ); ?></a>
				</div>
				<div class="hero-note">
					<span class="hero-note__sparkle" aria-hidden="true">✦</span>
					<p><?php esc_html_e( 'Ý tưởng trang trí cho những dịp đặc biệt của gia đình.', 'emsundecor' ); ?></p>
				</div>
			</div>
			<div class="hero-art" aria-label="<?php esc_attr_e( 'Minh họa bóng bay và bánh sinh nhật', 'emsundecor' ); ?>" role="img">
				<div class="hero-art__halo"></div>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/party-hero.svg' ) ); ?>" alt="" width="620" height="500">
				<span class="hero-art__badge"><span>✦</span> <?php esc_html_e( 'HAPPY MOMENTS', 'emsundecor' ); ?></span>
			</div>
		</div>
		<div class="hero-decoration hero-decoration--one" aria-hidden="true">✦</div>
		<div class="hero-decoration hero-decoration--two" aria-hidden="true">✧</div>
	</section>

	<section class="category-section section-space">
		<div class="site-container">
			<div class="section-heading">
				<div>
					<p class="eyebrow"><span class="eyebrow__line"></span><?php esc_html_e( 'TÌM THEO DỊP', 'emsundecor' ); ?></p>
					<h2><?php esc_html_e( 'Chọn một chủ đề thật vui', 'emsundecor' ); ?></h2>
				</div>
				<a class="text-link" href="<?php echo esc_url( home_url( '/san-pham/' ) ); ?>"><?php esc_html_e( 'Xem tất cả sản phẩm', 'emsundecor' ); ?> <span aria-hidden="true">→</span></a>
			</div>
			<?php if ( ! is_wp_error( $categories ) && $categories ) : ?>
				<div class="category-grid">
					<?php foreach ( $categories as $index => $category ) : ?>
						<?php
						if ( 'uncategorized' === $category->slug ) {
							continue;
						}
						$is_vip = $vip_slug === $category->slug;
						$category_colors = array( 'peach', 'pink', 'lavender', 'mint', 'cream', 'blue', 'rose', 'gold' );
						$category_color = $category_colors[ $index % count( $category_colors ) ];
						?>
						<a class="category-card category-card--<?php echo esc_attr( $category_color ); ?><?php echo $is_vip ? ' category-card--vip' : ''; ?>" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
							<span class="category-card__number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
							<span class="category-card__icon" aria-hidden="true"><?php echo $is_vip ? '✦' : '🎈'; ?></span>
							<span class="category-card__title"><?php echo esc_html( $category->name ); ?></span>
							<span class="category-card__arrow" aria-hidden="true">↗</span>
							<?php if ( $is_vip ) : ?>
								<span class="category-card__note"><?php esc_html_e( 'Liên hệ để tư vấn', 'emsundecor' ); ?></span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="empty-note"><?php esc_html_e( 'Danh mục sản phẩm đang được chuẩn bị.', 'emsundecor' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="featured-section section-space">
		<div class="site-container">
			<div class="section-heading">
				<div>
					<p class="eyebrow"><span class="eyebrow__line"></span><?php esc_html_e( 'GỢI Ý CHO BẠN', 'emsundecor' ); ?></p>
					<h2><?php esc_html_e( 'Mẫu decor nổi bật', 'emsundecor' ); ?></h2>
					<p class="section-description"><?php esc_html_e( 'Tham khảo sản phẩm và mẫu trang trí được cập nhật từ EMSUNDECOR.', 'emsundecor' ); ?></p>
				</div>
				<a class="text-link" href="<?php echo esc_url( home_url( '/san-pham/' ) ); ?>"><?php esc_html_e( 'Đến cửa hàng', 'emsundecor' ); ?> <span aria-hidden="true">→</span></a>
			</div>
			<?php if ( function_exists( 'wc_get_products' ) ) : ?>
				<?php
				$products = wc_get_products(
					array(
						'status'  => 'publish',
						'limit'   => 4,
						'orderby' => 'date',
						'order'   => 'DESC',
						'return'  => 'objects',
					)
				);
				?>
				<?php if ( $products ) : ?>
					<div class="product-grid">
						<?php foreach ( $products as $product ) : ?>
							<?php
							$product_id = $product->get_id();
							$is_vip = has_term( $vip_slug, 'product_cat', $product_id );
							?>
							<article class="product-card">
								<a class="product-card__image" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
									<?php if ( $is_vip ) : ?><span class="product-card__badge"><?php esc_html_e( 'VIP', 'emsundecor' ); ?></span><?php endif; ?>
								</a>
								<div class="product-card__body">
									<h3><a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
									<?php if ( $is_vip ) : ?>
										<a class="text-link" href="<?php echo esc_url( home_url( '/lien-he/?product=' . rawurlencode( $product->get_name() ) ) ); ?>"><?php esc_html_e( 'Liên hệ hỏi giá', 'emsundecor' ); ?> <span aria-hidden="true">→</span></a>
									<?php else : ?>
										<p class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="empty-products">
						<span class="empty-products__icon" aria-hidden="true">✦</span>
						<p><?php esc_html_e( 'Các mẫu decor đang được cập nhật. Liên hệ EMSUNDECOR để được tư vấn.', 'emsundecor' ); ?></p>
						<a class="button button--outline" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php esc_html_e( 'Liên hệ ngay', 'emsundecor' ); ?></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="about-strip">
		<div class="site-container about-strip__inner">
			<div class="about-strip__mark" aria-hidden="true">E<span>✦</span></div>
			<div>
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'DECOR YOUR HAPPY MOMENTS', 'emsundecor' ); ?></p>
				<h2><?php esc_html_e( 'Ngày vui của bạn xứng đáng có một không gian thật riêng.', 'emsundecor' ); ?></h2>
				<p><?php esc_html_e( 'Cùng EMSUNDECOR tìm mẫu trang trí phù hợp với dịp đặc biệt của gia đình.', 'emsundecor' ); ?></p>
			</div>
			<a class="button button--light" href="<?php echo esc_url( home_url( '/dat-dich-vu/' ) ); ?>"><?php esc_html_e( 'Gửi yêu cầu', 'emsundecor' ); ?><span aria-hidden="true">↗</span></a>
		</div>
	</section>

	<section class="contact-section section-space">
		<div class="site-container contact-section__inner">
			<div>
				<p class="eyebrow"><span class="eyebrow__line"></span><?php esc_html_e( 'BẮT ĐẦU TỪ MỘT LỜI CHÀO', 'emsundecor' ); ?></p>
				<h2><?php esc_html_e( 'Bạn đang chuẩn bị cho dịp đặc biệt nào?', 'emsundecor' ); ?></h2>
				<p class="section-description"><?php esc_html_e( 'Gửi yêu cầu cho EMSUNDECOR hoặc liên hệ trực tiếp để trao đổi thêm.', 'emsundecor' ); ?></p>
			</div>
			<div class="contact-section__actions">
				<a class="button" href="<?php echo esc_url( home_url( '/dat-dich-vu/' ) ); ?>"><?php esc_html_e( 'Gửi yêu cầu', 'emsundecor' ); ?></a>
				<a class="button button--outline" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php esc_html_e( 'Thông tin liên hệ', 'emsundecor' ); ?></a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
