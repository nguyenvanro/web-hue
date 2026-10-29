<?php
$consultationAction = function_exists('home_url') ? home_url('/?controller=contact&action=submit') : 'index.php?controller=contact&action=submit';
?>
<dialog id="homeConsultationModal" class="home-consultation-dialog" aria-labelledby="homeConsultationTitle" aria-describedby="homeConsultationDescription">
    <div class="home-promotion-content">
        <button type="button" class="home-consultation-close" data-consultation-close aria-label="Đóng đăng ký nhận tin">&times;</button>
        <div class="home-promotion-copy">
            <p class="home-promotion-eyebrow">O HƯƠNG XỨ HUẾ</p>
            <h2 id="homeConsultationTitle">ĐĂNG KÝ NHẬN TIN<br>KHUYẾN MÃI</h2>
            <p class="home-promotion-offer">Đón ưu đãi, thưởng vị Huế</p>
            <p id="homeConsultationDescription">Để lại email để nhận thông tin ưu đãi, đặc sản mới và gợi ý quà biếu từ O Hương Xứ Huế.</p>
            <form action="<?php echo esc_url($consultationAction); ?>" method="POST" class="home-promotion-form" data-consultation-form>
                <input type="hidden" name="intent" value="newsletter">
                <label class="home-promotion-label" for="hc_email">Email <span aria-hidden="true">*</span></label>
                <input type="email" id="hc_email" name="email" placeholder="Email của bạn *" maxlength="254" autocomplete="email" required>
                <label class="home-promotion-label" for="hc_phone">Số điện thoại (không bắt buộc)</label>
                <input type="tel" id="hc_phone" name="phone" placeholder="Số điện thoại (không bắt buộc)" maxlength="30" autocomplete="tel">
                <div class="home-consultation-trap" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                <label class="home-promotion-consent"><input type="checkbox" name="consent" value="1" required><span>Tôi đồng ý để O Hương Xứ Huế sử dụng thông tin trên để liên hệ và gửi tin khuyến mãi.</span></label>
                <div class="home-promotion-actions">
                    <button type="submit" class="home-promotion-submit">ĐĂNG KÝ NGAY</button>
                    <button type="button" class="home-consultation-later" data-consultation-close>Để lần sau nhé!</button>
                </div>
            </form>
            <p class="home-consultation-status" data-consultation-status role="status" aria-live="polite"></p>
        </div>
        <div class="home-promotion-art" aria-hidden="true">
            <span class="home-promotion-decoration home-promotion-decoration-top">ƯU ĐÃI</span>
            <img class="home-promotion-logo" src="<?php echo esc_url($theme_uri); ?>/assets/images/logo.jpg" alt="" width="280" height="280">
            <span class="home-promotion-decoration home-promotion-decoration-bottom">VỊ NGON CỐ ĐÔ</span>
        </div>
    </div>
</dialog>
<link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/home-consultation.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/home-consultation.css'); ?>">
<script src="<?php echo esc_url($theme_uri); ?>/assets/js/home-consultation.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/home-consultation.js'); ?>" defer></script>