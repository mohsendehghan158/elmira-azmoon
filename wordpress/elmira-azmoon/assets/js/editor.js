/* ==========================================================
   قالب المیرا آزمون — بلاک‌های ویرایشگر
   بدون مرحلهٔ build؛ فقط از wp.* سراسری استفاده می‌کند.
   ========================================================== */
(function (wp) {
  'use strict';

  var el = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var useState = wp.element.useState;
  var registerBlockType = wp.blocks.registerBlockType;
  var be = wp.blockEditor;
  var c = wp.components;
  var SSR = wp.serverSideRender;
  var useSelect = wp.data.useSelect;

  /* ---------- ابزارهای کمکی ---------- */
  var TAG_LABELS = {
    section: 'بخش', div: 'جعبه', p: 'پاراگراف', span: 'متن', a: 'پیوند', img: 'تصویر', button: 'دکمه',
    h1: 'تیتر ۱', h2: 'تیتر ۲', h3: 'تیتر ۳', h4: 'تیتر ۴', ul: 'فهرست', ol: 'فهرست شماره‌دار', li: 'آیتم فهرست',
    figure: 'شکل', figcaption: 'زیرنویس', form: 'فرم', label: 'برچسب', input: 'فیلد', select: 'فهرست کشویی',
    textarea: 'متن چندخطی', nav: 'ناوبری', article: 'مقاله', aside: 'کناری', blockquote: 'نقل‌قول', strong: 'پررنگ',
    small: 'متن کوچک', svg: 'گرافیک', dl: 'فهرست تعریف', dt: 'عنوان', dd: 'توضیح', fieldset: 'گروه فیلد',
    legend: 'عنوان گروه', header: 'سربرگ', footer: 'پانویس', main: 'محتوای اصلی'
  };

  function snippet(html, n) {
    var t = String(html || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
    return t.length > n ? t.slice(0, n) + '…' : t;
  }

  function parseStyle(str) {
    var o = {};
    String(str || '').split(';').forEach(function (d) {
      var i = d.indexOf(':');
      if (i < 0) return;
      var k = d.slice(0, i).trim(), v = d.slice(i + 1).trim();
      if (!k) return;
      o[k.indexOf('--') === 0 ? k : k.replace(/-([a-z])/g, function (m, ch) { return ch.toUpperCase(); })] = v;
    });
    return o;
  }

  // ویژگی‌های HTML → props ری‌اکت (فقط برای نمایش در ویرایشگر)
  var PROP = {
    'for': 'htmlFor', tabindex: 'tabIndex', readonly: 'readOnly', maxlength: 'maxLength', minlength: 'minLength',
    autocomplete: 'autoComplete', inputmode: 'inputMode', colspan: 'colSpan', rowspan: 'rowSpan', enctype: 'encType',
    novalidate: 'noValidate', crossorigin: 'crossOrigin', srcset: 'srcSet', datetime: 'dateTime', spellcheck: 'spellCheck',
    autofocus: 'autoFocus', value: 'defaultValue', checked: 'defaultChecked', fetchpriority: null, selected: null,
    id: null /* شناسه در ویرایشگر مال خود بلاک است */
  };
  var BOOL = ['required', 'disabled', 'readOnly', 'multiple', 'hidden', 'open', 'noValidate', 'defaultChecked', 'autoFocus'];

  function toProps(attrs) {
    var p = {};
    Object.keys(attrs || {}).forEach(function (k) {
      var v = attrs[k];
      if (/^on/i.test(k)) return;
      if (k === 'style') { p.style = parseStyle(v); return; }
      var n = Object.prototype.hasOwnProperty.call(PROP, k) ? PROP[k] : k;
      if (n === null) return;
      if (BOOL.indexOf(n) > -1) v = true;
      p[n] = v;
    });
    return p;
  }

  function stop(e) { e.preventDefault(); }

  function rawSpan(html) {
    return el('span', { className: 'ea-raw', dangerouslySetInnerHTML: { __html: html } });
  }

  /* ==========================================================
     elmira/el — عنصر طراحی
     ========================================================== */
  function ElEdit(props) {
    var a = props.attributes;
    var set = props.setAttributes;
    var tag = a.tag || 'div';
    var mode = a.mode || 'blocks';
    var attrs = a.attrs || {};
    var jsonState = useState(null);

    function setAttr(k, v) {
      var next = Object.assign({}, attrs);
      if (v === undefined || v === null || v === false) delete next[k]; else next[k] = v;
      set({ attrs: next });
    }

    // در ویرایشگر دکمه‌ها span می‌شوند تا تایپ درونشان ممکن باشد
    var etag = tag === 'button' ? 'span' : tag;
    var own = toProps(attrs);
    own['data-ea-tag'] = tag;
    if (tag === 'a') own.onClick = stop;
    if (tag === 'form') own.onSubmit = stop;
    var hasChildren = useSelect(function (select) {
      return select('core/block-editor').getBlockCount(props.clientId) > 0;
    }, [props.clientId]);

    var blockProps = be.useBlockProps(own);

    /* ---- تنظیمات کناری ---- */
    var panels = [];
    if (tag === 'a' || attrs.href !== undefined) {
      panels.push(el(c.PanelBody, { key: 'link', title: 'پیوند', initialOpen: true },
        attrs['data-booking'] !== undefined
          ? el(c.Notice, { status: 'info', isDismissible: false }, 'این دکمه به سامانهٔ نوبت‌دهی می‌رود (نمایش ← المیرا آزمون).')
          : el(be.URLInput || c.TextControl, {
            label: 'نشانی', value: attrs.href || '', className: 'ea-url-input',
            onChange: function (v) { setAttr('href', typeof v === 'string' ? v : (v && v.url) || ''); }
          }),
        el(c.ToggleControl, {
          label: 'باز شدن در تب جدید', checked: attrs.target === '_blank',
          onChange: function (v) { set({ attrs: Object.assign({}, attrs, v ? { target: '_blank', rel: 'noopener' } : { target: undefined, rel: undefined }) }); }
        })
      ));
    }
    if (tag === 'img') {
      panels.push(el(c.PanelBody, { key: 'img', title: 'تصویر', initialOpen: true },
        el(c.TextareaControl, { label: 'متن جایگزین (alt)', value: attrs.alt || '', onChange: function (v) { setAttr('alt', v); } }),
        el(c.TextControl, { label: 'نشانی تصویر', value: attrs.src || '', onChange: function (v) { setAttr('src', v); } })
      ));
    }
    if (tag === 'input' || tag === 'textarea') {
      panels.push(el(c.PanelBody, { key: 'field', title: 'فیلد', initialOpen: true },
        el(c.TextControl, { label: 'متن راهنما (placeholder)', value: attrs.placeholder || '', onChange: function (v) { setAttr('placeholder', v || undefined); } }),
        el(c.ToggleControl, { label: 'اجباری', checked: attrs.required !== undefined, onChange: function (v) { setAttr('required', v ? '' : undefined); } })
      ));
    }
    if (mode === 'html') {
      panels.push(el(c.PanelBody, { key: 'html', title: 'محتوای HTML', initialOpen: true },
        el(c.TextareaControl, { value: a.html || '', rows: 8, className: 'ea-code', onChange: function (v) { set({ html: v }); } })
      ));
    }
    panels.push(el(c.PanelBody, { key: 'adv', title: 'پیشرفته (HTML)', initialOpen: false },
      el(c.TextControl, { label: 'تگ', value: tag, className: 'ea-code', onChange: function (v) { set({ tag: v.replace(/[^a-z0-9-]/gi, '').toLowerCase() || 'div' }); } }),
      el(c.TextControl, { label: 'شناسه (id)', value: attrs.id || '', className: 'ea-code', onChange: function (v) { setAttr('id', v || undefined); } }),
      mode === 'text' && el(c.TextareaControl, { label: 'پیش از متن (HTML)', value: a.before || '', className: 'ea-code', onChange: function (v) { set({ before: v }); } }),
      mode === 'text' && el(c.TextareaControl, { label: 'پس از متن (HTML)', value: a.after || '', className: 'ea-code', onChange: function (v) { set({ after: v }); } }),
      el(c.TextareaControl, {
        label: 'ویژگی‌ها (JSON)', rows: 6, className: 'ea-code',
        help: 'data-reveal، data-count، aria-label و ... . تغییر پس از خروج از کادر اعمال می‌شود.',
        value: jsonState[0] !== null ? jsonState[0] : JSON.stringify(attrs, null, 1),
        onChange: function (v) { jsonState[1](v); },
        onBlur: function () {
          if (jsonState[0] === null) return;
          try { set({ attrs: JSON.parse(jsonState[0]) }); jsonState[1](null); } catch (e) { /* JSON نامعتبر: همان متن می‌ماند */ }
        }
      })
    ));
    var inspector = el(be.InspectorControls, { key: 'insp' }, panels);

    var toolbar = null;
    if (tag === 'img') {
      toolbar = el(be.BlockControls, { key: 'tb', group: 'other' },
        el(be.MediaReplaceFlow, {
          mediaURL: attrs.src, allowedTypes: ['image'], accept: 'image/*', name: 'جایگزینی',
          onSelect: function (m) {
            var next = Object.assign({}, attrs, { src: m.url });
            if (m.alt) next.alt = m.alt;
            delete next.srcset;
            set({ attrs: next, mediaId: m.id });
          },
          onSelectURL: function (url) { set({ attrs: Object.assign({}, attrs, { src: url }), mediaId: undefined }); }
        })
      );
    }

    /* ---- بدنه ---- */
    var body;
    if (mode === 'void' || tag === 'img' || tag === 'input' || tag === 'br' || tag === 'hr') {
      body = el(etag, blockProps);
    } else if (mode === 'text') {
      var rtProps = {
        identifier: 'content', value: a.content, placeholder: 'بنویسید…',
        onChange: function (v) { set({ content: v }); },
        withoutInteractiveFormatting: tag === 'a' || tag === 'button'
      };
      if (!a.before && !a.after) {
        body = el(be.RichText, Object.assign({}, blockProps, rtProps, { tagName: etag }));
      } else {
        body = el(etag, blockProps,
          a.before ? rawSpan(a.before) : null,
          el(be.RichText, Object.assign({}, rtProps, { tagName: 'span' })),
          a.after ? rawSpan(a.after) : null
        );
      }
    } else if (mode === 'html') {
      if (tag === 'textarea') body = el('textarea', Object.assign({}, blockProps, { defaultValue: a.html, readOnly: true }));
      else body = el(etag, Object.assign({}, blockProps, { dangerouslySetInnerHTML: { __html: a.html || '' } }));
    } else {
      var inner = be.useInnerBlocksProps(blockProps, {
        renderAppender: hasChildren ? false : be.InnerBlocks.ButtonBlockAppender
      });
      body = el(etag, inner);
    }
    return el(Fragment, null, inspector, toolbar, body);
  }

  registerBlockType('elmira/el', {
    edit: ElEdit,
    save: function () { return el(be.InnerBlocks.Content); },
    __experimentalLabel: function (a, ctx) {
      if (a.metadata && a.metadata.name) return a.metadata.name;
      var label = TAG_LABELS[a.tag || 'div'] || (a.tag || 'div');
      if (ctx && ctx.context === 'accessibility') return label;
      var s = snippet(a.content || (a.attrs && (a.attrs.alt || a.attrs['aria-label'] || a.attrs.placeholder)) || '', 28);
      return s ? label + ': ' + s : label;
    }
  });

  /* ==========================================================
     بلاک‌های پویا: پیش‌نمایش سروری + تنظیمات خودکار
     ========================================================== */
  var LABELS = {
    bookingLabel: 'متن دکمهٔ رزرو (خالی = بدون دکمه)', showCart: 'نمایش سبد خرید', showAccount: 'نمایش حساب کاربری',
    title: 'عنوان (خالی = خودکار)', sub: 'زیرعنوان (خالی = چکیده)', outline: 'متن توخالی پس‌زمینه',
    variant: 'نوع', layout: 'چیدمان', count: 'تعداد', inherit: 'فهرست همین صفحه (بایگانی، جستجو) با صفحه‌بندی',
    category: 'نامک دسته (اختیاری)', showExcerpt: 'نمایش خلاصه', gridClass: 'کلاس‌های شبکه',
    allLabel: 'برچسب «همه»', label: 'برچسب', placeholder: 'متن راهنما'
  };
  var OPTIONS = {
    variant: [['auto', 'خودکار'], ['post', 'نوشته (نویسنده، تاریخ، اشتراک‌گذاری)']],
    layout: [['grid', 'شبکهٔ کارت‌ها'], ['featured', 'مقالهٔ ویژه (سنجاق‌شده)']]
  };
  var SKIP = ['metadata', 'lock', 'className', 'style', 'anchor'];

  function DynEdit(name) {
    return function (props) {
      var type = wp.blocks.getBlockType(name);
      var a = props.attributes;
      var postId = useSelect(function (select) {
        var ed = select('core/editor');
        var id = ed && ed.getCurrentPostId && ed.getCurrentPostId();
        return typeof id === 'number' ? id : 0;
      }, []);
      var controls = Object.keys(type.attributes).filter(function (k) { return SKIP.indexOf(k) < 0; }).map(function (k) {
        var def = type.attributes[k];
        var common = { key: k, label: LABELS[k] || k };
        if (OPTIONS[k]) {
          return el(c.SelectControl, Object.assign(common, {
            value: a[k], options: OPTIONS[k].map(function (o) { return { value: o[0], label: o[1] }; }),
            onChange: function (v) { var o = {}; o[k] = v; props.setAttributes(o); }
          }));
        }
        if (def.type === 'boolean') {
          return el(c.ToggleControl, Object.assign(common, {
            checked: !!a[k], onChange: function (v) { var o = {}; o[k] = v; props.setAttributes(o); }
          }));
        }
        if (def.type === 'number') {
          return el(c.RangeControl, Object.assign(common, {
            value: a[k], min: 1, max: 12, onChange: function (v) { var o = {}; o[k] = v; props.setAttributes(o); }
          }));
        }
        return el(c.TextControl, Object.assign(common, {
          value: a[k] || '', onChange: function (v) { var o = {}; o[k] = v; props.setAttributes(o); }
        }));
      });
      var sent = {};
      Object.keys(type.attributes).forEach(function (k) { if (SKIP.indexOf(k) < 0) sent[k] = a[k]; });
      return el('div', be.useBlockProps({ className: 'ea-ssr' }),
        controls.length ? el(be.InspectorControls, null, el(c.PanelBody, { title: 'تنظیمات' }, controls)) : null,
        el(SSR, {
          block: name, attributes: sent, urlQueryArgs: postId ? { post_id: postId } : {},
          EmptyResponsePlaceholder: function () {
            return el('div', { className: 'ea-ssr-empty' }, type.title + ' (در این پیش‌نمایش محتوایی ندارد)');
          }
        })
      );
    };
  }

  ['header', 'page-hero', 'posts', 'category-chips', 'search-form', 'popular-posts', 'categories',
    'related-posts', 'toc', 'post-cover', 'post-footer', 'comments'].forEach(function (n) {
    var name = 'elmira/' + n;
    registerBlockType(name, { edit: DynEdit(name), save: function () { return null; } });
  });
})(window.wp);
