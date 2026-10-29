/**
 * File JavaScript chính cho O Hương Xứ Huế (main.js)
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Xử lý nút Đặt hàng (Thêm thông báo trực quan)
    const orderButtons = document.querySelectorAll('.btn-order');
    orderButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productCard = this.closest('.product-card');
            const productName = productCard ? productCard.querySelector('.product-title').innerText : 'Sản phẩm';
            
            showToast('Đã thêm "' + productName + '" vào giỏ hàng thành công!');
        });
    });

    // 2. Hàm hiển thị Toast thông báo nhanh
    function showToast(message) {
        let toast = document.getElementById('custom-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'custom-toast';
            toast.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: #8b5a2b;
                color: #fff;
                padding: 14px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                font-size: 14px;
                font-weight: 500;
                z-index: 99999;
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            document.body.appendChild(toast);
        }

        toast.innerHTML = '<span>🛒</span> ' + message;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
        }, 3000);
    }


});
