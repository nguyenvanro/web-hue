(() => {
    'use strict';
    const script = document.currentScript;
    const endpoint = script?.dataset.chatUrl || new URL('ohuongxuhue/index.php?controller=chat', script.src).href;
    const logoUrl = script?.dataset.logoUrl || new URL('images/logo.jpg', script.src).href;
    function init() {
        if (document.querySelector('.hue-contact-widget')) return;
        const widget = document.createElement('aside');
        widget.className = 'hue-contact-widget';
        widget.setAttribute('aria-label', 'Liên hệ và tư vấn');
        widget.innerHTML = `
            <div class="hue-contact-panel" id="hue-contact-panel" hidden>
                <button class="hue-contact-option hue-contact-start" type="button"><img class="hue-contact-avatar" alt="" width="38" height="38"><span>Chat với nhân viên</span></button>
                <a class="hue-contact-option" href="https://zalo.me/0342684980" target="_blank" rel="noopener noreferrer"><span class="hue-contact-icon hue-contact-zalo" aria-hidden="true">Zalo</span><span>Liên hệ Zalo</span></a>
                <a class="hue-contact-option" href="https://www.facebook.com/ohuongxuhue" target="_blank" rel="noopener noreferrer"><span class="hue-contact-icon hue-contact-facebook" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></span><span>Liên hệ Facebook</span></a>
                <a class="hue-contact-option" href="https://www.tiktok.com/@ohuongxuhue" target="_blank" rel="noopener noreferrer"><span class="hue-contact-icon hue-contact-tiktok" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.49 6.27 6.27 0 0 0 1.87-4.49v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-.87-.09z"/></svg></span><span>Liên hệ TikTok</span></a>
            </div>
            <section class="hue-chat-window" id="hue-chat-window" role="dialog" aria-modal="false" aria-labelledby="hue-chat-title" hidden>
                <header class="hue-chat-header"><img class="hue-chat-logo" alt="Logo O Hương Xứ Huế" width="48" height="48"><div><h2 id="hue-chat-title">Xin chào!</h2><p>O Hương Xứ Huế hỗ trợ bạn</p></div><button class="hue-chat-close" type="button" aria-label="Đóng cửa sổ chat">&times;</button></header>
                <div class="hue-chat-messages" role="log" aria-live="polite" aria-label="Nội dung hội thoại"></div>
                <p class="hue-chat-status" role="status"></p>
                <button class="hue-chat-retry" type="button" hidden>Thử kết nối lại</button>
                <form class="hue-chat-form"><label class="hue-chat-sr-only" for="hue-chat-input">Nhập tin nhắn cho nhân viên</label><textarea id="hue-chat-input" name="body" rows="1" maxlength="2000" placeholder="Nhập nội dung…" required></textarea><button class="hue-chat-emoji" type="button" aria-label="Thêm biểu tượng cười">☺</button><button class="hue-chat-send" type="submit" disabled>Gửi</button></form>
            </section>
            <button class="hue-contact-toggle" type="button" aria-expanded="false" aria-controls="hue-contact-panel" aria-label="Mở các lựa chọn liên hệ"><svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5H5l-4 3V11.5A8.5 8.5 0 0 1 9.5 3h3a8.5 8.5 0 0 1 8.5 8.5Z"/><path d="M7 11h.01M12 11h.01M17 11h.01"/></svg><span class="hue-contact-toggle-label">Liên hệ</span></button>`;
        widget.querySelectorAll('.hue-contact-avatar, .hue-chat-logo').forEach(img => { img.src = logoUrl; });
        document.body.append(widget);
        const toggle = widget.querySelector('.hue-contact-toggle');
        const panel = widget.querySelector('.hue-contact-panel');
        const chat = widget.querySelector('.hue-chat-window');
        const log = widget.querySelector('.hue-chat-messages');
        const status = widget.querySelector('.hue-chat-status');
        const retry = widget.querySelector('.hue-chat-retry');
        const form = widget.querySelector('.hue-chat-form');
        const input = form.querySelector('textarea');
        const send = form.querySelector('.hue-chat-send');
        let token = '', after = 0, epoch = 0, pollTimer, sending = false, loading = false, pending;
        const ids = new Set();
        function menu(open) {
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
        }
        function addMessage(message, welcome = false) {
            if (!welcome && ids.has(message.id)) return;
            if (!welcome) { ids.add(message.id); after = Math.max(after, message.id); }
            const nearBottom = log.scrollHeight - log.scrollTop - log.clientHeight < 80;
            const row = document.createElement('div');
            row.className = 'hue-chat-row ' + (message.sender === 'guest' ? 'is-guest' : 'is-staff');
            const bubble = document.createElement('p');
            bubble.className = 'hue-chat-bubble';
            bubble.textContent = message.body;
            const label = document.createElement('small');
            label.textContent = welcome ? 'O Hương Xứ Huế · Lời chào tự động' : (message.sender === 'guest' ? 'Bạn' : 'Nhân viên') + ' · ' + new Date(message.created_at).toLocaleTimeString('vi-VN', {hour:'2-digit', minute:'2-digit'});
            row.append(bubble, label); log.append(row);
            if (nearBottom || message.sender === 'guest') log.scrollTop = log.scrollHeight;
        }
        async function api(action, data, cursor = after) {
            const url = new URL(endpoint, document.baseURI);
            url.searchParams.set('action', action);
            if (!data) url.searchParams.set('after', String(cursor));
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 15000);
            try {
                const options = {credentials:'same-origin', cache:'no-store', headers:{Accept:'application/json'}, signal:controller.signal};
                if (data) { options.method = 'POST'; options.body = new URLSearchParams(data); }
                const response = await fetch(url, options);
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Chưa kết nối được chat.');
                return result;
            } finally { clearTimeout(timeout); }
        }
        function schedule(delay = 4000) {
            clearTimeout(pollTimer);
            if (!chat.hidden) pollTimer = setTimeout(() => refresh(!token), delay);
        }
        async function refresh(bootstrap = false) {
            if (chat.hidden || loading || (document.hidden && !bootstrap)) { schedule(); return; }
            loading = true;
            const version = epoch;
            try {
                const result = await api(bootstrap ? 'bootstrap' : 'messages');
                if (version !== epoch) return;
                if (result.token) token = result.token;
                result.messages.forEach(msg => addMessage(msg));
                status.textContent = 'Tin nhắn được lưu. Nhân viên sẽ trả lời tại đây.';
                retry.hidden = true;
                send.disabled = !token || sending;
                schedule(result.has_more ? 50 : 4000);
            } catch (_) {
                if (version === epoch) {
                    status.textContent = 'Chưa kết nối được. Bạn có thể thử lại hoặc liên hệ Zalo.';
                    retry.hidden = false;
                    schedule(8000);
                }
            } finally {
                loading = false;
                if (version !== epoch && !chat.hidden) refresh(!token);
            }
        }
        function closeChat() {
            chat.hidden = true; epoch++; clearTimeout(pollTimer);
            toggle.focus();
        }
        async function openChat() {
            menu(false); chat.hidden = false; epoch++; token = ''; after = 0; ids.clear(); log.replaceChildren();
            addMessage({sender:'staff',body:'Xin chào! O Hương Xứ Huế có thể tư vấn đặc sản, đặt hàng và quà biếu cho bạn. Hãy để lại câu hỏi; nhân viên sẽ phản hồi tại đây khi tiếp nhận.'}, true);
            status.textContent = 'Đang kết nối…'; send.disabled = true;
            input.focus(); await refresh(true);
        }
        toggle.addEventListener('click', () => { if (!chat.hidden) closeChat(); else menu(panel.hidden); });
        widget.querySelector('.hue-contact-start').addEventListener('click', openChat);
        widget.querySelector('.hue-chat-close').addEventListener('click', closeChat);
        retry.addEventListener('click', () => refresh(!token));
        document.addEventListener('click', event => { if (!widget.contains(event.target)) menu(false); });
        document.addEventListener('visibilitychange', () => { if (!document.hidden && !chat.hidden) refresh(!token); });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') { if (!chat.hidden) closeChat(); else if (!panel.hidden) { menu(false); toggle.focus(); } }
        });
        widget.querySelector('.hue-chat-emoji').addEventListener('click', () => { input.setRangeText('😊', input.selectionStart, input.selectionEnd, 'end'); input.focus(); });
        input.addEventListener('keydown', event => {
            if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) { event.preventDefault(); form.requestSubmit(); }
        });
        form.addEventListener('submit', async event => {
            event.preventDefault();
            const body = input.value.trim();
            if (!body || !token || sending) return;
            if (body.length > 2000) { status.textContent = 'Tin nhắn tối đa 2.000 ký tự.'; return; }
            if (!pending || pending.body !== body) {
                const bytes = new Uint8Array(16); crypto.getRandomValues(bytes);
                pending = {body, key:Array.from(bytes, n => n.toString(16).padStart(2,'0')).join('')};
            }
            sending = true; send.disabled = true;
            try {
                await api('send', {body,token,client_key:pending.key});
                if (input.value.trim() === body) input.value = '';
                pending = null;
                status.textContent = 'Đã gửi tin nhắn.';
                if (!chat.hidden) await refresh();
            } catch (error) { status.textContent = error.name === 'AbortError' ? 'Chưa xác nhận được tin nhắn. Bạn có thể nhấn Gửi lại.' : error.message; }
            finally { sending = false; send.disabled = !token; }
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
