<?php
/** Register a staff-only inbox in WordPress. */
add_action('admin_menu', function () {
    require_once __DIR__ . '/../controllers/ChatController.php';
    if (!ChatController::isStaff()) return;
    $capability = current_user_can('manage_options') ? 'manage_options' : 'manage_woocommerce';
    add_menu_page('Chat khách hàng', 'Chat khách hàng', $capability, 'ohx-chat', function () {
        if (!ChatController::isStaff()) wp_die('Bạn không có quyền truy cập hộp chat.');
        $chatAdminEndpoint = home_url('/?controller=chat');
        $chatAssetUrl = get_template_directory_uri();
        require __DIR__ . '/../views/chat/admin.php';
    }, 'dashicons-format-chat', 26);
});
