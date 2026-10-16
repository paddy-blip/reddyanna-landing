/* Reddy Anna — front-end interactions (vanilla, deferred). */
(function () {
	'use strict';

	var doc = document.documentElement;
	doc.classList.add('js');

	var body = document.body;
	var header = document.getElementById('site-header');

	/* ---- Mobile navigation ---- */
	var toggle = document.querySelector('.js-nav-toggle');
	var nav = document.getElementById('primary-nav');

	function setNav(open) {
		if (!toggle || !nav) { return; }
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		nav.classList.toggle('is-open', open);
		body.classList.toggle('nav-open', open);
	}

	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			setNav(toggle.getAttribute('aria-expanded') !== 'true');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) { setNav(false); }
		});
		window.matchMedia('(min-width: 900px)').addEventListener('change', function (mq) {
			if (mq.matches) { setNav(false); }
		});
	}

	/* ---- Search panel ---- */
	var panel = document.getElementById('search-panel');
	var openBtn = document.querySelector('.js-search-open');
	var closeBtn = document.querySelector('.js-search-close');

	function setSearch(open) {
		if (!panel) { return; }
		panel.hidden = !open;
		if (openBtn) { openBtn.setAttribute('aria-expanded', open ? 'true' : 'false'); }
		if (open) {
			var input = panel.querySelector('input[type="search"]');
			if (input) { input.focus(); }
		} else if (openBtn) {
			openBtn.focus();
		}
	}

	if (openBtn) { openBtn.addEventListener('click', function () { setSearch(panel.hidden); }); }
	if (closeBtn) { closeBtn.addEventListener('click', function () { setSearch(false); }); }

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') { return; }
		if (panel && !panel.hidden) { setSearch(false); }
		if (nav && nav.classList.contains('is-open')) { setNav(false); toggle.focus(); }
	});

	/* ---- Header shadow + back-to-top (single rAF-throttled scroll listener) ---- */
	var toTop = document.querySelector('.js-to-top');
	var ticking = false;

	function onScroll() {
		var y = window.scrollY;
		if (header) { header.classList.toggle('is-scrolled', y > 10); }
		if (toTop) { toTop.hidden = y < 600; }
		ticking = false;
	}

	window.addEventListener('scroll', function () {
		if (!ticking) { window.requestAnimationFrame(onScroll); ticking = true; }
	}, { passive: true });
	onScroll();

	if (toTop) {
		toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	}

	/* ---- Reveal + counters (IntersectionObserver) ---- */
	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function countUp(el) {
		var to = parseFloat(el.getAttribute('data-to')) || 0;
		var dur = 1400;
		var start = null;
		var fmt = new Intl.NumberFormat();
		function step(ts) {
			if (!start) { start = ts; }
			var p = Math.min((ts - start) / dur, 1);
			var eased = 1 - Math.pow(1 - p, 3);
			el.textContent = fmt.format(Math.round(to * eased));
			if (p < 1) { window.requestAnimationFrame(step); }
		}
		window.requestAnimationFrame(step);
	}

	if ('IntersectionObserver' in window && !reduced) {
		var targets = document.querySelectorAll('.section:not(.section--hero) .section-head, .feature, .point, .stat, .step, .vm-card, .faq__item, .testimonial, .cta, .post-card, .contact-card, .contact__form');
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) { return; }
				var el = entry.target;
				el.classList.add('is-visible');
				var counter = el.querySelector('.js-count');
				if (counter) { countUp(counter); }
				io.unobserve(el);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });

		targets.forEach(function (el, i) {
			el.classList.add('reveal');
			// Small stagger within a row.
			el.style.transitionDelay = (i % 4) * 70 + 'ms';
			io.observe(el);
		});
	}
})();
