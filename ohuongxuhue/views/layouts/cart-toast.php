<?php
// Dùng chung cho trang chủ và mọi danh mục đặc sản.
$cartToastAssetUrl = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$cartToastCartUrl = function_exists('home_url') ? home_url('/?controller=cart') : 'index.php?controller=cart';
$cartToastCheckoutUrl = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : (function_exists('home_url') ? home_url('/thanh-toan/') : 'thanh-toan/');
$cartToastCheckoutUrl .= (strpos($cartToastCheckoutUrl, '?') === false ? '?' : '&') . 'sync_cart=1';
$cartToastAddUrl = function_exists('home_url') ? home_url('/?controller=cart&action=add&ajax=1') : 'index.php?controller=cart&action=add&ajax=1';
?>
<link rel="stylesheet" href="<?php echo esc_url($cartToastAssetUrl); ?>/assets/css/cart-toast.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/cart-toast.css'); ?>">
<div id="cartToast" class="cart-toast-notification" role="status" aria-live="polite" aria-atomic="true" aria-hidden="true" data-add-url="<?php echo esc_url($cartToastAddUrl); ?>">
    <div class="cart-toast-icon" aria-hidden="true">🛍️</div>
    <div class="cart-toast-content">
        <div class="cart-toast-title">Đã thêm vào giỏ hàng!</div>
        <div id="cartToastMsg"></div>
        <div class="cart-toast-btns">
            <a href="<?php echo esc_url($cartToastCartUrl); ?>" class="cart-toast-btn-view">Xem Giỏ Hàng</a>
            <a href="<?php echo esc_url($cartToastCheckoutUrl); ?>" class="cart-toast-btn-checkout">Thanh Toán Ngay &rarr;</a>
        </div>
    </div>
</div>
<script src="<?php echo esc_url($cartToastAssetUrl); ?>/assets/js/cart-toast.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/cart-toast.js'); ?>"></script>
