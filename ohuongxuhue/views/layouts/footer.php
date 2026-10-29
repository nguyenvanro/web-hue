<?php
/**
 * Layout Footer chung cho website O Hương Xứ Huế
 */
$theme_uri = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
?>
    <!-- ============================================================== -->
    <!-- CHÂN TRANG HOÀNG GIA CỐ ĐÔ HUẾ (ROYAL LUXURY FOOTER) -->
    <!-- ============================================================== -->
    <footer class="main-footer">
        <div class="footer-top-trust">
            <div class="footer-trust-container">
                <div class="footer-trust-card">
                    <div class="trust-card-icon">👑</div>
                    <div class="trust-card-text">
                        <h4>100% Vị Cố Đô Gốc</h4>
                        <p>Bí quyết gia truyền lưu giữ ẩm thực Hoàng Gia Huế</p>
                    </div>
                </div>
                <div class="footer-trust-card">
                    <div class="trust-card-icon">🚀</div>
                    <div class="trust-card-text">
                        <h4>Giao Hàng Hỏa Tốc</h4>
                        <p>Đóng thùng xốp đá khô bảo quản bánh tươi trọn vẹn</p>
                    </div>
                </div>
                <div class="footer-trust-card">
                    <div class="trust-card-icon">🛡️</div>
                    <div class="trust-card-text">
                        <h4>Đảm Bảo Vệ Sinh ATTP</h4>
                        <p>Chứng nhận an toàn thực phẩm, không hóa chất độc hại</p>
                    </div>
                </div>
                <div class="footer-trust-card">
                    <div class="trust-card-icon">❤️</div>
                    <div class="trust-card-text">
                        <h4>Đổi Trả 24H Miễn Phí</h4>
                        <p>Cam kết bồi hoàn 100% nếu sản phẩm lỗi hay hỏng hóc</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-main-container">
            <!-- Cột 1: Thông tin thương hiệu -->
            <div class="footer-col footer-col-brand">
                <div class="footer-logo">
                    <img src="<?php echo esc_url($theme_uri); ?>/assets/images/logo.jpg" alt="Logo O Hương Xứ Huế">
                    <div class="footer-logo-text">
                        <h3>O Hương Xứ Huế</h3>
                        <span>Vị Ngon Cố Đô</span>
                    </div>
                </div>
                <p class="footer-brand-desc">
                    Tự hào gìn giữ và lan tỏa tinh hoa ẩm thực Cố Đô Huế. Từng chiếc bánh tươi, hộp kẹo mè xửng hay hũ tôm chua đều đong đầy tâm tình đất Thần Kinh.
                </p>
                <ul class="footer-contact-list">
                    <li>
                        <span class="f-icon">📍</span>
                        <span>101 Trường Sa, Phường Ngũ Hành Sơn, TP. Đà Nẵng</span>
                    </li>
                    <li>
                        <span class="f-icon">📞</span>
                        <span>Hotline: <a href="tel:0342684980">0342.684.980</a> (07:00 - 22:00)</span>
                    </li>
                </ul>
            </div>

            <!-- Cột 2: Danh mục đặc sản tuyển chọn -->
            <div class="footer-col">
                <h4 class="footer-heading">Đặc Sản Nổi Tiếng</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=banh-ep-hue') : 'index.php?controller=dac-san&cat=banh-ep-hue')); ?>">Bánh Ép &amp; Nem Tré Huế</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=keo-hue') : 'index.php?controller=dac-san&cat=keo-hue')); ?>">Kẹo Huế &amp; Mè Xửng Giòn</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=tra-hue') : 'index.php?controller=dac-san&cat=tra-hue')); ?>">Trà Cung Đình &amp; Trà Sen</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=mam-hue') : 'index.php?controller=dac-san&cat=mam-hue')); ?>">Mắm Tôm Chua, Ruốc, Nêm, Sò</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=hat-sen-hue') : 'index.php?controller=dac-san&cat=hat-sen-hue')); ?>">Hạt Sen Huế Tịnh Tâm</a></li>
                </ul>
            </div>

            <!-- Cột 3: Hướng dẫn & Chính sách -->
            <div class="footer-col">
                <h4 class="footer-heading">Hỗ Trợ Khách Hàng</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/gioi-thieu/') : 'index.php?controller=gioi-thieu')); ?>">Về Chúng Tôi (Câu Chuyện Cố Đô)</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/don-hang/') : 'index.php?controller=don-hang')); ?>">Tra Cứu Tiến Độ Đơn Hàng</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/lien-he/') : 'index.php?controller=lien-he')); ?>">Chính Sách Đổi Trả & Hoàn Tiền</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/lien-he/') : 'index.php?controller=lien-he')); ?>">Quy Trình Đóng Gói Thùng Xốp</a></li>
                    <li><a href="<?php echo esc_url((function_exists('home_url') ? home_url('/lien-he/') : 'index.php?controller=lien-he')); ?>">Bảo Mật Thông Tin Khách Hàng</a></li>
                </ul>
            </div>

            <!-- Cột 4: Thanh toán -->
            <div class="footer-col">
                <h4 class="footer-heading">Thanh Toán</h4>

                <div class="footer-payment-badges">
                    <span class="pay-badge">💳 VietQR Pro</span>
                    <span class="pay-badge">📦 COD Tận Nơi</span>
                    <span class="pay-badge">🏦 37 Ngân Hàng</span>
                    <span class="pay-badge">🛡️ SSL 256-bit</span>
                </div>
            </div>
        </div>

        <!-- Dòng đáy bản quyền -->
        <div class="footer-bottom-bar">
            <div class="footer-bottom-container">
                <p>&copy; <?php echo date('Y'); ?> <strong>O Hương Xứ Huế</strong> — Vị Ngon Cố Đô. <?php echo class_exists('LanguageEngine') ? LanguageEngine::t('footer_rights', 'Bảo lưu mọi quyền bởi Thương hiệu Đặc Sản O Hương Xứ Huế.') : 'Bảo lưu mọi quyền bởi Thương hiệu Đặc Sản O Hương Xứ Huế.'; ?></p>
                <div class="footer-bottom-links">
                    <span>Chuẩn vị truyền thống Cố Đô</span>
                    <span class="sep">•</span>
                    <span>Giao hàng toàn quốc</span>
                    <span class="sep">•</span>
                    <span>Hỗ trợ 24/7</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Nạp Buttonizer Speed Dial & Trợ Lý AI Hương Huế Chatbot -->
    <?php if (($currentNav ?? '') !== 'admin'): ?>
    <link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/contact-widget.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/contact-widget.css'); ?>">
    <script src="<?php echo esc_url($theme_uri); ?>/assets/js/contact-widget.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/contact-widget.js'); ?>"
        data-chat-url="<?php echo esc_url(function_exists('home_url') ? home_url('/?controller=chat') : 'index.php?controller=chat'); ?>"
        data-logo-url="<?php echo esc_url($theme_uri); ?>/assets/images/logo.jpg"
        data-contact-url="<?php echo esc_url(function_exists('home_url') ? home_url('/lien-he/') : 'index.php?controller=contact'); ?>"
        data-address-url="<?php echo esc_url(function_exists('home_url') ? home_url('/cua-hang/') : 'index.php?controller=store'); ?>" defer></script>
    <?php endif; ?>

    <!-- File JavaScript chính (Bộ nhớ đệm 304 / Disk Cache) -->
    <script src="<?php echo esc_url($theme_uri); ?>/assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/main.js'); ?>" defer></script>
    <!-- Trải Nghiệm Độc Bản: Không Gian Cố Đô -->
    <script src="<?php echo esc_url($theme_uri); ?>/assets/js/hue-ambient.js?v=3.0.0" defer></script>
    <!-- Bản Đồ Tương Tác Ẩm Thực Cố Đô -->
    <script src="<?php echo esc_url($theme_uri); ?>/assets/js/hue-food-map.js?v=1.1.0" defer></script>

    <?php if (function_exists('wp_footer')) wp_footer(); ?>
</body>
</html>
