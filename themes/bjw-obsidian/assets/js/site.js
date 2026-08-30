(function () {
    'use strict';

    /* ------------------------------------------------------------ nav */

    var toggle = document.querySelector('[data-nav-toggle]');
    var mobileNav = document.querySelector('[data-mobile-nav]');
    var header = document.querySelector('[data-header]');

    if (toggle && mobileNav) {
        toggle.addEventListener('click', function () {
            var open = mobileNav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(open));
        });

        mobileNav.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                mobileNav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    if (header) {
        var onScroll = function () {
            header.classList.toggle('is-scrolled', window.scrollY > 12);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* -------------------------------------------------------- reveals */

    var reveals = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window && reveals.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

        Array.prototype.forEach.call(reveals, function (el) { observer.observe(el); });
    } else {
        Array.prototype.forEach.call(reveals, function (el) { el.classList.add('is-visible'); });
    }

    /* ----------------------------------------------------- visualizer */

    var canvas = document.querySelector('[data-visualizer]');
    if (!canvas || !canvas.getContext) { return; }

    var ctx = canvas.getContext('2d');
    var style = getComputedStyle(document.documentElement);
    var colorHot = (style.getPropertyValue('--viz-hot') || '#ffe7a2').trim();
    var colorCool = (style.getPropertyValue('--viz-cool') || 'rgba(200,162,74,0.35)').trim();

    var BAR_W = 4;
    var GAP = 6;
    var SEGMENT = 4;
    var SEGMENT_GAP = 3;

    var bars = [];
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var width = 0;
    var height = 0;
    var count = 0;

    function resize() {
        var rect = canvas.getBoundingClientRect();
        width = rect.width;
        height = rect.height;
        canvas.width = Math.round(width * dpr);
        canvas.height = Math.round(height * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        count = Math.max(12, Math.floor((width + GAP) / (BAR_W + GAP)));
        bars = [];
        for (var i = 0; i < count; i++) {
            bars.push({ value: Math.random() * 0.35 + 0.05, velocity: (Math.random() - 0.5) * 0.02 });
        }
    }

    var time = 0;

    function draw() {
        ctx.clearRect(0, 0, width, height);

        var playing = document.body.classList.contains('is-audio-playing');
        var totalWidth = count * BAR_W + (count - 1) * GAP;
        var startX = (width - totalWidth) / 2;
        var maxHeight = height * 0.88;

        for (var i = 0; i < count; i++) {
            var bar = bars[i];
            bar.value += bar.velocity;

            if (bar.value > 1 || bar.value < 0.04) {
                bar.velocity *= -1;
                bar.velocity += (Math.random() - 0.5) * 0.008;
                bar.velocity = Math.max(-0.03, Math.min(0.03, bar.velocity));
                bar.value = Math.max(0.04, Math.min(1, bar.value));
            }

            var envelope = Math.sin((i / count) * Math.PI);
            var wave = Math.sin(time * 0.045 + i * 0.32) * 0.16;
            var amplitude = (bar.value * 0.55 + wave + 0.16) * envelope;
            amplitude *= playing ? 1 : 0.55;

            var barHeight = Math.max(SEGMENT, Math.min(maxHeight, amplitude * maxHeight));
            var segments = Math.max(1, Math.floor(barHeight / (SEGMENT + SEGMENT_GAP)));
            var x = startX + i * (BAR_W + GAP);

            for (var s = 0; s < segments; s++) {
                var y = height - SEGMENT - s * (SEGMENT + SEGMENT_GAP);
                ctx.fillStyle = s > segments - 3 ? colorHot : colorCool;
                ctx.fillRect(x, y, BAR_W, SEGMENT);
            }
        }

        time++;
        window.requestAnimationFrame(draw);
    }

    resize();
    window.addEventListener('resize', resize);
    window.requestAnimationFrame(draw);
}());
