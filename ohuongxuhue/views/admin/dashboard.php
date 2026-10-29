<?php
/**
 * View Bảng điều khiển Quản trị viên (views/admin/dashboard.php)
 * Quản lý Thành viên, Sản phẩm và Đơn hàng đã đặt
 */

$statusLabels = [
    'pending'   => ['label' => 'Chờ Xác Nhận', 'class' => 'status-pending', 'icon' => '⏳'],
    'confirmed' => ['label' => 'Đã Duyệt Đơn', 'class' => 'status-confirmed', 'icon' => '📑'],
    'shipping'  => ['label' => 'Đang Giao Hàng', 'class' => 'status-shipping', 'icon' => '🚚'],
    'completed' => ['label' => 'Hoàn Thành', 'class' => 'status-completed', 'icon' => '✅'],
    'cancelled' => ['label' => 'Đã Hủy Đơn', 'class' => 'status-cancelled', 'icon' => '❌'],
];
?>

<div class="admin-wrapper">
    <p><a href="<?php echo esc_url(function_exists('home_url') ? home_url('/?controller=chat&action=inbox') : 'index.php?controller=chat&action=inbox'); ?>">💬 Mở hộp Chat khách hàng</a></p>
    <!-- Tiêu đề Admin -->
    <div class="admin-header-box">
        <div>
            <h2>👑 Bảng Điều Khiển Quản Trị (Admin Dashboard)</h2>
            <p>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? 'Quản Trị Viên'); ?></strong>! Quản lý toàn bộ hệ thống đơn hàng và dữ liệu O Hương Xứ Huế.</p>
        </div>
        <div class="admin-role-badge">
            <span>Vai trò:</span> <strong>ADMIN</strong>
        </div>
    </div>

    <!-- Thông báo tác vụ nếu có -->
    <?php if (!empty($flashNotice)): ?>
        <div class="admin-alert-flash">
            <span>🔔 <?php echo htmlspecialchars($flashNotice); ?></span>
        </div>
    <?php endif; ?>

    <!-- Thống kê Tài Chính & Hiệu Suất Kinh Doanh (6 Metric Cards) -->
    <div class="admin-stats-grid">
        <!-- 1. Tổng Doanh Thu Bán Hàng -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">💰</div>
            <div class="stat-info">
                <h3 style="color: #2e7d32;"><?php echo number_format($financials['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                <p>Tổng Doanh Thu Bán Hàng</p>
                <small style="color: #6d584e; font-size: 11px;">(Từ <?php echo $financials['total_orders'] ?? 0; ?> đơn đặt hàng)</small>
            </div>
        </div>

        <!-- 2. Tổng Vốn Nhập Hàng -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #fff3e0; color: #e65100;">📉</div>
            <div class="stat-info">
                <h3 style="color: #d35400;"><?php echo number_format($financials['total_import_cost'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                <p>Tổng Vốn Nhập Hàng</p>
                <small style="color: #6d584e; font-size: 11px;">(Đã chi <?php echo $financials['total_imports_count'] ?? 0; ?> đợt nhập)</small>
            </div>
        </div>

        <!-- 3. Tổng Thu Nhập Thuần (Lợi Nhuận) -->
        <?php 
            $net = $financials['net_income'] ?? 0;
            $netColor = ($net >= 0) ? '#27ae60' : '#c0392b';
        ?>
        <div class="stat-card" style="border: 2px solid <?php echo $netColor; ?>; background: #faf9f6;">
            <div class="stat-icon" style="background: #e8f8f5; color: #16a085;">📈</div>
            <div class="stat-info">
                <h3 style="color: <?php echo $netColor; ?>;"><?php echo number_format($net, 0, ',', '.'); ?> ₫</h3>
                <p><strong>Tổng Thu Nhập (Lợi Nhuận)</strong></p>
                <small style="color: #7f8c8d; font-size: 11px;">(Doanh thu &minus; Vốn nhập hàng)</small>
            </div>
        </div>

        <!-- 4. Tổng Tồn Kho Còn Lại -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #e1f5fe; color: #0277bd;">📦</div>
            <div class="stat-info">
                <h3 style="color: #0277bd;"><?php echo number_format($financials['total_stock'] ?? 0); ?> phần</h3>
                <p>Tổng Tồn Kho Còn Lại</p>
                <small style="color: #6d584e; font-size: 11px;">(Đã bán: <?php echo number_format($financials['total_sold'] ?? 0); ?> phần)</small>
            </div>
        </div>

        <!-- 5. Đơn Chờ Xác Nhận -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #ffebee; color: #c62828;">⏳</div>
            <div class="stat-info">
                <h3 style="color: #c62828;"><?php echo $orderStats['pending_orders'] ?? 0; ?> đơn</h3>
                <p>Đơn Chờ Xác Nhận</p>
                <small style="color: #6d584e; font-size: 11px;">(Cần duyệt ngay)</small>
            </div>
        </div>

        <!-- 6. Thành Viên Hệ Thống -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #f3e5f5; color: #6a1b9a;">👥</div>
            <div class="stat-info">
                <h3><?php echo $totalUsers; ?> người</h3>
                <p>Thành Viên Hệ Thống</p>
                <small style="color: #6d584e; font-size: 11px;">(Tài khoản đã đăng ký)</small>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BẢNG TIẾP NHẬN & QUẢN LÝ ĐƠN HÀNG ĐÃ ĐẶT (QUAN TRỌNG NHẤT) -->
    <!-- ======================================================== -->
    <div class="admin-table-card" id="adminOrdersSection">
        <div class="table-card-header">
            <div>
                <h3>📦 Quản Lý Tiếp Nhận Đơn Hàng Đã Đặt (<?php echo count($orders ?? []); ?> đơn)</h3>
                <p style="font-size: 13px; color: #6d584e; margin-top: 4px;">Dữ liệu đơn hàng do khách đặt từ Giỏ Hàng sẽ đẩy tức thì về đây để Admin tiếp nhận & đổi trạng thái.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Mã Đơn Hàng</th>
                        <th style="width: 180px;">Khách Hàng & SĐT</th>
                        <th style="width: 200px;">Địa Chỉ Giao</th>
                        <th>Chi Tiết Món Đặt</th>
                        <th style="width: 110px;">Tổng Tiền</th>
                        <th style="width: 130px;">Trạng Thái</th>
                        <th style="width: 220px; text-align: center;">Thao Tác Xử Lý Đơn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #8c786d;">
                                Hiện chưa có đơn hàng nào được đặt. Khi khách hàng đặt hàng từ giỏ hàng, thông tin sẽ hiển thị tại đây!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $ord): ?>
                            <?php 
                                $stKey = $ord['status'] ?? 'pending';
                                $stObj = $statusLabels[$stKey] ?? $statusLabels['pending'];
                            ?>
                            <tr class="order-row-<?php echo $stKey; ?>">
                                <td>
                                    <strong style="color: #8b5a2b;">#<?php echo htmlspecialchars($ord['order_code']); ?></strong>
                                    <div style="font-size: 11px; color: #8c786d; margin-top: 4px;"><?php echo htmlspecialchars($ord['created_at']); ?></div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                                    <div style="font-size: 13px; color: #2980b9; margin-top: 2px;">📞 <?php echo htmlspecialchars($ord['customer_phone']); ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; line-height: 1.4;"><?php echo htmlspecialchars($ord['customer_address']); ?></div>
                                    <?php if (!empty($ord['customer_note'])): ?>
                                        <div style="font-size: 11px; color: #e67e22; margin-top: 4px;"><em>Ghi chú: <?php echo htmlspecialchars($ord['customer_note']); ?></em></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="admin-items-summary">
                                        <?php if (!empty($ord['items'])): ?>
                                            <?php foreach ($ord['items'] as $it): ?>
                                                <div class="admin-item-line">
                                                    <span>• <?php echo htmlspecialchars($it['name']); ?></span>
                                                    <strong>x<?php echo $it['quantity']; ?></strong>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span>Món đặc sản Cố Đô</span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="font-size: 11px; color: #8c786d; margin-top: 4px;">
                                        PT: <?php echo ($ord['payment_method'] === 'vietqr') ? 'Chuyển khoản VietQR' : 'COD (Tiền mặt)'; ?>
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #d35400; font-size: 15px;">
                                        <?php echo number_format($ord['total_amount'], 0, ',', '.'); ?> ₫
                                    </strong>
                                </td>
                                <td>
                                    <span class="order-status-pill <?php echo $stObj['class']; ?>">
                                        <?php echo $stObj['icon']; ?> <?php echo $stObj['label']; ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="admin-order-actions-grid">
                                        <?php if ($stKey === 'pending'): ?>
                                            <a href="?controller=admin&action=update_order_status&order_id=<?php echo $ord['order_code']; ?>&status=confirmed" class="btn-action-status btn-confirm" title="Xác nhận đơn">
                                                ✓ Duyệt Đơn
                                            </a>
                                            <a href="?controller=admin&action=update_order_status&order_id=<?php echo $ord['order_code']; ?>&status=cancelled" class="btn-action-status btn-cancel" title="Hủy đơn" onclick="return confirm('Hủy đơn hàng này?')">
                                                ✕ Hủy
                                            </a>
                                        <?php elseif ($stKey === 'confirmed'): ?>
                                            <a href="?controller=admin&action=update_order_status&order_id=<?php echo $ord['order_code']; ?>&status=shipping" class="btn-action-status btn-ship" title="Chuyển sang giao hàng">
                                                🚚 Giao Hàng
                                            </a>
                                            <a href="?controller=admin&action=update_order_status&order_id=<?php echo $ord['order_code']; ?>&status=cancelled" class="btn-action-status btn-cancel" title="Hủy đơn" onclick="return confirm('Hủy đơn hàng này?')">
                                                ✕ Hủy
                                            </a>
                                        <?php elseif ($stKey === 'shipping'): ?>
                                            <a href="?controller=admin&action=update_order_status&order_id=<?php echo $ord['order_code']; ?>&status=completed" class="btn-action-status btn-complete" title="Đã giao thành công">
                                                ✅ Hoàn Thành
                                            </a>
                                        <?php elseif ($stKey === 'completed'): ?>
                                            <span style="color: #27ae60; font-size: 12px; font-weight: 700;">✓ Đã giao xong</span>
                                        <?php else: ?>
                                            <span style="color: #7f8c8d; font-size: 12px;">Đã hủy</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bảng quản lý người dùng và phân quyền -->
    <div class="admin-table-card">
        <div class="table-card-header">
            <h3>👥 Danh Sách Người Dùng & Phân Quyền Hệ Thống</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và Tên</th>
                        <th>Tài khoản</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Phân Quyền</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($u['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($u['fullname']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($u['username']); ?></code></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars(!empty($u['phone']) ? $u['phone'] : 'Chưa cập nhật'); ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge-role badge-admin">👑 Quản trị viên (Admin)</span>
                                <?php else: ?>
                                    <span class="badge-role badge-user">👤 Thành viên (User)</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- PHÂN HỆ QUẢN LÝ TỒN KHO & NHẬP HÀNG ĐẶC SẢN -->
    <!-- ======================================================== -->
    <div class="admin-inventory-section" id="section-inventory">
        <!-- 1. Form Nhập Hàng Mới Vào Kho -->
        <div class="admin-table-card import-form-card">
            <div class="table-card-header">
                <div>
                    <h3>📥 Nhập Thêm Hàng Mới Vào Kho (Tăng Số Lượng Tồn Kho)</h3>
                    <p style="font-size: 13px; color: #6d584e; margin-top: 4px;">Điền thông tin lô hàng nhập về để tự động tăng số lượng tồn kho và ghi nhận chi phí vốn kinh doanh.</p>
                </div>
            </div>

            <form action="?controller=admin&action=import_product" method="POST" class="admin-import-form" id="adminImportForm">
                <div class="import-form-grid">
                    <!-- Chọn sản phẩm -->
                    <div class="import-form-group">
                        <label for="importProdSelect">Chọn Đặc Sản Cần Nhập: <span style="color:red;">*</span></label>
                        <select name="product_id" id="importProdSelect" required onchange="onProductSelectChange()">
                            <option value="">-- Chọn đặc sản nhập kho --</option>
                            <?php foreach ($productsWithStock as $p): ?>
                                <option value="<?php echo $p['id']; ?>" data-name="<?php echo htmlspecialchars($p['name']); ?>" data-price="<?php echo $p['raw_price']; ?>" data-stock="<?php echo $p['stock_quantity']; ?>">
                                    #<?php echo $p['id']; ?> - <?php echo htmlspecialchars($p['name']); ?> (Hiện còn: <?php echo $p['stock_quantity']; ?> phần)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Số lượng nhập -->
                    <div class="import-form-group">
                        <label for="importQty">Số Lượng Nhập (Phần/Hộp): <span style="color:red;">*</span></label>
                        <input type="number" id="importQty" name="quantity" min="1" max="10000" value="20" required oninput="calculateImportCost()">
                    </div>

                    <!-- Giá vốn nhập / đơn vị -->
                    <div class="import-form-group">
                        <label for="importPrice">Giá Vốn Nhập / Phần (₫): <span style="color:red;">*</span></label>
                        <input type="number" id="importPrice" name="import_price" min="0" step="1000" value="50000" required oninput="calculateImportCost()">
                    </div>

                    <!-- Thành tiền vốn ước tính -->
                    <div class="import-form-group">
                        <label>Tổng Tiền Vốn Đợt Nhập:</label>
                        <div class="calculated-cost-box" id="calculatedTotalCost">
                            1.000.000 ₫
                        </div>
                    </div>

                    <!-- Nhà cung cấp / Lò đặc sản -->
                    <div class="import-form-group">
                        <label for="importSupplier">Nhà Cung Cấp / Lò Sản Xuất:</label>
                        <input type="text" id="importSupplier" name="supplier" placeholder="Ví dụ: Lò Bánh Bà Cư, Xưởng Mè Xửng Kim Long...">
                    </div>

                    <!-- Ghi chú lô hàng -->
                    <div class="import-form-group">
                        <label for="importNote">Ghi Chú Lô Hàng:</label>
                        <input type="text" id="importNote" name="note" placeholder="Ví dụ: Lô hàng mới sản xuất sáng nay, kiểm định tươi ngon...">
                    </div>
                </div>

                <div class="import-form-actions">
                    <button type="submit" class="btn-submit-import">
                        📥 Xác Nhận Lưu Phiếu Nhập & Tăng Tồn Kho
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Bảng Theo Dõi Số Lượng Tồn Kho Còn Lại (12 Đặc Sản) -->
        <div class="admin-table-card">
            <div class="table-card-header">
                <div>
                    <h3>📊 Bảng Kiểm Soát Số Lượng Tồn Kho (<?php echo count($productsWithStock); ?> đặc sản)</h3>
                    <p style="font-size: 13px; color: #6d584e; margin-top: 4px;">Theo dõi trực tiếp số lượng còn lại trong kho của từng món đặc sản, số lượng đã bán và bổ sung kịp thời.</p>
                </div>
                <div class="stock-summary-badge">
                    <span>Tổng tồn kho: <strong><?php echo number_format($financials['total_stock'] ?? 0); ?> phần</strong></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table stock-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Hình ảnh</th>
                            <th>Tên Đặc Sản Huế</th>
                            <th>Danh Mục</th>
                            <th>Giá Bán Niêm Yết</th>
                            <th style="text-align: center;">Số Lượng Còn Trong Kho</th>
                            <th style="text-align: center;">Đã Bán</th>
                            <th style="text-align: center;">Tình Trạng Kho</th>
                            <th style="text-align: center;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productsWithStock as $p): ?>
                            <?php 
                                $qty = intval($p['stock_quantity']);
                                $sold = intval($p['sold_quantity']);
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="" style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #e0d0c1;">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                    <div style="font-size: 11px; color: #888;">ID: #<?php echo $p['id']; ?></div>
                                </td>
                                <td>
                                    <span class="badge-cat"><?php echo htmlspecialchars($p['category']); ?></span>
                                </td>
                                <td>
                                    <strong style="color: #8b5a2b;"><?php echo htmlspecialchars($p['price']); ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="stock-num-highlight <?php echo ($qty <= 15) ? 'low-stock-num' : ''; ?>">
                                        <strong><?php echo $qty; ?></strong> phần
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="color: #27ae60; font-weight: 600;"><?php echo $sold; ?></span> phần
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($qty <= 0): ?>
                                        <span class="stock-status-pill pill-out">🔴 Hết Hàng</span>
                                    <?php elseif ($qty <= 15): ?>
                                        <span class="stock-status-pill pill-low">🟡 Sắp Hết (Cần Nhập)</span>
                                    <?php else: ?>
                                        <span class="stock-status-pill pill-good">🟢 Dồi Dào</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-quick-import" onclick="quickSelectProduct(<?php echo $p['id']; ?>, '<?php echo addslashes($p['name']); ?>', <?php echo intval($p['raw_price'] * 0.65); ?>)">
                                        + Nhập Hàng
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Lịch Sử Các Đợt Nhập Hàng Gần Đây -->
        <div class="admin-table-card">
            <div class="table-card-header">
                <div>
                    <h3>📜 Lịch Sử Phiếu Nhập Hàng Gần Nhất (Tổng chi phí vốn: <?php echo number_format($financials['total_import_cost'] ?? 0); ?> ₫)</h3>
                    <p style="font-size: 13px; color: #6d584e; margin-top: 4px;">Lưu trữ minh bạch các đợt nhập hàng để tính toán chính xác tổng chi phí vốn và thu nhập thuần.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã Phiếu</th>
                            <th>Thời Gian Nhập</th>
                            <th>Sản Phẩm Nhập</th>
                            <th style="text-align: center;">Số Lượng</th>
                            <th style="text-align: right;">Đơn Giá Vốn</th>
                            <th style="text-align: right;">Tổng Tiền Vốn</th>
                            <th>Nhà Cung Cấp / Lò Đặc Sản</th>
                            <th>Ghi Chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($importHistory)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: #888; padding: 25px;">Chưa có phiếu nhập hàng nào được tạo.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($importHistory as $imp): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($imp['import_code']); ?></code></td>
                                    <td style="font-size: 13px; color: #555;">📅 <?php echo htmlspecialchars($imp['created_at']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($imp['product_name']); ?></strong></td>
                                    <td style="text-align: center;"><span class="badge-role badge-admin">+<?php echo $imp['quantity']; ?></span></td>
                                    <td style="text-align: right;"><?php echo number_format($imp['import_price']); ?> ₫</td>
                                    <td style="text-align: right;"><strong style="color: #d35400;"><?php echo number_format($imp['total_cost']); ?> ₫</strong></td>
                                    <td><?php echo htmlspecialchars(!empty($imp['supplier']) ? $imp['supplier'] : 'Tự chế biến Cố Đô'); ?></td>
                                    <td style="font-size: 12px; color: #666;"><?php echo htmlspecialchars($imp['note'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function onProductSelectChange() {
    var select = document.getElementById('importProdSelect');
    var opt = select.options[select.selectedIndex];
    if (opt && opt.value) {
        var rawPrice = parseInt(opt.getAttribute('data-price')) || 50000;
        // Gợi ý giá vốn thông thường xấp xỉ 60% - 65% giá bán lẻ
        var defaultCost = Math.round((rawPrice * 0.65) / 1000) * 1000;
        document.getElementById('importPrice').value = defaultCost;
    }
    calculateImportCost();
}

function calculateImportCost() {
    var qty = parseInt(document.getElementById('importQty').value) || 0;
    var price = parseInt(document.getElementById('importPrice').value) || 0;
    var total = qty * price;
    var formatted = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " ₫";
    document.getElementById('calculatedTotalCost').innerText = formatted;
}

function quickSelectProduct(productId, productName, suggestedCost) {
    var select = document.getElementById('importProdSelect');
    select.value = productId;
    if (suggestedCost > 0) {
        document.getElementById('importPrice').value = suggestedCost;
    }
    document.getElementById('importQty').focus();
    calculateImportCost();

    // Cuộn mượt lên form nhập hàng
    var formCard = document.querySelector('.import-form-card');
    if (formCard) {
        formCard.scrollIntoView({ behavior: 'smooth' });
    }
}

// Khởi chạy tính toán ban đầu
document.addEventListener('DOMContentLoaded', calculateImportCost);
</script>
