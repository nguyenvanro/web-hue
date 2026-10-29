document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('homeConsultationModal');
    if (!modal || typeof modal.showModal !== 'function') return;
    const form = modal.querySelector('[data-consultation-form]');
    const status = modal.querySelector('[data-consultation-status]');
    const key = 'ohx-home-promotion-shown';
    let shown = false;
    let ready = false;
    let previousOverflow = '';
    const hasShown = () => {
        try { return shown || sessionStorage.getItem(key) === '1'; }
        catch (_) { return shown; }
    };
    const open = () => {
        if (!ready || document.hidden || hasShown()) return;
        modal.showModal();
        shown = true;
        try { sessionStorage.setItem(key, '1'); } catch (_) {}
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        form.querySelector('input[name="email"]').focus({ preventScroll: true });
    };
    if (!hasShown()) window.setTimeout(() => { ready = true; open(); }, 12000);
    document.addEventListener('visibilitychange', open);
    modal.querySelectorAll('[data-consultation-close]').forEach(button => button.addEventListener('click', () => modal.close()));
    modal.addEventListener('click', event => {
        if (event.target !== modal) return;
        const box = modal.getBoundingClientRect();
        if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) modal.close();
    });
    modal.addEventListener('close', () => { document.body.style.overflow = previousOverflow; });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const button = form.querySelector('button[type="submit"]');
        if (button.disabled) return;
        button.disabled = true;
        status.textContent = 'Đang đăng ký nhận tin…';
        let errorMessage = 'Chưa đăng ký được. Vui lòng thử lại sau hoặc gọi hotline 0342.684.980.';
        try {
            const tokenResponse = await fetch(form.action, { headers: { Accept: 'application/json' }, cache: 'no-store' });
            if (!tokenResponse.ok) throw new Error('Chưa kết nối được. Vui lòng thử lại sau.');
            const tokenData = await tokenResponse.json();
            const data = new FormData(form);
            data.set('token', tokenData.token);
            const response = await fetch(form.action, { method: 'POST', body: data, headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) {
                errorMessage = result.message || errorMessage;
                throw new Error('Submission failed');
            }
            status.textContent = result.message;
            form.style.display = 'none';
        } catch (error) {
            status.textContent = errorMessage;
            button.disabled = false;
        }
    });
});
