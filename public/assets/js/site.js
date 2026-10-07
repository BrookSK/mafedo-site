/* Mafedo Engenharia — interações do frontend (vanilla JS, sem dependências) */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---- Header: transparente -> sólido ao rolar ---- */
    var header = document.querySelector('.site-header');
    if (header) {
        var onScroll = function () {
            if (window.scrollY > 40) { header.classList.add('is-solid'); }
            else { header.classList.remove('is-solid'); }
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ---- Menu mobile ---- */
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.querySelector('.nav');
    var backdrop = document.getElementById('navBackdrop');
    if (toggle && nav) {
        var setMenu = function (open) {
            nav.classList.toggle('is-open', open);
            toggle.classList.toggle('is-open', open);
            if (backdrop) backdrop.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
            document.body.style.overflow = open ? 'hidden' : '';
        };
        toggle.addEventListener('click', function () {
            setMenu(!nav.classList.contains('is-open'));
        });
        nav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () { setMenu(false); });
        });
        if (backdrop) backdrop.addEventListener('click', function () { setMenu(false); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) setMenu(false);
        });
    }

    /* ---- Reveal on scroll ---- */
    var revealEls = document.querySelectorAll('.reveal');
    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealEls.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(function (el) { io.observe(el); });
    }

    /* ---- Filtro de projetos ---- */
    var filters = document.querySelectorAll('.filter');
    if (filters.length) {
        var cards = document.querySelectorAll('[data-category]');
        filters.forEach(function (f) {
            f.addEventListener('click', function () {
                filters.forEach(function (x) { x.classList.remove('is-active'); });
                f.classList.add('is-active');
                var cat = f.getAttribute('data-filter');
                cards.forEach(function (c) {
                    var show = cat === 'all' || c.getAttribute('data-category') === cat;
                    c.style.display = show ? '' : 'none';
                });
            });
        });
    }

    /* ---- Lightbox da galeria ---- */
    var galleryLinks = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox]'));
    if (galleryLinks.length) {
        var lb = document.createElement('div');
        lb.className = 'lightbox';
        lb.innerHTML =
            '<button class="lightbox__close" aria-label="Fechar">&times;</button>' +
            '<button class="lightbox__nav lightbox__nav--prev" aria-label="Anterior">&#8249;</button>' +
            '<img alt="">' +
            '<button class="lightbox__nav lightbox__nav--next" aria-label="Próxima">&#8250;</button>';
        document.body.appendChild(lb);
        var lbImg = lb.querySelector('img');
        var current = 0;

        var show = function (i) {
            current = (i + galleryLinks.length) % galleryLinks.length;
            lbImg.src = galleryLinks[current].getAttribute('href');
            lbImg.alt = galleryLinks[current].getAttribute('data-alt') || '';
        };
        var open = function (i) { show(i); lb.classList.add('is-open'); document.body.style.overflow = 'hidden'; };
        var close = function () { lb.classList.remove('is-open'); document.body.style.overflow = ''; };

        galleryLinks.forEach(function (a, i) {
            a.addEventListener('click', function (e) { e.preventDefault(); open(i); });
        });
        lb.querySelector('.lightbox__close').addEventListener('click', close);
        lb.querySelector('.lightbox__nav--prev').addEventListener('click', function () { show(current - 1); });
        lb.querySelector('.lightbox__nav--next').addEventListener('click', function () { show(current + 1); });
        lb.addEventListener('click', function (e) { if (e.target === lb) close(); });
        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('is-open')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') show(current - 1);
            if (e.key === 'ArrowRight') show(current + 1);
        });
    }

    /* ---- Cookie banner ---- */
    try {
        var KEY = 'mafedo_cookie_consent';
        if (!localStorage.getItem(KEY)) {
            var banner = document.querySelector('.cookie');
            if (banner) {
                banner.style.display = 'flex';
                banner.querySelectorAll('[data-cookie]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        localStorage.setItem(KEY, btn.getAttribute('data-cookie'));
                        banner.style.display = 'none';
                    });
                });
            }
        }
    } catch (e) { /* localStorage indisponível: ignora */ }
})();
