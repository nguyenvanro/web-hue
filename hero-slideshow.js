(() => {
    'use strict';
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    async function init(container) {
        if (container.dataset.heroReady) return;
        container.dataset.heroReady = 'true';
        let sources;
        try { sources = JSON.parse(container.dataset.heroImages || '[]'); }
        catch (_) { return; }
        if (container.dataset.heroManifest && location.protocol !== 'file:') {
            try {
                const response = await fetch(container.dataset.heroManifest);
                if (response.ok) {
                    const filenames = await response.json();
                    if (Array.isArray(filenames) && filenames.length >= 2) {
                        const base = new URL(container.dataset.heroManifest, document.baseURI);
                        sources = filenames.map(name => new URL(encodeURIComponent(name), base).href);
                    }
                }
            } catch (_) { /* Giữ ảnh dự phòng nếu chưa có danh sách. */ }
        }
        sources = [...new Set(sources.filter(source => typeof source === 'string' && source))];
        if (sources.length < 2) return;
        const frames = [...container.querySelectorAll('.hero-slide-frame')];
        const initialSources = frames.map(frame => frame.querySelector('.hero-slide-image').src);
        sources = sources.filter(source => !initialSources.includes(new URL(source, document.baseURI).href));
        frames.forEach((frame, frameIndex) => {
            // Chia đều ảnh theo số khung; một khung sẽ dùng toàn bộ danh sách.
            const group = [initialSources[frameIndex], ...sources.filter((_, index) => index % frames.length === frameIndex)];
            let index = 0;
            let current = frame.querySelector('.hero-slide-image');
            let busy = false;
            const cache = new Map();
            function preload(source) {
                if (!cache.has(source)) {
                    const image = new Image();
                    image.decoding = 'async';
                    cache.set(source, new Promise(resolve => {
                        image.onload = async () => {
                            if (image.decode) {
                                try { await image.decode(); } catch (_) { /* Already loaded. */ }
                            }
                            resolve(image);
                        };
                        image.onerror = () => { cache.delete(source); resolve(null); };
                        image.src = source;
                    }));
                }
                return cache.get(source);
            }
            // Start fetching the next slide during the current slide's display time.
            preload(group[1]);
            window.setInterval(async () => {
                if (group.length < 2 || busy || document.hidden || reducedMotion.matches) return;
                busy = true;
                const nextIndex = (index + 1) % group.length;
                const next = await preload(group[nextIndex]);
                if (!next) { index = nextIndex; busy = false; return; }
                next.alt = current.alt;
                next.className = 'hero-slide-image hero-slide-incoming';
                frame.append(next);
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    frame.classList.add('hero-slide-moving');
                    window.setTimeout(() => {
                        current.remove();
                        frame.classList.remove('hero-slide-moving');
                        next.className = 'hero-slide-image';
                        current = next;
                        index = nextIndex;
                        busy = false;
                        preload(group[(index + 1) % group.length]);
                    }, 500);
                }));
            }, 3000);
        });
    }

    function start() { document.querySelectorAll('[data-hero-slideshow]').forEach(init); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
})();
