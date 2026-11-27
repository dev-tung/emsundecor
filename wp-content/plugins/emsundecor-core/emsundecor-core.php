<?php
/**
 * Plugin Name: EMSUNDECOR Core
 * Core site features for EMSUNDECOR.
 *
 * @package EMSUNDECOR_Core
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EMSUNDECOR_CORE_VERSION', '1.0.0' );

function emsundecor_default_business_info() {
	return array(
		'hotline' => '0337676422',
		'zalo'    => '0337676422',
		'address' => '203 P. Đông Khê, Gia Viên, Hải Phòng, Vietnam',
		'maps'    => 'https://maps.app.goo.gl/ejijnEbbAoNoRXNg7',
	);
}

function emsundecor_sanitize_business_info( $value ) {
	$defaults = emsundecor_default_business_info();
	$value    = is_array( $value ) ? $value : array();

	return array(
		'hotline' => isset( $value['hotline'] ) ? preg_replace( '/\D+/', '', $value['hotline'] ) : $defaults['hotline'],
		'zalo'    => isset( $value['zalo'] ) ? preg_replace( '/\D+/', '', $value['zalo'] ) : $defaults['zalo'],
		'address' => isset( $value['address'] ) ? sanitize_text_field( $value['address'] ) : $defaults['address'],
		'maps'    => isset( $value['maps'] ) ? esc_url_raw( $value['maps'] ) : $defaults['maps'],
	);
}

function emsundecor_get_business_info() {
	return wp_parse_args(
		get_option( 'emsundecor_business_info', array() ),
		emsundecor_default_business_info()
	);
}

function emsundecor_phone_href( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );
	if ( str_starts_with( $digits, '0' ) ) {
		$digits = '84' . substr( $digits, 1 );
	}

	return 'tel:+' . $digits;
}

function emsundecor_register_settings() {
	register_setting(
		'emsundecor_settings',
		'emsundecor_business_info',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'emsundecor_sanitize_business_info',
			'default'           => emsundecor_default_business_info(),
		)
	);
}
add_action( 'admin_init', 'emsundecor_register_settings' );

function emsundecor_add_settings_page() {
	add_options_page(
		__( 'EMSUNDECOR', 'emsundecor' ),
		__( 'EMSUNDECOR', 'emsundecor' ),
		'manage_options',
		'emsundecor',
		'emsundecor_render_settings_page'
	);
}
add_action( 'admin_menu', 'emsundecor_add_settings_page' );

function emsundecor_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$info = emsundecor_get_business_info();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'EMSUNDECOR', 'emsundecor' ); ?></h1>
		<p><?php esc_html_e( 'Thông tin này được dùng trên trang liên hệ và các nút liên hệ nổi.', 'emsundecor' ); ?></p>
		<form action="options.php" method="post">
			<?php settings_fields( 'emsundecor_settings' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="emsundecor-hotline"><?php esc_html_e( 'Hotline', 'emsundecor' ); ?></label></th>
					<td><input id="emsundecor-hotline" class="regular-text" name="emsundecor_business_info[hotline]" value="<?php echo esc_attr( $info['hotline'] ); ?>" inputmode="tel"></td>
				</tr>
				<tr>
					<th scope="row"><label for="emsundecor-zalo"><?php esc_html_e( 'Số Zalo', 'emsundecor' ); ?></label></th>
					<td><input id="emsundecor-zalo" class="regular-text" name="emsundecor_business_info[zalo]" value="<?php echo esc_attr( $info['zalo'] ); ?>" inputmode="tel"></td>
				</tr>
				<tr>
					<th scope="row"><label for="emsundecor-address"><?php esc_html_e( 'Địa chỉ', 'emsundecor' ); ?></label></th>
					<td><input id="emsundecor-address" class="large-text" name="emsundecor_business_info[address]" value="<?php echo esc_attr( $info['address'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="emsundecor-maps"><?php esc_html_e( 'Liên kết Google Maps', 'emsundecor' ); ?></label></th>
					<td><input id="emsundecor-maps" class="large-text" type="url" name="emsundecor_business_info[maps]" value="<?php echo esc_attr( $info['maps'] ); ?>"></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
		<h2><?php esc_html_e( 'Thanh toán QR', 'emsundecor' ); ?></h2>
		<p><?php esc_html_e( 'Cấu hình phương thức QR tại WooCommerce → Cài đặt → Thanh toán → EMSUNDECOR QR. Phương thức chỉ xuất hiện ở checkout sau khi bật và thêm ảnh QR hợp lệ.', 'emsundecor' ); ?></p>
		<p><?php esc_html_e( 'Đơn thanh toán QR sẽ ở trạng thái chờ xác minh; quản trị viên cần kiểm tra giao dịch trước khi đánh dấu đã thanh toán.', 'emsundecor' ); ?></p>
	</div>
	<?php
}

function emsundecor_register_inquiry_post_type() {
	register_post_type(
		'emsun_inquiry',
		array(
			'labels' => array(
				'name'          => __( 'Yêu cầu khách hàng', 'emsundecor' ),
				'singular_name' => __( 'Yêu cầu khách hàng', 'emsundecor' ),
				'menu_name'     => __( 'Yêu cầu khách hàng', 'emsundecor' ),
				'edit_item'     => __( 'Xem yêu cầu', 'emsundecor' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-email-alt',
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'emsundecor_register_inquiry_post_type' );

function emsundecor_add_inquiry_meta_boxes() {
	add_meta_box(
		'emsundecor-inquiry-contact',
		__( 'Thông tin liên hệ và sự kiện', 'emsundecor' ),
		'emsundecor_render_inquiry_meta_box',
		'emsun_inquiry',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'emsundecor_add_inquiry_meta_boxes' );

function emsundecor_render_inquiry_meta_box( $post ) {
	$fields = array(
		'_emsundecor_phone'          => __( 'Số điện thoại', 'emsundecor' ),
		'_emsundecor_interest'       => __( 'Sản phẩm/dịch vụ quan tâm', 'emsundecor' ),
		'_emsundecor_event_date'     => __( 'Ngày tổ chức', 'emsundecor' ),
		'_emsundecor_event_location' => __( 'Địa điểm', 'emsundecor' ),
		'_emsundecor_consent'        => __( 'Đồng ý chính sách quyền riêng tư', 'emsundecor' ),
		'_emsundecor_received_at'    => __( 'Thời điểm tiếp nhận', 'emsundecor' ),
	);

	echo '<dl>';
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( '' === (string) $value ) {
			continue;
		}
		echo '<dt><strong>' . esc_html( $label ) . '</strong></dt>';
		echo '<dd>' . esc_html( $value ) . '</dd>';
	}
	echo '</dl>';
}

function emsundecor_inquiry_admin_columns( $columns ) {
	$columns['emsundecor_phone'] = __( 'Điện thoại', 'emsundecor' );
	$columns['emsundecor_date']  = __( 'Ngày nhận', 'emsundecor' );
	return $columns;
}
add_filter( 'manage_emsun_inquiry_posts_columns', 'emsundecor_inquiry_admin_columns' );

function emsundecor_inquiry_admin_column_content( $column, $post_id ) {
	if ( 'emsundecor_phone' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_emsundecor_phone', true ) );
	}
	if ( 'emsundecor_date' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_emsundecor_received_at', true ) );
	}
}
add_action( 'manage_emsun_inquiry_posts_custom_column', 'emsundecor_inquiry_admin_column_content', 10, 2 );

function emsundecor_get_zalo_url( $phone ) {
	return 'https://zalo.me/' . preg_replace( '/\D+/', '', (string) $phone );
}

function emsundecor_render_contact_details() {
	$info = emsundecor_get_business_info();
	ob_start();
	?>
	<div class="contact-details">
		<div class="contact-detail">
			<strong><?php esc_html_e( 'Hotline', 'emsundecor' ); ?></strong>
			<p><a href="<?php echo esc_url( emsundecor_phone_href( $info['hotline'] ) ); ?>"><?php echo esc_html( $info['hotline'] ); ?></a></p>
		</div>
		<div class="contact-detail">
			<strong><?php esc_html_e( 'Zalo', 'emsundecor' ); ?></strong>
			<p><a href="<?php echo esc_url( emsundecor_get_zalo_url( $info['zalo'] ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $info['zalo'] ); ?></a></p>
		</div>
		<div class="contact-detail">
			<strong><?php esc_html_e( 'Địa chỉ', 'emsundecor' ); ?></strong>
			<p><?php echo esc_html( $info['address'] ); ?></p>
		</div>
		<div class="contact-detail">
			<strong><?php esc_html_e( 'Bản đồ', 'emsundecor' ); ?></strong>
			<p><a href="<?php echo esc_url( $info['maps'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Mở Google Maps', 'emsundecor' ); ?> ↗</a></p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function emsundecor_render_inquiry_form() {
	$notice = '';
	if ( isset( $_GET['inquiry'] ) && 'sent' === sanitize_key( wp_unslash( $_GET['inquiry'] ) ) ) {
		$notice = '<p class="inquiry-notice" role="status">' . esc_html__( 'Cảm ơn bạn! Yêu cầu đã được ghi nhận. EMSUNDECOR sẽ liên hệ lại.', 'emsundecor' ) . '</p>';
	} elseif ( isset( $_GET['inquiry_error'] ) ) {
		$notice = '<p class="inquiry-notice inquiry-notice--error" role="alert">' . esc_html__( 'Chưa gửi được yêu cầu. Vui lòng kiểm tra thông tin hoặc liên hệ Hotline/Zalo.', 'emsundecor' ) . '</p>';
	}

	$product = isset( $_GET['product'] ) ? sanitize_text_field( wp_unslash( $_GET['product'] ) ) : '';
	ob_start();
	?>
	<?php echo wp_kses_post( $notice ); ?>
	<form class="inquiry-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="emsundecor_submit_inquiry">
		<?php wp_nonce_field( 'emsundecor_submit_inquiry', 'emsundecor_nonce' ); ?>
		<div class="inquiry-form__honeypot" aria-hidden="true">
			<label><?php esc_html_e( 'Để trống trường này', 'emsundecor' ); ?><input type="text" name="company_website" tabindex="-1" autocomplete="off"></label>
		</div>
		<div class="inquiry-form__row">
			<label><?php esc_html_e( 'Họ và tên *', 'emsundecor' ); ?><input name="customer_name" type="text" autocomplete="name" required maxlength="120"></label>
			<label><?php esc_html_e( 'Số điện thoại *', 'emsundecor' ); ?><input name="customer_phone" type="tel" autocomplete="tel" required maxlength="24"></label>
		</div>
		<div class="inquiry-form__row">
			<label><?php esc_html_e( 'Sản phẩm/dịch vụ quan tâm', 'emsundecor' ); ?><input name="interest" type="text" value="<?php echo esc_attr( $product ); ?>" maxlength="180"></label>
			<label><?php esc_html_e( 'Ngày tổ chức (nếu đã có)', 'emsundecor' ); ?><input name="event_date" type="date"></label>
		</div>
		<label><?php esc_html_e( 'Địa điểm tổ chức (nếu đã có)', 'emsundecor' ); ?><input name="event_location" type="text" maxlength="180"></label>
		<label><?php esc_html_e( 'Nội dung yêu cầu *', 'emsundecor' ); ?><textarea name="message" required maxlength="3000"></textarea></label>
		<label class="inquiry-consent"><span><input name="privacy_consent" type="checkbox" value="1" required> <?php esc_html_e( 'Tôi đồng ý để EMSUNDECOR sử dụng thông tin này nhằm phản hồi yêu cầu.', 'emsundecor' ); ?></span></label>
		<button class="button" type="submit"><?php esc_html_e( 'Gửi yêu cầu', 'emsundecor' ); ?> <span aria-hidden="true">→</span></button>
	</form>
	<?php
	return ob_get_clean();
}

function emsundecor_contact_shortcode() {
	return emsundecor_render_contact_details() . emsundecor_render_inquiry_form();
}
add_shortcode( 'emsundecor_contact', 'emsundecor_contact_shortcode' );
add_shortcode( 'emsundecor_inquiry_form', 'emsundecor_render_inquiry_form' );

function emsundecor_services_shortcode() {
	ob_start();
	?>
	<div class="service-intro">
		<p><?php esc_html_e( 'EMSUNDECOR tập trung vào các sản phẩm và dịch vụ trang trí tiệc sinh nhật. Xem danh mục sản phẩm để tham khảo mẫu, thông tin và giá đã được công bố.', 'emsundecor' ); ?></p>
		<a class="button" href="<?php echo esc_url( home_url( '/san-pham/' ) ); ?>"><?php esc_html_e( 'Xem sản phẩm decor', 'emsundecor' ); ?></a>
		<a class="button button--outline" href="<?php echo esc_url( home_url( '/dat-dich-vu/' ) ); ?>"><?php esc_html_e( 'Gửi yêu cầu', 'emsundecor' ); ?></a>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'emsundecor_services', 'emsundecor_services_shortcode' );

function emsundecor_about_shortcode() {
	return '<p>' . esc_html__( 'EMSUNDECOR mang đến các lựa chọn trang trí tiệc sinh nhật và mẫu decor để bạn tham khảo cho dịp đặc biệt của mình.', 'emsundecor' ) . '</p><p><a class="button" href="' . esc_url( home_url( '/san-pham/' ) ) . '">' . esc_html__( 'Khám phá sản phẩm', 'emsundecor' ) . '</a></p>';
}
add_shortcode( 'emsundecor_about', 'emsundecor_about_shortcode' );

function emsundecor_gallery_shortcode() {
	return '<p>' . esc_html__( 'Khám phá các mẫu decor trong danh mục sản phẩm.', 'emsundecor' ) . '</p><p><a class="button" href="' . esc_url( home_url( '/san-pham/' ) ) . '">' . esc_html__( 'Xem mẫu decor', 'emsundecor' ) . '</a></p>';
}
add_shortcode( 'emsundecor_gallery', 'emsundecor_gallery_shortcode' );

function emsundecor_inquiry_redirect( $result ) {
	$referer = wp_get_referer();
	$target  = $referer ? $referer : home_url( '/dat-dich-vu/' );
	$target  = remove_query_arg( array( 'inquiry', 'inquiry_error' ), $target );
	$target  = add_query_arg( $result ? 'inquiry' : 'inquiry_error', $result ? 'sent' : 'failed', $target );
	wp_safe_redirect( $target );
	exit;
}

function emsundecor_handle_inquiry_submission() {
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		emsundecor_inquiry_redirect( false );
	}

	$nonce = isset( $_POST['emsundecor_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['emsundecor_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'emsundecor_submit_inquiry' ) ) {
		emsundecor_inquiry_redirect( false );
	}

	if ( ! empty( $_POST['company_website'] ) ) {
		emsundecor_inquiry_redirect( false );
	}

	$ip        = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rate_key  = 'emsundecor_inquiry_' . hash( 'sha256', $ip );
	if ( get_transient( $rate_key ) ) {
		emsundecor_inquiry_redirect( false );
	}

	$name      = isset( $_POST['customer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_name'] ) ) : '';
	$phone     = isset( $_POST['customer_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_phone'] ) ) : '';
	$interest  = isset( $_POST['interest'] ) ? sanitize_text_field( wp_unslash( $_POST['interest'] ) ) : '';
	$event     = isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
	$location  = isset( $_POST['event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['event_location'] ) ) : '';
	$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$consented = isset( $_POST['privacy_consent'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['privacy_consent'] ) );
	$phone_num = preg_replace( '/\D+/', '', $phone );

	if ( '' === $name || strlen( $phone_num ) < 8 || strlen( $phone_num ) > 15 || '' === $message || ! $consented ) {
		emsundecor_inquiry_redirect( false );
	}

	$inquiry_id = wp_insert_post(
		array(
			'post_type'    => 'emsun_inquiry',
			'post_status'  => 'private',
			'post_title'   => sprintf( '%s — %s', $name, current_time( 'd/m/Y H:i' ) ),
			'post_content' => $message,
			'meta_input'   => array(
				'_emsundecor_phone'          => $phone_num,
				'_emsundecor_interest'       => $interest,
				'_emsundecor_event_date'     => $event,
				'_emsundecor_event_location' => $location,
				'_emsundecor_consent'        => current_time( 'mysql' ),
				'_emsundecor_received_at'    => current_time( 'd/m/Y H:i' ),
			),
		),
		true
	);

	if ( is_wp_error( $inquiry_id ) ) {
		error_log( 'EMSUNDECOR inquiry could not be saved: ' . $inquiry_id->get_error_message() );
		emsundecor_inquiry_redirect( false );
	}

	set_transient( $rate_key, 1, MINUTE_IN_SECONDS );
	emsundecor_inquiry_redirect( true );
}
add_action( 'admin_post_nopriv_emsundecor_submit_inquiry', 'emsundecor_handle_inquiry_submission' );
add_action( 'admin_post_emsundecor_submit_inquiry', 'emsundecor_handle_inquiry_submission' );

function emsundecor_is_vip_product( $product ) {
	if ( is_numeric( $product ) ) {
		$product = wc_get_product( $product );
	}
	if ( ! $product instanceof WC_Product ) {
		return false;
	}

	$product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
	return has_term( 'sinh-nhat-vip', 'product_cat', $product_id );
}

function emsundecor_hide_vip_product_price( $price_html, $product ) {
	return emsundecor_is_vip_product( $product ) ? '' : $price_html;
}
add_filter( 'woocommerce_get_price_html', 'emsundecor_hide_vip_product_price', 20, 2 );

function emsundecor_disable_vip_purchase( $purchasable, $product ) {
	return emsundecor_is_vip_product( $product ) ? false : $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'emsundecor_disable_vip_purchase', 20, 2 );

function emsundecor_vip_loop_contact_link( $html, $product ) {
	if ( ! emsundecor_is_vip_product( $product ) ) {
		return $html;
	}

	$url = add_query_arg( 'product', rawurlencode( $product->get_name() ), home_url( '/lien-he/' ) );
	return '<a class="button product_type_simple" href="' . esc_url( $url ) . '">' . esc_html__( 'Liên hệ hỏi giá', 'emsundecor' ) . '</a>';
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'emsundecor_vip_loop_contact_link', 20, 2 );

function emsundecor_single_vip_contact_link() {
	global $product;
	if ( ! emsundecor_is_vip_product( $product ) ) {
		return;
	}

	$url = add_query_arg( 'product', rawurlencode( $product->get_name() ), home_url( '/lien-he/' ) );
	echo '<div class="vip-contact-box"><p>' . esc_html__( 'Sản phẩm SINH NHẬT VIP không công khai giá. Liên hệ EMSUNDECOR để được tư vấn.', 'emsundecor' ) . '</p><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Liên hệ hỏi giá', 'emsundecor' ) . '</a></div>';
}
add_action( 'woocommerce_single_product_summary', 'emsundecor_single_vip_contact_link', 31 );

function emsundecor_remove_vip_offer_schema( $markup, $product ) {
	if ( emsundecor_is_vip_product( $product ) ) {
		unset( $markup['offers'] );
	}
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'emsundecor_remove_vip_offer_schema', 20, 2 );

function emsundecor_remove_vip_price_from_rest( $response, $product, $request ) {
	if ( ! emsundecor_is_vip_product( $product ) || ! $response instanceof WP_REST_Response ) {
		return $response;
	}

	$data = $response->get_data();
	unset( $data['price'], $data['regular_price'], $data['sale_price'], $data['price_html'] );
	$response->set_data( $data );
	return $response;
}
add_filter( 'woocommerce_rest_prepare_product_object', 'emsundecor_remove_vip_price_from_rest', 20, 3 );

function emsundecor_block_vip_items_in_cart() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	foreach ( WC()->cart->get_cart() as $cart_item ) {
		if ( isset( $cart_item['data'] ) && emsundecor_is_vip_product( $cart_item['data'] ) ) {
			wc_add_notice( __( 'Sản phẩm SINH NHẬT VIP cần được tư vấn riêng và không thể đặt trực tiếp qua giỏ hàng.', 'emsundecor' ), 'error' );
			return;
		}
	}
}
add_action( 'woocommerce_check_cart_items', 'emsundecor_block_vip_items_in_cart' );

function emsundecor_register_qr_gateway( $gateways ) {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return $gateways;
	}

	if ( ! class_exists( 'WC_Gateway_EMSUNDECOR_QR' ) ) {
	class WC_Gateway_EMSUNDECOR_QR extends WC_Payment_Gateway {
		public $qr_image_url = '';

		public function __construct() {
			$this->id                 = 'emsundecor_qr';
			$this->icon               = '';
			$this->has_fields         = true;
			$this->method_title       = __( 'EMSUNDECOR QR', 'emsundecor' );
			$this->method_description = __( 'Thanh toán chuyển khoản bằng QR code do EMSUNDECOR cung cấp.', 'emsundecor' );
			$this->supports           = array( 'products' );

			$this->init_form_fields();
			$this->init_settings();

			$this->title          = $this->get_option( 'title', __( 'Quét QR để chuyển khoản', 'emsundecor' ) );
			$this->description    = $this->get_option( 'description', __( 'Đơn hàng sẽ được xác nhận sau khi giao dịch được kiểm tra.', 'emsundecor' ) );
			$this->qr_image_url   = esc_url_raw( $this->get_option( 'qr_image_url', '' ) );
			$this->instructions   = $this->get_option( 'instructions', __( 'Vui lòng chuyển đúng số tiền và ghi mã đơn hàng trong nội dung chuyển khoản.', 'emsundecor' ) );

			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
			add_action( 'woocommerce_email_after_order_table', array( $this, 'email_instructions' ), 10, 4 );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'      => array(
					'title'   => __( 'Bật/tắt', 'emsundecor' ),
					'type'    => 'checkbox',
					'label'   => __( 'Bật thanh toán bằng QR', 'emsundecor' ),
					'default' => 'no',
				),
				'title'        => array(
					'title'       => __( 'Tên phương thức', 'emsundecor' ),
					'type'        => 'text',
					'default'     => __( 'Quét QR để chuyển khoản', 'emsundecor' ),
					'description' => __( 'Tên hiển thị ở bước thanh toán.', 'emsundecor' ),
				),
				'description'  => array(
					'title'   => __( 'Mô tả', 'emsundecor' ),
					'type'    => 'textarea',
					'default' => __( 'Quét mã QR để thanh toán. Đơn hàng chờ xác minh giao dịch.', 'emsundecor' ),
				),
				'qr_image_url' => array(
					'title'       => __( 'URL ảnh QR', 'emsundecor' ),
					'type'        => 'text',
					'default'     => '',
					'description' => __( 'Tải ảnh QR lên Thư viện Media, sau đó dán URL ảnh tại đây. Phương thức sẽ không xuất hiện nếu chưa cấu hình ảnh.', 'emsundecor' ),
				),
				'instructions' => array(
					'title'   => __( 'Hướng dẫn chuyển khoản', 'emsundecor' ),
					'type'    => 'textarea',
					'default' => __( 'Vui lòng chuyển đúng số tiền và ghi mã đơn hàng trong nội dung chuyển khoản.', 'emsundecor' ),
				),
			);
		}

		public function is_available() {
			return parent::is_available() && '' !== $this->qr_image_url;
		}

		public function payment_fields() {
			if ( $this->description ) {
				echo wp_kses_post( wpautop( $this->description ) );
			}
			if ( $this->qr_image_url ) {
				echo '<div class="emsundecor-qr-checkout"><img src="' . esc_url( $this->qr_image_url ) . '" alt="' . esc_attr__( 'Mã QR thanh toán EMSUNDECOR', 'emsundecor' ) . '"><p>' . wp_kses_post( nl2br( esc_html( $this->instructions ) ) ) . '</p></div>';
			}
		}

		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order ) {
				wc_add_notice( __( 'Không tìm thấy đơn hàng để xử lý thanh toán.', 'emsundecor' ), 'error' );
				return array( 'result' => 'failure' );
			}

			$order->update_status( 'on-hold', __( 'Chờ EMSUNDECOR xác minh giao dịch QR.', 'emsundecor' ) );
			wc_reduce_stock_levels( $order_id );
			WC()->cart->empty_cart();

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}

		public function thankyou_page( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order ) {
				return;
			}
			?>
			<section class="emsundecor-qr-order">
				<h2><?php esc_html_e( 'Thanh toán bằng QR', 'emsundecor' ); ?></h2>
				<?php if ( $this->qr_image_url ) : ?>
					<img src="<?php echo esc_url( $this->qr_image_url ); ?>" alt="<?php esc_attr_e( 'Mã QR thanh toán EMSUNDECOR', 'emsundecor' ); ?>">
				<?php endif; ?>
				<p><?php echo wp_kses_post( nl2br( esc_html( $this->instructions ) ) ); ?></p>
				<p><?php esc_html_e( 'Mã đơn hàng:', 'emsundecor' ); ?> <strong><?php echo esc_html( $order->get_order_number() ); ?></strong></p>
				<p><?php esc_html_e( 'Số tiền:', 'emsundecor' ); ?> <strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong></p>
				<p><?php esc_html_e( 'Đơn hàng đang chờ xác minh giao dịch.', 'emsundecor' ); ?></p>
			</section>
			<?php
		}

		public function email_instructions( $order, $sent_to_admin, $plain_text, $email ) {
			if ( $sent_to_admin || ! $order instanceof WC_Order || $this->id !== $order->get_payment_method() || ! $order->has_status( 'on-hold' ) ) {
				return;
			}

			if ( $plain_text ) {
				echo "\n" . esc_html( $this->instructions ) . "\n";
				return;
			}
			echo '<p>' . wp_kses_post( nl2br( esc_html( $this->instructions ) ) ) . '</p>';
		}
	}
	}

	$gateways[] = 'WC_Gateway_EMSUNDECOR_QR';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'emsundecor_register_qr_gateway' );
