import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('rv-ready');
    const NAVBAR_OFFSET = 72;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    // ─── Smooth scroll untuk anchor links ───
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href');
            if (!targetId || targetId === '#') return;
            const targetEl = document.querySelector(targetId);
            if (!targetEl) return;

            e.preventDefault();
            const top = targetEl.getBoundingClientRect().top + window.scrollY - NAVBAR_OFFSET;

            window.scrollTo({
                top: Math.max(0, top),
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });

            // Tutup mobile menu jika terbuka (khusus navbar utama)
            const nav = document.getElementById('main-nav');
            if (nav && typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(nav);
                if (alpineData && typeof alpineData.open !== 'undefined') {
                    alpineData.open = false;
                }
            }
        });
    });

    // ─── Scroll reveal dengan animasi smooth ───
    const revealEls = document.querySelectorAll('.rv');
    if (revealEls.length) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.dataset.rvDelay || 0;
                        setTimeout(() => {
                            entry.target.classList.add('rv-visible');
                        }, prefersReducedMotion ? 0 : parseInt(delay, 10));
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
        );
        revealEls.forEach((el) => observer.observe(el));
    }

    // ─── Parallax hero (background bergerak halus saat scroll) ───
    const hero = document.getElementById('hero');
    if (hero && !prefersReducedMotion) {
        const parallaxLayers = hero.querySelectorAll('.parallax-layer');
        const handleScroll = () => {
            const scrolled = window.scrollY;
            const heroHeight = hero.offsetHeight;
            if (scrolled < heroHeight) {
                parallaxLayers.forEach((layer, i) => {
                    const speed = parseFloat(layer.dataset.speed) || 0.15;
                    const y = scrolled * speed * (i % 2 === 0 ? 1 : -1);
                    layer.style.transform = `translate3d(0, ${y}px, 0)`;
                });
            }
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
    }

    // ─── Hero stats counter ───
    document.querySelectorAll('[data-counter]').forEach((el) => {
        const target = el.getAttribute('data-counter');
        if (!target) return;
        const end = parseInt(String(target).replace(/\D/g, ''), 10);
        if (Number.isNaN(end)) return;

        let started = false;
        const obs = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && !started) {
                        started = true;
                        let current = 0;
                        const duration = 1400;
                        const startTime = performance.now();
                        const step = (now) => {
                            const progress = Math.min((now - startTime) / duration, 1);
                            const easeOut = 1 - Math.pow(1 - progress, 3);
                            current = Math.floor(easeOut * end);
                            el.textContent = current.toLocaleString('id-ID');
                            if (progress < 1) requestAnimationFrame(step);
                            else el.textContent = target;
                        };
                        requestAnimationFrame(step);
                    }
                });
            },
            { threshold: 0.3 }
        );
        obs.observe(el);
    });

    // ─── Hover tilt pada cards (hanya di desktop, non-touch) ───
    if (!isTouch && !prefersReducedMotion) {
        const addTilt = (selector) => {
            document.querySelectorAll(selector).forEach((card) => {
                card.style.transition = 'transform 0.4s cubic-bezier(0.23, 1, 0.32, 1)';
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;
                    const rotateY = x * 8;
                    const rotateX = -y * 6;
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px) scale(1.02)`;
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0) scale(1)';
                });
            });
        };
        addTilt('#events article, #why-join .rounded-3xl, #articles article, #gallery .group');
    }

    // ─── Smooth transisi untuk tombol & link ───
    document.querySelectorAll('a, button').forEach((el) => {
        if (!el.style.transition) {
            el.style.transition = 'color 0.2s ease, background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease';
        }
    });
});
