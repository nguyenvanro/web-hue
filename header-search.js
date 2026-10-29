document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form.search-bar');
    if (!form) return;
    const input = form.querySelector('input[name="q"]');
    const results = form.querySelector('.live-search-dropdown');
    if (!input || !results) return;
    let timer;
    let request;
    let generation = 0;
    const hide = () => { results.style.display = 'none'; };
    const message = text => {
        const item = document.createElement('p');
        item.className = 'live-search-message';
        item.textContent = text;
        results.replaceChildren(item);
        results.style.display = '';
    };
    const search = async () => {
        const keyword = input.value.trim();
        const version = ++generation;
        request?.abort();
        if (!keyword) { hide(); return; }
        request = new AbortController();
        message('Đang tìm đặc sản…');
        try {
            const url = new URL(form.dataset.searchUrl, window.location.href);
            url.searchParams.set('q', keyword);
            const response = await fetch(url, { signal: request.signal, headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Search failed');
            const data = await response.json();
            if (version !== generation) return;
            if (!data.success || !Array.isArray(data.products)) throw new Error('Invalid results');
            if (!data.products.length) { message('Không tìm thấy đặc sản phù hợp. Hãy thử từ khóa khác.'); return; }
            results.replaceChildren();
            data.products.forEach(product => {
                const link = document.createElement('a');
                link.className = 'live-search-item';
                // Relative API links resolve against the PHP endpoint, including static HTML pages.
                link.href = new URL(product.link, url).href;
                const image = document.createElement('img');
                image.src = new URL(product.image, url).href;
                image.alt = product.name;
                const info = document.createElement('span');
                const name = document.createElement('strong');
                name.textContent = product.name;
                const price = document.createElement('small');
                price.textContent = product.price;
                info.append(name, price);
                link.append(image, info);
                results.append(link);
            });
            const all = document.createElement('a');
            all.className = 'live-search-all';
            const target = new URL(form.action, window.location.href);
            target.searchParams.set('controller', 'dac-san');
            target.searchParams.set('q', keyword);
            all.href = target.href;
            all.textContent = `Xem tất cả ${data.count} kết quả →`;
            results.append(all);
            results.style.display = '';
        } catch (error) {
            if (error.name !== 'AbortError' && version === generation) message('Chưa tải được gợi ý. Nhấn Tìm kiếm để xem kết quả.');
        }
    };
    input.addEventListener('input', () => {
        clearTimeout(timer);
        ++generation;
        request?.abort();
        hide();
        if (input.value.trim()) timer = setTimeout(search, 250);
    });
    input.addEventListener('focus', () => { if (input.value.trim()) search(); });
    input.addEventListener('keydown', event => {
        if (event.key === 'Escape') { clearTimeout(timer); ++generation; request?.abort(); hide(); }
    });
    form.addEventListener('submit', event => {
        input.value = input.value.trim();
        if (!input.value) { event.preventDefault(); input.focus(); hide(); }
    });
    document.addEventListener('click', event => {
        if (!form.contains(event.target)) { ++generation; request?.abort(); clearTimeout(timer); hide(); }
    });
});
