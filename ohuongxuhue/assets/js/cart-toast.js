(function () {
    'use strict';
    const toast = document.getElementById('cartToast');
    if (!toast) return;
    const pending = new Set();
    let timer;

    function showMessage(message, isError) {
        clearTimeout(timer);
        toast.querySelector('.cart-toast-title').textContent = isError ? 'Chưa thể thêm vào giỏ' : 'Đã thêm vào giỏ hàng!';
        document.getElementById('cartToastMsg').textContent = message;
        toast.classList.toggle('is-error', isError);
        toast.classList.add('show');
        toast.setAttribute('aria-hidden', 'false');
        timer = setTimeout(function () {
            toast.classList.remove('show');
            toast.setAttribute('aria-hidden', 'true');
        }, isError ? 6000 : 4000);
    }

    window.addCartAjax = async function (productId, productName) {
        const key = String(productId);
        if (pending.has(key)) return;
        pending.add(key);
        try {
            const url = new URL(toast.dataset.addUrl, window.location.href);
            url.searchParams.set('id', key);
            const response = await fetch(url.href, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            const data = await response.json();
            if (!data.success) {
                showMessage(data.message || 'Không thể thêm sản phẩm vào giỏ.', true);
                return;
            }
            document.querySelectorAll('.cart-count-badge, #headerCartCount').forEach(function (badge) {
                badge.textContent = data.cart_count;
                badge.style.display = 'inline-block';
            });
            showMessage('"' + productName + '" đã được thêm vào giỏ hàng.', false);
        } catch (error) {
            // Không gửi lại yêu cầu: máy chủ có thể đã thêm hàng trước khi mất kết nối.
            showMessage('Chưa nhận được xác nhận. Vui lòng kiểm tra giỏ hàng trước khi thử lại.', true);
        } finally {
            pending.delete(key);
        }
    };
}());
