<?php
/**
 * View Toàn Bộ Danh Mục Đặc Sản O Hương Xứ Huế
 */
$theme_uri  = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url   = function_exists('home_url') ? home_url('/') : 'index.php';
$detail_base= function_exists('home_url') ? home_url('/?controller=product&action=detail&id=') : 'index.php?controller=product&action=detail&id=';
$checkoutUrl= function_exists('wc_get_checkout_url') ? wc_get_checkout_url() . '?sync_cart=1' : (function_exists('home_url') ? home_url('/thanh-toan/?sync_cart=1') : 'thanh-toan/?sync_cart=1');
$cartUrl    = function_exists('home_url') ? home_url('/?controller=cart') : 'index.php?controller=cart';
$products   = $products ?? [];
?>

<div class="catalog-page-master">
    <!-- Header Banner -->
    <section class="catalog-hero-banner">
        <div class="catalog-hero-content">
            <nav class="catalog-breadcrumb">
                <a href="<?php echo esc_url($home_url); ?>">Trang Chủ</a> &rsaquo; <span>Đặc Sản Xứ Huế</span>
            </nav>
            <span class="catalog-hero-tag">🏺 Kho Tàng Hương Vị Cố Đô</span>
            <h1 class="catalog-hero-title"><?php echo !empty($searchQuery) ? 'Kết quả tìm kiếm đặc sản' : 'Đặc Sản Huế Truyền Thống Chính Gốc'; ?></h1>
            <p class="catalog-hero-desc">
                <?php if (!empty($searchQuery)): ?>
                    Tìm thấy <?php echo count($products); ?> đặc sản đang bán cho “<?php echo htmlspecialchars($searchQuery); ?>”.
                <?php else: ?>
                    Tuyển chọn những tinh hoa ẩm thực trứ danh sông Hương núi Ngự, chế biến thủ công giữ trọn phong vị cổ truyền ngàn năm.
                <?php endif; ?>
            </p>
        </div>
    </section>

    <div class="catalog-container">
        <!-- Thanh Công Cụ Bộ Lọc & Tìm Kiếm -->
        <div class="catalog-toolbar">
            <!-- Phân loại danh mục -->
            <div class="filter-categories-pills">
                <button type="button" class="filter-pill active" onclick="filterCatalog('all', this)"><?php echo class_exists('LanguageEngine') ? LanguageEngine::t('all_products') : 'Tất Cả'; ?> (<?php echo count($products); ?>)</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('ban-chay', this)" style="border-color: #d4af37; font-weight: 700;">🔥 Bán Chạy Nhất</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Bánh', this)">🥟 Bánh Ép Huế</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Mắm', this)">🦐 Mắm Cố Đô</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Trà', this)">🍵 Trà Cung Đình & Sen</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Kẹo', this)">🍬 Kẹo Mè Xửng</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Tré', this)">🥩 Tré Bò & Heo</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Sen', this)">🪷 Hạt Sen Huế</button>
                <button type="button" class="filter-pill" onclick="filterCatalog('Nem', this)">🥓 Nem Chua Huế</button>
            </div>

            <!-- Bộ lọc mức giá & tình trạng kho -->
            <div class="filter-secondary-bar" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e8d8c8;">
                <span style="font-size: 13px; font-weight: 700; color: #8b1d24;">💵 Mức Giá:</span>
                <button type="button" class="price-pill active" onclick="filterPrice('all', this)" style="background: #8b1d24; color: #fff; border: 1px solid #8b1d24; padding: 4px 12px; border-radius: 14px; font-size: 12px; cursor: pointer;">Tất cả</button>
                <button type="button" class="price-pill" onclick="filterPrice('under_50k', this)" style="background: #fff; color: #555; border: 1px solid #ddd; padding: 4px 12px; border-radius: 14px; font-size: 12px; cursor: pointer;">Dưới 50k</button>
                <button type="button" class="price-pill" onclick="filterPrice('50_100k', this)" style="background: #fff; color: #555; border: 1px solid #ddd; padding: 4px 12px; border-radius: 14px; font-size: 12px; cursor: pointer;">50k - 100k</button>
                <button type="button" class="price-pill" onclick="filterPrice('100_200k', this)" style="background: #fff; color: #555; border: 1px solid #ddd; padding: 4px 12px; border-radius: 14px; font-size: 12px; cursor: pointer;">100k - 200k</button>
                <button type="button" class="price-pill" onclick="filterPrice('above_200k', this)" style="background: #fff; color: #555; border: 1px solid #ddd; padding: 4px 12px; border-radius: 14px; font-size: 12px; cursor: pointer;">Trên 200k</button>

                <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
                    <label style="font-size: 13px; font-weight: 600; color: #444; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="checkbox" id="stockOnlyCheckbox" onchange="applyFilters()" style="cursor: pointer;">
                        <span>📦 Chỉ hiện còn hàng</span>
                    </label>
                </div>
            </div>

            <!-- Tìm kiếm & Sắp xếp -->
            <div class="toolbar-controls" style="margin-top: 12px;">
                <div class="catalog-search-box">
                    <input type="text" id="catalogSearchInput" placeholder="<?php echo class_exists('LanguageEngine') ? LanguageEngine::t('search_placeholder') : 'Tìm tên đặc sản...'; ?>" onkeyup="searchCatalog()">
                    <span class="search-icon">🔍</span>
                </div>
                <select id="catalogSortSelect" class="catalog-sort" onchange="sortCatalog()">
                    <option value="default">Sắp xếp: Mặc định</option>
                    <option value="price-asc">Giá: Thấp đến Cao</option>
                    <option value="price-desc">Giá: Cao đến Thấp</option>
                    <option value="rating">Đánh giá cao nhất</option>
                </select>
            </div>
        </div>

        <!-- Lưới Danh Sách Sản Phẩm -->
        <div id="catalogGrid" class="catalog-grid">
            <?php foreach ($products as $item): ?>
                <div class="catalog-card" 
                     data-id="<?php echo $item['id']; ?>"
                     data-name="<?php echo htmlspecialchars($item['name']); ?>"
                     data-search="<?php echo esc_attr(html_entity_decode(strip_tags(implode(' ', [$item['name'], $item['category'], $item['short_desc'] ?? '', $item['description'] ?? ''])), ENT_QUOTES, 'UTF-8')); ?>"
                     data-category="<?php echo htmlspecialchars($item['category']); ?>"
                     data-price="<?php echo $item['raw_price']; ?>"
                     data-rating="<?php echo $item['rating']; ?>">
                    
                    <!-- Nhấn vào ảnh để xem chi tiết -->
                    <a href="<?php echo esc_url($detail_base . $item['id']); ?>" class="catalog-card-img-link" style="display: block; text-decoration: none;" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                        <div class="catalog-card-img">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy" decoding="async">
                            <span class="card-badge"><?php echo htmlspecialchars($item['category']); ?></span>
                            <div class="card-hover-overlay">
                                <span>🔍 Xem chi tiết đặc sản</span>
                            </div>
                        </div>
                    </a>

                    <div class="catalog-card-info">
                        <div class="card-rating">
                            <span class="stars">★★★★★</span>
                            <span class="rate-val"><?php echo $item['rating']; ?></span>
                            <span class="reviews-count">(<?php echo $item['reviews_count']; ?>)</span>
                        </div>

                        <!-- Hiển thị số lượng còn lại trong kho -->
                        <div class="card-stock-tag-line">
                            <?php 
                                $stockQty = isset($item['stock_quantity']) ? intval($item['stock_quantity']) : (isset($item['stock']) ? intval($item['stock']) : 50);
                                if ($stockQty <= 0): 
                            ?>
                                <span class="catalog-stock-tag tag-out">🔴 Tạm hết hàng</span>
                            <?php elseif ($stockQty <= 15): ?>
                                <span class="catalog-stock-tag tag-low">🟡 Chỉ còn <?php echo $stockQty; ?> phần</span>
                            <?php else: ?>
                                <span class="catalog-stock-tag tag-good">📦 Còn: <?php echo $stockQty; ?> phần</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="card-title">
                            <a href="<?php echo esc_url($detail_base . $item['id']); ?>" style="color: inherit; text-decoration: none;" title="Xem chi tiết <?php echo htmlspecialchars($item['name']); ?>">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </a>
                        </h3>
                        <p class="card-short-desc"><?php echo htmlspecialchars($item['short_desc']); ?></p>

                        <div class="card-footer-row">
                            <div class="card-price"><?php echo htmlspecialchars($item['price']); ?></div>
                            <div class="card-btns" style="display: flex; gap: 6px; align-items: center;">
                                <button type="button" class="btn-card-buy-ajax" onclick="addCartAjax(<?php echo (int) $item['id']; ?>, <?php echo htmlspecialchars(json_encode($item['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>)" style="background:#fdf6e7; color:#8b1d24; border:1px solid #d4af37; padding:7px 11px; font-size:11px; border-radius:15px; font-weight:700; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:3px;">
                                    🛒 Thêm vào giỏ
                                </button>
                                <a href="?controller=cart&action=add&id=<?php echo $item['id']; ?>&redirect=<?php echo urlencode($checkoutUrl); ?>" class="btn-card-buy-now" style="background:linear-gradient(135deg, #8b1d24, #6b1218); color:#fff; padding:7px 11px; font-size:11px; text-decoration:none; border-radius:15px; font-weight:700; white-space:nowrap; display:inline-flex; align-items:center; gap:3px; box-shadow: 0 3px 8px rgba(139,29,36,0.3);">
                                    ⚡ Mua ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Thông báo không tìm thấy kết quả -->
        <div id="noResults" class="no-results-msg" style="<?php echo empty($products) ? '' : 'display: none;'; ?>">
            <p>🏮 Không tìm thấy đặc sản phù hợp với từ khóa của bạn.</p>
            <button type="button" class="btn-reset-filter" onclick="resetFilters()">Xem tất cả đặc sản</button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- POPUP MODAL XEM NHANH SẢN PHẨM (QUICK VIEW) -->
<!-- ======================================================== -->
<div id="productQuickModal" class="quick-modal-overlay" onclick="closeProductQuickView(event)">
    <div class="quick-modal-content" onclick="event.stopPropagation()">
        <button type="button" class="quick-modal-close" onclick="closeProductQuickView(null)">&times;</button>
        
        <div class="quick-modal-body">
            <div class="quick-modal-img-col">
                <img id="modalImg" src="" alt="Sản phẩm">
                <span id="modalCategoryBadge" class="modal-badge">Đặc Sản Huế</span>
            </div>

            <div class="quick-modal-info-col">
                <h2 id="modalTitle">Tên Đặc Sản</h2>
                <div class="modal-rating">
                    <span style="color: #f39c12;">★★★★★</span>
                    <span id="modalRatingText">5.0 (Đánh giá cao)</span>
                </div>
                <div id="modalPrice" class="modal-price">135.000 ₫</div>
                <p id="modalShortDesc" class="modal-short-desc">Mô tả...</p>

                <div class="modal-specs-list">
                    <div class="modal-spec-row"><strong>📍 Xuất xứ:</strong> <span id="modalOrigin">Huế</span></div>
                    <div class="modal-spec-row"><strong>📦 Quy cách:</strong> <span id="modalPackaging">Hộp</span></div>
                    <div class="modal-spec-row"><strong>⏳ Hạn dùng:</strong> <span id="modalShelfLife">Chuẩn NSX</span></div>
                    <div class="modal-spec-row"><strong>🌿 Thành phần:</strong> <span id="modalIngredients">Tự nhiên</span></div>
                    <div class="modal-spec-row"><strong>📊 Số lượng còn:</strong> <span id="modalStock" style="font-weight: 700; color: #27ae60;">Đang tải...</span></div>
                </div>

                <div class="modal-actions">
                    <a id="modalDetailLink" href="#" class="btn-modal-action btn-modal-detail">📄 Xem Toàn Bộ Trang Chi Tiết</a>
                    <button type="button" class="btn-modal-action btn-modal-order" id="modalOrderBtn" onclick="orderFromModal()">🛒 Đặt Hàng Ngay</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const allProductsMasterData = <?php echo json_encode($products, JSON_UNESCAPED_UNICODE); ?>;
const baseDetailUrl = "<?php echo esc_url($detail_base); ?>";

let currentQuickModalProductId = null;

function openProductQuickView(productId) {
    var prod = Array.isArray(allProductsMasterData) 
        ? (allProductsMasterData.find(function(p) { return p.id == productId; }) || allProductsMasterData[productId]) 
        : allProductsMasterData[productId];
    if (!prod) return;
    currentQuickModalProductId = prod.id;

    document.getElementById('modalImg').src = prod.image;
    document.getElementById('modalTitle').innerText = prod.name;
    document.getElementById('modalCategoryBadge').innerText = prod.category || 'Đặc Sản Huế';
    document.getElementById('modalPrice').innerText = prod.price;
    document.getElementById('modalRatingText').innerText = (prod.rating || '5.0') + ' (' + (prod.reviews_count || '100+') + ' đánh giá)';
    document.getElementById('modalShortDesc').innerText = prod.short_desc || '';
    document.getElementById('modalOrigin').innerText = prod.origin || 'TP. Huế';
    document.getElementById('modalPackaging').innerText = prod.packaging || 'Đóng gói chuẩn';
    document.getElementById('modalShelfLife').innerText = prod.shelf_life || 'Chuẩn quy định';
    document.getElementById('modalIngredients').innerText = prod.ingredients || 'Nguyên liệu tự nhiên';
    document.getElementById('modalDetailLink').href = baseDetailUrl + prod.id;

    var stockQty = parseInt(prod.stock_quantity);
    var stockElem = document.getElementById('modalStock');
    var orderBtn = document.getElementById('modalOrderBtn');
    if (stockQty <= 0) {
        stockElem.innerHTML = '<span style="color:#c0392b;">🔴 Tạm Hết Hàng</span>';
        orderBtn.disabled = true;
        orderBtn.style.opacity = '0.5';
        orderBtn.innerText = '🔴 Tạm Hết Hàng';
    } else if (stockQty <= 15) {
        stockElem.innerHTML = '<span style="color:#f39c12;">🟡 Chỉ còn ' + stockQty + ' phần</span>';
        orderBtn.disabled = false;
        orderBtn.style.opacity = '1';
        orderBtn.innerText = '🛒 Đặt Hàng Ngay';
    } else {
        stockElem.innerHTML = '<span style="color:#27ae60;">🟢 Còn ' + stockQty + ' phần có sẵn</span>';
        orderBtn.disabled = false;
        orderBtn.style.opacity = '1';
        orderBtn.innerText = '🛒 Đặt Hàng Ngay';
    }

    var modal = document.getElementById('productQuickModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeProductQuickView(e) {
    if (e && e.target !== document.getElementById('productQuickModal') && !e.target.classList.contains('quick-modal-close')) return;
    var modal = document.getElementById('productQuickModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function orderFromModal() {
    if (!currentQuickModalProductId) return;
    window.location.href = 'index.php?controller=cart&action=add&id=' + currentQuickModalProductId;
}

// BỘ LỌC DANH MỤC & MỨC GIÁ
let currentCatFilter = 'all';
let currentPriceFilter = 'all';

function filterCatalog(categoryKey, btn) {
    currentCatFilter = categoryKey;
    document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilters();
}

function filterPrice(priceKey, btn) {
    currentPriceFilter = priceKey;
    document.querySelectorAll('.price-pill').forEach(b => {
        b.classList.remove('active');
        b.style.background = '#fff';
        b.style.color = '#555';
        b.style.borderColor = '#ddd';
    });
    btn.classList.add('active');
    btn.style.background = '#8b1d24';
    btn.style.color = '#fff';
    btn.style.borderColor = '#8b1d24';
    applyFilters();
}

function searchCatalog() {
    applyFilters();
}

function sortCatalog() {
    var sortType = document.getElementById('catalogSortSelect').value;
    var grid = document.getElementById('catalogGrid');
    var cards = Array.from(grid.querySelectorAll('.catalog-card'));

    cards.sort(function(a, b) {
        if (sortType === 'price-asc') {
            return parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price'));
        } else if (sortType === 'price-desc') {
            return parseInt(b.getAttribute('data-price')) - parseInt(a.getAttribute('data-price'));
        } else if (sortType === 'rating') {
            return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute('data-rating'));
        }
        return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
    });

    cards.forEach(c => grid.appendChild(c));
}

function applyFilters() {
    const normalize = text => text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\u0111/g, 'd');
    var keyword = normalize(document.getElementById('catalogSearchInput').value).trim();
    var stockOnly = document.getElementById('stockOnlyCheckbox') ? document.getElementById('stockOnlyCheckbox').checked : false;
    var cards = document.querySelectorAll('.catalog-card');
    var visibleCount = 0;

    cards.forEach(function(card) {
        var name = card.getAttribute('data-name').toLowerCase();
        var cat = card.getAttribute('data-category');
        var price = parseInt(card.getAttribute('data-price')) || 0;
        
        // Category check
        // Category check
        var rating = parseFloat(card.getAttribute('data-rating') || 0);
        var matchCat = (currentCatFilter === 'all') || 
                       (currentCatFilter === 'ban-chay' ? (rating >= 4.9) : (cat.toLowerCase().indexOf(currentCatFilter.toLowerCase()) !== -1));
        
        // Search check
        var searchText = normalize(card.getAttribute('data-search') || name + ' ' + cat);
        var matchSearch = !keyword || keyword.split(/\s+/).every(token => searchText.includes(token));

        // Price check
        var matchPrice = true;
        if (currentPriceFilter === 'under_50k') {
            matchPrice = (price < 50000);
        } else if (currentPriceFilter === '50_100k') {
            matchPrice = (price >= 50000 && price <= 100000);
        } else if (currentPriceFilter === '100_200k') {
            matchPrice = (price >= 100000 && price <= 200000);
        } else if (currentPriceFilter === 'above_200k') {
            matchPrice = (price > 200000);
        }

        // Stock check
        var matchStock = true;
        if (stockOnly) {
            var stockTag = card.querySelector('.tag-out');
            if (stockTag) matchStock = false;
        }

        if (matchCat && matchSearch && matchPrice && matchStock) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('noResults').style.display = (visibleCount === 0) ? 'block' : 'none';
}

function resetFilters() {
    const url = new URL(window.location.href);
    if (url.searchParams.has('q')) {
        ['q', 'cat', 'price', 'sort'].forEach(key => url.searchParams.delete(key));
        window.location.assign(url.href);
        return;
    }
    document.getElementById('catalogSearchInput').value = '';
    document.getElementById('catalogSortSelect').value = 'default';
    if (document.getElementById('stockOnlyCheckbox')) document.getElementById('stockOnlyCheckbox').checked = false;
    filterPrice('all', document.querySelector('.price-pill'));
    filterCatalog('all', document.querySelector('.filter-pill'));
}

window.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);
    var q = params.get('q');
    var cat = params.get('cat');

    if (q) {
        var searchInput = document.getElementById('catalogSearchInput');
        if (searchInput) searchInput.value = q;
    }

    if (cat) {
        var map = {
            'ban-chay': 'ban-chay', 'best_seller': 'ban-chay',
            'Bánh': 'Bánh', 'banh': 'Bánh', 'banh-ep': 'Bánh', 'banh-ep-hue': 'Bánh',
            'Mắm': 'Mắm', 'mam': 'Mắm', 'mam-hue': 'Mắm',
            'Trà': 'Trà', 'tra': 'Trà', 'tra-hue': 'Trà',
            'Kẹo': 'Kẹo', 'Mè Xửng': 'Kẹo', 'me-xung': 'Kẹo', 'keo': 'Kẹo', 'keo-hue': 'Kẹo',
            'Tré': 'Tré', 'tre': 'Tré', 'tre-hue': 'Tré',
            'Sen': 'Sen', 'sen': 'Sen', 'hat-sen-hue': 'Sen',
            'Nem': 'Nem', 'nem': 'Nem', 'nem-chua-hue': 'Nem',
            'Ăn Vặt': 'Ăn Vặt', 'an-vat': 'Ăn Vặt'
        };
        var targetCat = map[cat] || cat;
        var pills = Array.from(document.querySelectorAll('.filter-pill'));
        var matchedPill = pills.find(function(p) {
            var oc = p.getAttribute('onclick') || '';
            return oc.indexOf("'" + targetCat + "'") !== -1 || p.textContent.indexOf(targetCat) !== -1;
        });
        if (matchedPill) {
            filterCatalog(targetCat, matchedPill);
        } else {
            currentCatFilter = targetCat;
        }
    }
    applyFilters();
});

</script>

<?php require __DIR__ . '/../layouts/cart-toast.php'; ?>
