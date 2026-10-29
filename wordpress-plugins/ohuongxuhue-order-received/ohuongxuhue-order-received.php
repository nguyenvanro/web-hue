<?php
/**
 * Plugin Name: O Hương Xứ Huế — Xác nhận đơn hàng
 * Description: Giao diện xác nhận đơn hàng gọn gàng và kiểm tra trạng thái thanh toán chỉ đọc.
 * Version: 1.0.0
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_order_received_page') || !is_order_received_page()) return;
    $base = plugin_dir_url(__FILE__);
    wp_enqueue_style('ohx-receipt', $base . 'assets/receipt.css', [], filemtime(__DIR__ . '/assets/receipt.css'));
    wp_enqueue_script('ohx-receipt', $base . 'assets/receipt.js', [], filemtime(__DIR__ . '/assets/receipt.js'), true);
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '';
    wp_localize_script('ohx-receipt', 'OHXReceipt', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'orderId' => absint(get_query_var('order-received')),
        'shopUrl' => $shop ?: home_url('/'),
        'phone' => '0342684980',
    ]);
}, 100);

/** Read-only: order key is required; never changes order/payment/stock data. */
function ohx_receipt_read_status() {
    nocache_headers();
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') wp_send_json_error(['message' => 'Phương thức không hợp lệ.'], 405);
    $id = isset($_POST['order_id']) && is_scalar($_POST['order_id']) ? absint($_POST['order_id']) : 0;
    $key = isset($_POST['order_key']) && is_string($_POST['order_key']) ? wp_unslash($_POST['order_key']) : '';
    $order = $id && function_exists('wc_get_order') ? wc_get_order($id) : false;
    if (!$order || !$key || !hash_equals((string) $order->get_order_key(), $key)) {
        wp_send_json_error(['message' => 'Không xác minh được đơn hàng. Vui lòng mở lại liên kết xác nhận đơn.'], 403);
    }
    $method = $order->get_payment_method();
    // A COD order marked processing still needs payment on delivery.
    $paid = $order->is_paid() && ($method !== 'cod' || $order->has_status('completed'));
    wp_send_json_success([
        'paid' => $paid,
        'status' => $order->get_status(),
        'method' => $method,
    ]);
}
add_action('wp_ajax_ohx_receipt_status', 'ohx_receipt_read_status');
add_action('wp_ajax_nopriv_ohx_receipt_status', 'ohx_receipt_read_status');
