<?php
/**
 * View Hệ Thống Cửa Hàng & Showroom O Hương Xứ Huế
 */
$theme_uri = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url  = function_exists('home_url') ? home_url('/') : 'index.php';
$stores    = $stores ?? [];
?>

<div class="store-page-master">
    <!-- Header Banner -->
    <section class="store-hero-banner">
        <div class="store-hero-content">
            <nav class="store-breadcrumb">
                <a href="<?php echo esc_url($home_url); ?>">Trang Chủ</a> &rsaquo; <span>Hệ Thống Cửa Hàng</span>
            </nav>
            <span class="store-hero-tag">📍 Không Gian Trải Nghiệm Ẩm Thực</span>
            <h1 class="store-hero-title">Hệ Thống Cửa Hàng & Showroom O Hương Xứ Huế</h1>
            <p class="store-hero-desc">
                Kính mời quý khách ghé thăm không gian trà bánh Cố Đô, tận mắt chứng kiến bàn tay khéo léo của nghệ nhân và thưởng thức hương vị đặc sản hoàn toàn miễn phí!
            </p>
        </div>
    </section>

    <div class="store-container">
        <!-- LƯỚI SHOWROOMS -->
        <div class="store-grid">
            <?php foreach ($stores as $s): ?>
                <div class="store-card">
                    <div class="store-img-wrapper">
                        <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars('Ảnh minh họa trải nghiệm đặc sản Huế — ' . $s['name']); ?>" loading="lazy" decoding="async">
                        <span class="store-badge"><?php echo htmlspecialchars($s['badge']); ?></span>
                        <span class="store-image-caption">Ảnh minh họa</span>
                    </div>

                    <div class="store-body">
                        <span class="store-type"><?php echo htmlspecialchars($s['type']); ?></span>
                        <h3 class="store-name"><?php echo htmlspecialchars($s['name']); ?></h3>

                        <div class="store-details-list">
                            <div class="store-detail-row">
                                <span class="detail-icon">📍</span>
                                <div>
                                    <strong>Địa chỉ:</strong>
                                    <p><?php echo htmlspecialchars($s['address']); ?></p>
                                </div>
                            </div>

                            <div class="store-detail-row">
                                <span class="detail-icon">📞</span>
                                <div>
                                    <strong>Hotline:</strong>
                                    <p><?php echo htmlspecialchars($s['phone']); ?></p>
                                </div>
                            </div>

                            <div class="store-detail-row">
                                <span class="detail-icon">⏰</span>
                                <div>
                                    <strong>Giờ phục vụ:</strong>
                                    <p><?php echo htmlspecialchars($s['hours']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Các tiện ích nổi bật -->
                        <div class="store-services-box">
                            <strong><?php echo htmlspecialchars($s['services_title'] ?? '✨ Tiện ích & Trải nghiệm miễn phí:'); ?></strong>
                            <ul>
                                <?php foreach ($s['services'] as $srv): ?>
                                    <li>✓ <?php echo htmlspecialchars($srv); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="store-card-actions">
                            <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', explode(' - ', $s['phone'])[0])); ?>" class="btn-call-store">📞 Gọi Đặt Bàn / Giữ Bánh</a>
                            <a href="https://maps.google.com/?q=<?php echo urlencode($s['address']); ?>" target="_blank" class="btn-direct-map">🗺️ Chỉ Đường</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- BẢN ĐỒ GOOGLE MAPS TƯƠNG TÁC -->
        <section class="map-section-card">
            <div class="map-header">
                <h2>🗺️ Bản Đồ Vị Trí Trụ Sở Chính</h2>
                <p>128 Nguyễn Huệ, Phường Vĩnh Ninh, TP. Huế (Nằm ngay trung tâm Cố Đô, cách Cầu Trường Tiền 800m)</p>
            </div>
            <div class="map-frame-wrapper">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3826.241517452814!2d107.58550187514488!3d16.463379284274958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141a13e2f5b61b1%3A0xa19bf9a1c6a0c00b!2zMTI4IE5ndXnhu4VuIEh14buHLCBWxKluaCBOaW5oLCBUaMOgbmggcGjhu5EgSHXhur8!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </section>
    </div>
</div>
