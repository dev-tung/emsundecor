<?php
/**
 * Theme header.
 *
 * @package EMSUNDECOR
 */

$business_info = emsundecor_get_business_info();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Bỏ qua đến nội dung', 'emsundecor' ); ?></a>
<div class="announcement-bar">
	<div class="site-container announcement-bar__inner">
		<span><?php esc_html_e( 'Trang trí tiệc sinh nhật & những khoảnh khắc đáng nhớ', 'emsundecor' ); ?></span>
		<a class="announcement-bar__phone" href="<?php echo esc_url( emsundecor_phone_href( $business_info['hotline'] ) ); ?>">
			<svg class="announcement-bar__phone-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.57 2.8.7A2 2 0 0 1 22 16.92Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<?php echo esc_html( $business_info['hotline'] ); ?>
		</a>
	</div>
</div>
<header class="site-header">
	<div class="site-container site-header__inner">
		<div class="brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="brand__text" href="<?php echo esc_url( home_url( '/' ) ); ?>">EMSUNDECOR</a>
				<?php
			}
			?>
		</div>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation">
			<span class="menu-toggle__icon" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Mở menu', 'emsundecor' ); ?></span>
		</button>
		<nav class="primary-navigation" id="primary-navigation" aria-label="<?php esc_attr_e( 'Điều hướng chính', 'emsundecor' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'emsundecor_header' => true,
					'container'      => false,
					'menu_class'     => 'primary-menu',
					'fallback_cb'    => 'wp_page_menu',
				)
			);
			?>
		</nav>
	</div>
	<?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
		<div class="site-search-bar">
			<form class="site-container product-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="header-product-search"><?php esc_html_e( 'Tìm sản phẩm', 'emsundecor' ); ?></label>
				<input id="header-product-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Tên mẫu decor bạn muốn tìm...', 'emsundecor' ); ?>" required>
				<input type="hidden" name="post_type" value="product">
				<button type="submit"><?php esc_html_e( 'Tìm kiếm', 'emsundecor' ); ?></button>
			</form>
		</div>
	<?php endif; ?>
</header>
