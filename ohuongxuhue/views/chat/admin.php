<link rel="stylesheet" href="<?php echo htmlspecialchars($chatAssetUrl, ENT_QUOTES, 'UTF-8'); ?>/assets/css/chat-admin.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/chat-admin.css'); ?>">
<div class="ohx-inbox" data-chat-endpoint="<?php echo htmlspecialchars($chatAdminEndpoint, ENT_QUOTES, 'UTF-8'); ?>">
    <h1>Chat khách hàng — O Hương Xứ Huế</h1>
    <p>Chọn hội thoại để trả lời. Danh sách hiển thị 100 hội thoại hoạt động gần nhất và tự cập nhật mỗi 5 giây.</p>
    <div class="ohx-inbox-layout">
        <nav class="ohx-inbox-threads" aria-label="Danh sách hội thoại"><p>Đang tải hội thoại…</p></nav>
        <section class="ohx-inbox-detail" aria-labelledby="ohx-inbox-title">
            <h2 id="ohx-inbox-title">Chọn hội thoại</h2>
            <div class="ohx-inbox-messages" role="log" aria-live="polite" aria-label="Tin nhắn trong hội thoại"></div>
            <p class="ohx-inbox-status" role="status"></p>
            <form class="ohx-inbox-form">
                <label for="ohx-reply">Trả lời khách hàng</label>
                <textarea id="ohx-reply" maxlength="2000" rows="3" placeholder="Nhập câu trả lời…" required disabled></textarea>
                <button type="submit" disabled>Gửi trả lời</button>
            </form>
        </section>
    </div>
</div>
<script src="<?php echo htmlspecialchars($chatAssetUrl, ENT_QUOTES, 'UTF-8'); ?>/assets/js/chat-admin.js?v=<?php echo filemtime(__DIR__ . '/../../assets/js/chat-admin.js'); ?>" defer></script>
