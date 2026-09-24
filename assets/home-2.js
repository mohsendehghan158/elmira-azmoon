/* ==========================================================
   المیرا آزمون — رفتار طراحی دوم صفحهٔ اصلی
   منوی موبایل، سایهٔ سربرگ، تاریخ‌های شمسی رزرو سریع، نوار رزرو موبایل، و ورود به دید.
   بدون جاوااسکریپت هم همهٔ محتوا دیده می‌شود.
   ========================================================== */
(function () {
  'use strict';

  var root = document.documentElement;
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

  /* ---------- تاریخ‌های شمسی هفت روز آینده ---------- */
  var dateSelect = document.getElementById('qb-date');
  if (dateSelect && window.Intl) {
    try {
      var fmt = new Intl.DateTimeFormat('fa-IR-u-ca-persian', { weekday: 'long', day: 'numeric', month: 'long' });
      var names = ['امروز', 'فردا'];
      var html = '<option value="">انتخاب کنید</option>';
      for (var i = 0; i < 7; i++) {
        var d = new Date();
        d.setDate(d.getDate() + i);
        var label = fmt.format(d);
        if (names[i]) label = names[i] + ' — ' + label;
        html += '<option value="' + i + '">' + label + '</option>';
      }
      dateSelect.innerHTML = html;
    } catch (e) { /* گزینه‌های ثابت HTML می‌مانند */ }
  }

  /* ---------- سایهٔ سربرگ + نوار رزرو موبایل ---------- */
  var topbar = document.getElementById('topbar');
  var mobileBook = document.getElementById('mobile-book');
  var hero = document.querySelector('.hero');
  var quick = document.getElementById('quick-book');
  var footer = document.querySelector('.footer');

  if (hasIO && hero) {
    var heroGone = false, quickVisible = false, footerVisible = false;
    var update = function () {
      if (topbar) topbar.classList.toggle('stuck', heroGone);
      if (mobileBook) mobileBook.classList.toggle('show', heroGone && !quickVisible && !footerVisible);
    };
    new IntersectionObserver(function (en) { heroGone = !en[0].isIntersecting; update(); }, { rootMargin: '-80px 0px 0px 0px' }).observe(hero);
    if (quick) new IntersectionObserver(function (en) { quickVisible = en[0].isIntersecting; update(); }).observe(quick);
    if (footer) new IntersectionObserver(function (en) { footerVisible = en[0].isIntersecting; update(); }).observe(footer);
  }

  /* ---------- ورود به دید ---------- */
  var items = document.querySelectorAll('.reveal');
  if (!hasIO || reduce || !items.length) return;

  root.classList.add('js');
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-in');
      io.unobserve(entry.target);
    });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
  Array.prototype.forEach.call(items, function (el) { io.observe(el); });
})();
