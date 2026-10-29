(() => {
    'use strict';
    const fold = text => String(text || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[đĐ]/g, 'd').toLowerCase();
    function init(section) {
        if (section.dataset.ordersReady) return;
        section.dataset.ordersReady = '1';
        const rows = [...section.querySelectorAll('.order-table-row')];
        const search = section.querySelector('[data-orders-search]');
        const status = section.querySelector('[data-orders-status]');
        const count = section.querySelector('[data-orders-count]');
        const empty = section.querySelector('.orders-no-results');
        const detailsFor = row => document.getElementById(row.querySelector('.orders-row-toggle').getAttribute('aria-controls'));
        function setExpanded(row, open) {
            const button = row.querySelector('.orders-row-toggle');
            const details = detailsFor(row);
            if (!details) return;
            details.hidden = !open; button.setAttribute('aria-expanded', String(open));
            button.textContent = open ? 'Thu gọn' : 'Chi tiết';
            row.classList.toggle('is-expanded', open);
        }
        rows.forEach(row => setExpanded(row, false));
        section.querySelector('.orders-table-toolbar').hidden = false;
        section.addEventListener('click', event => {
            const button = event.target.closest('.orders-row-toggle');
            if (!button || !section.contains(button)) return;
            setExpanded(button.closest('.order-table-row'), button.getAttribute('aria-expanded') !== 'true');
        });
        function filter() {
            const terms = fold(search.value).trim().split(/\s+/).filter(Boolean);
            let visible = 0;
            rows.forEach(row => {
                const text = fold(row.dataset.orderSearch);
                const match = (!status.value || row.dataset.orderStatus === status.value) && terms.every(term => text.includes(term));
                row.hidden = !match;
                if (!match) setExpanded(row, false);
                if (match) visible++;
            });
            count.textContent = visible === rows.length ? rows.length + ' đơn hàng' : visible + ' / ' + rows.length + ' đơn hàng';
            empty.hidden = visible !== 0;
        }
        search.addEventListener('input', filter); status.addEventListener('change', filter);
        filter();
    }
    window.OHXOrdersTable = {init};
    function start() { document.querySelectorAll('.orders-table-section').forEach(init); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();