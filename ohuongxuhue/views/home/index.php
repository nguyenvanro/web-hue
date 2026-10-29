<?php
/**
 * View Trang chủ O Hương Xứ Huế
 * Nhận dữ liệu từ HomeController: $categories, $topProducts, $banhHueProducts, $meXungProducts, $allProductsMaster
 */

$detailUrl       = function_exists('home_url') ? home_url('/?controller=product&action=detail&id=') : 'index.php?controller=product&action=detail&id=';
$checkoutUrl     = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() . '?sync_cart=1' : (function_exists('home_url') ? home_url('/thanh-toan/?sync_cart=1') : 'thanh-toan/?sync_cart=1');
$cartUrl         = function_exists('home_url') ? home_url('/?controller=cart') : 'index.php?controller=cart';
$theme_uri       = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
require __DIR__ . '/consultation.php';
// Thứ tự slideshow được chỉ định, không phụ thuộc thứ tự tên file.
$heroImageNames = ['Hat_Sen_Tuoi_Hue.png', 'Tre_Hue.png', 'Tra_Sen_Hue.png', 'Mam_Hue.png'];
$heroImageUrl = $theme_uri . '/assets/images/banner';
$heroImageDir = dirname(__DIR__, 2) . '/assets/images/banner';
if (!function_exists('home_url') && is_dir(dirname(__DIR__, 3) . '/images/banner')) {
    $heroImageUrl = '../images/banner';
    $heroImageDir = dirname(__DIR__, 3) . '/images/banner';
}
$heroImages = array_map(function ($filename) use ($heroImageUrl, $heroImageDir) {
    $imagePath = $heroImageDir . '/' . $filename;
    $version = is_file($imagePath) ? '?v=' . filemtime($imagePath) : '';
    return $heroImageUrl . '/' . rawurlencode($filename) . $version;
}, $heroImageNames);
?>

<style>
/* CSS Nút Thêm vào giỏ & Mua ngay cho Card sản phẩm */
.card-btn-group {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
    margin-top: 12px;
}

.btn-add-cart-ajax {
    flex: 1;
    background: #fdf6e7;
    color: #8b1d24;
    border: 1.5px solid #d4af37;
    border-radius: 20px;
    padding: 8px 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-add-cart-ajax:hover {
    background: #d4af37;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.35);
}

.btn-buy-now {
    flex: 1;
    background: linear-gradient(135deg, #8b1d24 0%, #6b1218 100%);
    color: #ffffff !important;
    border: 1.5px solid #8b1d24;
    border-radius: 20px;
    padding: 8px 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 3px 10px rgba(139, 29, 36, 0.25);
}

.btn-buy-now:hover {
    background: linear-gradient(135deg, #a0232b 0%, #7d161d 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(139, 29, 36, 0.45);
}

.product-img-link {
    display: block;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    border-radius: 12px;
}

.product-img-link .product-img img {
    transition: transform 0.35s ease;
    display: block;
    width: 100%;
}

.product-card:hover .product-img img {
    transform: scale(1.06);
}

.product-title a {
    color: #3e2723;
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: #8b1d24;
}

/* Toast thông báo thêm giỏ hàng */
</style>

    <!-- BANNER TRANG CHỦ; DANH MỤC NẰM TRONG MENU ĐẶC SẢN HUẾ -->
    <div class="main-layout main-layout-home">
        
        <!-- Banner -->
        <div class="content-area">
            <section class="hero-section">
                <!-- 1. Cột Trái: Chữ Hương Vị Cố Đô -->
                <div class="hero-codo-left">
                    <h2>HƯƠNG VỊ CỐ ĐÔ</h2>
                    <p>Hương vị tinh túy cố đô nơi rạng danh nét ẩm thực Hương Xứ Huế</p>
                </div>
                
                <!-- Cột ảnh rộng: gộp cột giữa và cột phải thành một slideshow. -->
                <div class="hero-wide-slideshow" data-hero-slideshow data-hero-images="<?php echo esc_attr(json_encode($heroImages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?>">
                    <div class="hero-wide-frame hero-slide-frame">
                        <img class="hero-slide-image" src="<?php echo esc_url($heroImages[0]); ?>" alt="Banner đặc sản O Hương Xứ Huế" fetchpriority="high" decoding="async">
                    </div>
                </div>
            </section>
        </div> 
        <!-- Đóng Banner -->

    </div> 
    <!-- Đóng Bố Cục Banner -->
    <script src="<?php echo esc_url($theme_uri); ?>/assets/js/hero-slideshow.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/hero-slideshow.js'); ?>" defer></script>


    <!-- ========================================== -->
    <!-- KHU VỰC TOP ĐẶC SẢN (KÉO RỘNG CÂN ĐỐI 1400PX) -->
    <!-- ========================================== -->
    <div style="max-width: 1400px; margin: 0 auto 55px auto; padding: 0 24px;">
        <section class="products-section reveal-on-scroll">
            <div class="section-title-wrapper">
                <h2 class="section-title">Top Đặc Sản Huế Nổi Bật</h2>
                <p class="section-subtitle">Tinh hoa ẩm thực Cố Đô lưu truyền ngàn đời, đậm đà phong vị sông Hương núi Ngự</p>
            </div>
            <div class="products-grid">
                <?php foreach ($topProducts as $item): ?>
                    <?php $itemUrl = $detailUrl . $item['id']; ?>
                    <div class="product-card">
                        <!-- Nhấn vào ảnh để xem chi tiết (qua trang chi tiết ở giữa) -->
                        <a href="<?php echo esc_url($itemUrl); ?>" class="product-img-link" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                            <div class="product-img">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                        </a>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="<?php echo esc_url($itemUrl); ?>" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            </h3>
                            <div class="product-price"><?php echo htmlspecialchars($item['price']); ?></div>
                            
                            <!-- 2 Nút: Thêm vào giỏ hàng & Mua ngay -->
                            <div class="card-btn-group">
                                <button type="button" class="btn-add-cart-ajax" onclick="addCartAjax(<?php echo (int) $item['id']; ?>, <?php echo htmlspecialchars(json_encode($item['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>)" title="Thêm vào giỏ hàng">
                                    🛒 Thêm vào giỏ
                                </button>
                                <a href="?controller=cart&action=add&id=<?php echo $item['id']; ?>&redirect=<?php echo urlencode($checkoutUrl); ?>" class="btn-buy-now" title="Mua ngay đặc sản này">
                                    ⚡ Mua ngay
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>


    <!-- ========================================== -->
    <!-- KHU VỰC CÁC DANH MỤC LỚN BÊN DƯỚI -->
    <!-- ========================================== -->
    <div class="bottom-category-wrapper">
        
        <!-- Khối 1: BÁNH HUẾ TRUYỀN THỐNG (THEME XANH LỤC HOÀNG GIA & VÀNG) -->
        <div class="category-showcase-box theme-banh-hue reveal-on-scroll">
            <div class="showcase-header">
                <div class="showcase-title-group">
                    <div class="showcase-icon-badge">🥟</div>
                    <div>
                        <h3 class="showcase-title">BÁNH ÉP &amp; NEM TRÉ HUẾ</h3>
                        <span class="showcase-tagline">Đậm đà phong vị Cố Đô • Bánh ép giòn rụm, tré bò rơm lên men chuẩn vị</span>
                    </div>
                </div>
                <div class="showcase-pills">
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=banh-ep-hue') : 'index.php?controller=dac-san&cat=banh-ep-hue')); ?>" class="showcase-pill-link active">Tất Cả Bánh Ép</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=banh-ep-hue') : 'index.php?controller=dac-san&cat=banh-ep-hue')); ?>" class="showcase-pill-link">Bánh Ép Khô</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=nem-chua-hue') : 'index.php?controller=dac-san&cat=nem-chua-hue')); ?>" class="showcase-pill-link">Nem Chua Huế</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=tre-hue') : 'index.php?controller=dac-san&cat=tre-hue')); ?>" class="showcase-pill-link">Tré Bò Cố Đô</a>
                </div>
            </div>
            
            <div class="showcase-content">
                <!-- Thẻ điểm nhấn bên trái -->
                <div class="showcase-hero-card">
                    <div class="showcase-img-wrap">
                        <img src="<?php echo esc_url($theme_uri . '/assets/images/spotlight-banh.jpg'); ?>" alt="Đặc sản Bánh Huế truyền thống">
                        <div class="showcase-badge-floating">🌿 Tươi Ngon Chuẩn Vị</div>
                    </div>
                    <div class="showcase-card-overlay">
                        <div>
                            <h4>Bánh Ép &amp; Nem Tré Cố Đô</h4>
                            <p>Bánh ép Thuận An giòn rụm đậm vị, nem chua chùm lá ổi lên men truyền thống, tré bò cay nồng thơm ngát mùi riềng tiêu.</p>
                        </div>
                        <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=banh-ep-hue') : 'index.php?controller=dac-san&cat=banh-ep-hue')); ?>" class="showcase-cta-btn">Khám Phá Menu &rarr;</a>
                    </div>
                </div>

                <!-- Lưới sản phẩm bên phải -->
                <div class="showcase-products-grid">
                    <?php foreach ($banhHueProducts as $item): ?>
                        <?php $itemUrl = $detailUrl . $item['id']; ?>
                        <div class="product-card">
                            <a href="<?php echo esc_url($itemUrl); ?>" class="product-img-link" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                <div class="product-img">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                            </a>
                            <div class="product-info">
                                <h4 class="product-title">
                                    <a href="<?php echo esc_url($itemUrl); ?>" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </a>
                                </h4>
                                <div class="product-price"><?php echo htmlspecialchars($item['price']); ?></div>
                                
                                <div class="card-btn-group">
                                    <button type="button" class="btn-add-cart-ajax" onclick="addCartAjax(<?php echo (int) $item['id']; ?>, <?php echo htmlspecialchars(json_encode($item['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>)" title="Thêm vào giỏ hàng">
                                        🛒 Thêm vào giỏ
                                    </button>
                                    <a href="?controller=cart&action=add&id=<?php echo $item['id']; ?>&redirect=<?php echo urlencode($checkoutUrl); ?>" class="btn-buy-now" title="Mua ngay đặc sản này">
                                        ⚡ Mua ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Khối 2: MÈ XỬNG & TRÀ CUNG ĐÌNH (THEME ĐỎ RUBY HOÀNG GIA & VÀNG) -->
        <div class="category-showcase-box theme-me-xung reveal-on-scroll">
            <div class="showcase-header">
                <div class="showcase-title-group">
                    <div class="showcase-icon-badge">🍬</div>
                    <div>
                        <h3 class="showcase-title">MÈ XỬNG, KẸO HUẾ &amp; TRÀ CUNG ĐÌNH</h3>
                        <span class="showcase-tagline">Thức quà tiến vua • Mè rang vàng ruộm, hạt sen Tịnh Tâm bùi béo trứ danh</span>
                    </div>
                </div>
                <div class="showcase-pills">
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=keo-hue') : 'index.php?controller=dac-san&cat=keo-hue')); ?>" class="showcase-pill-link active">Tất Cả Kẹo Huế</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=tra-hue') : 'index.php?controller=dac-san&cat=tra-hue')); ?>" class="showcase-pill-link">Trà Cung Đình</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=hat-sen-hue') : 'index.php?controller=dac-san&cat=hat-sen-hue')); ?>" class="showcase-pill-link">Hạt Sen Huế</a>
                    <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=mam-hue') : 'index.php?controller=dac-san&cat=mam-hue')); ?>" class="showcase-pill-link">Mắm Cố Đô</a>
                </div>
            </div>
            
            <div class="showcase-content">
                <!-- Thẻ điểm nhấn bên trái -->
                <div class="showcase-hero-card">
                    <div class="showcase-img-wrap">
                        <img src="<?php echo esc_url($theme_uri . '/assets/images/spotlight-mexung.jpg'); ?>" alt="Đặc sản Mè Xửng & Trà Cung Đình Huế">
                        <div class="showcase-badge-floating">👑 Đặc Sản Tiến Vua</div>
                    </div>
                    <div class="showcase-card-overlay">
                        <div>
                            <h4>Mè Xửng &amp; Trà Huế</h4>
                            <p>Thưởng thức miếng mè xửng bùi béo ngọt thanh, nhâm nhi tách trà cung đình thơm ngát hoa cúc hoa sen.</p>
                        </div>
                        <a href="<?php echo esc_url((function_exists('home_url') ? home_url('/dac-san/?cat=me-xung') : 'index.php?controller=dac-san&cat=me-xung')); ?>" class="showcase-cta-btn">Khám Phá Menu &rarr;</a>
                    </div>
                </div>

                <!-- Lưới sản phẩm bên phải -->
                <div class="showcase-products-grid">
                    <?php foreach ($meXungProducts as $item): ?>
                        <?php $itemUrl = $detailUrl . $item['id']; ?>
                        <div class="product-card">
                            <a href="<?php echo esc_url($itemUrl); ?>" class="product-img-link" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                <div class="product-img">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                            </a>
                            <div class="product-info">
                                <h4 class="product-title">
                                    <a href="<?php echo esc_url($itemUrl); ?>" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </a>
                                </h4>
                                <div class="product-price"><?php echo htmlspecialchars($item['price']); ?></div>
                                
                                <div class="card-btn-group">
                                    <button type="button" class="btn-add-cart-ajax" onclick="addCartAjax(<?php echo (int) $item['id']; ?>, <?php echo htmlspecialchars(json_encode($item['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>)" title="Thêm vào giỏ hàng">
                                        🛒 Thêm vào giỏ
                                    </button>
                                    <a href="?controller=cart&action=add&id=<?php echo $item['id']; ?>&redirect=<?php echo urlencode($checkoutUrl); ?>" class="btn-buy-now" title="Mua ngay đặc sản này">
                                        ⚡ Mua ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div> 
    <!-- Đóng khu vực khối danh mục dưới -->

    <!-- Toast thông báo thêm giỏ hàng thành công -->
    <?php require __DIR__ . '/../layouts/cart-toast.php'; ?>

    <script>
    function toggleSidebarCategory(headerEl) {
        const parent = headerEl.closest('.sidebar-item.has-accordion');
        if (!parent) return;
        const isAlreadyActive = parent.classList.contains('active');
        
        // Đóng tất cả các mục accordion khác để giữ sidebar gọn gàng
        document.querySelectorAll('.sidebar-item.has-accordion').forEach(function(item) {
            item.classList.remove('active');
        });
        
        // Nếu mục vừa bấm chưa mở thì mở ra
        if (!isAlreadyActive) {
            parent.classList.add('active');
        }
    }

    </script>
