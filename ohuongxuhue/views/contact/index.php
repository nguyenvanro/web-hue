<?php
/**
 * View Trang Liên Hệ & Hỗ Trợ Khách Hàng (Contact)
 */
$theme_uri      = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url       = function_exists('home_url') ? home_url('/') : 'index.php';
$successMessage = $successMessage ?? '';
?>

<div class="contact-page-master">
    <!-- Header Banner -->
    <section class="contact-hero-banner">
        <div class="contact-hero-content">
            <nav class="contact-breadcrumb">
                <a href="<?php echo esc_url($home_url); ?>">Trang Chủ</a> &rsaquo; <span>Liên Hệ</span>
            </nav>
            <span class="contact-hero-tag">💌 Lắng Nghe & Đồng Hành</span>
            <h1 class="contact-hero-title">Liên Hệ Với O Hương Xứ Huế</h1>
            <p class="contact-hero-desc">
                Đội ngũ O Hương Xứ Huế luôn sẵn sàng lắng nghe, tư vấn tận tâm về các set quà biếu và giải đáp mọi thắc mắc của quý khách 24/7.
            </p>
        </div>
    </section>

    <div class="contact-container">
        <!-- THÔNG BÁO GỬI THÀNH CÔNG NẾU CÓ -->
        <?php if (!empty($successMessage)): ?>
            <div class="contact-alert-success">
                <span class="alert-icon">🎉</span>
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php endif; ?>

        <!-- THÔNG TIN LIÊN LẠC NHANH -->
        <div class="contact-info-cards-grid">
            <div class="info-card">
                <div class="info-card-icon">📞</div>
                <h3>Hotline Tư Vấn</h3>
                <p class="info-main">0234 388 9999</p>
                <p class="info-sub">0905 123 456 (Hỗ trợ 24/7)</p>
                <span class="info-badge">Tư vấn miễn phí</span>
            </div>

            <div class="info-card">
                <div class="info-card-icon">🏛️</div>
                <h3>Trụ Sở & Showroom</h3>
                <p class="info-main">128 Nguyễn Huệ, TP. Huế</p>
                <p class="info-sub">Mở cửa: 06:30 - 22:30 mỗi ngày</p>
                <span class="info-badge">Dùng thử trà bánh</span>
            </div>
        </div>

        <!-- BỐ CỤC FORM LIÊN HỆ & FAQ -->
        <div class="contact-form-faq-grid">
            <!-- Cột Trái: Form Gửi Tin Nhắn -->
            <div class="contact-form-box">
                <h2 class="form-box-title">Gửi Lời Nhắn Cho Chúng Tôi</h2>
                <p class="form-box-sub">Quý khách vui lòng điền thông tin bên dưới, chuyên viên tư vấn sẽ liên hệ lại ngay.</p>

                <form action="" method="POST" class="main-contact-form" onsubmit="return validateContactForm()">
                    <div class="form-row">
                        <div class="form-group col-half">
                            <label for="c_name">Họ và tên quý khách <span class="req">*</span></label>
                            <input type="text" id="c_name" name="fullname" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="form-group col-half">
                            <label for="c_phone">Số điện thoại liên hệ <span class="req">*</span></label>
                            <input type="tel" id="c_phone" name="phone" placeholder="0905xxxxxx" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-half">
                            <label for="c_email">Địa chỉ Email</label>
                            <input type="email" id="c_email" name="email" placeholder="email@domain.com">
                        </div>
                        <div class="form-group col-half">
                            <label for="c_subject">Chủ đề cần tư vấn</label>
                            <select id="c_subject" name="subject">
                                <option value="order">🛒 Đặt mua đặc sản giao tận nhà</option>
                                <option value="gift">🎁 Tư vấn giỏ quà biếu doanh nghiệp số lượng lớn</option>
                                <option value="agency">🤝 Đăng ký làm đại lý phân phối</option>
                                <option value="feedback">💌 Góp ý chất lượng dịch vụ</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="c_message">Nội dung yêu cầu / Lời nhắn chi tiết <span class="req">*</span></label>
                        <textarea id="c_message" name="message" rows="5" placeholder="Ví dụ: Tôi muốn đặt 20 hộp bánh bèo nậm lọc và 10 hộp mè xửng giao tại Hà Nội ngày mai..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit-contact">
                        🚀 Gửi Yêu Cầu Tư Vấn Ngay
                    </button>
                </form>
            </div>

            <!-- Cột Phải: FAQ Câu Hỏi Thường Gặp -->
            <div class="contact-faq-box">
                <h2 class="faq-box-title">Câu Hỏi Thường Gặp (FAQ)</h2>

                <div class="faq-accordion">
                    <div class="faq-item active">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <span>1. Bánh Huế tươi bảo quản được bao lâu khi đi máy bay?</span>
                            <span class="faq-toggle-icon">&minus;</span>
                        </div>
                        <div class="faq-answer">
                            <p>Bánh được hấp chín và hút chân không chuyên dụng, kèm thùng xốp đá khô giữ nhiệt. Bánh giữ được độ dẻo mềm chuẩn vị trong 24 - 48 giờ di chuyển máy bay hoặc tàu hỏa.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <span>2. Thời gian giao hàng toàn quốc mất bao lâu?</span>
                            <span class="faq-toggle-icon">&#43;</span>
                        </div>
                        <div class="faq-answer">
                            <p>Khu vực TP. Huế: Giao hỏa tốc trong 1-2 giờ. Khu vực Hà Nội & TP.HCM: Giao trong ngày hoặc 24 giờ. Các tỉnh thành khác: Từ 1 - 2 ngày làm việc.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <span>3. Có chính sách chiết khấu khi đặt quà số lượng lớn không?</span>
                            <span class="faq-toggle-icon">&#43;</span>
                        </div>
                        <div class="faq-answer">
                            <p>Có! Chúng tôi chiết khấu từ 10% - 25% cho các đơn hàng quà tặng lễ Tết, hội nghị, công ty, hỗ trợ in logo doanh nghiệp lên hộp quà sang trọng.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <span>4. Nếu sản phẩm bị hư hỏng do vận chuyển thì sao?</span>
                            <span class="faq-toggle-icon">&#43;</span>
                        </div>
                        <div class="faq-answer">
                            <p>O Hương Xứ Huế cam kết đổi mới 100% hoặc hoàn tiền ngay trong ngày nếu hàng nhận được không đảm bảo chất lượng, móp méo hoặc sai lệch.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateContactForm() {
    var name = document.getElementById('c_name').value.trim();
    var phone = document.getElementById('c_phone').value.trim();
    if (!name || !phone) {
        alert('Vui lòng điền đầy đủ họ tên và số điện thoại!');
        return false;
    }
    return true;
}

function toggleFaq(elem) {
    var item = elem.parentElement;
    var isActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(i => {
        i.classList.remove('active');
        i.querySelector('.faq-toggle-icon').innerHTML = '&#43;';
    });

    if (!isActive) {
        item.classList.add('active');
        item.querySelector('.faq-toggle-icon').innerHTML = '&minus;';
    }
}
</script>
