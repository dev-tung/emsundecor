<?php
/**
 * EMSUNDECOR theme setup.
 *
 * @package EMSUNDECOR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function emsundecor_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 180, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu chính', 'emsundecor' ),
			'footer'  => __( 'Menu chân trang', 'emsundecor' ),
		)
	);
}
add_action( 'after_setup_theme', 'emsundecor_theme_setup' );

function emsundecor_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'emsundecor-theme', get_stylesheet_uri(), array(), $theme_version );
	wp_enqueue_style( 'emsundecor-main', get_theme_file_uri( 'assets/css/main.css' ), array( 'emsundecor-theme' ), $theme_version );
	wp_enqueue_script( 'emsundecor-main', get_theme_file_uri( 'assets/js/main.js' ), array(), $theme_version, true );
}
add_action( 'wp_enqueue_scripts', 'emsundecor_enqueue_assets' );

function emsundecor_product_category_links() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	if ( is_wp_error( $categories ) ) {
		error_log( 'EMSUNDECOR product categories could not be loaded: ' . $categories->get_error_message() );
		return array();
	}

	$links = array();
	foreach ( $categories as $category ) {
		$url = get_term_link( $category );
		if ( is_wp_error( $url ) ) {
			error_log( 'EMSUNDECOR product category link could not be loaded: ' . $url->get_error_message() );
			continue;
		}
		$links[] = array( 'name' => $category->name, 'url' => $url );
	}
	return $links;
}

function emsundecor_is_shop_menu_item( $item, $args, $depth ) {
	if ( empty( $args->emsundecor_header ) || 'primary' !== $args->theme_location || 0 !== $depth || ! function_exists( 'wc_get_page_id' ) ) {
		return false;
	}

	$shop_id = wc_get_page_id( 'shop' );
	if ( $shop_id <= 0 ) {
		return false;
	}

	return ( 'post_type' === $item->type && 'page' === $item->object && (int) $item->object_id === $shop_id )
		|| ( 'custom' === $item->type && untrailingslashit( $item->url ) === untrailingslashit( get_permalink( $shop_id ) ) );
}

function emsundecor_shop_menu_classes( $classes, $item, $args, $depth ) {
	if ( emsundecor_is_shop_menu_item( $item, $args, $depth ) && emsundecor_product_category_links() ) {
		$classes[] = 'menu-item--product-categories';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'emsundecor_shop_menu_classes', 10, 4 );

function emsundecor_shop_menu_dropdown( $output, $item, $depth, $args ) {
	if ( ! emsundecor_is_shop_menu_item( $item, $args, $depth ) ) {
		return $output;
	}

	$categories = emsundecor_product_category_links();
	if ( ! $categories ) {
		return $output;
	}

	$id = 'product-categories-' . (int) $item->ID;
	$output .= '<button class="product-category-toggle" type="button" aria-expanded="false" aria-controls="' . esc_attr( $id ) . '"><span class="screen-reader-text">' . esc_html__( 'Mở danh mục sản phẩm', 'emsundecor' ) . '</span><svg class="product-category-toggle__icon" aria-hidden="true" focusable="false" viewBox="0 0 16 16"><path d="m4 6 4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';
	$output .= '<ul class="product-category-menu" id="' . esc_attr( $id ) . '">';
	foreach ( $categories as $category ) {
		$output .= '<li><a href="' . esc_url( $category['url'] ) . '">' . esc_html( $category['name'] ) . '</a></li>';
	}
	$output .= '</ul>';
	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'emsundecor_shop_menu_dropdown', 10, 4 );

function emsundecor_render_product_category_navigation() {
	if ( ! function_exists( 'is_shop' ) || ( ! is_shop() && ! is_product_category() ) ) {
		return;
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $categories ) || ! $categories ) {
		return;
	}
	?>
	<nav class="shop-category-nav" aria-label="<?php esc_attr_e( 'Danh mục sản phẩm', 'emsundecor' ); ?>">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Tất cả', 'emsundecor' ); ?></a>
		<?php foreach ( $categories as $category ) : ?>
			<?php if ( 'uncategorized' === $category->slug ) { continue; } ?>
			<a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php
}
add_action( 'woocommerce_before_shop_loop', 'emsundecor_render_product_category_navigation', 5 );

function emsundecor_render_contact_actions() {
	$info = emsundecor_get_business_info();
	?>
	<div class="floating-contact" aria-label="<?php esc_attr_e( 'Liên hệ nhanh', 'emsundecor' ); ?>">
		<a class="floating-contact__link floating-contact__link--phone" href="<?php echo esc_url( emsundecor_phone_href( $info['hotline'] ) ); ?>">
			<span class="floating-contact__icon" aria-hidden="true">☎</span>
			<span><?php esc_html_e( 'Hotline', 'emsundecor' ); ?></span>
		</a>
		<a class="floating-contact__link floating-contact__link--zalo" href="<?php echo esc_url( 'https://zalo.me/' . preg_replace( '/\D+/', '', $info['zalo'] ) ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="floating-contact__icon floating-contact__icon--zalo" aria-hidden="true">Z</span>
			<span><?php esc_html_e( 'Zalo', 'emsundecor' ); ?></span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'emsundecor_render_contact_actions', 30 );
