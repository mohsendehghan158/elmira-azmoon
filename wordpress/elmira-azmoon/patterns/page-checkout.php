<?php
/**
 * Title: برگهٔ تسویه حساب
 * Slug: elmira/page-checkout
 * Categories: elmira-pages
 * Description: تسویه حساب فروشگاه المیرا آزمون: آدرس، روش ارسال و پرداخت.
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1400
 *
 * ساخته‌شده با wordpress/build/convert.py؛ مستقیم ویرایش نکنید.
 *
 * @package elmira
 */
?>
<!-- wp:elmira/el {"tag":"section","className":"grad spotlight page-hero","attrs":{"data-hero":""},"metadata":{"name":"هیرو"}} -->
<!-- wp:elmira/el {"className":"aurora","attrs":{"aria-hidden":"true"},"mode":"html","html":"\u003cspan\u003e\u003c/span\u003e\u003cspan\u003e\u003c/span\u003e\u003cspan\u003e\u003c/span\u003e"} /-->
<!-- wp:elmira/el {"tag":"span","className":"outline-text font-display absolute -bottom-6 md:-bottom-12 left-2 md:left-8 text-[26vw] md:text-[15rem] leading-none pointer-events-none select-none","attrs":{"aria-hidden":"true","data-parallax":"-.12"},"mode":"text","content":"پرداخت"} /-->
<!-- wp:elmira/el {"className":"wrap relative"} -->
<!-- wp:elmira/el {"tag":"nav","className":"crumbs","attrs":{"aria-label":"مسیر صفحه","data-reveal":"up"}} -->
<!-- wp:elmira/el {"tag":"a","className":"link-u","attrs":{"href":"/"},"mode":"text","content":"خانه"} /-->
<!-- wp:elmira/el {"tag":"span","attrs":{"aria-hidden":"true"},"mode":"text","content":"/"} /-->
<!-- wp:elmira/el {"tag":"a","className":"link-u","attrs":{"href":"/cart/"},"mode":"text","content":"سبد خرید"} /-->
<!-- wp:elmira/el {"tag":"span","attrs":{"aria-hidden":"true"},"mode":"text","content":"/"} /-->
<!-- wp:elmira/el {"tag":"span","className":"text-white","attrs":{"aria-current":"page"},"mode":"text","content":"تسویه حساب"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"h1","className":"font-display text-5xl md:text-7xl leading-[1.05] mt-4","attrs":{"data-split":""},"mode":"text","content":"تسویه حساب"} /-->
<!-- wp:elmira/el {"tag":"ol","className":"steps mt-8 max-w-2xl","attrs":{"aria-label":"مراحل","data-reveal":"up","style":"\u002d\u002dd:3"}} -->
<!-- wp:elmira/el {"tag":"li","className":"step is-done"} -->
<!-- wp:elmira/el {"tag":"b","mode":"text","content":"✓"} /-->
<!-- wp:elmira/el {"tag":"span","className":"hidden sm:inline","mode":"text","content":"سبد خرید"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"li","className":"step-line","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"li","className":"step is-current","attrs":{"aria-current":"step"}} -->
<!-- wp:elmira/el {"tag":"b","mode":"text","content":"۲"} /-->
<!-- wp:elmira/el {"tag":"span","className":"hidden sm:inline","mode":"text","content":"آدرس و ارسال"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"li","className":"step-line","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"li","className":"step"} -->
<!-- wp:elmira/el {"tag":"b","mode":"text","content":"۳"} /-->
<!-- wp:elmira/el {"tag":"span","className":"hidden sm:inline","mode":"text","content":"پرداخت"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->

<!-- wp:elmira/el {"tag":"section","className":"py-14 md:py-20"} -->
<!-- wp:elmira/el {"tag":"form","className":"wrap grid lg:grid-cols-[1fr_380px] gap-10 items-start","attrs":{"data-validate":"","data-next":"order-ok"},"metadata":{"name":"فرم"}} -->
<!-- wp:elmira/el {"className":"flex flex-col gap-10"} -->
<!-- wp:elmira/el {"tag":"fieldset","attrs":{"data-reveal":"up"},"metadata":{"name":"آدرس"}} -->
<!-- wp:elmira/el {"className":"flex items-center justify-between mb-5"} -->
<!-- wp:elmira/el {"tag":"legend","className":"font-display text-3xl float-right","mode":"text","content":"آدرس تحویل"} /-->
<!-- wp:elmira/el {"tag":"button","className":"link-arrow text-sm","attrs":{"type":"button","onclick":"eaToast('فرم افزودن آدرس این‌جا باز می‌شود','ph-map-pin-plus')"},"mode":"text","content":"افزودن آدرس جدید","before":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-plus\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"grid md:grid-cols-2 gap-4 clear-both"} -->
<!-- wp:elmira/el {"tag":"label","className":"opt !items-start","mode":"text","content":"\u003cspan class=\u0022flex-1 text-sm flex flex-col gap-1\u0022\u003e\u003cstrong class=\u0022flex items-center justify-between\u0022\u003eخانه \u003cspan class=\u0022text-xs text-copper-ink font-semibold\u0022\u003eویرایش\u003c/span\u003e\u003c/strong\u003e\u003cspan class=\u0022text-ink-2\u0022\u003e[آدرس کامل — استان، شهر، خیابان، پلاک، واحد]\u003c/span\u003e\u003cspan class=\u0022text-xs text-ink-2\u0022\u003eکد پستی: [۱۰ رقم] · تلفن: [۰۹۱۲xxxxxxx]\u003c/span\u003e\u003c/span\u003e","before":"\u003cinput checked name=\u0022addr\u0022 required type=\u0022radio\u0022\u003e\u003cspan class=\u0022dot mt-1\u0022\u003e\u003c/span\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"opt !items-start","mode":"text","content":"\u003cspan class=\u0022flex-1 text-sm flex flex-col gap-1\u0022\u003e\u003cstrong class=\u0022flex items-center justify-between\u0022\u003eمحل کار \u003cspan class=\u0022text-xs text-copper-ink font-semibold\u0022\u003eویرایش\u003c/span\u003e\u003c/strong\u003e\u003cspan class=\u0022text-ink-2\u0022\u003e[آدرس کامل]\u003c/span\u003e\u003c/span\u003e","before":"\u003cinput name=\u0022addr\u0022 type=\u0022radio\u0022\u003e\u003cspan class=\u0022dot mt-1\u0022\u003e\u003c/span\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"fieldset","attrs":{"data-reveal":"up"},"metadata":{"name":"روش ارسال"}} -->
<!-- wp:elmira/el {"tag":"legend","className":"font-display text-3xl mb-5","mode":"text","content":"روش ارسال"} /-->
<!-- wp:elmira/el {"className":"grid gap-3"} -->
<!-- wp:elmira/el {"tag":"label","className":"opt"} -->
<!-- wp:elmira/el {"tag":"input","attrs":{"type":"radio","name":"ship","required":"","checked":""},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"span","className":"dot","mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"i","className":"ph-light ph-moped text-2xl text-copper-ink","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"span","className":"flex-1 text-sm"} -->
<!-- wp:elmira/el {"tag":"strong","className":"block","mode":"text","content":"پیک تهران"} /-->
<!-- wp:elmira/el {"tag":"small","className":"text-ink-2","mode":"text","content":"تحویل ۲۴ ساعته"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"b","className":"text-sm num","mode":"text","content":"۶۵٬۰۰۰ ت"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"label","className":"opt"} -->
<!-- wp:elmira/el {"tag":"input","attrs":{"type":"radio","name":"ship"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"span","className":"dot","mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"i","className":"ph-light ph-package text-2xl text-copper-ink","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"span","className":"flex-1 text-sm"} -->
<!-- wp:elmira/el {"tag":"strong","className":"block","mode":"text","content":"پست پیشتاز"} /-->
<!-- wp:elmira/el {"tag":"small","className":"text-ink-2","mode":"text","content":"تحویل ۲ تا ۴ روز کاری"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"b","className":"text-sm num","mode":"text","content":"۴۵٬۰۰۰ ت"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"label","className":"opt"} -->
<!-- wp:elmira/el {"tag":"input","attrs":{"type":"radio","name":"ship"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"span","className":"dot","mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"i","className":"ph-light ph-storefront text-2xl text-copper-ink","attrs":{"aria-hidden":"true"},"mode":"html","html":""} /-->
<!-- wp:elmira/el {"tag":"span","className":"flex-1 text-sm"} -->
<!-- wp:elmira/el {"tag":"strong","className":"block","mode":"text","content":"تحویل حضوری در سالن"} /-->
<!-- wp:elmira/el {"tag":"small","className":"text-ink-2","mode":"text","content":"[آدرس سالن] — همان روز"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"span","className":"tag tag-ok","mode":"text","content":"رایگان"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"p","className":"label mt-6","mode":"text","content":"زمان ترجیحی تحویل (اختیاری)"} /-->
<!-- wp:elmira/el {"className":"grid grid-cols-3 gap-2"} -->
<!-- wp:elmira/el {"tag":"label","className":"opt !p-3 justify-center text-xs sm:text-sm text-center","mode":"text","content":"صبح ۹ تا ۱۳","before":"\u003cinput name=\u0022slot\u0022 type=\u0022radio\u0022\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"opt !p-3 justify-center text-xs sm:text-sm text-center","mode":"text","content":"بعدازظهر ۱۳ تا ۱۷","before":"\u003cinput name=\u0022slot\u0022 type=\u0022radio\u0022\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"opt !p-3 justify-center text-xs sm:text-sm text-center","mode":"text","content":"عصر ۱۷ تا ۲۱","before":"\u003cinput name=\u0022slot\u0022 type=\u0022radio\u0022\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"fieldset","attrs":{"data-reveal":"up"},"metadata":{"name":"پرداخت"}} -->
<!-- wp:elmira/el {"tag":"legend","className":"font-display text-3xl mb-5","mode":"text","content":"روش پرداخت"} /-->
<!-- wp:elmira/el {"className":"grid gap-3"} -->
<!-- wp:elmira/el {"tag":"label","className":"opt","mode":"text","content":"\u003cspan class=\u0022flex-1 text-sm font-semibold\u0022\u003eپرداخت آنلاین (درگاه بانکی)\u003c/span\u003e","before":"\u003cinput checked name=\u0022pay\u0022 required type=\u0022radio\u0022\u003e\u003cspan class=\u0022dot\u0022\u003e\u003c/span\u003e\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-credit-card text-2xl text-copper-ink\u0022\u003e\u003c/i\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"opt","mode":"text","content":"\u003cspan class=\u0022flex-1 text-sm font-semibold\u0022\u003eپرداخت در محل (فقط تهران)\u003c/span\u003e","before":"\u003cinput name=\u0022pay\u0022 type=\u0022radio\u0022\u003e\u003cspan class=\u0022dot\u0022\u003e\u003c/span\u003e\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-money text-2xl text-copper-ink\u0022\u003e\u003c/i\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"opt","mode":"text","content":"\u003cspan class=\u0022flex-1 text-sm font-semibold\u0022\u003eکیف پول من \u003csmall class=\u0022font-normal text-ink-2\u0022\u003e— موجودی: ۱۵۰٬۰۰۰ ت\u003c/small\u003e\u003c/span\u003e","before":"\u003cinput name=\u0022pay\u0022 type=\u0022radio\u0022\u003e\u003cspan class=\u0022dot\u0022\u003e\u003c/span\u003e\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-wallet text-2xl text-copper-ink\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"label","className":"check mt-5","mode":"text","content":"نیاز به فاکتور رسمی دارم","before":"\u003cinput type=\u0022checkbox\u0022\u003e"} /-->
<!-- wp:elmira/el {"tag":"label","className":"block mt-5","mode":"text","content":"\u003cspan class=\u0022label\u0022\u003eتوضیحات سفارش (اختیاری)\u003c/span\u003e","after":"\u003ctextarea class=\u0022textarea\u0022 placeholder=\u0022توضیحی برای ارسال یا بسته‌بندی\u0022\u003e\u003c/textarea\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"aside","className":"card p-6 flex flex-col gap-4 lg:sticky lg:top-24","attrs":{"data-reveal":"left"},"metadata":{"name":"خلاصه"}} -->
<!-- wp:elmira/el {"tag":"h2","className":"font-display text-3xl","mode":"text","content":"خلاصهٔ سفارش"} /-->
<!-- wp:elmira/el {"tag":"ul","className":"flex flex-col gap-3 text-sm"} -->
<!-- wp:elmira/el {"tag":"li","className":"flex items-center gap-3"} -->
<!-- wp:elmira/el {"tag":"img","className":"size-12 rounded-lg object-cover","attrs":{"src":"https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format\u0026fit=crop\u0026w=96\u0026h=96\u0026q=70","alt":"","width":"96","height":"96"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"span","className":"flex-1","mode":"text","content":"سرم روشن‌کننده \u003csmall class=\u0022text-ink-2\u0022\u003e× ۱\u003c/small\u003e"} /-->
<!-- wp:elmira/el {"tag":"span","className":"num","mode":"text","content":"۴۸۰٬۰۰۰"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"li","className":"flex items-center gap-3"} -->
<!-- wp:elmira/el {"tag":"img","className":"size-12 rounded-lg object-cover","attrs":{"src":"https://images.unsplash.com/photo-1552046122-03184de85e08?auto=format\u0026fit=crop\u0026w=96\u0026h=96\u0026q=70","alt":"","width":"96","height":"96"},"mode":"void"} /-->
<!-- wp:elmira/el {"tag":"span","className":"flex-1","mode":"text","content":"ماسک تقویت مو \u003csmall class=\u0022text-ink-2\u0022\u003e× ۲\u003c/small\u003e"} /-->
<!-- wp:elmira/el {"tag":"span","className":"num","mode":"text","content":"۶۴۰٬۰۰۰"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"dl","className":"flex flex-col gap-3 text-sm border-t border-line pt-4"} -->
<!-- wp:elmira/el {"className":"flex justify-between"} -->
<!-- wp:elmira/el {"tag":"dt","className":"text-ink-2","mode":"text","content":"جمع کالاها"} /-->
<!-- wp:elmira/el {"tag":"dd","className":"num","mode":"text","content":"۱٬۱۲۰٬۰۰۰ ت"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"flex justify-between"} -->
<!-- wp:elmira/el {"tag":"dt","className":"text-ink-2","mode":"text","content":"تخفیف"} /-->
<!-- wp:elmira/el {"tag":"dd","className":"num text-[var(\u002d\u002dok)]","mode":"text","content":"− ۸۵٬۰۰۰ ت"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"flex justify-between"} -->
<!-- wp:elmira/el {"tag":"dt","className":"text-ink-2","mode":"text","content":"هزینهٔ ارسال"} /-->
<!-- wp:elmira/el {"tag":"dd","className":"num","mode":"text","content":"۶۵٬۰۰۰ ت"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"border-t border-line pt-4 flex items-end justify-between"} -->
<!-- wp:elmira/el {"tag":"span","className":"text-sm text-ink-2","mode":"text","content":"مبلغ قابل پرداخت"} /-->
<!-- wp:elmira/el {"tag":"strong","className":"font-display text-3xl num","mode":"text","content":"۱٬۱۰۰٬۰۰۰ \u003csmall class=\u0022text-base text-ink-2\u0022\u003eتومان\u003c/small\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"label","className":"check text-xs","mode":"text","content":"قوانین سایت را خوانده‌ام و می‌پذیرم.","before":"\u003cinput required type=\u0022checkbox\u0022\u003e"} /-->
<!-- wp:elmira/el {"tag":"button","className":"btn btn-salmon btn-lg w-full","attrs":{"type":"submit"},"mode":"text","content":"پرداخت و ثبت سفارش","after":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-lock-key\u0022\u003e\u003c/i\u003e"} /-->
<!-- wp:elmira/el {"tag":"p","className":"flex items-center justify-center gap-2 text-xs text-ink-2","mode":"text","content":"پرداخت امن از درگاه معتبر بانکی","before":"\u003ci aria-hidden=\u0022true\u0022 class=\u0022ph-light ph-shield-check text-base text-copper-ink\u0022\u003e\u003c/i\u003e"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"className":"wrap","attrs":{"id":"order-ok","hidden":"","data-scroll":"yes"},"metadata":{"name":"حالت پس از پرداخت موفق"}} -->
<!-- wp:elmira/el {"className":"max-w-xl mx-auto card p-8 md:p-12 text-center flex flex-col items-center gap-4"} -->
<!-- wp:elmira/el {"tag":"svg","className":"check-anim","attrs":{"viewbox":"0 0 88 88","aria-hidden":"true"},"mode":"html","html":"\u003ccircle cx=\u002244\u0022 cy=\u002244\u0022 r=\u002241\u0022\u003e\u003c/circle\u003e\u003cpath d=\u0022M27 45l11 11 23-24\u0022\u003e\u003c/path\u003e"} /-->
<!-- wp:elmira/el {"tag":"h2","className":"font-display text-4xl","mode":"text","content":"سفارش شما ثبت شد"} /-->
<!-- wp:elmira/el {"tag":"p","className":"text-ink-2","mode":"text","content":"شمارهٔ سفارش: \u003cstrong class=\u0022text-ink num\u0022\u003e۱۴۰۴-۲۸۷۳\u003c/strong\u003e\u003cbr\u003eوضعیت و کد رهگیری مرسوله از طریق پیامک اطلاع‌رسانی می‌شود."} /-->
<!-- wp:elmira/el {"className":"flex flex-wrap justify-center gap-3 mt-2"} -->
<!-- wp:elmira/el {"tag":"a","className":"btn btn-dark","attrs":{"href":"/account/"},"mode":"text","content":"پیگیری سفارش"} /-->
<!-- wp:elmira/el {"tag":"a","className":"btn btn-outline","attrs":{"href":"/shop/"},"mode":"text","content":"ادامهٔ خرید"} /-->
<!-- /wp:elmira/el -->
<!-- wp:elmira/el {"tag":"p","className":"text-xs text-ink-2","mode":"text","content":"(این صفحه نمایشی است؛ پرداختی انجام نشده.)"} /-->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
<!-- /wp:elmira/el -->
