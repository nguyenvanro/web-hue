(() => {
    'use strict';
    function init() {
        const config = window.OHXReceipt;
        if (!config) return;
        const root = document.querySelector('.wc-hue-thankyou-wrapper, .woocommerce-order');
        if (!root || root.dataset.receiptReady || !root.querySelector('.wc-hue-summary-card, .woocommerce-order-overview, .woocommerce-order-details, .woocommerce-thankyou-order-failed')) return;
        root.dataset.receiptReady = '1';
        document.body.classList.add('ohx-receipt-ready');
        root.classList.add('ohx-receipt');
        const oldHero = document.querySelector('.wc-page-hero-banner');
        if (oldHero) (oldHero.parentElement.children.length === 1 ? oldHero.parentElement : oldHero).classList.add('ohx-receipt-superseded');
        const tidy = text => (text || '').replace(/\s+/g, ' ').trim();
        const read = (...selectors) => {
            for (const selector of selectors) {
                const node = root.querySelector(selector);
                if (node) return tidy(node.textContent);
            }
            return '';
        };
        const method = read('.cell-method .cell-value', '.woocommerce-order-overview__payment-method strong');
        const methodLabel = /vietqr|chuyển khoản/i.test(method) ? 'Chuyển khoản ngân hàng' : /nhận hàng|\bcod\b/i.test(method) ? 'Thanh toán khi nhận hàng' : method;
        const header = document.createElement('header');
        header.className = 'ohx-receipt-header';
        header.innerHTML = '<span class="ohx-receipt-check" aria-hidden="true">✓</span><div><p class="ohx-receipt-eyebrow">O HƯƠNG XỨ HUẾ</p><h1>Đã nhận đơn hàng</h1><p class="ohx-receipt-intro">Cảm ơn bạn đã đặt hàng cùng chúng tôi.</p></div><span class="ohx-receipt-badge" role="status">Đã ghi nhận</span>';
        const meta = document.createElement('dl');
        meta.className = 'ohx-receipt-meta';
        const fields = [
            ['Mã đơn', read('.cell-order .cell-value', '.woocommerce-order-overview__order strong')],
            ['Tổng thanh toán', read('.cell-total .cell-value', '.woocommerce-order-overview__total strong')],
            ['Thanh toán', methodLabel],
        ];
        fields.forEach(([label, value]) => {
            if (!value) return;
            const box = document.createElement('div'), dt = document.createElement('dt'), dd = document.createElement('dd');
            dt.textContent = label; dd.textContent = value; box.append(dt, dd); meta.append(box);
        });
        root.prepend(header, meta);
        root.querySelectorAll('.wc-hue-thankyou-hero, .wc-hue-summary-card, .woocommerce-order-overview, .woocommerce-thankyou-order-received, #ohxPaymentConfirmCard, #ez-toc-container, .ez-toc-container').forEach(node => { node.hidden = true; node.classList.add('ohx-receipt-superseded'); });
        // The old button can mark a payment successful even on network failure.
        // It is no longer interactive; the new button only reads the server status.
        const oldConfirm = root.querySelector('#btnConfirmPaid');
        if (oldConfirm) { oldConfirm.disabled = true; oldConfirm.setAttribute('tabindex', '-1'); }
        [...root.children].forEach(node => {
            if (node.tagName === 'P' && /Quý khách vui lòng mở App ngân hàng/.test(node.textContent)) node.hidden = true;
        });
        const qr = root.querySelector('.woocommerce-vietqr-qr-scan');
        const bank = root.querySelector('.woocommerce-vietqr-bank-details');
        let payment;
        if (qr || bank) {
            payment = document.createElement('section'); payment.className = 'ohx-receipt-payment';
            const heading = document.createElement('h2'); heading.textContent = 'Thanh toán chuyển khoản';
            const hint = document.createElement('p'); hint.className = 'ohx-receipt-hint'; hint.textContent = 'Quét mã bằng ứng dụng ngân hàng hoặc chuyển theo thông tin bên dưới.';
            const layout = document.createElement('div'); layout.className = 'ohx-receipt-payment-grid';
            if (qr) {
                qr.querySelectorAll('.wc-vietqr-bank-details-heading').forEach(node => { node.hidden = true; });
                const image = qr.querySelector('#qrcode img'); if (image) image.loading = 'eager';
                const download = qr.querySelector('#btnDownloadQR'); if (download) download.textContent = 'Lưu mã QR';
                layout.append(qr);
            }
            if (bank) {
                bank.querySelectorAll('.wc-vietqr-bank-details-heading, h4').forEach(node => { node.hidden = true; });
                bank.querySelectorAll('tr').forEach(row => {
                    const cells = row.querySelectorAll('td'); if (cells.length !== 2) return;
                    const label = tidy(cells[0].textContent), value = tidy(cells[1].textContent);
                    if (/tài khoản|nội dung/i.test(label) && value) {
                        const copy = document.createElement('button'); copy.type = 'button'; copy.className = 'ohx-receipt-copy'; copy.textContent = 'Chép'; copy.setAttribute('aria-label', 'Sao chép ' + label);
                        copy.addEventListener('click', async () => {
                            try { await navigator.clipboard.writeText(value); copy.textContent = 'Đã chép'; }
                            catch (_) { copy.textContent = 'Hãy chép thủ công'; }
                            setTimeout(() => { copy.textContent = 'Chép'; }, 2500);
                        });
                        cells[1].append(copy);
                    }
                });
                layout.append(bank);
            }
            payment.append(heading, hint, layout); meta.after(payment);
        }
        const controls = document.createElement('div'); controls.className = 'ohx-receipt-controls';
        const check = document.createElement('button'); check.type = 'button'; check.className = 'ohx-receipt-refresh'; check.textContent = 'Kiểm tra thanh toán';
        const note = document.createElement('p'); note.className = 'ohx-receipt-status'; note.setAttribute('role', 'status');
        controls.append(check, note); if (payment) payment.append(controls); else meta.after(controls);
        const wrapDetails = (selector, label) => {
            const node = root.querySelector(selector); if (!node) return;
            const details = document.createElement('details'); details.className = 'ohx-receipt-details';
            const summary = document.createElement('summary'); summary.textContent = label;
            node.before(details); details.append(summary, node);
        };
        wrapDetails('.woocommerce-order-details', 'Sản phẩm & chi tiết đơn hàng');
        const billing = root.querySelector('.woocommerce-column--billing-address');
        const shipping = root.querySelector('.woocommerce-column--shipping-address');
        if (billing && shipping) {
            const addressText = node => { const copy = node.querySelector('address')?.cloneNode(true); if (!copy) return ''; copy.querySelectorAll('p').forEach(p => p.remove()); return tidy(copy.textContent); };
            if (addressText(billing) && addressText(billing) === addressText(shipping)) { billing.hidden = true; billing.classList.add('ohx-receipt-superseded'); }
        }
        wrapDetails('.woocommerce-customer-details', 'Thông tin nhận hàng');
        const actions = document.createElement('nav'); actions.className = 'ohx-receipt-actions'; actions.setAttribute('aria-label', 'Sau khi đặt hàng');
        const shop = document.createElement('a'); shop.href = config.shopUrl; shop.className = 'ohx-receipt-shop'; shop.textContent = 'Tiếp tục mua hàng';
        const help = document.createElement('a'); help.href = 'tel:' + config.phone; help.textContent = 'Gọi hỗ trợ'; actions.append(shop, help); root.append(actions);
        const badge = header.querySelector('.ohx-receipt-badge');
        const title = header.querySelector('h1');
        let busy = false, terminal = false, timer;
        const key = new URL(location.href).searchParams.get('key');
        const applyStatus = data => {
            const endStates = {failed:'Thanh toán chưa thành công',cancelled:'Đơn hàng đã hủy',refunded:'Đơn hàng đã hoàn tiền'};
            if (endStates[data.status]) {
                title.textContent = endStates[data.status]; badge.textContent = endStates[data.status]; badge.dataset.state = 'attention'; terminal = true;
                if (payment) payment.hidden = true; check.hidden = true; note.textContent = 'Liên hệ cửa hàng nếu bạn cần hỗ trợ.';
            } else if (data.paid) {
                badge.textContent = 'Đã thanh toán'; badge.dataset.state = 'paid'; terminal = true;
                if (payment) payment.hidden = true; check.hidden = true;
                note.textContent = data.status === 'completed' ? 'Đơn hàng đã hoàn tất.' : 'Cửa hàng đang xử lý đơn hàng.';
                if (payment) { payment.after(controls); }
            } else if (data.method === 'cod') {
                badge.textContent = 'Thanh toán khi nhận hàng'; terminal = true; check.hidden = true;
                if (payment) { payment.hidden = true; payment.after(controls); }
                note.textContent = 'Bạn thanh toán cho người giao hàng khi nhận đơn.';
            } else {
                badge.textContent = 'Chờ thanh toán'; badge.dataset.state = 'pending';
                note.textContent = 'Chưa ghi nhận thanh toán. Trạng thái sẽ tự cập nhật.';
            }
            if (terminal) clearTimeout(timer);
        };
        async function refresh(manual = false) {
            if (busy || terminal) return;
            if (!key || !config.orderId || !config.ajaxUrl) { check.hidden = true; note.textContent = 'Giữ lại mã đơn để tiện liên hệ cửa hàng.'; return; }
            if (document.hidden && !manual) { timer = setTimeout(() => refresh(), 8000); return; }
            busy = true; check.disabled = true;
            const abort = new AbortController(), timeout = setTimeout(() => abort.abort(), 15000);
            try {
                const response = await fetch(config.ajaxUrl, {method:'POST',credentials:'same-origin',cache:'no-store',headers:{Accept:'application/json'},signal:abort.signal,body:new URLSearchParams({action:'ohx_receipt_status',order_id:String(config.orderId),order_key:key})});
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error('status unavailable');
                applyStatus(result.data);
            } catch (_) { note.textContent = 'Chưa kiểm tra được thanh toán. Vui lòng thử lại.'; }
            finally { clearTimeout(timeout); busy = false; check.disabled = false; if (!terminal) { clearTimeout(timer); timer = setTimeout(() => refresh(), 8000); } }
        }
        if (root.querySelector('.woocommerce-thankyou-order-failed')) applyStatus({status:'failed',paid:false});
        check.addEventListener('click', () => refresh(true));
        document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); });
        refresh();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
