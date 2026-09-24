/* ==========================================================
   المیرا آزمون — رفتار طراحی سوم صفحهٔ اصلی
   منوی موبایل، نکته‌های چرخشی، اسلایدر نظرات، نوار رزرو موبایل و ورود به دید.
   بدون جاوااسکریپت هم همهٔ محتوا دیده می‌شود.
   ========================================================== */
(function () {
  'use strict';

  var hasIO = 'IntersectionObserver' in window;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- منوی موبایل ---------- */
  var drawer = document.getElementById('drawer');
  var openBtn = document.getElementById('menu-open');
  var closeBtn = document.getElementById('menu-close');

  function openDrawer() {
    drawer.hidden = false;
    openBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }
  function closeDrawer() {
    drawer.hidden = true;
    openBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    openBtn.focus();
  }
  if (drawer && openBtn && closeBtn) {
    openBtn.addEventListener('click', openDrawer);
    closeBtn.addEventListener('click', closeDrawer);
    drawer.addEventListener('click', function (e) { if (e.target === drawer) closeDrawer(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !drawer.hidden) closeDrawer();
    });
  }

  /* ---------- نکته‌های زیبایی ---------- */
  var tips = [
    ['ضدآفتاب:', 'حتی در روزهای ابری هر دو تا سه ساعت یک‌بار تمدید کنید.'],
    ['رنگ مو:', 'تا ۴۸ ساعت بعد از رنگ، مو را نشویید تا رنگ بهتر تثبیت شود.'],
    ['برس آرایش:', 'هفته‌ای یک‌بار با شامپوی ملایم بشویید و به‌صورت خوابیده خشک کنید.'],
    ['بعد از فیشیال:', 'تا یک روز از لایه‌بردار و آرایش سنگین دوری کنید.'],
    ['ناخن:', 'روغن کوتیکول را شب‌ها بزنید تا مانیکور دیرتر از بین برود.']
  ];
  var tipEl = document.getElementById('tip');
  var tipIdx = 0;
  function showTip(step) {
    tipIdx = (tipIdx + step + tips.length) % tips.length;
    var t = tips[tipIdx];
    tipEl.style.opacity = 0;
    setTimeout(function () {
      tipEl.innerHTML = '<strong>' + t[0] + '</strong> ' + t[1];
      tipEl.style.opacity = 1;
    }, reduce ? 0 : 200);
  }
  if (tipEl) {
    document.getElementById('tip-prev').addEventListener('click', function () { showTip(-1); });
    document.getElementById('tip-next').addEventListener('click', function () { showTip(1); });
  }

  /* ---------- اسلایدر نظرات ---------- */
  var track = document.getElementById('rev-track');
  function slide(dir) {
    var card = track.querySelector('.rev');
    var step = card ? card.getBoundingClientRect().width + 18 : 300;
    // در RTL، پیشروی یعنی اسکرول به سمت چپ (مقدار منفی)
    track.scrollBy({ left: -dir * step, behavior: reduce ? 'auto' : 'smooth' });
  }
  if (track) {
    document.getElementById('rev-next').addEventListener('click', function () { slide(1); });
    document.getElementById('rev-prev').addEventListener('click', function () { slide(-1); });
  }

  /* ---------- نوار رزرو موبایل ---------- */
  var mobileBook = document.getElementById('mobile-book');
  var hero = document.querySelector('.hero-wrap');
  var footer = document.querySelector('.footer');
  if (hasIO && hero && mobileBook) {
    var heroGone = false, footerVisible = false;
    var update = function () { mobileBook.classList.toggle('show', heroGone && !footerVisible); };
    new IntersectionObserver(function (en) { heroGone = !en[0].isIntersecting; update(); }).observe(hero);
    if (footer) new IntersectionObserver(function (en) { footerVisible = en[0].isIntersecting; update(); }).observe(footer);
  }

  /* ---------- ورود به دید ---------- */
  var items = document.querySelectorAll('.reveal');
  if (!hasIO || reduce || !items.length) return;

  document.documentElement.classList.add('js');
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-in');
      io.unobserve(entry.target);
    });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
  Array.prototype.forEach.call(items, function (el) { io.observe(el); });
})();
