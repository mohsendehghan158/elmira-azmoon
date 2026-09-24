// کپی دارایی‌های مشترک از قالب HTML و بسته‌های npm به قالب وردپرس.
// theme.css بی‌تغییر کپی می‌شود؛ theme.js با چند وصلهٔ کوچک برای وردپرس.
import { cpSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const html = join(here, '../../elmira-theme/assets');
const theme = join(here, '../elmira-azmoon/assets');
const nm = join(here, 'node_modules');
const banner = (src) => `/* کپی‌شده از ${src} با wordpress/build/vendor.mjs؛ مستقیم ویرایش نکنید. */\n`;

mkdirSync(join(theme, 'css'), { recursive: true });
mkdirSync(join(theme, 'js'), { recursive: true });
mkdirSync(join(theme, 'fonts'), { recursive: true });

writeFileSync(join(theme, 'css/theme.css'), banner('elmira-theme/assets/css/theme.css') + readFileSync(join(html, 'css/theme.css'), 'utf8'));

// ---------- theme.js ----------
let js = readFileSync(join(html, 'js/theme.js'), 'utf8');
const patch = (from, to) => {
  if (!js.includes(from)) throw new Error('theme.js patch not found: ' + from.slice(0, 60));
  js = js.replace(from, to);
};
// آدرس سامانهٔ نوبت‌دهی از تنظیمات قالب در پیشخوان می‌آید
patch("var BOOKING_URL = '#booking-system-url';",
  "var BOOKING_URL = (window.elmiraTheme && window.elmiraTheme.bookingUrl) || '#booking-system-url';");
// نشانی‌های وردپرس کامل‌اند (https://...)؛ پیوندهای داخلی هم باید پردهٔ انتقال بگیرند
patch("if (a.target === '_blank' || a.hasAttribute('download') || !href || href.charAt(0) === '#' || /^(mailto|tel|https?):/i.test(href)) return;",
  "if (a.target === '_blank' || a.hasAttribute('download') || !href || href.charAt(0) === '#' || /^(mailto|tel):/i.test(href) || a.closest('#wpadminbar')) return;\n" +
  "    var u = new URL(a.href, location.href);\n" +
  "    if (u.origin !== location.origin || /\\/wp-(admin|login)/.test(u.pathname) || (u.pathname === location.pathname && u.search === location.search && u.hash)) return;");
writeFileSync(join(theme, 'js/theme.js'), banner('elmira-theme/assets/js/theme.js') + js);

// ---------- فونت‌ها ----------
for (const [pkg, files] of Object.entries({
  vazirmatn: ['vazirmatn-arabic-wght-normal.woff2', 'vazirmatn-latin-wght-normal.woff2'],
  'markazi-text': ['markazi-text-arabic-wght-normal.woff2', 'markazi-text-latin-wght-normal.woff2'],
})) {
  for (const f of files) cpSync(join(nm, '@fontsource-variable', pkg, 'files', f), join(theme, 'fonts', f));
}

// ---------- آیکن‌های Phosphor (فقط woff2) ----------
for (const w of ['light', 'fill']) {
  const dir = join(nm, '@phosphor-icons/web/src', w);
  const name = w === 'light' ? 'Phosphor-Light' : 'Phosphor-Fill';
  let css = readFileSync(join(dir, 'style.css'), 'utf8');
  css = css.replace(/src:\s*url\([^;]*;/, `src: url("../fonts/${name}.woff2") format("woff2");`);
  css = css.replace(/\s*\n\s*/g, '').replace(/;\s*}/g, '}');
  writeFileSync(join(theme, `css/phosphor-${w}.css`), `/* Phosphor Icons 2.1.1 (MIT) — ${w} */\n` + css + '\n');
  cpSync(join(dir, `${name}.woff2`), join(theme, `fonts/${name}.woff2`));
}
console.log('vendored assets');
