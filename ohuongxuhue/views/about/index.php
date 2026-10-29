<?php
/**
 * View Trang giới thiệu O Hương Xứ Huế - Vị Ngon Cố Đô
 */
$theme_uri = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url  = function_exists('home_url') ? home_url('/') : 'index.php';
?>

<div class="about-page-master">
    <!-- ========================================================== -->
    <!-- 1. HERO BANNER HOÀNG GIA: GIỚI THIỆU THƯƠNG HIỆU -->
    <!-- ========================================================== -->
    <section class="about-hero-section">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <nav class="about-breadcrumb">
                <a href="<?php echo esc_url($home_url); ?>">Trang Chủ</a> &rsaquo; <span>Về O Hương Xứ Huế</span>
            </nav>
            <span class="hero-badge-tag">👑 Tinh Hoa Ẩm Thực Cố Đô</span>
            <h1 class="about-hero-title">O Hương Xứ Huế - Vị Ngon Cố Đô</h1>
            <p class="about-hero-subtitle">
                Hành trình chắt chiu, gìn giữ và lan tỏa tinh hoa ẩm thực cung đình cùng phong vị dân gian xứ Huế đến mâm cơm mọi gia đình Việt.
            </p>

            <!-- Thống kê thành tựu nổi bật -->
            <div class="about-stats-grid">
                <div class="stat-card">
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Năm gìn giữ nghề truyền thống</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">50.000+</span>
                    <span class="stat-label">Khách hàng tin yêu khắp 3 miền</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Nguyên liệu sạch chính gốc Huế</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">4.9★</span>
                    <span class="stat-label">Đánh giá chất lượng hảo hạng</span>
                </div>
            </div>
        </div>
    </section>

    <div class="about-container">
        <!-- ========================================================== -->
        <!-- 2. CÂU CHUYỆN KHỞI NGUỒN (BRAND STORY) -->
        <!-- ========================================================== -->
        <section class="about-section story-section">
            <div class="story-grid">
                <div class="story-text-col">
                    <div class="section-badge">Câu Chuyện Thương Hiệu</div>
                    <h2 class="section-heading">Từ Bếp Lửa Gia Truyền Ven Sông Hương Đến Bàn Ăn Mọi Miền</h2>
                    <p class="story-lead">
                        Huế không chỉ nức tiếng gần xa bởi những lăng tẩm trầm mặc, thành quách cổ kính của triều đại phong kiến, mà mảnh đất sông Hương núi Ngự này còn nâng niu một nền văn hóa ẩm thực vô cùng kỳ công, tinh tế bậc nhất phương Nam.
                    </p>
                    <p>
                        Sinh ra và lớn lên trong lòng Cố Đô, thương hiệu <strong>O Hương Xứ Huế</strong> được khai sinh từ niềm đam mê cháy bỏng và ước vọng gìn giữ những công thức gia truyền đã có tự bao đời. Chúng tôi tin rằng, món ăn xứ Huế không chỉ đơn thuần là sự kết hợp của ngũ vị (chua, cay, mặn, ngọt, bùi), mà đó còn là cốt cách, là sự thanh tao, khéo léo và cái tình nồng hậu của người con gái Huế gửi gắm qua từng mẻ bánh, gói mè xửng, tách trà thơm.
                    </p>
                    <div class="quote-callout">
                        <div class="quote-icon">“</div>
                        <p class="quote-text">
                            Ăn món Huế không chỉ để thưởng thức bằng vị giác, mà là cảm nhận trọn vẹn cả bề dày văn hóa ngàn năm và tấm lòng thơm thảo của đất Cố Đô.
                        </p>
                        <span class="quote-author">— Nghệ Nhân Sáng Lập O Hương Xứ Huế</span>
                    </div>
                </div>

                <div class="story-media-col">
                    <div class="media-stack">
                        <div class="media-card-main">
                            <img src="<?php echo esc_url($theme_uri); ?>/assets/images/about/Banh_Trang_Truyen_Thong.jpg" alt="Bánh tráng truyền thống">
                            <div class="media-caption">Gìn giữ nét đẹp bánh truyền thống cung đình</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- 3. GIÁ TRỊ CỐT LÕI (CORE VALUES) -->
        <!-- ========================================================== -->
        <section class="about-section values-section">
            <div class="section-center-header">
                <span class="section-badge">Tôn Chỉ Hoạt Động</span>
                <h2 class="section-heading">4 Trụ Cột Giá Trị Cốt Lõi</h2>
                <p class="section-subtext">Những nguyên tắc vàng giúp O Hương Xứ Huế khẳng định uy tín và chất lượng qua năm tháng.</p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon-box">👑</div>
                    <h3 class="value-title">Gìn Giữ Cội Nguồn</h3>
                    <p class="value-desc">
                        Tuyệt đối trung thành với công thức cổ truyền bí truyền từ thời cung đình Nguyễn, bảo tồn trọn vẹn phong vị đậm đà nguyên bản của xứ kinh kỳ.
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon-box">🌿</div>
                    <h3 class="value-title">Nguyên Liệu Tinh Khiết</h3>
                    <p class="value-desc">
                        Tuyển chọn nông sản trứ danh địa phương: tôm đất Phá Tam Giang tươi rói, hạt sen thơm hồ Tịnh Tâm, mè vàng xóm bến Kim Long.
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon-box">🍯</div>
                    <h3 class="value-title">Tận Tâm Tinh Tế</h3>
                    <p class="value-desc">
                        Chế biến thủ công chuẩn mực từng công đoạn. Từng gói quà, hũ mắm trao đến tay thực khách đều được đóng gói tỉ mỉ như một tác phẩm mỹ nghệ.
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon-box">🛡️</div>
                    <h3 class="value-title">Chất Lượng Vàng</h3>
                    <p class="value-desc">
                        100% không chất bảo quản công nghiệp hay phụ gia hóa chất độc hại. Đạt chứng nhận ATVSTP tiêu chuẩn quốc gia, an tâm cho mọi lứa tuổi.
                    </p>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- 4. HÀNH TRÌNH PHÁT TRIỂN (TIMELINE DI SẢN) -->
        <!-- ========================================================== -->
        <section class="about-section timeline-section">
            <div class="section-center-header">
                <span class="section-badge">Mốc Son Lịch Sử</span>
                <h2 class="section-heading">Hành Trình Gieo Mầm & Tỏa Hương</h2>
                <p class="section-subtext">Từng bước nỗ lực đưa đặc sản quê hương vượt khỏi lũy tre làng để vươn tầm toàn quốc.</p>
            </div>

            <div class="timeline-tree">
                <div class="timeline-item left">
                    <div class="timeline-dot"></div>
                    <div class="timeline-card">
                        <span class="timeline-year">2012</span>
                        <h4 class="timeline-title">Khởi Điểm Từ Gian Bếp Nhỏ Kim Long</h4>
                        <p class="timeline-desc">
                            Bắt đầu bằng gánh bánh bèo nậm lọc và mẻ mè xửng dẻo phục vụ bà con lối xóm cùng những đoàn du khách ghé thăm chùa Thiên Mụ.
                        </p>
                    </div>
                </div>

                <div class="timeline-item right">
                    <div class="timeline-dot"></div>
                    <div class="timeline-card">
                        <span class="timeline-year">2016</span>
                        <h4 class="timeline-title">Khôi Phục Trà Cung Đình 16 Vị Thảo Mộc</h4>
                        <p class="timeline-desc">
                            Nghiên cứu và tái hiện thành công bí phương "Nhất Dạ Đế Vương" từ thư tịch cổ, mang hương trà thanh nhiệt hoàng cung đến rộng rãi người yêu trà.
                        </p>
                    </div>
                </div>

                <div class="timeline-item left">
                    <div class="timeline-dot"></div>
                    <div class="timeline-card">
                        <span class="timeline-year">2020</span>
                        <h4 class="timeline-title">Xây Dựng Xưởng Đạt Chuẩn ATVSTP</h4>
                        <p class="timeline-desc">
                            Nâng cấp cơ sở sản xuất Mè Xửng Hạt Sen và Bánh Ép Thuận An khép kín vô trùng, ứng dụng công nghệ hút chân không bảo toàn hương vị trọn vẹn.
                        </p>
                    </div>
                </div>

                <div class="timeline-item right">
                    <div class="timeline-dot"></div>
                    <div class="timeline-card">
                        <span class="timeline-year">2026</span>
                        <h4 class="timeline-title">Chuyển Mình Số Hóa Với Nền Tảng Trực Tuyến</h4>
                        <p class="timeline-desc">
                            Thương hiệu O Hương Xứ Huế chính thức phủ sóng hệ thống phân phối online, giao hàng hỏa tốc trong 24-48h đến tận tay người tiêu dùng trên cả nước.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- 5. QUY TRÌNH CHẾ BIẾN CÔNG PHU (CRAFTSMANSHIP) -->
        <!-- ========================================================== -->
        <section class="about-section process-section">
            <div class="section-center-header">
                <span class="section-badge">Bí Quyết Nghề Nghiệp</span>
                <h2 class="section-heading">Quy Trình 4 Bước Tạo Nên Vị Ngon Đỉnh Cao</h2>
                <p class="section-subtext">Sự giao thoa hoàn hảo giữa kinh nghiệm gia truyền và tiêu chuẩn vệ sinh hiện đại.</p>
            </div>

            <div class="process-grid">
                <div class="process-step-card">
                    <div class="step-badge">Bước 01</div>
                    <div class="step-icon">🌅</div>
                    <h4 class="step-name">Tuyển Chọn Tinh Túy</h4>
                    <p class="step-desc">Đích thân nghệ nhân tuyển lựa nguyên liệu tươi ngon sớm mai: bột lọc thủ công, tôm sông, hạt sen tươi đầm sen.</p>
                </div>

                <div class="process-step-card">
                    <div class="step-badge">Bước 02</div>
                    <div class="step-icon">🥣</div>
                    <h4 class="step-name">Nấu Ủ Gia Truyền</h4>
                    <p class="step-desc">Thắng mạch nha nếp đúng nhiệt, xào nhân tôm thịt nêm nước mắm cốt nhĩ theo tỷ lệ vàng hàng chục năm kinh nghiệm.</p>
                </div>

                <div class="process-step-card">
                    <div class="step-badge">Bước 03</div>
                    <div class="step-icon">🔬</div>
                    <h4 class="step-name">Kiểm Soát Vệ Sinh</h4>
                    <p class="step-desc">Mọi sản phẩm đều trải qua khâu kiểm tra độ ẩm, độ giòn, hạn dùng và vệ sinh an toàn thực phẩm khắt khe trước khi xuất xưởng.</p>
                </div>

                <div class="process-step-card">
                    <div class="step-badge">Bước 04</div>
                    <div class="step-icon">🎁</div>
                    <h4 class="step-name">Đóng Gói Hoàng Triều</h4>
                    <p class="step-desc">Hút chân không màng nhôm tráng bạc, đựng trong hộp giấy hoa văn cung đình tao nhã sẵn sàng làm quà biếu trang trọng.</p>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- 6. CAM KẾT VÀNG & CHỨNG NHẬN (ASSURANCES) -->
        <!-- ========================================================== -->
        <section class="about-section assurance-section">
            <div class="assurance-inner">
                <div class="assurance-badge-col">
                    <div class="gold-seal">
                        <div class="seal-inner">
                            <span>CAM KẾT</span>
                            <strong>100%</strong>
                            <span>CHÍNH GỐC HUẾ</span>
                        </div>
                    </div>
                </div>
                <div class="assurance-content-col">
                    <h2 class="assurance-title">Lời Hứa Danh Dự Từ O Hương Xứ Huế</h2>
                    <p class="assurance-text">
                        Chúng tôi cam kết không chỉ bán một món hàng, mà trao gửi cả uy tín của người làm nghề truyền thống. Nếu bất kỳ sản phẩm nào không đúng với hương vị mô tả hoặc suy giảm chất lượng do vận chuyển, chúng tôi sẵn sàng <strong>hoàn tiền 100% hoặc đổi mới ngay lập tức</strong> mà không phát sinh thêm bất kỳ chi phí nào.
                    </p>
                    <div class="assurance-features">
                        <div class="feat-pill">✓ Đầy đủ giấy tờ VSATTP</div>
                        <div class="feat-pill">✓ Bao bì khử khuẩn tiêu chuẩn</div>
                        <div class="feat-pill">✓ Vận chuyển toàn quốc chống va đập</div>
                        <div class="feat-pill">✓ Hỗ trợ khách hàng 24/7</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- 7. LỜI MỜI TRẢI NGHIỆM (CALL TO ACTION) -->
        <!-- ========================================================== -->
        <section class="about-cta-section">
            <div class="about-cta-card">
                <span class="cta-mini-tag">🏮 Phong Vị Cố Đô Chờ Bạn Khám Phá</span>
                <h2 class="cta-heading">Thưởng Thức Hương Vị Xứ Huế Ngay Tại Nhà Bạn</h2>
                <p class="cta-desc">
                    Đặt hàng hôm nay để nhận ngay ưu đãi vận chuyển và thưởng thức những đặc sản thơm ngon, tinh túy nhất đất Thần Kinh!
                </p>
                <div class="cta-actions">
                    <a href="<?php echo esc_url($home_url); ?>#dac-san" class="btn-cta-primary">
                        🛍️ Khám Phá Danh Mục Đặc Sản
                    </a>
                    <a href="<?php echo esc_url($home_url); ?>?controller=product&action=detail&id=1" class="btn-cta-secondary">
                        🔍 Xem Sản Phẩm Bán Chạy Nhất
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
