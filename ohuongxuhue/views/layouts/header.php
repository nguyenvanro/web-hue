<?php
/**
 * Layout Header chung cho toàn bộ website O Hương Xứ Huế
 */
$currentNav = isset($currentNav) ? $currentNav : 'home';
$pageTitle  = isset($pageTitle) ? $pageTitle : 'O Hương Xứ Huế - Vị Ngon Cố Đô';
$theme_uri  = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$site_url   = function_exists('home_url') ? home_url('/') : 'index.php';
$about_url   = function_exists('home_url') ? home_url('/gioi-thieu/') : 'index.php?controller=about';
$dacsan_url  = function_exists('home_url') ? home_url('/dac-san/') : 'index.php?controller=dac-san';
$blog_url    = function_exists('home_url') ? home_url('/blog/') : 'index.php?controller=blog';
$contact_url = function_exists('home_url') ? home_url('/lien-he/') : 'index.php?controller=lien-he';
$store_url   = function_exists('home_url') ? home_url('/cua-hang/') : 'index.php?controller=cua-hang';
$map_url     = function_exists('home_url') ? home_url('/?controller=map') : 'index.php?controller=map';
$cart_url    = function_exists('home_url') ? home_url('/gio-hang/') : 'index.php?controller=cart';
$checkout_url= function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : (function_exists('home_url') ? home_url('/thanh-toan/') : 'thanh-toan/');
$orders_url  = function_exists('home_url') ? home_url('/don-hang/') : 'index.php?controller=cart&action=orders';
$login_url   = function_exists('home_url') ? home_url('/?controller=auth&action=login') : 'index.php?controller=auth&action=login';
$logout_url  = function_exists('home_url') ? home_url('/?controller=auth&action=logout') : 'index.php?controller=auth&action=logout';
$admin_url   = function_exists('home_url') ? home_url('/?controller=admin') : 'index.php?controller=admin';

$isLoggedIn  = isset($_SESSION['user']);
$currentUser = $isLoggedIn ? $_SESSION['user'] : null;
$isAdmin     = $isLoggedIn && isset($currentUser['role']) && $currentUser['role'] === 'admin';

$customCartCount = class_exists('CartController') ? CartController::getCartTotalCount() : 0;
$wcCartCount     = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
$cartCount       = (!empty($_SESSION['cart'])) ? $customCartCount : $wcCartCount;
$navCategoryModel = class_exists('CategoryModel') ? new CategoryModel() : null;
$navCategories = $navCategoryModel ? $navCategoryModel->getSidebarCategories() : [];

?>
<!DOCTYPE html>
<html <?php if (function_exists('language_attributes')) language_attributes(); else echo 'lang="vi"'; ?>>
<head>
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url($theme_uri); ?>/assets/images/logo.ico">
    <meta charset="<?php echo function_exists('bloginfo') ? get_bloginfo('charset') : 'UTF-8'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
    if (!empty($article)) {
        require_once __DIR__ . '/../../models/SEOEngine.php';
        SEOEngine::renderBlogArticleHead($article);
        if (function_exists('remove_action')) remove_action('wp_head', '_wp_render_title_tag', 1);
    } elseif (class_exists('SEOEngine')) {
        SEOEngine::renderHead($pageTitle ?? '', $currentNav ?? '');
    } else {
        echo '    <title>' . htmlspecialchars($pageTitle) . '</title>' . "\n";
    }
?>
    <!-- TỐI ƯU TẢI PHÔNG CHỮ & KẾT NỐI SỚM (PRECONNECT & DNS-PREFETCH) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    
    <!-- PRELOAD LCP HERO BANNER ĐẢM BẢO TỐC ĐỘ TẢI TRANG TỨC THÌ -->
    <?php if ($currentNav === 'home'): ?>
    <link rel="preload" as="image" href="<?php echo esc_url($theme_uri); ?>/assets/images/banner/Hat_Sen_Tuoi_Hue.png">
    <?php endif; ?>

    <!-- STYLESHEET TỐI ƯU (BỘ NHỚ ĐỆM TRÌNH DUYỆT 304 / DISK CACHE) -->
    <?php if (!function_exists('wp_head')): ?>
    <link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/style.css'); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/hue-ambient.css?v=3.0.0">
    <link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/hue-food-map.css?v=1.1.0">
    <script>
        window.HUE_THEME_URI = '<?php echo esc_url($theme_uri); ?>';
    </script>
    <?php if (function_exists('wp_head')) wp_head(); ?>
<link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/responsive.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/responsive.css'); ?>">
<script src="<?php echo esc_url($theme_uri); ?>/assets/js/responsive.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/responsive.js'); ?>" defer></script>
<script src="<?php echo esc_url($theme_uri); ?>/assets/js/header-search.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/header-search.js'); ?>" defer></script>
</head>
<body <?php if (function_exists('body_class')) body_class(); ?>>
<?php if (function_exists('wp_body_open')) wp_body_open(); ?>

    <!-- KHÔNG GIAN HOA VĂN TRỐNG ĐỒNG ĐÔNG SƠN HUẾ XOAY ĐỘNG (ROYAL ROTATING BRONZE DRUM BACKGROUND) -->
    <div class="hue-trong-dong-bg" id="hueTrongDongBg" aria-hidden="true">
        <div class="hue-trong-dong-glow"></div>
        <div class="hue-trong-dong-disc hue-trong-dong-main" title="Hoa văn Trống Đồng Đông Sơn xoay">
            <img src="<?php echo esc_url($theme_uri); ?>/assets/images/trong-dong-dong-son.svg" alt="Hoa văn Trống Đồng Đông Sơn Cố Đô" loading="eager">
        </div>
    </div>

    <!-- THANH TIỆN ÍCH TRÊN CÙNG (TOP ANNOUNCEMENT BAR) -->
    <div class="top-announcement-bar">
        <div class="top-bar-container">
            <div class="top-bar-left">
                <span class="top-tag"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('top_tag', 'CỐ ĐÔ HUẾ') : 'CỐ ĐÔ HUẾ'; ?></span>
                <span class="top-text"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('top_slogan', 'Tinh hoa ẩm thực Xứ Huế — Chuẩn vị truyền thống cố đô') : 'Tinh hoa ẩm thực Xứ Huế — Chuẩn vị truyền thống cố đô'; ?></span>
            </div>
            <div class="top-bar-right">
                <span class="top-feature">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    <?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nationwide_ship', 'Giao hàng toàn quốc') : 'Giao hàng toàn quốc'; ?>
                </span>
                <span class="top-sep">|</span>
                <span class="top-feature">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <?php echo class_exists('LanguageEngine') ? LanguageEngine::t('food_safety', 'Đảm bảo ATTP 100%') : 'Đảm bảo ATTP 100%'; ?>
                </span>
                <span class="top-sep">|</span>
                <!-- Nút chuyển ngôn ngữ Song ngữ Việt - Anh -->
                <?php if (class_exists('LanguageEngine')) echo LanguageEngine::renderLanguageSwitcher(); ?>
            </div>
        </div>
    </div>

    <!-- THÔNG TIN HEADER CHÍNH -->
    <header class="main-header">
        <div class="header-container">
            <!-- LOGO THƯƠNG HIỆU -->
            <a href="<?php echo esc_url($site_url); ?>" class="logo-brand" title="Trang chủ O Hương Xứ Huế">
                <div class="logo-img-wrapper">
                    <img src="<?php echo esc_url($theme_uri); ?>/assets/images/logo.jpg" alt="Logo O Hương Xứ Huế">
                </div>
                <div class="logo-text">
                    <?php if (!empty($article)): ?>
                        <div class="site-brand-title">O Hương Xứ Huế</div>
                    <?php else: ?>
                        <h1>O Hương Xứ Huế</h1>
                    <?php endif; ?>
                    <p class="logo-slogan">Vị Ngon Cố Đô</p>
                </div>
            </a>

            <!-- KHUNG TÌM KIẾM SANG TRỌNG KÈM LIVE SEARCH GỢI Ý TỨC THÌ -->
            <?php $curQ = sanitize_text_field($_GET['q'] ?? ''); ?>
            <form class="search-bar" role="search" method="get" action="<?php echo esc_url(function_exists('home_url') ? home_url('/') : 'index.php'); ?>" data-search-url="<?php echo esc_url(function_exists('home_url') ? home_url('/?controller=product&action=ajax_search') : 'index.php?controller=product&action=ajax_search'); ?>">
                <input type="hidden" name="controller" value="dac-san">
                <div class="search-input-wrapper">
                    <input type="search" name="q" aria-label="Tìm kiếm đặc sản đang bán" id="headerSearchInput" value="<?php echo esc_attr($curQ); ?>" placeholder="<?php echo class_exists('LanguageEngine') ? LanguageEngine::t('search_placeholder', 'Tìm kiếm đặc sản Huế thơm ngon...') : 'Tìm kiếm đặc sản Huế thơm ngon...'; ?>" autocomplete="off">
                </div>
                <button type="submit" id="headerSearchBtn" title="Tìm kiếm">
                    <svg class="search-btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('search_btn', 'Tìm kiếm') : 'Tìm kiếm'; ?></span>
                </button>

                <!-- Dropdown kết quả gợi ý Live AJAX Search -->
                <div class="live-search-dropdown" id="liveSearchResults" aria-label="Gợi ý đặc sản" style="display: none;"></div>
            </form>

            <!-- CỤM NÚT HÀNH ĐỘNG ĐỒNG BỘ -->
            <div class="header-actions">
                <?php if ($isLoggedIn): ?>
                    <div class="user-chip" title="Tài khoản: <?php echo htmlspecialchars($currentUser['fullname'] ?? $currentUser['username']); ?>">
                        <div class="user-chip-avatar">
                            <?php echo $isAdmin ? '👑' : '👤'; ?>
                        </div>
                        <div class="user-chip-info">
                            <span class="user-chip-name"><?php echo htmlspecialchars($currentUser['fullname'] ?? $currentUser['username']); ?></span>
                            <?php if ($isAdmin): ?>
                                <a href="<?php echo esc_url($admin_url); ?>" class="user-chip-admin">ADMIN</a>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url($logout_url); ?>" class="user-chip-logout" title="Đăng xuất khỏi tài khoản">
                            Đăng xuất
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo esc_url($login_url); ?>" class="header-action-btn header-account-btn" title="Đăng nhập hoặc đăng ký">
                        <span class="action-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <span class="action-label"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('login', 'Đăng nhập') : 'Đăng nhập'; ?></span>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url($orders_url); ?>" class="header-action-btn header-orders-btn" title="Kiểm tra trạng thái đơn hàng">
                    <span class="action-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </span>
                    <span class="action-label"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('orders', 'Đơn hàng') : 'Đơn hàng'; ?></span>
                </a>

                <a href="<?php echo esc_url($cart_url); ?>" class="header-action-btn header-cart-btn" title="Xem giỏ hàng và thanh toán">
                    <span class="action-icon">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </span>
                    <span class="cart-badge cart-count-badge" id="headerCartBadge" style="<?php echo ($cartCount > 0) ? 'display:inline-block;' : 'display:none;'; ?>"><?php echo $cartCount; ?></span>
                </a>
            </div>
        </div>
    </header>

    <!-- MENU NGANG HOÀNG GIA -->
    <nav class="main-nav">
        <div class="nav-wrapper">
            <ul class="nav-container">
                <li><a href="<?php echo esc_url($site_url); ?>" class="<?php echo ($currentNav === 'home') ? 'active' : ''; ?>"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nav_home', 'Trang Chủ') : 'Trang Chủ'; ?></a></li>
                <li><a href="<?php echo esc_url($about_url); ?>" class="<?php echo ($currentNav === 'about') ? 'active' : ''; ?>"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nav_about', 'Giới Thiệu') : 'Giới Thiệu'; ?></a></li>
                <li class="has-dropdown nav-item-dacsan">
                    <a href="<?php echo esc_url($dacsan_url); ?>" class="<?php echo ($currentNav === 'dac-san') ? 'active' : ''; ?>">
                        <?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nav_dacsan', 'Đặc Sản Huế') : 'Đặc Sản Huế'; ?>
                        <span class="nav-dropdown-caret">▾</span>
                    </a>
                    <div class="nav-dropdown-mega">
                        <ul class="dropdown-category-list">
                            <?php foreach ($navCategories as $cat): 
                                $catName = $cat['name'];
                                $catBadge = $cat['badge'] ?? '';
                                $catBadgeCls = $cat['badge_cls'] ?? '';
                                $hasChildren = !empty($cat['children']);
                            ?>
                                <li class="dropdown-category-item <?php echo $hasChildren ? 'has-flyout' : ''; ?>">
                                    <a href="<?php echo esc_url($cat['link']); ?>" class="dropdown-category-link">
                                        <span class="dropdown-cat-left">
                                            <span class="dropdown-cat-name"><?php echo htmlspecialchars($catName); ?></span>
                                        </span>
                                        <span class="dropdown-cat-right">
                                            <?php if (!empty($catBadge)): ?>
                                                <span class="sidebar-item-badge <?php echo htmlspecialchars($catBadgeCls); ?>"><?php echo htmlspecialchars($catBadge); ?></span>
                                            <?php endif; ?>
                                            <span class="dropdown-cat-arrow">›</span>
                                        </span>
                                    </a>
                                    <?php if ($hasChildren): ?>
                                        <ul class="flyout-submenu">
                                            <?php foreach ($cat['children'] as $child): ?>
                                                <li>
                                                    <a href="<?php echo esc_url($child['link']); ?>">
                                                        <?php echo htmlspecialchars($child['name']); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                            <li class="flyout-view-all">
                                                <a href="<?php echo esc_url($cat['link']); ?>">
                                                    Xem tất cả <?php echo htmlspecialchars($catName); ?> &rarr;
                                                </a>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
                <li class="nav-item-blog">
                    <a href="<?php echo esc_url($blog_url); ?>" class="<?php echo ($currentNav === 'blog') ? 'active' : ''; ?>">BLOG <span class="nav-dropdown-caret">▾</span></a>
                    <ul class="nav-blog-submenu" aria-label="Chuyên mục Blog">
                        <li><a href="<?php echo esc_url($blog_url . (strpos($blog_url, '?') === false ? '?' : '&') . 'cat=am-thuc'); ?>">GÓC ẨM THỰC</a></li>
                        <li><a href="<?php echo esc_url($blog_url . (strpos($blog_url, '?') === false ? '?' : '&') . 'cat=du-lich'); ?>">GÓC DU LỊCH</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo esc_url($contact_url); ?>" class="<?php echo ($currentNav === 'contact') ? 'active' : ''; ?>"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nav_contact', 'Liên Hệ') : 'Liên Hệ'; ?></a></li>
                <li><a href="<?php echo esc_url($store_url); ?>" class="<?php echo ($currentNav === 'store') ? 'active' : ''; ?>"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('nav_store', 'Cửa Hàng') : 'Cửa Hàng'; ?></a></li>
                <li><a href="<?php echo esc_url($map_url); ?>" class="<?php echo ($currentNav === 'map') ? 'active' : ''; ?>" style="color: #ffd700; font-weight: 700;">🗺️ Bản Đồ Cố Đô</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="<?php echo esc_url($admin_url); ?>" class="<?php echo ($currentNav === 'admin') ? 'active' : ''; ?>" style="color: #ffd700; font-weight: 700;">⚙️ Quản Trị</a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-contact-badge">
                <span class="badge-pulse-ring"></span>
                <span class="badge-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </span>
                <span class="badge-info">
                    <span class="badge-label"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('hotline_label', 'Hotline tư vấn') : 'Hotline tư vấn'; ?></span>
                    <a href="tel:0342684980" class="badge-phone">0342.684.980</a>
                </span>
            </div>
        </div>
    </nav>
