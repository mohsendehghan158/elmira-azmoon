/* ==========================================================
   وایرفریم سالن زیبایی المیرا آزمون
   هدر/فوتر مشترک + نوار ابزار پرزنت
   ========================================================== */
(function () {
  'use strict';

  /* فهرست کامل صفحات — ترتیب همین‌جا ترتیب دکمه‌های بعدی/قبلی است */
  var PAGES = [
    { f: 'sitemap.html',         t: 'نقشه سایت',              g: 'عمومی' },
    { f: 'index.html',           t: 'صفحه اصلی',              g: 'بخش ۱ — سالن' },
    { f: 'booking.html',         t: 'خدمات و نوبت‌دهی',        g: 'بخش ۱ — سالن' },
    { f: 'service-single.html',  t: 'جزئیات خدمت',            g: 'بخش ۱ — سالن' },
    { f: 'booking-confirm.html', t: 'تایید نوبت',             g: 'بخش ۱ — سالن' },
    { f: 'gallery.html',         t: 'گالری نمونه‌کار',         g: 'بخش ۱ — سالن' },
    { f: 'shop.html',            t: 'فروشگاه',                g: 'بخش ۲ — فروشگاه' },
    { f: 'product-single.html',  t: 'جزئیات محصول',           g: 'بخش ۲ — فروشگاه' },
    { f: 'cart.html',            t: 'سبد خرید',               g: 'بخش ۲ — فروشگاه' },
    { f: 'checkout.html',        t: 'تسویه حساب',             g: 'بخش ۲ — فروشگاه' },
    { f: 'academy.html',         t: 'آموزشگاه',               g: 'بخش ۳ — آموزشگاه' },
    { f: 'course-single.html',   t: 'جزئیات دوره',            g: 'بخش ۳ — آموزشگاه' },
    { f: 'enroll.html',          t: 'ثبت‌نام دوره',            g: 'بخش ۳ — آموزشگاه' },
    { f: 'blog.html',            t: 'مجله زیبایی',            g: 'عمومی' },
    { f: 'blog-single.html',     t: 'مقاله',                  g: 'عمومی' },
    { f: 'about.html',           t: 'درباره ما',              g: 'عمومی' },
    { f: 'contact.html',         t: 'تماس با ما',             g: 'عمومی' },
    { f: 'auth.html',            t: 'ورود / ثبت‌نام',          g: 'حساب کاربری' },
    { f: 'account.html',         t: 'پنل کاربری',             g: 'حساب کاربری' },
    { f: 'mobile.html',          t: 'نمای موبایل',            g: 'عمومی' }
  ];

  /* منوی اصلی سایت */
  var NAV = [
    { f: 'index.html',   t: 'خانه' },
    { f: 'booking.html', t: 'خدمات و نوبت‌دهی' },
    { f: 'shop.html',    t: 'فروشگاه' },
    { f: 'academy.html', t: 'آموزشگاه' },
    { f: 'gallery.html', t: 'گالری' },
    { f: 'blog.html',    t: 'مجله' },
    { f: 'about.html',   t: 'درباره ما' },
    { f: 'contact.html', t: 'تماس' }
  ];

  var ICON_CART = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h18l-1.5 12.5a2 2 0 0 1-2 1.5H6.5a2 2 0 0 1-2-1.5L3 7Z"/><path d="M8 7V5a4 4 0 0 1 8 0v2"/></svg>';
  var ICON_USER = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>';

  var here = (location.pathname.split('/').pop() || 'index.html');
  var body = document.body;
  /* گروه منوی فعال؛ مثلا در صفحه جزئیات خدمت هم «خدمات» فعال بماند */
  var activeNav = body.getAttribute('data-nav') || here;

  function headerHTML() {
    var links = NAV.map(function (n) {
      return '<a href="' + n.f + '"' + (n.f === activeNav ? ' class="active"' : '') + '>' + n.t + '</a>';
    }).join('');

    return '' +
      '<header class="site-header">' +
        '<div class="brand">' +
          '<div class="brand-mark">لوگو</div>' +
          '<a href="index.html" class="brand-name">المیرا آزمون</a>' +
        '</div>' +
        '<nav class="nav">' + links + '</nav>' +
        '<div class="header-tools">' +
          '<a href="account.html" class="cart-btn" title="حساب کاربری">' + ICON_USER + '</a>' +
          '<a href="cart.html" class="cart-btn" title="سبد خرید">' + ICON_CART +
            '<span class="cart-count">۲</span>' +
          '</a>' +
          '<a href="booking.html"><button class="btn">رزرو نوبت</button></a>' +
        '</div>' +
      '</header>';
  }

  function footerHTML() {
    return '' +
      '<footer class="site-footer">' +
        '<div class="col g14">' +
          '<div class="ft-brand">المیرا آزمون</div>' +
          '<div class="bars" style="width:80%">' +
            '<div class="bar dark"></div><div class="bar dark"></div>' +
          '</div>' +
          '<div class="social"><i></i><i></i><i></i></div>' +
        '</div>' +
        '<div class="col g10">' +
          '<div class="ft-title">سالن</div>' +
          '<a href="booking.html">خدمات</a>' +
          '<a href="booking.html">رزرو نوبت</a>' +
          '<a href="gallery.html">گالری نمونه‌کار</a>' +
        '</div>' +
        '<div class="col g10">' +
          '<div class="ft-title">فروشگاه و آموزش</div>' +
          '<a href="shop.html">فروشگاه محصولات</a>' +
          '<a href="academy.html">دوره‌های آموزشی</a>' +
          '<a href="blog.html">مجله زیبایی</a>' +
        '</div>' +
        '<div class="col g10">' +
          '<div class="ft-title">تماس با ما</div>' +
          '<div class="sm">[ شماره تماس ]</div>' +
          '<div class="sm">[ آدرس سالن ]</div>' +
          '<div class="sm">[ ساعات کاری ]</div>' +
        '</div>' +
        '<div class="copy">' +
          '<span>© تمامی حقوق برای سالن المیرا آزمون محفوظ است.</span>' +
          '<span>[ نماد اعتماد الکترونیکی ]</span>' +
        '</div>' +
      '</footer>';
  }

  function toolbarHTML() {
    var idx = -1;
    PAGES.forEach(function (p, i) { if (p.f === here) idx = i; });

    var opts = '', lastGroup = '';
    PAGES.forEach(function (p) {
      if (p.g !== lastGroup) {
        if (lastGroup) opts += '</optgroup>';
        opts += '<optgroup label="' + p.g + '">';
        lastGroup = p.g;
      }
      opts += '<option value="' + p.f + '"' + (p.f === here ? ' selected' : '') + '>' + p.t + '</option>';
    });
    if (lastGroup) opts += '</optgroup>';

    var prev = idx > 0 ? PAGES[idx - 1] : null;
    var next = idx > -1 && idx < PAGES.length - 1 ? PAGES[idx + 1] : null;
    var pos  = idx > -1 ? (idx + 1) + ' از ' + PAGES.length : '';

    return '' +
      '<div class="wf-bar">' +
        '<div class="wf-group">' +
          '<b>وایرفریم المیرا آزمون</b>' +
          '<span style="color:#8a8785">' + pos + '</span>' +
        '</div>' +
        '<div class="wf-group">' +
          '<button class="wf-btn" id="wf-prev"' + (prev ? '' : ' disabled style="opacity:.35"') + '>→ قبلی</button>' +
          '<select class="wf-sel" id="wf-sel">' + opts + '</select>' +
          '<button class="wf-btn" id="wf-next"' + (next ? '' : ' disabled style="opacity:.35"') + '>بعدی ←</button>' +
        '</div>' +
        '<div class="wf-group">' +
          '<button class="wf-btn" id="wf-notes">نمایش توضیحات</button>' +
          '<a href="sitemap.html"><button class="wf-btn">نقشه سایت</button></a>' +
        '</div>' +
      '</div>';
  }

  function init() {
    var wrap = document.querySelector('.wrap');
    if (wrap && !body.hasAttribute('data-no-chrome')) {
      wrap.insertAdjacentHTML('afterbegin', headerHTML());
      wrap.insertAdjacentHTML('beforeend', footerHTML());
    }

    body.insertAdjacentHTML('beforeend', toolbarHTML());

    var idx = -1;
    PAGES.forEach(function (p, i) { if (p.f === here) idx = i; });

    var prevBtn = document.getElementById('wf-prev');
    var nextBtn = document.getElementById('wf-next');
    var sel     = document.getElementById('wf-sel');
    var notes   = document.getElementById('wf-notes');

    if (prevBtn) prevBtn.onclick = function () { if (idx > 0) location.href = PAGES[idx - 1].f; };
    if (nextBtn) nextBtn.onclick = function () { if (idx > -1 && idx < PAGES.length - 1) location.href = PAGES[idx + 1].f; };
    if (sel)     sel.onchange    = function () { location.href = sel.value; };

    /* حالت نمایش توضیحات بین صفحات حفظ می‌شود */
    var on = false;
    try { on = localStorage.getItem('wf-notes') === '1'; } catch (e) {}
    apply(on);

    if (notes) notes.onclick = function () {
      on = !on;
      apply(on);
      try { localStorage.setItem('wf-notes', on ? '1' : '0'); } catch (e) {}
    };

    function apply(v) {
      body.classList.toggle('show-notes', v);
      if (notes) {
        notes.textContent = v ? 'پنهان کردن توضیحات' : 'نمایش توضیحات';
        notes.classList.toggle('on', v);
      }
    }

    /* جابجایی با کلیدهای جهت‌دار در جلسه */
    document.addEventListener('keydown', function (e) {
      if (e.target && /INPUT|TEXTAREA|SELECT/.test(e.target.tagName)) return;
      if (e.key === 'ArrowLeft'  && nextBtn && !nextBtn.disabled) nextBtn.click();
      if (e.key === 'ArrowRight' && prevBtn && !prevBtn.disabled) prevBtn.click();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
