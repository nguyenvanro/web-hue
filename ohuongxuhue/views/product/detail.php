<?php
/**
 * View Chi tiết sản phẩm (views/product/detail.php)
 * Kế thừa toàn bộ layout Header & Footer của hệ thống
 */

$homeUrl   = function_exists('home_url') ? home_url('/') : 'index.php';
$detailUrl = function_exists('home_url') ? home_url('/?controller=product&action=detail&id=') : 'index.php?controller=product&action=detail&id=';
?>

<div class="product-detail-container" style="position: relative; z-index: 2;">
    <!-- Thanh điều hướng Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="<?php echo esc_url($homeUrl); ?>">Trang chủ</a>
        <span>&rsaquo;</span>
        <a href="<?php echo esc_url(function_exists('home_url') ? home_url('/dac-san/') : 'index.php?controller=dac-san'); ?>"><?php echo htmlspecialchars($product['category'] ?? 'Đặc Sản Huế'); ?></a>
        <span>&rsaquo;</span>
        <span class="current-crumb"><?php echo htmlspecialchars($product['name']); ?></span>
    </nav>

    <!-- Khối Chi Tiết Sản Phẩm Chính (2 Cột) -->
    <div class="product-detail-main" style="position: relative; z-index: 2; background: #ffffff;">
        <!-- Cột Trái: Hình Ảnh -->
        <div class="detail-gallery-col">
            <div class="main-image-box">
                <img id="mainProductImage" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null; this.src='<?php echo esc_url($theme_uri . '/assets/images/codo-salad.jpg'); ?>';">
                <span class="badge-tag">🌿 Đặc Sản Chuẩn Vị Cố Đô</span>
            </div>
            
            <div class="image-features-box">
                <div class="feature-badge">
                    <span>✨</span> 100% Tự Nhiên
                </div>
                <div class="feature-badge">
                    <span>👑</span> Công Thức Cung Đình
                </div>
                <div class="feature-badge">
                    <span>🛡️</span> An Toàn Vệ Sinh
                </div>
            </div>
        </div>

        <!-- Cột Phải: Thông Tin Mua Hàng -->
        <div class="detail-info-col">
            <div class="product-category-tag"><?php echo htmlspecialchars($product['category']); ?></div>
            <h1 class="detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>

            <!-- Đánh giá sao -->
            <div class="detail-rating-row">
                <div class="stars">★★★★★</div>
                <span class="rating-score"><?php echo htmlspecialchars($product['rating']); ?> / 5.0</span>
                <span class="divider">|</span>
                <span class="rating-reviews"><?php echo htmlspecialchars($product['reviews_count']); ?> lượt đánh giá</span>
                <span class="divider">|</span>
                <span class="sold-count">Đã bán 850+</span>
            </div>

            <!-- Giá bán -->
            <div class="detail-price-box">
                <span class="detail-price"><?php echo htmlspecialchars($product['price']); ?></span>
                <span class="vat-tag">(Đã bao gồm VAT & cam kết chính gốc)</span>
            </div>

            <!-- Tóm tắt sản phẩm -->
            <p class="detail-short-desc">
                <?php echo htmlspecialchars($product['short_desc']); ?>
            </p>

            <!-- Thông số quy cách -->
            <div class="detail-specs-box">
                <div class="spec-item">
                    <span class="spec-label">📍 Xuất xứ:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['origin']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">📦 Quy cách:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['packaging']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">⏳ Hạn dùng:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['shelf_life']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">📊 Tình trạng kho:</span>
                    <span class="spec-value">
                        <?php if ($product['stock_quantity'] <= 0): ?>
                            <strong style="color: #c0392b;">🔴 Tạm Hết Hàng</strong>
                        <?php elseif ($product['stock_quantity'] <= 15): ?>
                            <strong style="color: #f39c12;">🟡 Sắp hết (Còn <?php echo $product['stock_quantity']; ?> phần)</strong>
                        <?php else: ?>
                            <strong style="color: #27ae60;">🟢 Còn <?php echo $product['stock_quantity']; ?> phần có sẵn</strong>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- Chọn số lượng & Mua hàng -->
            <div class="purchase-actions-box">
                <?php if ($product['stock_quantity'] > 0): ?>
                    <div class="quantity-wrapper">
                        <span class="quantity-label">Số lượng:</span>
                        <div class="quantity-counter">
                            <button type="button" class="btn-qty btn-minus" onclick="decreaseQty()">-</button>
                            <input type="number" id="buyQuantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" readonly>
                            <button type="button" class="btn-qty btn-plus" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <div class="detail-btn-row">
                        <button type="button" class="btn-detail-order btn-add-cart" onclick="addToCartDetail(<?php echo (int)$product['id']; ?>, '<?php echo addslashes($product['name']); ?>')">
                            <span>🛒</span> THÊM VÀO GIỎ HÀNG
                        </button>
                        <button type="button" class="btn-detail-order btn-buy-now" onclick="buyNowDetail(<?php echo (int)$product['id']; ?>, '<?php echo addslashes($product['name']); ?>')">
                            ⚡ MUA NGAY
                        </button>
                    </div>
                <?php else: ?>
                    <div class="detail-out-of-stock-alert">
                        <p style="color: #c0392b; font-weight: 700; font-size: 16px; margin-bottom: 8px;">🔴 Món đặc sản này hiện đang tạm hết hàng!</p>
                        <p style="font-size: 13px; color: #666;">Bếp O Hương Xứ Huế đang làm mẻ mới, quý khách vui lòng chọn món đặc sản khác hoặc liên hệ Hotline để đặt trước.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cam kết dịch vụ -->
            <div class="service-commitments">
                <div class="commit-item">
                    <span class="commit-icon">🚀</span>
                    <div>
                        <strong>Giao Hàng Hỏa Tốc</strong>
                        <p>Đóng gói cẩn thận, chuyển phát nhanh toàn quốc</p>
                    </div>
                </div>
                <div class="commit-item">
                    <span class="commit-icon">🏅</span>
                    <div>
                        <strong>Chuẩn Vị 100%</strong>
                        <p>Đặc sản gốc Huế, không chất bảo quản công nghiệp</p>
                    </div>
                </div>
                <div class="commit-item">
                    <span class="commit-icon">🔄</span>
                    <div>
                        <strong>Đổi Trả Uy Tín</strong>
                        <p>Hỗ trợ đổi trả miễn phí nếu sản phẩm lỗi hoặc không đúng chất lượng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khối Thông Tin Chi Tiết & Hướng Dẫn (Tabs / Khối nội dung) -->
    <div class="product-description-section">
        <div class="desc-tab-header">
            <button type="button" class="desc-tab-btn active" onclick="switchTab(event, 'tab-story')">📜 Câu Chuyện & Hương Vị</button>
            <button type="button" class="desc-tab-btn" onclick="switchTab(event, 'tab-ingredients')">🌿 Thành Phần Nguyên Liệu</button>
            <button type="button" class="desc-tab-btn" onclick="switchTab(event, 'tab-usage')">💡 Cách Dùng & Bảo Quản</button>
        </div>

        <div class="desc-tab-content">
            <!-- Tab 1: Câu chuyện -->
            <div id="tab-story" class="tab-pane active">
                <h3>Nét Đẹp Ẩm Thực Cố Đô</h3>
                <div class="rich-text-content">
                    <?php echo $product['description']; ?>
                </div>
            </div>

            <!-- Tab 2: Thành phần -->
            <div id="tab-ingredients" class="tab-pane">
                <h3>Nguyên Liệu Tự Nhiên Tuyển Chọn</h3>
                <p><?php echo htmlspecialchars($product['ingredients']); ?></p>
            </div>

            <!-- Tab 3: Hướng dẫn -->
            <div id="tab-usage" class="tab-pane">
                <h3>Thưởng Thức & Gìn Giữ Hương Vị</h3>
                <div class="guide-box">
                    <p><strong>Cách thưởng thức ngon nhất:</strong> <?php echo htmlspecialchars($product['instructions']); ?></p>
                    <p style="margin-top: 10px;"><strong>Bảo quản:</strong> <?php echo htmlspecialchars($product['storage']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
    <!-- ========================================================== -->
    <!-- KHỐI ĐẶC SẢN TƯƠNG TỰ (RELATED PRODUCTS) -->
    <!-- ========================================================== -->
    <div class="product-related-section" style="margin-top: 45px; padding-top: 30px; border-top: 2px dashed #ecd8c0;">
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 12.5px; font-weight: 700; color: #7A1E2E; background: #FAF7F0; border: 1px solid #D4AF37; padding: 3px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.8px;">Gợi Ý Thưởng Thức</span>
            <h2 style="font-size: 24px; color: #4A0E17; font-family: 'Playfair Display', Georgia, serif; margin: 8px 0 4px 0; font-weight: 800;">Đặc Sản Cố Đô Cùng Loại</h2>
            <p style="font-size: 13.5px; color: #666; margin: 0;">Khám phá thêm các phong vị ẩm thực truyền thống đậm đà bản sắc Huế</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
            <?php foreach ($relatedProducts as $rel): 
                $relUrl = function_exists('home_url') ? home_url('/?controller=product&action=detail&id=' . $rel['id']) : 'index.php?controller=product&action=detail&id=' . $rel['id'];
                $relImg = !empty($rel['image']) ? $rel['image'] : ($theme_uri . '/assets/images/codo-main.jpg');
            ?>
                <div class="product-card" style="background: #ffffff; border: 1px solid #EFEAE2; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.25s, box-shadow 0.25s; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                    <a href="<?php echo esc_url($relUrl); ?>" style="display: block; position: relative; height: 170px; overflow: hidden;">
                        <img src="<?php echo esc_url($relImg); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                        <span style="position: absolute; top: 10px; left: 10px; background: rgba(74, 14, 23, 0.85); color: #FDFBF7; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px;"><?php echo htmlspecialchars($rel['category'] ?? 'Đặc Sản Huế'); ?></span>
                    </a>
                    <div style="padding: 14px; display: flex; flex-direction: column; flex: 1;">
                        <h4 style="margin: 0 0 6px; font-size: 14.5px; line-height: 1.35; font-weight: 700;">
                            <a href="<?php echo esc_url($relUrl); ?>" style="color: #4A0E17; text-decoration: none;"><?php echo htmlspecialchars($rel['name']); ?></a>
                        </h4>
                        <div style="font-size: 15px; font-weight: 800; color: #7A1E2E; margin-bottom: 12px;"><?php echo htmlspecialchars($rel['price']); ?></div>
                        <div style="margin-top: auto; display: flex; gap: 8px;">
                            <a href="<?php echo esc_url($relUrl); ?>" style="flex: 1; text-align: center; background: #FAF7F0; color: #7A1E2E; border: 1.5px solid #D4AF37; padding: 7px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">Xem Chi Tiết</a>
                            <button type="button" onclick="addToCartDetail(<?php echo (int)$rel['id']; ?>, '<?php echo addslashes($rel['name']); ?>')" style="background: #D4AF37; color: #4A0E17; border: none; padding: 7px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;" title="Thêm vào giỏ hàng">🛒</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========================================================== -->
    <!-- KHỐI ĐÁNH GIÁ & PHẢN HỒI KÈM HÌNH ẢNH THỰC TẾ (CUSTOMER UGC) -->
    <!-- ========================================================== -->
    <?php if ($reviewsEnabled ?? false): ?>
    <div class="product-reviews-section">
        <!-- Tiêu đề phân đoạn -->
        <div class="reviews-section-title-wrap">
            <span class="reviews-eyebrow">👑 Ý Kiến Khách Hàng</span>
            <h2 class="reviews-main-title">Đánh Giá & Trải Nghiệm Thực Tế</h2>
            <p class="reviews-sub-title">100% cảm nhận chân thực từ quý khách đã thưởng thức đặc sản O Hương Xứ Huế</p>
        </div>

        <!-- Bảng Tổng Quan Điểm & Đảm Bảo -->
        <div class="reviews-header-card">
            <!-- Cột trái: Điểm số & Phân bổ sao -->
            <div class="reviews-summary-left">
                <div class="reviews-big-score">
                    <span class="score-num"><?php echo $ratingStats['average'] ?? '4.9'; ?></span>
                    <div class="score-stars-stars">★★★★★</div>
                    <span class="score-count"><?php echo $ratingStats['total'] ?? '12'; ?> lượt đánh giá</span>
                </div>
                <div class="reviews-bars-list">
                    <?php 
                    $starCounts = $ratingStats['star_counts'] ?? [5 => 10, 4 => 2, 3 => 0, 2 => 0, 1 => 0];
                    $totalRev = max(1, $ratingStats['total'] ?? 12);
                    for ($s = 5; $s >= 1; $s--): 
                        $c = $starCounts[$s] ?? 0;
                        $pct = round(($c / $totalRev) * 100);
                    ?>
                        <div class="star-bar-row">
                            <span class="star-bar-label"><?php echo $s; ?> ★</span>
                            <div class="star-bar-track">
                                <div class="star-bar-fill" style="width: <?php echo $pct; ?>%;"></div>
                            </div>
                            <span class="star-bar-num"><?php echo $c; ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Cột phải: Huy hiệu bảo đảm & Nút viết đánh giá -->
            <div class="reviews-summary-right">
                <div class="buyer-guarantee-badge">
                    <div class="guarantee-icon-box">🛡️</div>
                    <div class="guarantee-text-box">
                        <h4>Cam Kết Đánh Giá Người Mua Thật 100%</h4>
                        <p>Mỗi phản hồi đều gắn liền với đơn hàng đã giao thành công và hình ảnh thực tế từ khách.</p>
                    </div>
                </div>
                <button type="button" class="btn-write-review" onclick="toggleReviewForm()">
                    <span class="btn-icon">✍️</span>
                    <span>Viết Đánh Giá Của Bạn</span>
                </button>
            </div>
        </div>

        <!-- Form gửi đánh giá kèm ảnh (Ẩn/Hiện) -->
        <div class="review-form-container" id="reviewFormContainer" style="display: none;">
            <div class="review-form-box">
                <div class="form-royal-badge">
                    <span>👑 CHIA SẺ TRẢI NGHIỆM ẨM THỰC</span>
                </div>
                <h3 class="form-title">Đánh Giá Sản Phẩm: <?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="form-sub">Cảm nhận của bạn là nguồn động lực quý báu để O Hương Xứ Huế không ngừng giữ gìn hương vị Cố Đô!</p>
                
                <form id="productReviewForm" onsubmit="submitProductReview(event)">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    
                    <!-- Chọn số sao tương tác -->
                    <div class="form-group form-rating-group">
                        <label class="form-label-title">Mức độ hài lòng của bạn:</label>
                        <div class="rating-stars-interactive">
                            <div class="rating-stars-input" id="starsInput">
                                <span class="star-input active" data-val="1" title="1 sao">★</span>
                                <span class="star-input active" data-val="2" title="2 sao">★</span>
                                <span class="star-input active" data-val="3" title="3 sao">★</span>
                                <span class="star-input active" data-val="4" title="4 sao">★</span>
                                <span class="star-input active" data-val="5" title="5 sao">★</span>
                            </div>
                            <span class="star-rating-hint" id="starRatingHint">⭐⭐⭐⭐⭐ Tuyệt hảo - Rất hài lòng!</span>
                            <input type="hidden" name="rating" id="reviewRatingVal" value="5">
                        </div>
                    </div>

                    <!-- 2 Cột Họ tên & Số điện thoại -->
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label-title">Họ và tên của bạn <span class="req-star">*</span></label>
                            <div class="input-with-icon">
                                <span class="field-icon">👤</span>
                                <input type="text" name="user_name" class="styled-input" placeholder="Ví dụ: Nguyễn Thị Mai" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-title">Số điện thoại liên hệ <span class="req-star">*</span></label>
                            <div class="input-with-icon">
                                <span class="field-icon">📞</span>
                                <input type="tel" name="user_phone" class="styled-input" placeholder="Để xác thực người mua hàng (bảo mật)" required>
                            </div>
                        </div>
                    </div>

                    <!-- Nhận xét chi tiết -->
                    <div class="form-group">
                        <label class="form-label-title">Cảm nhận chi tiết về hương vị, độ tươi & bao bì <span class="req-star">*</span></label>
                        <textarea name="comment" rows="3" class="styled-textarea" placeholder="Bánh dẻo ngon, tôm nhảy giòn sần sật, nước mắm thơm lừng vừa miệng, đóng gói lá chuối rất cẩn thận..." required></textarea>
                    </div>

                    <!-- Chọn ảnh thực tế -->
                    <div class="form-group">
                        <label class="form-label-title">Hình ảnh thực tế khi bạn nhận đặc sản:</label>
                        <p class="field-hint">Chọn ảnh mâm bánh/hộp quà thực tế bạn đã chụp:</p>
                        <div class="photo-select-cards">
                            <label class="photo-card-label active">
                                <input type="radio" name="photo_preset" value="assets/images/codo-salad.jpg" checked onchange="updateSelectedPhoto(this)">
                                <div class="photo-card-inner">
                                    <img src="<?php echo esc_url($theme_uri . '/assets/images/codo-salad.jpg'); ?>" alt="Mâm bánh tươi" class="card-thumb">
                                    <span class="card-title">Mâm Bánh Tươi</span>
                                    <span class="card-checked-badge">✓</span>
                                </div>
                            </label>
                            <label class="photo-card-label">
                                <input type="radio" name="photo_preset" value="assets/images/codo-banhep.jpg" onchange="updateSelectedPhoto(this)">
                                <div class="photo-card-inner">
                                    <img src="<?php echo esc_url($theme_uri . '/assets/images/codo-banhep.jpg'); ?>" alt="Bánh nướng giòn" class="card-thumb">
                                    <span class="card-title">Bánh Nướng Giòn</span>
                                    <span class="card-checked-badge">✓</span>
                                </div>
                            </label>
                            <label class="photo-card-label">
                                <input type="radio" name="photo_preset" value="assets/images/spotlight-banh.jpg" onchange="updateSelectedPhoto(this)">
                                <div class="photo-card-inner">
                                    <img src="<?php echo esc_url($theme_uri . '/assets/images/spotlight-banh.jpg'); ?>" alt="Hộp quà Cố Đô" class="card-thumb">
                                    <span class="card-title">Hộp Quà Cố Đô</span>
                                    <span class="card-checked-badge">✓</span>
                                </div>
                            </label>
                        </div>
                        <input type="hidden" name="photo_url" id="photoUrlInput" value="assets/images/codo-salad.jpg">
                    </div>

                    <!-- Nút gửi & đóng -->
                    <div class="form-actions-row">
                        <button type="submit" class="btn-royal-submit" id="btnSubmitReview">
                            <span>✦ Gửi Đánh Giá Ngay</span>
                        </button>
                        <button type="button" class="btn-royal-cancel" onclick="toggleReviewForm()">
                            <span>Đóng Lại</span>
                        </button>
                    </div>
                    <div id="reviewNotice" class="review-status-notice" style="display: none;"></div>
                </form>
            </div>
        </div>

        <!-- Danh sách phản hồi thực tế của khách hàng -->
        <div class="reviews-list-wrapper">
            <div class="reviews-list-header">
                <h3 class="reviews-list-title">
                    <span>💬 Nhận Xét Từ Khách Hàng</span>
                    <span class="count-pill">(<?php echo count($reviews); ?> đánh giá)</span>
                </h3>
            </div>
            
            <?php if (empty($reviews)): ?>
                <div class="no-reviews-box">
                    <span class="no-rev-icon">🏺</span>
                    <p>Chưa có đánh giá nào. Hãy là người đầu tiên thưởng thức và chia sẻ cảm nhận về món đặc sản này nhé!</p>
                </div>
            <?php else: ?>
                <div class="reviews-cards-grid">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="single-review-card">
                            <div class="card-top-header">
                                <div class="reviewer-meta">
                                    <div class="reviewer-avatar-circle">
                                        <?php 
                                            $firstLetter = mb_substr($rev['user_name'] ?? 'K', 0, 1, 'UTF-8');
                                            echo htmlspecialchars($firstLetter);
                                        ?>
                                    </div>
                                    <div class="reviewer-details">
                                        <h5 class="reviewer-fullname"><?php echo htmlspecialchars($rev['user_name']); ?></h5>
                                        <span class="verified-buyer-tag">
                                            <span class="tag-icon">✓</span>
                                            <span>Đã mua tại O Hương Xứ Huế</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="review-date-badge">
                                    📅 <?php echo date('d/m/Y', strtotime($rev['created_at'] ?? 'now')); ?>
                                </div>
                            </div>

                            <div class="review-rating-line">
                                <span class="stars-gold">
                                    <?php for ($i = 0; $i < (int)$rev['rating']; $i++): ?>★<?php endfor; ?>
                                </span>
                                <span class="rating-grade">
                                    <?php 
                                        $r = (int)$rev['rating'];
                                        echo ($r === 5) ? 'Cực kỳ hài lòng' : (($r === 4) ? 'Rất hài lòng' : 'Hài lòng');
                                    ?>
                                </span>
                            </div>
                            
                            <div class="review-quote-box">
                                <p class="review-comment-body">"<?php echo nl2br(htmlspecialchars($rev['comment'])); ?>"</p>
                            </div>

                            <?php if (!empty($rev['photo_url'])): 
                                $photoSrc = (strpos($rev['photo_url'], 'http') === 0) ? $rev['photo_url'] : ($theme_uri . '/' . ltrim($rev['photo_url'], '/'));
                            ?>
                                <div class="review-photo-preview-wrap">
                                    <div class="preview-img-container" onclick="window.open(this.querySelector('img').src, '_blank')">
                                        <img src="<?php echo esc_url($photoSrc); ?>" alt="Ảnh thực tế từ khách hàng" class="ugc-customer-photo">
                                        <span class="zoom-overlay">🔍 Phóng to</span>
                                    </div>
                                    <span class="photo-ugc-caption">📸 Ảnh chụp thực tế của khách</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php endif; ?>

    <!-- STYLE NỘI BỘ CHO KHỐI ĐÁNH GIÁ (ĐẢM BẢO HOÀN TOÀN CHUẨN GIAO DIỆN HOÀNG GIA HUẾ) -->
    <style>
    .product-reviews-section {
        margin-top: 48px;
        padding-top: 36px;
        border-top: 2px dashed #e8dac9;
        font-family: inherit;
    }
    .reviews-section-title-wrap {
        text-align: center;
        margin-bottom: 28px;
    }
    .reviews-eyebrow {
        display: inline-block;
        font-size: 13px;
        font-weight: 700;
        color: #8b1d24;
        background: #fbf4ea;
        border: 1px solid #e8d6bd;
        padding: 4px 14px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .reviews-main-title {
        font-size: 26px;
        color: #8b1d24;
        font-family: 'Playfair Display', Georgia, serif;
        margin: 0 0 6px 0;
        font-weight: 800;
    }
    .reviews-sub-title {
        font-size: 13.5px;
        color: #7a6b5c;
        margin: 0;
    }

    /* Thẻ Tổng Quan Đánh Giá */
    .reviews-header-card {
        background: linear-gradient(135deg, #fffdfa 0%, #fcf6ed 100%);
        border: 1.5px solid #d4af37;
        border-radius: 18px;
        padding: 28px 32px;
        box-shadow: 0 8px 30px rgba(139, 29, 36, 0.05);
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 32px;
        align-items: center;
        margin-bottom: 28px;
    }
    @media (max-width: 820px) {
        .reviews-header-card {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
        }
    }
    .reviews-summary-left {
        display: flex;
        align-items: center;
        gap: 28px;
    }
    @media (max-width: 540px) {
        .reviews-summary-left {
            flex-direction: column;
            gap: 16px;
        }
    }
    .reviews-big-score {
        background: #ffffff;
        border: 1.5px solid #ecd8c0;
        border-radius: 16px;
        padding: 18px 24px;
        text-align: center;
        min-width: 140px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    }
    .reviews-big-score .score-num {
        display: block;
        font-size: 46px;
        font-weight: 800;
        color: #8b1d24;
        line-height: 1;
        font-family: 'Playfair Display', Georgia, serif;
    }
    .reviews-big-score .score-stars-stars {
        color: #f39c12;
        font-size: 18px;
        letter-spacing: 2px;
        margin: 6px 0;
    }
    .reviews-big-score .score-count {
        font-size: 12px;
        color: #887260;
        font-weight: 600;
    }
    .reviews-bars-list {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
    }
    .star-bar-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12.5px;
    }
    .star-bar-label {
        width: 38px;
        font-weight: 700;
        color: #444;
    }
    .star-bar-track {
        flex: 1;
        height: 10px;
        background: #ebdccf;
        border-radius: 6px;
        overflow: hidden;
    }
    .star-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #d4af37, #f39c12);
        border-radius: 6px;
        transition: width 0.6s ease;
    }
    .star-bar-num {
        width: 24px;
        text-align: right;
        font-weight: 700;
        color: #888;
        font-size: 12px;
    }

    /* Cột Phải Header */
    .reviews-summary-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .buyer-guarantee-badge {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #ffffff;
        border: 1px dashed #d4af37;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .guarantee-icon-box {
        font-size: 28px;
        line-height: 1;
    }
    .guarantee-text-box h4 {
        font-size: 14px;
        font-weight: 700;
        color: #8b1d24;
        margin: 0 0 3px 0;
    }
    .guarantee-text-box p {
        font-size: 12px;
        color: #6d5d4d;
        margin: 0;
        line-height: 1.45;
    }
    .btn-write-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, #8b1d24 0%, #6b1218 100%);
        color: #ffffff;
        border: 1.5px solid #d4af37;
        border-radius: 30px;
        padding: 13px 24px;
        font-size: 14.5px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(139, 29, 36, 0.28);
        transition: all 0.2s ease;
    }
    .btn-write-review:hover {
        background: linear-gradient(135deg, #a0222a 0%, #7d151c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 29, 36, 0.38);
    }
    .btn-write-review .btn-icon {
        font-size: 16px;
    }

    /* Form Gửi Đánh Giá */
    .review-form-box {
        background: #ffffff;
        border: 2px solid #d4af37;
        border-radius: 18px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 10px 35px rgba(139, 29, 36, 0.08);
        position: relative;
    }
    @media (max-width: 600px) {
        .review-form-box {
            padding: 20px;
        }
    }
    .form-royal-badge {
        display: inline-block;
        background: #fbf4ea;
        color: #8b1d24;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 12px;
        border-radius: 12px;
        border: 1px solid #ebd4b8;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-title {
        font-size: 20px;
        color: #8b1d24;
        font-weight: 700;
        font-family: 'Playfair Display', Georgia, serif;
        margin: 0 0 6px 0;
    }
    .form-sub {
        font-size: 13px;
        color: #777;
        margin: 0 0 22px 0;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-label-title {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #443022;
        margin-bottom: 7px;
    }
    .req-star {
        color: #c0392b;
    }

    /* Interactive Stars */
    .rating-stars-interactive {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .rating-stars-input {
        display: flex;
        gap: 4px;
    }
    .rating-stars-input .star-input {
        font-size: 32px;
        color: #d1c4b5;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1;
        user-select: none;
    }
    .rating-stars-input .star-input.active,
    .rating-stars-input .star-input.hovered {
        color: #f39c12;
        text-shadow: 0 0 8px rgba(243, 156, 18, 0.4);
    }
    .rating-stars-input .star-input:hover {
        transform: scale(1.2);
    }
    .star-rating-hint {
        font-size: 13px;
        font-weight: 700;
        color: #8b1d24;
        background: #fdf5ea;
        border: 1px solid #f2dec4;
        padding: 4px 12px;
        border-radius: 14px;
    }

    /* Form Fields */
    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 600px) {
        .form-row-2 {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-with-icon .field-icon {
        position: absolute;
        left: 14px;
        font-size: 14px;
        color: #888;
        pointer-events: none;
    }
    .styled-input {
        width: 100%;
        padding: 11px 14px 11px 38px;
        background: #fffdfb;
        border: 1.5px solid #d9c8b8;
        border-radius: 10px;
        font-size: 13.5px;
        color: #333;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .styled-input:focus, .styled-textarea:focus {
        border-color: #8b1d24;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(139, 29, 36, 0.1);
        outline: none;
    }
    .styled-textarea {
        width: 100%;
        padding: 12px 14px;
        background: #fffdfb;
        border: 1.5px solid #d9c8b8;
        border-radius: 10px;
        font-size: 13.5px;
        color: #333;
        box-sizing: border-box;
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
        transition: all 0.2s;
    }
    .field-hint {
        font-size: 12px;
        color: #777;
        margin: -3px 0 10px 0;
    }

    /* Thẻ Chọn Ảnh Mẫu Thực Tế */
    .photo-select-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    @media (max-width: 600px) {
        .photo-select-cards {
            grid-template-columns: 1fr;
        }
    }
    .photo-card-label {
        cursor: pointer;
        position: relative;
        display: block;
    }
    .photo-card-label input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .photo-card-inner {
        border: 2px solid #ecd8c2;
        border-radius: 12px;
        background: #faf6f0;
        padding: 8px;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
    }
    .photo-card-inner .card-thumb {
        width: 100%;
        height: 85px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ebd9c5;
        display: block;
        margin-bottom: 6px;
    }
    .photo-card-inner .card-title {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #443022;
    }
    .photo-card-inner .card-checked-badge {
        display: none;
        position: absolute;
        top: 6px;
        right: 6px;
        background: #8b1d24;
        color: #fff;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 800;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }
    .photo-card-label.active .photo-card-inner {
        border-color: #8b1d24;
        background: #fffdf9;
        box-shadow: 0 4px 14px rgba(139, 29, 36, 0.15);
    }
    .photo-card-label.active .card-checked-badge {
        display: flex;
    }

    /* Actions */
    .form-actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
    }
    .btn-royal-submit {
        background: linear-gradient(135deg, #8b1d24 0%, #6b1218 100%);
        color: #ffffff;
        border: 1.5px solid #d4af37;
        padding: 12px 28px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(139, 29, 36, 0.25);
        transition: all 0.2s;
    }
    .btn-royal-submit:hover {
        background: linear-gradient(135deg, #a0222a 0%, #7d151c 100%);
        transform: translateY(-2px);
    }
    .btn-royal-cancel {
        background: #fbf5ef;
        color: #665243;
        border: 1px solid #d9c8b8;
        padding: 12px 22px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-royal-cancel:hover {
        background: #f0e6da;
    }
    .review-status-notice {
        margin-top: 14px;
        padding: 10px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13.5px;
    }

    /* Danh Sách Đánh Giá */
    .reviews-list-wrapper {
        margin-top: 36px;
    }
    .reviews-list-header {
        margin-bottom: 20px;
    }
    .reviews-list-title {
        font-size: 21px;
        color: #8b1d24;
        font-family: 'Playfair Display', Georgia, serif;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .reviews-list-title .count-pill {
        font-size: 13px;
        font-weight: 600;
        color: #888;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .no-reviews-box {
        text-align: center;
        padding: 36px 20px;
        background: #fdfaf6;
        border: 1px dashed #d9c8b8;
        border-radius: 14px;
        color: #777;
    }
    .no-rev-icon {
        font-size: 36px;
        display: block;
        margin-bottom: 8px;
    }
    .reviews-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
    }
    @media (max-width: 600px) {
        .reviews-cards-grid {
            grid-template-columns: 1fr;
        }
    }
    .single-review-card {
        background: #ffffff;
        border: 1px solid #ebdccf;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
    }
    .single-review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 29, 36, 0.07);
    }
    .card-top-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .reviewer-meta {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .reviewer-avatar-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b1d24, #d4af37);
        color: #ffffff;
        font-weight: 800;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(139, 29, 36, 0.2);
    }
    .reviewer-fullname {
        font-size: 14.5px;
        font-weight: 700;
        color: #2c2c2c;
        margin: 0 0 3px 0;
    }
    .verified-buyer-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #eafaf1;
        color: #27ae60;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
    }
    .review-date-badge {
        font-size: 11.5px;
        color: #999;
    }
    .review-rating-line {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .stars-gold {
        color: #f39c12;
        font-size: 14px;
        letter-spacing: 2px;
    }
    .rating-grade {
        font-size: 11.5px;
        font-weight: 700;
        color: #8b1d24;
        background: #fdf5ea;
        padding: 2px 7px;
        border-radius: 8px;
    }
    .review-quote-box {
        flex: 1;
        background: #fdfaf6;
        border-left: 3px solid #d4af37;
        border-radius: 4px 8px 8px 4px;
        padding: 12px 14px;
        margin-bottom: 12px;
    }
    .review-comment-body {
        font-size: 13.5px;
        color: #443c33;
        line-height: 1.6;
        margin: 0;
        font-style: italic;
    }
    .review-photo-preview-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px dashed #eee;
    }
    .preview-img-container {
        position: relative;
        width: 64px;
        height: 64px;
        border-radius: 8px;
        overflow: hidden;
        border: 1.5px solid #d4af37;
        cursor: pointer;
    }
    .ugc-customer-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s;
    }
    .preview-img-container:hover .ugc-customer-photo {
        transform: scale(1.1);
    }
    .preview-img-container:hover .zoom-overlay {
        opacity: 1;
    }
    .zoom-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .photo-ugc-caption {
        font-size: 11.5px;
        color: #777;
        font-weight: 600;
    }
    </style>

    <!-- Khối Sản Phẩm Liên Quan (Related Products) -->
    <div class="related-products-section">
        <h2 class="section-title">🏮 Đặc Sản Cố Đô Cùng Loại Nên Thử</h2>
        <div class="products-grid">
            <?php foreach ($relatedProducts as $relItem): ?>
                <?php $relUrl = $detailUrl . $relItem['id']; ?>
                <div class="product-card">
                    <a href="<?php echo esc_url($relUrl); ?>" class="product-img">
                        <img src="<?php echo htmlspecialchars($relItem['image']); ?>" alt="<?php echo htmlspecialchars($relItem['name']); ?>">
                    </a>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="<?php echo esc_url($relUrl); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($relItem['name']); ?>
                            </a>
                        </h3>
                        <div class="product-price"><?php echo htmlspecialchars($relItem['price']); ?></div>
                        <a href="<?php echo esc_url($relUrl); ?>" class="btn-order">XEM CHI TIẾT</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Script tăng giảm số lượng & chuyển tab -->
<script>
const maxStockAvailable = <?php echo intval($product['stock_quantity']); ?>;

function increaseQty() {
    var input = document.getElementById('buyQuantity');
    var val = parseInt(input.value) || 1;
    if (val < maxStockAvailable && val < 99) {
        input.value = val + 1;
    } else {
        alert('Số lượng chọn đã đạt mức tối đa hiện có trong kho (' + maxStockAvailable + ' phần)!');
    }
}

function decreaseQty() {
    var input = document.getElementById('buyQuantity');
    var val = parseInt(input.value) || 1;
    if (val > 1) input.value = val - 1;
}

function switchTab(evt, tabId) {
    var tabPanes = document.querySelectorAll('.tab-pane');
    tabPanes.forEach(function(p) { p.classList.remove('active'); });

    var tabBtns = document.querySelectorAll('.desc-tab-btn');
    tabBtns.forEach(function(b) { b.classList.remove('active'); });

    document.getElementById(tabId).classList.add('active');
    evt.currentTarget.classList.add('active');
}

function addToCartDetail(id, productName) {
    var qty = parseInt(document.getElementById('buyQuantity').value) || 1;
    fetch('index.php?controller=cart&action=add&id=' + id + '&quantity=' + qty + '&ajax=1')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if(data && data.success) {
                var badges = document.querySelectorAll('.cart-badge, #headerCartBadge, .cart-count-badge, #headerCartCount');
                badges.forEach(function(b) {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                });
                alert('✓ Đã thêm ' + qty + ' phần "' + productName + '" vào giỏ hàng!');
            } else {
                window.location.href = 'index.php?controller=cart&action=add&id=' + id + '&quantity=' + qty;
            }
        })
        .catch(function() {
            window.location.href = 'index.php?controller=cart&action=add&id=' + id + '&quantity=' + qty;
        });
}

function buyNowDetail(id, productName) {
    var qty = parseInt(document.getElementById('buyQuantity').value) || 1;
    var checkoutUrl = <?php echo json_encode(function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : (function_exists('home_url') ? home_url('/thanh-toan/') : 'index.php?controller=cart&action=checkout'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    window.location.href = 'index.php?controller=cart&action=add&id=' + id + '&quantity=' + qty + '&redirect=' + encodeURIComponent(checkoutUrl);
}

// Bật/tắt Form gửi đánh giá
function toggleReviewForm() {
    var formBox = document.getElementById('reviewFormContainer');
    if (formBox.style.display === 'none' || formBox.style.display === '') {
        formBox.style.display = 'block';
        formBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        formBox.style.display = 'none';
    }
}

// Cập nhật thẻ ảnh mẫu được chọn
function updateSelectedPhoto(radio) {
    document.querySelectorAll('.photo-card-label').forEach(function(lbl) {
        lbl.classList.remove('active');
    });
    if (radio && radio.closest('.photo-card-label')) {
        radio.closest('.photo-card-label').classList.add('active');
    }
    var photoInput = document.getElementById('photoUrlInput');
    if (photoInput && radio) {
        photoInput.value = radio.value;
    }
}

// Chọn số sao đánh giá trực quan & hiệu ứng tương tác
const starHints = {
    1: '⭐ Rất không hài lòng',
    2: '⭐⭐ Chưa ưng ý - Cần cải thiện',
    3: '⭐⭐⭐ Tạm ổn - Hương vị vừa miệng',
    4: '⭐⭐⭐⭐ Rất ngon - Đóng gói chu đáo',
    5: '⭐⭐⭐⭐⭐ Tuyệt hảo - Chuẩn phong vị Cố Đô!'
};

document.addEventListener('DOMContentLoaded', function() {
    var stars = document.querySelectorAll('#starsInput .star-input');
    var ratingInput = document.getElementById('reviewRatingVal');
    var hint = document.getElementById('starRatingHint');

    function highlightStars(val) {
        stars.forEach(function(s) {
            var sVal = parseInt(s.getAttribute('data-val'));
            if (sVal <= val) {
                s.classList.add('active');
            } else {
                s.classList.remove('active');
            }
        });
        if (hint && starHints[val]) {
            hint.textContent = starHints[val];
        }
    }

    stars.forEach(function(star) {
        star.addEventListener('click', function() {
            var val = parseInt(this.getAttribute('data-val'));
            ratingInput.value = val;
            highlightStars(val);
        });
        star.addEventListener('mouseenter', function() {
            var val = parseInt(this.getAttribute('data-val'));
            highlightStars(val);
        });
    });

    var starsContainer = document.getElementById('starsInput');
    if (starsContainer) {
        starsContainer.addEventListener('mouseleave', function() {
            var currentVal = parseInt(ratingInput.value) || 5;
            highlightStars(currentVal);
        });
    }
});

// Gửi đánh giá sản phẩm qua AJAX
function submitProductReview(e) {
    e.preventDefault();
    var form = document.getElementById('productReviewForm');
    var btn = document.getElementById('btnSubmitReview');
    var notice = document.getElementById('reviewNotice');
    var formData = new FormData(form);

    btn.disabled = true;
    btn.textContent = 'Đang gửi đánh giá...';
    notice.style.display = 'none';

    fetch('index.php?controller=product&action=submit_review', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.textContent = 'Gửi Đánh Giá Ngay ✦';
        notice.style.display = 'block';
        if (data.success) {
            notice.style.color = '#27ae60';
            notice.textContent = '✓ ' + data.message;
            setTimeout(function() {
                window.location.reload();
            }, 1200);
        } else {
            notice.style.color = '#c0392b';
            notice.textContent = '✗ ' + data.message;
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.textContent = 'Gửi Đánh Giá Ngay ✦';
        notice.style.display = 'block';
        notice.style.color = '#c0392b';
        notice.textContent = '✗ Không thể gửi đánh giá, vui lòng thử lại sau!';
    });
}
</script>
