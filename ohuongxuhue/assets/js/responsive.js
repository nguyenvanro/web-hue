/* Shared navigation for the PHP theme and the static preview. */
(function () {
    'use strict';

    function initNavigation() {
        document.querySelectorAll('.main-nav').forEach(function (nav, navIndex) {
            let wrapper = nav.querySelector('.nav-wrapper');
            const list = nav.querySelector('.nav-container');
            if (!list || nav.hasAttribute('data-responsive-nav')) return;
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'nav-wrapper';
                while (nav.firstChild) wrapper.append(nav.firstChild);
                nav.append(wrapper);
            }

            const compact = window.matchMedia('(max-width: 1100px)');
            const toggle = document.createElement('button');
            list.id = list.id || 'site-navigation-' + navIndex;
            toggle.type = 'button';
            toggle.className = 'mobile-nav-toggle';
            toggle.setAttribute('aria-controls', list.id);
            toggle.setAttribute('aria-expanded', 'false');
            toggle.innerHTML = '<span class="mobile-nav-icon" aria-hidden="true">☰</span><span>Danh mục &amp; Menu</span>';
            wrapper.prepend(toggle);
            nav.setAttribute('data-responsive-nav', '');
            nav.setAttribute('aria-label', 'Điều hướng chính');

            function fitMenuHeight() {
                if (compact.matches && nav.classList.contains('is-open')) {
                    nav.style.setProperty('--nav-menu-offset', Math.max(0, list.getBoundingClientRect().top) + 12 + 'px');
                }
            }

            function closeNavigation(restoreFocus) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.firstElementChild.textContent = '☰';
                if (restoreFocus) toggle.focus();
            }

            toggle.addEventListener('click', function () {
                const open = toggle.getAttribute('aria-expanded') !== 'true';
                nav.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', String(open));
                toggle.firstElementChild.textContent = open ? '×' : '☰';
                fitMenuHeight();
            });

            const branches = [];
            list.querySelectorAll('li').forEach(function (item, index) {
                const submenu = Array.from(item.children).find(function (child) {
                    return child.matches('.nav-dropdown-mega, .nav-blog-submenu, .flyout-submenu');
                });
                const link = Array.from(item.children).find(function (child) { return child.tagName === 'A'; });
                if (!submenu || !link) return;
                submenu.id = submenu.id || 'site-submenu-' + navIndex + '-' + index;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'mobile-submenu-toggle';
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-controls', submenu.id);
                const label = link.textContent.replace(/[▾›]/g, '').trim();
                button.setAttribute('aria-label', 'Mở danh mục ' + label);
                button.innerHTML = '<span aria-hidden="true">+</span>';
                link.after(button);
                item.classList.add('responsive-nav-branch');
                branches.push({ item: item, button: button, submenu: submenu, label: label });
                button.addEventListener('click', function () {
                    const open = button.getAttribute('aria-expanded') !== 'true';
                    item.classList.toggle('submenu-open', open);
                    button.setAttribute('aria-expanded', String(open));
                    button.setAttribute('aria-label', (open ? 'Đóng danh mục ' : 'Mở danh mục ') + label);
                    button.firstElementChild.textContent = open ? '−' : '+';
                    fitMenuHeight();
                });
            });

            nav.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape' || !compact.matches) return;
                const branch = event.target.closest('.submenu-open');
                if (branch) {
                    const entry = branches.find(function (entry) { return entry.item === branch; });
                    entry.button.click();
                    entry.button.focus();
                } else closeNavigation(true);
            });
            document.addEventListener('click', function (event) {
                if (compact.matches && !nav.contains(event.target)) closeNavigation(false);
            });
            nav.addEventListener('focusout', function () {
                // Keep hidden mobile links out of the keyboard tab order when focus leaves.
                setTimeout(function () {
                    if (compact.matches && !nav.contains(document.activeElement)) closeNavigation(false);
                }, 0);
            });
            compact.addEventListener('change', function () {
                closeNavigation(false);
                branches.forEach(function (entry) {
                    entry.item.classList.remove('submenu-open');
                    entry.button.setAttribute('aria-expanded', 'false');
                    entry.button.setAttribute('aria-label', 'Mở danh mục ' + entry.label);
                    entry.button.firstElementChild.textContent = '+';
                });
            });
            window.addEventListener('resize', fitMenuHeight);
            window.addEventListener('scroll', fitMenuHeight, { passive: true });
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initNavigation);
    else initNavigation();
}());
