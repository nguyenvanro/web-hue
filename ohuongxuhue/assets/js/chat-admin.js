(() => {
    'use strict';
    function init() {
        const root = document.querySelector('.ohx-inbox');
        if (!root) return;
        const list = root.querySelector('.ohx-inbox-threads');
        const title = root.querySelector('h2');
        const log = root.querySelector('.ohx-inbox-messages');
        const status = root.querySelector('.ohx-inbox-status');
        const form = root.querySelector('form');
        const input = form.querySelector('textarea');
        const button = form.querySelector('button');
        let token = '', active = '', after = 0, version = 0, loading = false, listing = false, sending = false, stopped = false;
        const ids = new Set(), drafts = new Map(), pending = new Map();
        async function api(action, data, conversation = active, cursor = after) {
            const url = new URL(root.dataset.chatEndpoint, document.baseURI);
            url.searchParams.set('action', action);
            if (!data) { url.searchParams.set('conversation', conversation); url.searchParams.set('after', String(cursor)); }
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 15000);
            try {
                const options = {credentials:'same-origin', cache:'no-store', headers:{Accept:'application/json'}, signal:controller.signal};
                if (data) { options.method='POST'; options.body=new URLSearchParams(data); }
                const response = await fetch(url, options), result = await response.json();
                if (!response.ok) { if(response.status===403) stopped=true; throw new Error(result.message || 'Chưa kết nối được hộp chat.'); }
                return result;
            } finally { clearTimeout(timeout); }
        }
        function add(message) {
            if (ids.has(message.id)) return;
            const nearBottom = log.scrollHeight-log.scrollTop-log.clientHeight < 80;
            ids.add(message.id); after=Math.max(after,message.id);
            const row=document.createElement('div'); row.className='ohx-inbox-message ' + (message.sender==='staff'?'is-staff':'is-guest');
            const body=document.createElement('p'); body.textContent=message.body;
            const time=document.createElement('small'); time.textContent=(message.sender==='staff'?'Nhân viên':'Khách hàng')+' · '+new Date(message.created_at).toLocaleString('vi-VN');
            row.append(body,time); log.append(row);
            if (nearBottom || message.sender==='staff') log.scrollTop=log.scrollHeight;
        }
        async function history() {
            if (!active || loading || stopped) return;
            loading=true; const current=version;
            try {
                let more;
                do {
                    const result=await api('admin_messages');
                    if(current!==version)return;
                    result.messages.forEach(add); more=result.has_more;
                } while(more);
            } catch(error){ if(current===version)status.textContent=error.name==='AbortError'?'Kết nối chậm. Hệ thống sẽ thử lại.':error.message; }
            finally{ loading=false; if(current!==version && active)history(); }
        }
        function choose(thread) {
            if (active) drafts.set(active,input.value);
            active=thread.id; after=0; version++; ids.clear(); log.replaceChildren();
            title.textContent=thread.label; input.value=drafts.get(active)||''; input.disabled=false; button.disabled=!token||sending;
            status.textContent='Đang mở hội thoại…';
            list.querySelectorAll('button').forEach(item=>item.setAttribute('aria-current',String(item.dataset.id===active)));
            history().then(()=>{ if(!stopped)status.textContent='Tin mới tự cập nhật mỗi 5 giây.'; });
        }
        async function threads() {
            if(listing||stopped)return; listing=true;
            try {
                const result=await api('admin_threads');
                const focused=list.contains(document.activeElement)?document.activeElement.dataset.id:null;
                list.replaceChildren();
                if(!result.threads.length){const p=document.createElement('p');p.textContent='Chưa có tin nhắn từ khách hàng.';list.append(p);}
                result.threads.forEach(thread=>{
                    const item=document.createElement('button');item.type='button';item.className='ohx-inbox-thread';item.dataset.id=thread.id;item.setAttribute('aria-current',String(thread.id===active));
                    const heading=document.createElement('strong');heading.textContent=thread.label+(thread.unread?' · Chưa đọc':'');if(thread.unread)heading.className='ohx-inbox-unread';
                    const preview=document.createElement('span');preview.textContent=thread.preview;
                    const time=document.createElement('small');time.textContent=new Date(thread.updated_at).toLocaleString('vi-VN');
                    item.append(heading,preview,time);item.addEventListener('click',()=>choose(thread));list.append(item);
                    if(focused===thread.id)item.focus();
                });
            }catch(error){status.textContent=error.name==='AbortError'?'Kết nối chậm. Hệ thống sẽ thử lại.':error.message;}
            finally{listing=false;}
        }
        input.addEventListener('input',()=>{if(active)drafts.set(active,input.value);});
        input.addEventListener('keydown',event=>{if(event.key==='Enter'&&!event.shiftKey&&!event.isComposing){event.preventDefault();form.requestSubmit();}});
        form.addEventListener('submit',async event=>{
            event.preventDefault();const body=input.value.trim(),conversation=active;
            if(!body||!conversation||!token||sending||stopped)return;
            if(body.length>2000){status.textContent='Tin nhắn tối đa 2.000 ký tự.';return;}
            let request=pending.get(conversation);
            if(!request||request.body!==body){const bytes=new Uint8Array(16);crypto.getRandomValues(bytes);request={body,key:Array.from(bytes,n=>n.toString(16).padStart(2,'0')).join('')};pending.set(conversation,request);}
            sending=true;button.disabled=true;
            try{
                await api('admin_send',{token,conversation,body,client_key:request.key});
                pending.delete(conversation);
                if(drafts.get(conversation)?.trim()===body)drafts.delete(conversation);
                if(active===conversation&&input.value.trim()===body)input.value='';
                status.textContent='Đã gửi trả lời.';await history();await threads();
            }catch(error){status.textContent=error.name==='AbortError'?'Chưa xác nhận được tin nhắn. Có thể nhấn Gửi trả lời lần nữa.':error.message;}
            finally{sending=false;button.disabled=!active||!token||stopped;}
        });
        async function start(){
            try{const result=await api('admin_bootstrap');token=result.token;await threads();}
            catch(error){status.textContent=error.name==='AbortError'?'Chưa kết nối được. Vui lòng tải lại trang.':error.message;}
        }
        start();
        setInterval(()=>{if(!document.hidden&&!stopped){threads();history();}},5000);
        document.addEventListener('visibilitychange',()=>{if(!document.hidden&&!stopped){threads();history();}});
    }
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init);else init();
})();