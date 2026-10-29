<?php
/** Compact customer order history with expandable rows. */
$theme_uri = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url = function_exists('home_url') ? home_url('/') : 'index.php';
$dacsan_url = function_exists('home_url') ? home_url('/?controller=dac-san') : 'index.php?controller=dac-san';
$orders = $orders ?? [];
$orderSuccessCode = $orderSuccessCode ?? null;
$statusConfig = [
    'pending' => ['label' => 'Chờ xác nhận', 'class' => 'status-pending'],
    'confirmed' => ['label' => 'Đã duyệt', 'class' => 'status-confirmed'],
    'shipping' => ['label' => 'Đang giao', 'class' => 'status-shipping'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'status-completed'],
    'cancelled' => ['label' => 'Đã hủy', 'class' => 'status-cancelled'],
];
?>
<link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/orders-table.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/orders-table.css'); ?>">
<div class="orders-page-master orders-table-page">
    <section class="orders-hero-banner">
        <div class="orders-hero-content">
            <nav class="orders-breadcrumb"><a href="<?php echo esc_url($home_url); ?>">Trang chủ</a> &rsaquo; <span>Đơn hàng của tôi</span></nav>
            <h1 class="orders-hero-title">Đơn hàng của tôi</h1>
            <p class="orders-hero-desc">Xem đơn đã đặt và theo dõi trạng thái giao hàng.</p>
        </div>
    </section>
    <div class="orders-container">
        <?php if ($orderSuccessCode): ?>
            <p class="orders-table-success" role="status">✓ Đã nhận đơn <strong>#<?php echo htmlspecialchars($orderSuccessCode); ?></strong>.</p>
        <?php endif; ?>
        <?php if (!$orders): ?>
            <div class="empty-orders-box"><h2>Bạn chưa có đơn hàng</h2><p>Chọn đặc sản yêu thích để bắt đầu nhé.</p><a href="<?php echo esc_url($dacsan_url); ?>" class="btn-go-shop">Khám phá đặc sản</a></div>
        <?php else: ?>
        <section class="orders-table-section" aria-labelledby="orders-table-title">
            <div class="orders-table-heading"><div><h2 id="orders-table-title">Lịch sử đơn hàng</h2><span data-orders-count aria-live="polite"><?php echo count($orders); ?> đơn hàng</span></div><a href="<?php echo esc_url($dacsan_url); ?>" class="orders-table-shop">+ Đặt thêm</a></div>
            <div class="orders-table-toolbar" hidden>
                <label class="orders-table-search"><span>Tìm đơn hàng</span><input type="search" data-orders-search placeholder="Mã đơn, tên món…" autocomplete="off"></label>
                <label class="orders-table-filter"><span>Trạng thái</span><select data-orders-status><option value="">Tất cả trạng thái</option><?php foreach ($statusConfig as $key => $status): ?><option value="<?php echo $key; ?>"><?php echo $status['label']; ?></option><?php endforeach; ?></select></label>
            </div>
            <div class="orders-data-wrap">
                <table class="orders-data-table" role="table">
                    <caption class="orders-table-sr-only">Danh sách đơn hàng. Chọn Chi tiết để xem sản phẩm và thông tin nhận hàng.</caption>
                    <thead><tr><th scope="col">Đơn hàng</th><th scope="col">Sản phẩm</th><th scope="col">Trạng thái</th><th scope="col" class="orders-money">Tổng tiền</th><th scope="col"><span class="orders-table-sr-only">Thao tác</span></th></tr></thead>
                    <tbody>
                    <?php foreach (array_values($orders) as $index => $order):
                        $items = is_array($order['items'] ?? null) ? $order['items'] : [];
                        $statusKey = $order['status'] ?? 'pending';
                        $status = $statusConfig[$statusKey] ?? ['label' => 'Đang cập nhật', 'class' => 'status-unknown'];
                        $code = (string) ($order['order_code'] ?? '');
                        $dateRaw = (string) ($order['created_at'] ?? '');
                        $dateStamp = $dateRaw !== '' ? strtotime($dateRaw) : false;
                        $dateLabel = $dateStamp !== false ? date('d/m/Y · H:i', $dateStamp) : $dateRaw;
                        $firstItem = $items ? reset($items) : [];
                        $detailId = 'order-detail-' . $index;
                        $buttonId = 'order-toggle-' . $index;
                        $search = $code . ' ' . implode(' ', array_column($items, 'name'));
                        $payment = $order['payment_method'] ?? '';
                        $paymentLabels = ['vietqr' => 'Chuyển khoản VietQR', 'bacs' => 'Chuyển khoản ngân hàng', 'cod' => 'Thanh toán khi nhận hàng'];
                        $paymentLabel = $paymentLabels[$payment] ?? ($payment ?: 'Chưa có thông tin');
                    ?>
                        <tr class="order-table-row" data-order-status="<?php echo htmlspecialchars($statusKey, ENT_QUOTES); ?>" data-order-search="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                            <th scope="row" class="orders-code-cell"><strong>#<?php echo htmlspecialchars($code); ?></strong><small><?php echo htmlspecialchars($dateLabel); ?></small></th>
                            <td class="orders-product-cell"><span><?php echo htmlspecialchars($firstItem['name'] ?? 'Chưa có chi tiết sản phẩm'); ?></span><?php if (count($items) > 1): ?><small>và <?php echo count($items) - 1; ?> món khác</small><?php endif; ?></td>
                            <td class="orders-status-cell"><span class="order-status-pill <?php echo $status['class']; ?>"><?php echo $status['label']; ?></span></td>
                            <td class="orders-money orders-total-cell"><?php echo number_format((float) ($order['total_amount'] ?? 0), 0, ',', '.'); ?> ₫</td>
                            <td class="orders-action-cell"><button type="button" class="orders-row-toggle" id="<?php echo $buttonId; ?>" aria-expanded="true" aria-controls="<?php echo $detailId; ?>">Thu gọn</button></td>
                        </tr>
                        <tr class="orders-detail-row" id="<?php echo $detailId; ?>">
                            <td colspan="5">
                                <div class="orders-expanded" role="region" aria-labelledby="<?php echo $buttonId; ?>">
                                    <div class="orders-detail-header"><h3>Chi tiết đơn #<?php echo htmlspecialchars($code); ?></h3><a href="tel:0342684980">Gọi hỗ trợ</a></div>
                                    <dl class="orders-delivery-grid">
                                        <div><dt>Người nhận</dt><dd><?php echo htmlspecialchars($order['customer_name'] ?? ''); ?></dd></div>
                                        <div><dt>Số điện thoại</dt><dd><?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?></dd></div>
                                        <div class="orders-address"><dt>Địa chỉ giao hàng</dt><dd><?php echo htmlspecialchars($order['customer_address'] ?? ''); ?></dd></div>
                                        <div><dt>Thanh toán</dt><dd><?php echo htmlspecialchars($paymentLabel); ?></dd></div>
                                        <?php if (!empty($order['customer_note'])): ?><div class="orders-address"><dt>Ghi chú</dt><dd><?php echo htmlspecialchars($order['customer_note']); ?></dd></div><?php endif; ?>
                                    </dl>
                                    <div class="orders-detail-table-wrap"><table class="orders-detail-items"><caption class="orders-table-sr-only">Sản phẩm trong đơn #<?php echo htmlspecialchars($code); ?></caption><thead><tr><th scope="col">Sản phẩm</th><th scope="col">Đơn giá</th><th scope="col">SL</th><th scope="col">Thành tiền</th></tr></thead><tbody>
                                    <?php foreach ($items as $item): $qty = (int) ($item['quantity'] ?? 1); $price = (float) ($item['price_raw'] ?? 0); ?>
                                        <tr><td><?php echo htmlspecialchars($item['name'] ?? ''); ?></td><td><?php echo htmlspecialchars($item['price'] ?? (number_format($price, 0, ',', '.') . ' ₫')); ?></td><td>×<?php echo $qty; ?></td><td><?php echo number_format($price * $qty, 0, ',', '.'); ?> ₫</td></tr>
                                    <?php endforeach; ?>
                                    <?php if (!$items): ?><tr><td colspan="4">Chưa có chi tiết sản phẩm cho đơn này.</td></tr><?php endif; ?>
                                    </tbody></table></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr class="orders-no-results" hidden><td colspan="5">Không tìm thấy đơn phù hợp. Hãy thử từ khóa hoặc trạng thái khác.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>
<script src="<?php echo esc_url($theme_uri); ?>/assets/js/orders-table.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/orders-table.js'); ?>" defer></script>