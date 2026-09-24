/* ==========================================================
   المیرا آزمون — رفتارِ صفحهٔ اصلی
   سه کار: ورقِ روشن/تیره، ریلِ چسبان، و برقِ مینا هنگام ورودِ تابلو.
   هیچ‌کدام شرطِ دیدنِ محتوا نیستند؛ بدون جاوااسکریپت هم صفحه کامل است.
   ========================================================== */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  /* ---------- ورقِ مینا: روشن یا تیره ---------- */
  var THEME_KEY = 'ea-theme';

  function readTheme() {
    try { return localStorage.getItem(THEME_KEY); } catch (e) { return null; }
  }

  function currentTheme() {
    var saved = readTheme();
    if (saved === 'light' || saved === 'dark') return saved;
    return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
  }

  function applyTheme(mode) {
    root.setAttribute('data-theme', mode);
    var btn = document.getElementById('theme-toggle');
    if (!btn) return;
    var toDark = mode === 'light';
    btn.setAttribute('aria-label', toDark ? 'تغییر به حالت تیره' : 'تغییر به حالت روشن');
    btn.innerHTML = '<i class="ph ' + (toDark ? 'ph-moon' : 'ph-sun') + '" aria-hidden="true"></i>';
  }

  applyTheme(currentTheme());

  var toggle = document.getElementById('theme-toggle');
  if (toggle) {
    toggle.addEventListener('click', function () {
      var next = currentTheme() === 'light' ? 'dark' : 'light';
      try { localStorage.setItem(THEME_KEY, next); } catch (e) {}
      applyTheme(next);
    });
  }

  /* ---------- ریلِ بالا: وقتی صفحه راه افتاد، سایه بگیرد ---------- */
  var railbar = document.querySelector('.railbar');
  var sentinel = document.getElementById('top-sentinel');
  if (railbar && sentinel && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      railbar.classList.toggle('stuck', !entries[0].isIntersecting);
    }, { threshold: 0 }).observe(sentinel);
  }

  /* ---------- برقِ مینا: یک بار، هنگام ورودِ هر تابلو ---------- */
  var plaques = document.querySelectorAll('.reveal');
  if (!plaques.length) return;

  if (reduce.matches || !('IntersectionObserver' in window)) {
    Array.prototype.forEach.call(plaques, function (el) { el.classList.add('is-in'); });
  } else {
    var seen = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-delay') || '0', 10);
        window.setTimeout(function () { el.classList.add('is-in'); }, delay);
        obs.unobserve(el);
      });
    }, { threshold: 0, rootMargin: '0px 0px -10% 0px' });

    Array.prototype.forEach.call(plaques, function (el) { seen.observe(el); });
  }

  /* ---------- منوی موبایل ---------- */
  var sheet = document.getElementById('menu-sheet');
  var openBtn = document.getElementById('menu-open');
  var closeBtn = document.getElementById('menu-close');

  function setMenu(open) {
    if (!sheet || !openBtn) return;
    sheet.setAttribute('data-open', open ? 'true' : 'false');
    openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
    if (open && closeBtn) closeBtn.focus();
    else if (!open) openBtn.focus();
  }

  if (openBtn) openBtn.addEventListener('click', function () { setMenu(true); });
  if (closeBtn) closeBtn.addEventListener('click', function () { setMenu(false); });
  if (sheet) {
    sheet.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') setMenu(false);
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && sheet && sheet.getAttribute('data-open') === 'true') setMenu(false);
  });
})();
