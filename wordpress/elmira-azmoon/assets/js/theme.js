/* کپی‌شده از elmira-theme/assets/js/theme.js با wordpress/build/vendor.mjs؛ مستقیم ویرایش نکنید. */
/* ==========================================================
   قالب المیرا آزمون — رفتارهای مشترک
   هر رفتار با یک data-attribute فعال می‌شود؛ بدون JS همهٔ محتوا دیده می‌شود.
   ========================================================== */
(function () {
  'use strict';

  /* ---------- تنظیمات ---------- */
  // نوبت‌دهی در سامانهٔ داخلی سالن انجام می‌شود؛ آدرس واقعی را این‌جا بگذارید.
  var BOOKING_URL = (window.elmiraTheme && window.elmiraTheme.bookingUrl) || '#booking-system-url';

  var doc = document, root = doc.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var $ = function (s, c) { return (c || doc).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
  var fa = function (n) { return Number(n).toLocaleString('fa-IR'); };
  var lerp = function (a, b, t) { return a + (b - a) * t; };

  root.classList.add('js');

  /* ---------- لینک سامانهٔ نوبت‌دهی ---------- */
  $$('[data-booking]').forEach(function (a) {
    a.setAttribute('href', BOOKING_URL);
    a.setAttribute('target', '_blank');
    a.setAttribute('rel', 'noopener');
  });

  /* ---------- پرده‌ی ورود و خروج ---------- */
  function ready() { root.classList.add('is-ready'); }
  var readyP = Promise.race([
    Promise.all([
      new Promise(function (r) { if (doc.readyState !== 'loading') r(); else doc.addEventListener('DOMContentLoaded', r); }),
      doc.fonts ? doc.fonts.ready : Promise.resolve()
    ]).then(function () { return new Promise(function (r) { setTimeout(r, 250); }); }),
    new Promise(function (r) { setTimeout(r, 2500); })
  ]);
  readyP.then(ready);

  window.addEventListener('pageshow', function (e) { if (e.persisted) { root.classList.remove('is-leaving'); ready(); } });

  doc.addEventListener('click', function (e) {
    var a = e.target.closest('a[href]');
    if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
    var href = a.getAttribute('href');
    if (a.target === '_blank' || a.hasAttribute('download') || !href || href.charAt(0) === '#' || /^(mailto|tel):/i.test(href) || a.closest('#wpadminbar')) return;
    var u = new URL(a.href, location.href);
    if (u.origin !== location.origin || /\/wp-(admin|login)/.test(u.pathname) || (u.pathname === location.pathname && u.search === location.search && u.hash)) return;
    if (reduce) return;
    e.preventDefault();
    root.classList.remove('is-ready');
    root.classList.add('is-leaving');
    setTimeout(function () { window.location.href = href; }, 480);
  });

  /* ---------- تم روشن/تیره با موج دایره‌ای ---------- */
  function setTheme(mode) {
    root.classList.toggle('dark', mode === 'dark');
    root.classList.toggle('light', mode === 'light');
    try { localStorage.setItem('ea-theme', mode); } catch (e) {}
    $$('[data-theme-toggle]').forEach(function (b) {
      b.setAttribute('aria-label', mode === 'dark' ? 'حالت روشن' : 'حالت تیره');
    });
  }
  $$('[data-theme-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var next = root.classList.contains('dark') ? 'light' : 'dark';
      if (!doc.startViewTransition || reduce) { setTheme(next); return; }
      var x = e.clientX || innerWidth / 2, y = e.clientY || 40;
      var r = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));
      var t = doc.startViewTransition(function () { setTheme(next); });
      t.ready.then(function () {
        root.animate({ clipPath: ['circle(0px at ' + x + 'px ' + y + 'px)', 'circle(' + r + 'px at ' + x + 'px ' + y + 'px)'] },
          { duration: 700, easing: 'cubic-bezier(.16,1,.3,1)', pseudoElement: '::view-transition-new(root)' });
      });
    });
  });

  /* ---------- اسکرول: سربرگ، نوار پیشرفت، برگشت به بالا، نوار رزرو ---------- */
  var header = $('.site-header');
  var bar = $('.scroll-progress span');
  var toTop = $('.to-top');
  var mBook = $('.m-book');
  var heroEl = $('[data-hero]');
  var lastY = scrollY, ticking = false;
  var parallax = $$('[data-parallax]');

  function onScroll() {
    var y = scrollY, h = doc.documentElement.scrollHeight - innerHeight;
    var p = h > 0 ? y / h : 0;
    if (header) {
      header.classList.toggle('is-scrolled', y > 40);
      var drawerOpen = root.classList.contains('drawer-open');
      header.classList.toggle('is-hidden', !drawerOpen && y > 500 && y > lastY + 2);
      if (y < lastY - 2) header.classList.remove('is-hidden');
    }
    if (bar) bar.style.transform = 'scaleX(' + p + ')';
    if (toTop) { toTop.classList.toggle('is-on', y > 700); toTop.style.setProperty('--sp', p); }
    if (mBook) {
      var past = heroEl ? heroEl.getBoundingClientRect().bottom < 0 : y > 500;
      var nearEnd = h - y < 260;
      mBook.classList.toggle('is-on', past && !nearEnd);
    }
    if (!reduce) parallax.forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.bottom < -200 || r.top > innerHeight + 200) return;
      var s = parseFloat(el.dataset.parallax) || .15;
      var c = r.top + r.height / 2 - innerHeight / 2;
      el.style.transform = 'translate3d(0,' + (-c * s).toFixed(1) + 'px,0)';
    });
    lastY = y; ticking = false;
  }
  addEventListener('scroll', function () { if (!ticking) { requestAnimationFrame(onScroll); ticking = true; } }, { passive: true });
  addEventListener('resize', onScroll);
  onScroll();
  if (toTop) toTop.addEventListener('click', function () { scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }); });

  /* ---------- تیتر کلمه‌به‌کلمه ---------- */
  $$('[data-split]').forEach(function (el) {
    var i = 0;
    function walk(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (n) {
        if (n.nodeType === 3) {
          var frag = doc.createDocumentFragment();
          n.textContent.split(/(\s+)/).forEach(function (part) {
            if (!part) return;
            if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(part)); return; }
            var w = doc.createElement('span'); w.className = 'w';
            var inner = doc.createElement('span'); inner.textContent = part; inner.style.setProperty('--i', i++);
            w.appendChild(inner); frag.appendChild(w);
          });
          n.parentNode.replaceChild(frag, n);
        } else if (n.nodeType === 1 && n.tagName !== 'BR') { walk(n); }
      });
    }
    el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
    walk(el);
    $$('.w', el).forEach(function (w) { w.setAttribute('aria-hidden', 'true'); });
    el.classList.add('split');
  });

  /* ---------- شمارنده ---------- */
  function countUp(el) {
    var to = parseFloat(el.dataset.count), dec = (el.dataset.count.split('.')[1] || '').length;
    var pre = el.dataset.prefix || '', suf = el.dataset.suffix || '';
    if (reduce) { el.textContent = pre + fa(to.toFixed(dec)) + suf; return; }
    var t0 = performance.now(), dur = 1800;
    (function step(t) {
      var k = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - k, 4);
      el.textContent = pre + Number((to * e).toFixed(dec)).toLocaleString('fa-IR', { minimumFractionDigits: dec }) + suf;
      if (k < 1) requestAnimationFrame(step);
    })(t0);
  }

  /* ---------- ورود به دید ---------- */
  var revealables = $$('[data-reveal], .split, .progress, [data-count]');
  if ('IntersectionObserver' in window) {
    // عنصری که با clip-path صفر شروع می‌شود هیچ‌وقت «دیده» نمی‌شود؛ والدش را زیر نظر می‌گیریم
    var proxy = new Map();
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = proxy.get(en.target) || en.target;
        el.classList.add('is-in');
        if (el.dataset.count) countUp(el);
        io.unobserve(en.target);
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0 });
    // عناصر بالای صفحه بعد از کنار رفتن پرده وارد می‌شوند
    readyP.then(function () {
      setTimeout(function () {
        revealables.forEach(function (el) {
          var r = el.getAttribute('data-reveal') || '';
          var target = r.indexOf('clip') === 0 && el.parentElement ? el.parentElement : el;
          // چیزی که قبل از فعال شدن ناظر از بالای صفحه رد شده، فوراً نمایان شود
          if (target.getBoundingClientRect().bottom < 0) { el.classList.add('is-in'); if (el.dataset.count) countUp(el); return; }
          if (target !== el) proxy.set(target, el);
          io.observe(target);
        });
      }, 200);
    });
  } else {
    revealables.forEach(function (el) { el.classList.add('is-in'); if (el.dataset.count) countUp(el); });
  }

  /* ---------- نشانگر سفارشی ---------- */
  if (fine && !reduce) {
    var dot = doc.createElement('div'); dot.className = 'cursor-dot';
    var ring = doc.createElement('div'); ring.className = 'cursor-ring'; ring.innerHTML = '<span>مشاهده</span>';
    doc.body.appendChild(dot); doc.body.appendChild(ring);
    var mx = -100, my = -100, rx = -100, ry = -100;
    addEventListener('mousemove', function (e) {
      mx = e.clientX; my = e.clientY;
      root.classList.add('has-cursor');
      dot.style.transform = 'translate(' + mx + 'px,' + my + 'px)';
    }, { passive: true });
    doc.addEventListener('mouseleave', function () { root.classList.remove('has-cursor'); });
    (function loop() {
      rx = lerp(rx, mx, .18); ry = lerp(ry, my, .18);
      ring.style.transform = 'translate(' + rx + 'px,' + ry + 'px)';
      requestAnimationFrame(loop);
    })();
    doc.addEventListener('mouseover', function (e) {
      var v = e.target.closest('[data-cursor]');
      var l = e.target.closest('a, button, label, select, input, [role="tab"]');
      ring.classList.toggle('is-view', !!v);
      if (v) ring.firstChild.textContent = v.dataset.cursor || 'مشاهده';
      ring.classList.toggle('is-link', !v && !!l);
    });
    addEventListener('mousedown', function () { ring.classList.add('is-down'); });
    addEventListener('mouseup', function () { ring.classList.remove('is-down'); });
  }

  /* ---------- دکمه‌ی مغناطیسی ---------- */
  if (fine && !reduce) $$('[data-magnetic]').forEach(function (el) {
    var s = parseFloat(el.dataset.magnetic) || .3;
    el.addEventListener('mousemove', function (e) {
      var r = el.getBoundingClientRect();
      el.style.transform = 'translate(' + ((e.clientX - r.left - r.width / 2) * s) + 'px,' + ((e.clientY - r.top - r.height / 2) * s) + 'px)';
    });
    el.addEventListener('mouseleave', function () { el.style.transform = ''; });
  });

  /* ---------- کج‌شدن سه‌بعدی ---------- */
  if (fine && !reduce) $$('[data-tilt]').forEach(function (el) {
    var max = parseFloat(el.dataset.tilt) || 6;
    el.classList.add('tilt');
    var glare = doc.createElement('span'); glare.className = 'tilt-glare'; el.appendChild(glare);
    el.addEventListener('mousemove', function (e) {
      var r = el.getBoundingClientRect(), px = (e.clientX - r.left) / r.width, py = (e.clientY - r.top) / r.height;
      el.style.transform = 'perspective(900px) rotateX(' + ((.5 - py) * max) + 'deg) rotateY(' + ((px - .5) * max) + 'deg) translateY(-4px)';
      el.style.setProperty('--gx', px * 100 + '%'); el.style.setProperty('--gy', py * 100 + '%');
    });
    el.addEventListener('mouseleave', function () { el.style.transform = ''; });
  });

  /* ---------- نور دنبال‌کننده روی بنرها ---------- */
  if (fine) $$('.spotlight').forEach(function (el) {
    el.addEventListener('mousemove', function (e) {
      var r = el.getBoundingClientRect();
      el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      el.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* ---------- نوار متحرک ---------- */
  $$('[data-marquee]').forEach(function (m) {
    var track = $('.marquee-track', m);
    if (!track) return;
    var clone = track.cloneNode(true); clone.setAttribute('aria-hidden', 'true');
    m.appendChild(clone);
  });

  /* ---------- آکاردئون ---------- */
  $$('[data-acc]').forEach(function (group) {
    var single = group.dataset.acc !== 'multi';
    $$('.acc-item', group).forEach(function (item, idx) {
      var btn = $('.acc-btn', item), panel = $('.acc-panel', item);
      var id = (group.id || 'acc') + '-' + idx;
      panel.id = id; btn.setAttribute('aria-controls', id);
      btn.setAttribute('aria-expanded', item.classList.contains('is-open'));
      btn.addEventListener('click', function () {
        var open = !item.classList.contains('is-open');
        if (single) $$('.acc-item', group).forEach(function (o) { o.classList.remove('is-open'); $('.acc-btn', o).setAttribute('aria-expanded', 'false'); });
        item.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open);
      });
    });
  });

  /* ---------- تب‌ها با خط لغزان ---------- */
  $$('[data-tabs]').forEach(function (wrap) {
    var list = $('.tabs', wrap), tabs = $$('[role="tab"]', wrap);
    var ink = doc.createElement('span'); ink.className = 'tab-ink'; list.appendChild(ink);
    function move(t) { ink.style.left = t.offsetLeft + 'px'; ink.style.width = t.offsetWidth + 'px'; }
    function select(t, focus) {
      tabs.forEach(function (o) {
        var on = o === t;
        o.setAttribute('aria-selected', on); o.tabIndex = on ? 0 : -1;
        doc.getElementById(o.getAttribute('aria-controls')).hidden = !on;
      });
      move(t); if (focus) t.focus();
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(t); });
      t.addEventListener('keydown', function (e) {
        var k = e.key === 'ArrowLeft' ? 1 : e.key === 'ArrowRight' ? -1 : 0;
        if (k) { e.preventDefault(); select(tabs[(i + k + tabs.length) % tabs.length], true); }
      });
    });
    var cur = tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0] || tabs[0];
    select(cur);
    addEventListener('resize', function () { move(tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0]); });
    if (doc.fonts) doc.fonts.ready.then(function () { select(tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0]); });
  });

  /* ---------- فیلتر با چیپ ---------- */
  $$('[data-filter]').forEach(function (group) {
    var target = doc.getElementById(group.dataset.filter);
    if (!target) return;
    var chips = $$('.chip', group);
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        chips.forEach(function (o) { o.setAttribute('aria-pressed', o === c); });
        var cat = c.dataset.cat;
        $$('[data-cat]', target).forEach(function (it) {
          var show = cat === 'all' || it.dataset.cat.split(' ').indexOf(cat) > -1;
          if (show) { it.classList.remove('is-gone'); requestAnimationFrame(function () { it.classList.remove('is-hidden'); }); }
          else { it.classList.add('is-hidden'); setTimeout(function () { if (it.classList.contains('is-hidden')) it.classList.add('is-gone'); }, 450); }
        });
      });
    });
  });

  /* ---------- کشوها (منو، سبد) ---------- */
  var overlay = $('.overlay'), openDrawer = null, lastFocus = null;
  function closeDrawers() {
    if (!openDrawer) return;
    openDrawer.classList.remove('is-open'); openDrawer.setAttribute('aria-hidden', 'true');
    overlay && overlay.classList.remove('is-open');
    root.classList.remove('drawer-open'); doc.body.style.overflow = '';
    $$('[data-open="' + openDrawer.id + '"]').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    openDrawer = null; if (lastFocus) lastFocus.focus();
  }
  $$('[data-open]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var d = doc.getElementById(btn.dataset.open); if (!d) return;
      e.preventDefault(); closeDrawers();
      lastFocus = btn; openDrawer = d;
      d.classList.add('is-open'); d.setAttribute('aria-hidden', 'false');
      overlay && overlay.classList.add('is-open');
      root.classList.add('drawer-open'); doc.body.style.overflow = 'hidden';
      btn.setAttribute('aria-expanded', 'true');
      setTimeout(function () { var f = $('[data-close]', d); f && f.focus(); }, 60);
    });
  });
  $$('[data-close]').forEach(function (b) { b.addEventListener('click', closeDrawers); });
  overlay && overlay.addEventListener('click', closeDrawers);
  doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeDrawers(); closeLightbox(); } });

  /* ---------- اعلان ---------- */
  var toastBox = $('.toasts');
  function toast(msg, icon) {
    if (!toastBox) return;
    var t = doc.createElement('div'); t.className = 'toast'; t.setAttribute('role', 'status');
    t.innerHTML = '<i class="ph-light ' + (icon || 'ph-check-circle') + '" aria-hidden="true"></i><span></span>';
    t.lastChild.textContent = msg; toastBox.appendChild(t);
    setTimeout(function () { t.classList.add('is-out'); setTimeout(function () { t.remove(); }, 400); }, 2800);
  }
  window.eaToast = toast;

  /* ---------- افزودن به سبد با پرواز تصویر ---------- */
  var countEls = $$('.cart-count');
  function bumpCart(n) {
    countEls.forEach(function (c) {
      var v = (parseInt(c.dataset.n || '0', 10)) + n; c.dataset.n = v; c.textContent = fa(v);
      c.classList.remove('bump'); void c.offsetWidth; c.classList.add('bump');
    });
  }
  $$('[data-add]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var card = btn.closest('[data-product]') || doc.body;
      var img = $('img', card);
      if (!img || !img.getBoundingClientRect().width) img = $('[data-main]') || img;
      var cart = $$('.cart-target').filter(function (c) { return c.offsetParent; })[0];
      var qtyEl = $('.qty output', card), n = qtyEl ? parseInt(qtyEl.dataset.v || '1', 10) : 1;
      if (img && cart && !reduce) {
        var a = img.getBoundingClientRect(), b = cart.getBoundingClientRect();
        var f = img.cloneNode(); f.className = 'fly'; f.removeAttribute('loading');
        f.style.left = (a.left + a.width / 2 - 27) + 'px'; f.style.top = (a.top + a.height / 2 - 27) + 'px';
        doc.body.appendChild(f);
        var dx = b.left + b.width / 2 - (a.left + a.width / 2), dy = b.top + b.height / 2 - (a.top + a.height / 2);
        f.animate([
          { transform: 'translate(0,0) scale(1)', opacity: 1 },
          { transform: 'translate(' + dx * .5 + 'px,' + (dy - 120) + 'px) scale(.8)', opacity: 1, offset: .5 },
          { transform: 'translate(' + dx + 'px,' + dy + 'px) scale(.2)', opacity: .3 }
        ], { duration: 800, easing: 'cubic-bezier(.5,0,.3,1)' }).onfinish = function () { f.remove(); bumpCart(n); };
      } else { bumpCart(n); }
      var name = btn.dataset.add || 'محصول';
      toast(name + ' به سبد اضافه شد', 'ph-handbag');
      var label = $('.add-label', btn);
      if (label) { var old = label.textContent; label.textContent = 'اضافه شد ✓'; setTimeout(function () { label.textContent = old; }, 1600); }
    });
  });

  // حذف از سبد کوچک یا صفحهٔ سبد
  doc.addEventListener('click', function (e) {
    var rm = e.target.closest('[data-remove]'); if (!rm) return;
    var row = rm.closest('[data-line]'); if (!row) return;
    row.classList.add('is-removing');
    setTimeout(function () { row.remove(); bumpCart(-1); recalc(); toast('از سبد حذف شد', 'ph-trash'); }, 350);
  });

  /* ---------- علاقه‌مندی ---------- */
  $$('.heart').forEach(function (h) {
    h.addEventListener('click', function (e) {
      e.preventDefault(); e.stopPropagation();
      var on = h.getAttribute('aria-pressed') !== 'true';
      h.setAttribute('aria-pressed', on);
      toast(on ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد', on ? 'ph-heart' : 'ph-heart-break');
    });
  });

  /* ---------- شمارندهٔ تعداد و محاسبهٔ سبد ---------- */
  function recalc() {
    var box = $('[data-cart]'); if (!box) return;
    var sum = 0, items = 0;
    $$('[data-line]', box).forEach(function (row) {
      var q = parseInt($('.qty output', row).dataset.v, 10), p = parseInt(row.dataset.price, 10);
      items += q; sum += q * p;
      var lt = $('[data-line-total]', row); if (lt) lt.textContent = fa(q * p);
    });
    var disc = parseInt(box.dataset.discount || '0', 10), ship = sum >= 1000000 ? 0 : parseInt(box.dataset.ship || '0', 10);
    var set = function (k, v) { $$('[data-sum="' + k + '"]').forEach(function (el) { el.textContent = v; }); };
    set('items', fa(items)); set('sub', fa(sum)); set('ship', ship ? fa(ship) + ' ت' : 'رایگان'); set('total', fa(Math.max(0, sum - disc + ship)));
    var left = Math.max(0, 1000000 - sum), fb = $('[data-free]');
    if (fb) {
      $('[data-free-left]', fb).textContent = fa(left);
      $('.progress span', fb).style.width = Math.min(100, sum / 10000) + '%';
      fb.classList.toggle('is-free', left === 0);
    }
    var empty = $('[data-empty]'), full = $('[data-full]');
    if (empty && full) { var none = !$$('[data-line]', box).length; empty.hidden = !none; full.hidden = none; }
  }
  $$('.qty').forEach(function (q) {
    var out = $('output', q), min = parseInt(q.dataset.min || '1', 10), max = parseInt(q.dataset.max || '20', 10);
    out.dataset.v = out.dataset.v || '1';
    $$('button', q).forEach(function (b) {
      b.addEventListener('click', function () {
        var v = parseInt(out.dataset.v, 10) + (b.dataset.step === '-' ? -1 : 1);
        if (v < min || v > max) { q.animate([{ transform: 'translateX(-4px)' }, { transform: 'translateX(4px)' }, { transform: 'none' }], 250); return; }
        out.dataset.v = v; out.textContent = fa(v);
        out.classList.remove('tick'); void out.offsetWidth; out.classList.add('tick');
        recalc();
      });
    });
  });
  recalc();

  // کد تخفیف نمایشی
  $$('[data-coupon]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault();
      var inp = $('input', f);
      if (!inp.value.trim()) { inp.classList.remove('is-invalid'); void inp.offsetWidth; inp.classList.add('is-invalid'); return; }
      inp.classList.remove('is-invalid'); toast('کد تخفیف بررسی شد (نمایشی)', 'ph-ticket');
    });
  });

  /* ---------- قبل/بعد ---------- */
  $$('.ba').forEach(function (ba) {
    var r = $('input', ba);
    var set = function () { ba.style.setProperty('--pos', (100 - r.value) + '%'); };
    r.addEventListener('input', set); set();
  });

  /* ---------- لایت‌باکس ---------- */
  var lb = $('.lightbox'), lbImg, lbCap, lbItems = [], lbIdx = 0;
  function showLb(i) {
    var vis = lbItems.filter(function (x) { return !x.closest('.is-gone'); });
    if (!vis.length) return;
    lbIdx = (i + vis.length) % vis.length;
    var src = vis[lbIdx].dataset.full || $('img', vis[lbIdx]).src;
    lbImg.src = src; lbImg.alt = $('img', vis[lbIdx]).alt; lbCap.textContent = vis[lbIdx].dataset.caption || lbImg.alt;
  }
  function closeLightbox() { if (lb && lb.classList.contains('is-open')) { lb.classList.remove('is-open'); doc.body.style.overflow = ''; } }
  if (lb) {
    lbImg = $('img', lb); lbCap = $('.lb-cap', lb);
    lbItems = $$('[data-lightbox]');
    lbItems.forEach(function (it) {
      it.addEventListener('click', function (e) {
        e.preventDefault();
        var vis = lbItems.filter(function (x) { return !x.closest('.is-gone'); });
        showLb(vis.indexOf(it)); lb.classList.add('is-open'); doc.body.style.overflow = 'hidden'; $('.lb-close', lb).focus();
      });
    });
    $('.lb-close', lb).addEventListener('click', closeLightbox);
    $('.lb-next', lb).addEventListener('click', function () { showLb(lbIdx + 1); });
    $('.lb-prev', lb).addEventListener('click', function () { showLb(lbIdx - 1); });
    lb.addEventListener('click', function (e) { if (e.target === lb) closeLightbox(); });
    doc.addEventListener('keydown', function (e) {
      if (!lb.classList.contains('is-open')) return;
      if (e.key === 'ArrowLeft') showLb(lbIdx + 1);
      if (e.key === 'ArrowRight') showLb(lbIdx - 1);
    });
  }

  /* ---------- اسلایدر افقی ---------- */
  $$('[data-carousel]').forEach(function (wrap) {
    var track = $('[data-track]', wrap); if (!track) return;
    var step = function () { var c = track.firstElementChild; return c ? c.getBoundingClientRect().width + 20 : 300; };
    var prev = $('[data-prev]', wrap), next = $('[data-next]', wrap);
    next && next.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: reduce ? 'auto' : 'smooth' }); });
    prev && prev.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: reduce ? 'auto' : 'smooth' }); });
    // کشیدن با ماوس
    var down = false, sx = 0, sl = 0, moved = false;
    track.addEventListener('pointerdown', function (e) { if (e.pointerType !== 'mouse') return; down = true; moved = false; sx = e.clientX; sl = track.scrollLeft; track.style.scrollSnapType = 'none'; });
    addEventListener('pointermove', function (e) { if (!down) return; var d = e.clientX - sx; if (Math.abs(d) > 4) moved = true; track.scrollLeft = sl - d; });
    addEventListener('pointerup', function () { if (!down) return; down = false; track.style.scrollSnapType = ''; });
    track.addEventListener('click', function (e) { if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; } }, true);
  });

  /* ---------- چرخش متن (نکته‌ها) ---------- */
  $$('[data-rotator]').forEach(function (w) {
    var items = JSON.parse(w.dataset.rotator), out = $('[data-rot-out]', w), i = 0, timer;
    function show(k) {
      i = (k + items.length) % items.length;
      out.animate([{ opacity: 1, transform: 'none' }, { opacity: 0, transform: 'translateY(-8px)' }], { duration: reduce ? 0 : 200 }).onfinish = function () {
        out.innerHTML = '<strong>' + items[i][0] + '</strong> ' + items[i][1];
        out.animate([{ opacity: 0, transform: 'translateY(8px)' }, { opacity: 1, transform: 'none' }], { duration: reduce ? 0 : 300, easing: 'ease-out' });
      };
    }
    function auto() { clearInterval(timer); if (!reduce) timer = setInterval(function () { show(i + 1); }, 6000); }
    $('[data-rot-next]', w).addEventListener('click', function () { show(i + 1); auto(); });
    $('[data-rot-prev]', w).addEventListener('click', function () { show(i - 1); auto(); });
    auto();
  });

  /* ---------- لغزندهٔ قیمت ---------- */
  $$('.range').forEach(function (r) {
    var out = r.dataset.out ? doc.getElementById(r.dataset.out) : null;
    var set = function () {
      var p = (r.value - r.min) / (r.max - r.min) * 100;
      r.style.setProperty('--p', p + '%');
      if (out) out.textContent = fa(r.value);
    };
    r.addEventListener('input', set); set();
  });

  /* ---------- بارگذاری فایل با پیش‌نمایش ---------- */
  $$('.drop').forEach(function (d) {
    var inp = $('input', d);
    ['dragenter', 'dragover'].forEach(function (ev) { d.addEventListener(ev, function () { d.classList.add('is-over'); }); });
    ['dragleave', 'drop'].forEach(function (ev) { d.addEventListener(ev, function () { d.classList.remove('is-over'); }); });
    inp.addEventListener('change', function () {
      var f = inp.files[0]; if (!f) return;
      if (f.size > 2 * 1024 * 1024) { toast('حجم فایل بیشتر از ۲ مگابایت است', 'ph-warning'); inp.value = ''; return; }
      d.classList.add('has-file');
      var old = $('img.preview', d); old && old.remove();
      if (/^image\//.test(f.type)) { var im = doc.createElement('img'); im.className = 'preview'; im.alt = ''; im.src = URL.createObjectURL(f); d.appendChild(im); }
    });
  });

  /* ---------- کد یکبار مصرف ---------- */
  $$('.otp').forEach(function (box) {
    var ins = $$('input', box);
    function check() {
      var full = ins.every(function (i) { return i.value; });
      box.classList.toggle('is-done', full);
      var btn = box.dataset.submit && doc.getElementById(box.dataset.submit);
      if (btn) btn.classList.toggle('is-disabled', !full);
    }
    ins.forEach(function (inp, i) {
      inp.addEventListener('input', function () {
        inp.value = inp.value.replace(/[^\d۰-۹]/g, '').slice(-1);
        inp.classList.toggle('filled', !!inp.value);
        if (inp.value && ins[i + 1]) ins[i + 1].focus();
        check();
      });
      inp.addEventListener('keydown', function (e) { if (e.key === 'Backspace' && !inp.value && ins[i - 1]) ins[i - 1].focus(); });
      inp.addEventListener('paste', function (e) {
        var t = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, ins.length);
        if (!t) return; e.preventDefault();
        t.split('').forEach(function (ch, k) { ins[k].value = ch; ins[k].classList.add('filled'); });
        ins[Math.min(t.length, ins.length - 1)].focus(); check();
      });
    });
    check();
  });

  /* ---------- شمارش معکوس ---------- */
  $$('[data-countdown]').forEach(function (el) {
    var s = parseInt(el.dataset.countdown, 10), btn = el.dataset.resend && doc.getElementById(el.dataset.resend);
    function tick() {
      var mm = String(Math.floor(Math.max(s, 0) / 60)).padStart(2, '0'), ss = String(Math.max(s, 0) % 60).padStart(2, '0');
      el.textContent = (mm + ':' + ss).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; });
      if (s-- <= 0) { clearInterval(tm); if (btn) btn.hidden = false; el.parentNode.hidden = true; }
    }
    var tm;
    // فقط وقتی دیده شد شروع شود (مثلاً مرحلهٔ دوم فرم ورود)
    if ('IntersectionObserver' in window) {
      var cio = new IntersectionObserver(function (en) { if (en[0].isIntersecting) { cio.disconnect(); tm = setInterval(tick, 1000); tick(); } });
      cio.observe(el);
    } else { tm = setInterval(tick, 1000); tick(); }
  });

  /* ---------- فرم‌ها: اعتبارسنجی و حالت موفق ---------- */
  $$('form[data-validate]').forEach(function (form) {
    form.setAttribute('novalidate', '');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var bad = null;
      $$('[required]', form).forEach(function (f) {
        var ok = f.type === 'checkbox' ? f.checked : (f.pattern ? new RegExp('^' + f.pattern + '$').test(f.value.trim()) : !!f.value.trim());
        if (f.type === 'radio') ok = !!$('input[name="' + f.name + '"]:checked', form);
        var target = f.type === 'checkbox' || f.type === 'radio' ? f.closest('label') : f;
        target.classList.remove('is-invalid'); void target.offsetWidth;
        if (!ok) { target.classList.add('is-invalid'); bad = bad || f; }
      });
      if (bad) { bad.focus(); toast('لطفاً فیلدهای ضروری را کامل کنید', 'ph-warning-circle'); return; }
      var btn = $('[type="submit"]', form);
      btn && btn.classList.add('is-loading');
      setTimeout(function () {
        btn && btn.classList.remove('is-loading');
        var next = form.dataset.next;
        if (next) {
          var nx = doc.getElementById(next);
          form.hidden = true; nx.hidden = false;
          var f = $('input, button, a', nx); f && f.focus();
          if (nx.dataset.scroll !== 'no') nx.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'center' });
          nx.animate([{ opacity: 0, transform: 'translateY(14px)' }, { opacity: 1, transform: 'none' }], { duration: 500, easing: 'cubic-bezier(.16,1,.3,1)' });
        } else {
          toast(form.dataset.ok || 'ارسال شد', 'ph-check-circle');
          form.reset();
          $$('.filled', form).forEach(function (x) { x.classList.remove('filled'); });
        }
      }, 1100);
    });
    $$('[required]', form).forEach(function (f) {
      f.addEventListener('input', function () { (f.type === 'checkbox' ? f.closest('label') : f).classList.remove('is-invalid'); });
    });
  });
  $$('[data-back]').forEach(function (b) {
    b.addEventListener('click', function () {
      var cur = b.closest('[data-step]'), prev = doc.getElementById(b.dataset.back);
      cur.hidden = true; prev.hidden = false;
    });
  });

  /* ---------- دکمهٔ اشتراک ---------- */
  $$('[data-share]').forEach(function (b) {
    b.addEventListener('click', function () {
      if (navigator.share) { navigator.share({ title: doc.title, url: location.href }).catch(function () {}); return; }
      if (navigator.clipboard) navigator.clipboard.writeText(location.href).then(function () { toast('پیوند کپی شد', 'ph-link'); });
    });
  });

  /* ---------- گالری تصاویر محصول/خدمت ---------- */
  $$('[data-thumbs]').forEach(function (g) {
    var main = $('[data-main]', g);
    $$('[data-thumb]', g).forEach(function (t) {
      t.addEventListener('click', function () {
        $$('[data-thumb]', g).forEach(function (o) { o.setAttribute('aria-pressed', o === t); });
        main.animate([{ opacity: 1 }, { opacity: 0 }], { duration: reduce ? 0 : 180 }).onfinish = function () {
          main.src = t.dataset.thumb; main.alt = $('img', t).alt;
          main.animate([{ opacity: 0, transform: 'scale(1.03)' }, { opacity: 1, transform: 'none' }], { duration: reduce ? 0 : 450, easing: 'ease-out' });
        };
      });
    });
    // بزرگ‌نمایی با ماوس
    if (fine) {
      var box = main.parentNode;
      box.addEventListener('mousemove', function (e) {
        var r = box.getBoundingClientRect();
        main.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
        main.style.transform = 'scale(1.6)';
      });
      box.addEventListener('mouseleave', function () { main.style.transform = ''; });
    }
  });

  /* ---------- انتخاب گزینه‌های محصول (حجم) ---------- */
  $$('[data-variants]').forEach(function (g) {
    var price = doc.getElementById(g.dataset.variants);
    $$('input', g).forEach(function (i) {
      i.addEventListener('change', function () {
        if (!price) return;
        price.textContent = fa(i.dataset.price);
        price.animate([{ transform: 'translateY(-6px)', opacity: 0 }, { transform: 'none', opacity: 1 }], { duration: reduce ? 0 : 350, easing: 'ease-out' });
      });
    });
  });

  /* ---------- فهرست مطالب با دنبال‌کردن بخش فعال ---------- */
  $$('[data-spy]').forEach(function (nav) {
    var links = $$('a[href^="#"]', nav);
    var map = links.map(function (a) { return [a, doc.getElementById(a.getAttribute('href').slice(1))]; }).filter(function (x) { return x[1]; });
    if (!map.length) return;
    // فعال = آخرین تیتری که از ۳۰٪ بالای صفحه رد شده (با پرش ناگهانی هم درست کار می‌کند)
    var busy = false;
    function update() {
      var line = innerHeight * .3, cur = map[0];
      map.forEach(function (m) { if (m[1].getBoundingClientRect().top <= line) cur = m; });
      map.forEach(function (m) { m[0].classList.toggle('is-active', m === cur); });
      busy = false;
    }
    addEventListener('scroll', function () { if (!busy) { busy = true; requestAnimationFrame(update); } }, { passive: true });
    update();
  });

  /* ---------- مرتب‌سازی نمایشی ---------- */
  $$('[data-sort]').forEach(function (s) {
    s.addEventListener('change', function () {
      var grid = doc.getElementById(s.dataset.sort); if (!grid) return;
      var items = $$('[data-price]', grid);
      items.sort(function (a, b) {
        var pa = +a.dataset.price, pb = +b.dataset.price;
        return s.value === 'asc' ? pa - pb : s.value === 'desc' ? pb - pa : (+a.dataset.order || 0) - (+b.dataset.order || 0);
      });
      items.forEach(function (it, k) {
        grid.appendChild(it);
        if (!reduce) it.animate([{ opacity: 0, transform: 'translateY(14px)' }, { opacity: 1, transform: 'none' }], { duration: 450, delay: k * 40, easing: 'cubic-bezier(.16,1,.3,1)', fill: 'backwards' });
      });
    });
  });
})();
