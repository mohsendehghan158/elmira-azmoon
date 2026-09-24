<?php
/**
 * Title: برگهٔ ورود / ثبت‌نام
 * Slug: elmira/page-auth
 * Categories: elmira-pages
 * Description: ورود به حساب کاربری المیرا آزمون با شمارهٔ موبایل و کد پیامکی.
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1400
 *
 * ساخته‌شده با wordpress/build/convert.py؛ مستقیم ویرایش نکنید.
 *
 * @package elmira
 */
?>
<!-- wp:elmira/el {"className":"min-h-svh grid lg:grid-cols-2"} -->
<!-- wp:elmira/el {"className":"relative flex flex-col px-5 py-6 md:px-12","metadata":{"name":"فرم"}} -->
<!-- wp:elmira/el {"className":"flex items-center justify-between"} -->
<!-- wp:elmira/el {"tag":"a","className":"brand text-ink","attrs":{"href":"/"},"mode":"text","content":"\u003cspan\u003eالمیرا آزمون\u003c/span\u003e","before":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-flower-lotus\u0022\u003e\u003c/i\u003e"} /-->
<!-- wp:elmira/el {"tag":"button","className":"hdr-icon theme-toggle inline-grid","attrs":{"type":"button","data-theme-toggle":"","aria-label":"تغییر حالت روشن و تیره"},"mode":"html","html":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-sun sun\u0022\u003e\u003c/i\u003e\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-moon-stars moon\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"flex-1 grid place-items-center py-10"} -->
<!-- wp:elmira/el {"className":"w-full max-w-sm"} -->
<!-- wp:elmira/el {"tag":"form","className":"flex flex-col gap-5","attrs":{"id":"step1","data-step":"","data-validate":"","data-next":"step2"},"metadata":{"name":"قدم ۱"}} -->
<!-- wp:elmira/el {"attrs":{"data-reveal":"up"}} -->
<!-- wp:elmira/el {"tag":"p","className":"kicker","mode":"text","content":"قدم ۱ از ۲"} /-->
<!-- wp:elmira/el {"tag":"h1","className":"font-display text-5xl mt-3","mode":"text","content":"ورود / ثبت‌نام"} /-->
<!-- wp:elmira/el {"tag":"p","className":"text-sm text-ink-2 mt-3","mode":"text","content":"با یک حساب، سفارش‌های فروشگاه و دوره‌های آموزشگاه را یکجا مدیریت کنید."} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"label","attrs":{"data-reveal":"up","style":"\u002d\u002dd:1"}} -->
<!-- wp:elmira/el {"tag":"span","className":"label","mode":"text","content":"شمارهٔ موبایل"} /-->
<!-- wp:elmira/el {"tag":"span","className":"relative block"} -->
<!-- wp:elmira/el {"tag":"i","className":"ph-light ph-device-mobile absolute right-4 top-1/2 -translate-y-1/2 text-xl text-ink-2","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"input","className":"input !ps-12 num text-lg tracking-widest","attrs":{"required":"","inputmode":"tel","pattern":"(09|۰۹)[0-9۰-۹]{9}","placeholder":"۰۹۱۲xxxxxxx","dir":"ltr","autocomplete":"tel","autofocus":""},"mode":"void"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"span","className":"err-msg","mode":"text","content":"شمارهٔ موبایل ۱۱ رقمی با ۰۹ شروع می‌شود."} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"button","className":"btn btn-salmon btn-lg w-full","attrs":{"type":"submit","data-reveal":"up","style":"\u002d\u002dd:2"},"mode":"text","content":"دریافت کد تأیید","after":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-arrow-left\u0022\u003e\u003c/i\u003e"} /-->
<!-- wp:elmira/el {"tag":"p","className":"text-xs text-ink-2 text-center","mode":"text","content":"با ورود، \u003ca class=\u0022link-u text-ink\u0022 href=\u0022#\u0022\u003eقوانین\u003c/a\u003e و \u003ca class=\u0022link-u text-ink\u0022 href=\u0022#\u0022\u003eحریم خصوصی\u003c/a\u003e سایت را می‌پذیرید."} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"form","className":"flex flex-col gap-5","attrs":{"id":"step2","data-step":"","data-validate":"","data-next":"auth-ok","hidden":""},"metadata":{"name":"قدم ۲"}} -->
<!-- wp:elmira/el -->
<!-- wp:elmira/el {"tag":"p","className":"kicker","mode":"text","content":"قدم ۲ از ۲"} /-->
<!-- wp:elmira/el {"tag":"h1","className":"font-display text-5xl mt-3","mode":"text","content":"کد تأیید"} /-->
<!-- wp:elmira/el {"tag":"p","className":"text-sm text-ink-2 mt-3","mode":"html","html":"کد ۵ رقمی پیامک‌شده را وارد کنید. \u003cbutton class=\u0022link-u text-copper-ink font-semibold\u0022 data-back=\u0022step1\u0022 type=\u0022button\u0022\u003eویرایش شماره\u003c/button\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"otp","attrs":{"data-submit":"otp-btn","role":"group","aria-label":"کد ۵ رقمی"}} -->
<!-- wp:elmira/el {"tag":"input","attrs":{"required":"","inputmode":"numeric","maxlength":"1","aria-label":"رقم ۱","autocomplete":"one-time-code"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"input","attrs":{"required":"","inputmode":"numeric","maxlength":"1","aria-label":"رقم ۲"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"input","attrs":{"required":"","inputmode":"numeric","maxlength":"1","aria-label":"رقم ۳"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"input","attrs":{"required":"","inputmode":"numeric","maxlength":"1","aria-label":"رقم ۴"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"input","attrs":{"required":"","inputmode":"numeric","maxlength":"1","aria-label":"رقم ۵"},"mode":"void"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"p","className":"text-xs text-ink-2 text-center","mode":"text","content":"ارسال مجدد کد تا \u003cb class=\u0022num text-ink\u0022 data-countdown=\u0022119\u0022 data-resend=\u0022resend\u0022\u003e۰۱:۵۹\u003c/b\u003e دیگر"} /-->
<!-- wp:elmira/el {"tag":"button","className":"text-sm text-copper-ink font-semibold","attrs":{"type":"button","id":"resend","hidden":"","onclick":"eaToast('کد دوباره ارسال شد','ph-chat-text')"},"mode":"text","content":"ارسال مجدد کد"} /-->
<!-- wp:elmira/el {"tag":"button","className":"btn btn-salmon btn-lg w-full is-disabled","attrs":{"type":"submit","id":"otp-btn"},"mode":"text","content":"ورود به حساب"} /-->
<!-- wp:elmira/el {"className":"flex items-center gap-3 text-xs text-ink-2","mode":"text","content":"یا","before":"\u003cspan class=\u0022flex-1 h-px bg-line\u0022\u003e\u003c/span\u003e","after":"\u003cspan class=\u0022flex-1 h-px bg-line\u0022\u003e\u003c/span\u003e"} /-->
<!-- wp:elmira/el {"tag":"a","className":"btn btn-outline w-full","attrs":{"href":"/"},"mode":"text","content":"ادامه به‌عنوان مهمان"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"flex flex-col items-center gap-4 text-center","attrs":{"id":"auth-ok","hidden":"","data-scroll":"no"},"metadata":{"name":"موفق"}} -->
<!-- wp:elmira/el {"tag":"svg","className":"check-anim","attrs":{"viewbox":"0 0 88 88","aria-hidden":"true"},"mode":"html","html":"\u003ccircle cx=\u002244\u0022 cy=\u002244\u0022 r=\u002241\u0022\u003e\u003c/circle\u003e\u003cpath d=\u0022M27 45l11 11 23-24\u0022\u003e\u003c/path\u003e"} /-->
<!-- wp:elmira/el {"tag":"h1","className":"font-display text-5xl","mode":"text","content":"خوش آمدید!"} /-->
<!-- wp:elmira/el {"tag":"p","className":"text-sm text-ink-2","mode":"text","content":"وارد حساب کاربری شدید. (نمایشی)"} /-->
<!-- wp:elmira/el {"tag":"a","className":"btn btn-salmon w-full","attrs":{"href":"/account/"},"mode":"text","content":"رفتن به پنل کاربری","after":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-arrow-left\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"a","className":"link-arrow text-sm self-center","attrs":{"href":"/"},"mode":"text","content":"بازگشت به صفحهٔ اصلی","before":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-arrow-right\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"grad spotlight relative hidden lg:flex flex-col justify-end p-12 overflow-hidden","metadata":{"name":"تصویر"}} -->
<!-- wp:elmira/el {"className":"aurora","attrs":{"aria-hidden":"true"},"mode":"html","html":"\u003cspan\u003e\u003c/span\u003e\u003cspan\u003e\u003c/span\u003e\u003cspan\u003e\u003c/span\u003e"} /-->
<!-- wp:elmira/el {"tag":"img","className":"absolute inset-0 size-full object-cover opacity-60 mix-blend-luminosity","attrs":{"src":"https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format\u0026fit=crop\u0026w=1000\u0026h=1300\u0026q=70","alt":"","width":"1000","height":"1300","data-parallax":".04"},"mode":"void"} /-->
<!-- wp:elmira/el {"className":"absolute inset-0 bg-gradient-to-t from-[#262d3c] via-[#262d3c]/30 to-transparent","mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"svg","className":"badge-spin absolute size-36 top-12 left-12 text-white/80","attrs":{"viewbox":"0 0 200 200","aria-hidden":"true"},"mode":"html","html":"\u003cdefs\u003e\u003cpath d=\u0022M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0\u0022 id=\u0022bc3\u0022\u003e\u003c/path\u003e\u003c/defs\u003e\u003ctext\u003e\u003ctextpath href=\u0022#bc3\u0022 textlength=\u0022440\u0022\u003eالمیرا آزمون • سالن • فروشگاه • آموزشگاه •\u003c/textpath\u003e\u003c/text\u003e\u003ccircle cx=\u0022100\u0022 cy=\u0022100\u0022 r=\u00224\u0022\u003e\u003c/circle\u003e"} /-->
<!-- wp:elmira/el {"tag":"blockquote","className":"relative font-display text-4xl leading-snug max-w-md","attrs":{"data-split":""},"mode":"text","content":"یک حساب برای سفارش‌ها، دوره‌ها و هر چیزی که به زیبایی شما مربوط است."} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
