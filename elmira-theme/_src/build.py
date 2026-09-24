#!/usr/bin/env python3
"""
ساخت صفحه‌های قالب المیرا آزمون.

هر فایل در _src/pages با یک خط META (JSON) شروع می‌شود و فقط محتوای <main> را دارد.
این اسکریپت head، سربرگ، فوتر و اجزای شناور مشترک را دورش می‌چیند و خروجی HTML ایستا
را در ریشهٔ elmira-theme می‌نویسد.

اجرا:  python3 _src/build.py
"""
import html
import json
import pathlib
import re
import sys

SRC = pathlib.Path(__file__).resolve().parent
OUT = SRC.parent

# تصاویر (Unsplash) — همه بررسی شده‌اند؛ در صفحه‌ها با @@name,w,h@@ استفاده می‌شوند.
IMAGES = {
    "hair_iron": "1560869713-7d0a29430803",
    "nails": "1610992015762-45dca7fa3a85",
    "brushes": "1596704017254-9b121068fb31",
    "dropper": "1620916297397-a4a5402a3c6c",
    "facial": "1570172619644-dfd03ed5d881",
    "wash": "1634449571010-02389ed0f9b0",
    "mirror": "1551723454-7565a1f5b161",
    "serum": "1608571423902-eed4a5ad8108",
    "tools": "1595475884562-073c30d45670",
    "face_makeup": "1594465919760-441fe5908ab0",
    "balayage": "1554519934-e32b1629d9ee",
    "blowdry": "1580618672591-eb180b1a973f",
    "darkhair": "1574015974293-817f0ebebb74",
    "nailart": "1604654894610-df63bc536371",
    "skinglow": "1544717304-a2db4a7b16ee",
    "interior_round": "1633681926022-84c23e8cb2d6",
    "green_jars": "1552046122-03184de85e08",
    "palette": "1583784561105-a674080f391e",
    "cream": "1613803745799-ba6c10aace85",
    "cream_swipe": "1585945037805-5fd82c2e60b1",
    "interior_chairs": "1521590832167-7bcbfaa6381f",
    "flatlay_light": "1596462502278-27bfdc403348",
    "eye_makeup": "1487412947147-5cebf100ffc2",
    "oil": "1515377905703-c4788e51af15",
    "lounge": "1560750588-73207b1ef5b8",
    "redhair": "1616683693504-3ea7e9ad6fec",
    "cosmetics": "1522335789203-aabd1fc54bc9",
    "flatlay2": "1512496015851-a90fb38ba796",
    "curly_pink": "1519699047748-de8e457a634e",
    "collar": "1531123897727-8f129e1688ce",
    "p_bun": "1580489944761-15a19d654956",
    "p_laugh": "1494790108377-be9c29b29330",
    "p_red": "1438761681033-6461ffad8d80",
    "p_stripe": "1544005313-94ddf0286df2",
}


def img_url(m):
    name, w, h = m.group(1), m.group(2), m.group(3)
    if name not in IMAGES:
        sys.exit(f"unknown image: {name}")
    return f"https://images.unsplash.com/photo-{IMAGES[name]}?auto=format&amp;fit=crop&amp;w={w}&amp;h={h}&amp;q=70"


FA = str.maketrans("0123456789", "۰۱۲۳۴۵۶۷۸۹")


def fa(n):
    """عدد → رقم فارسی با جداکنندهٔ هزارگان."""
    s = f"{int(n):,}" if str(n).isdigit() else str(n)
    return s.replace(",", "٬").translate(FA)


def stars(n):
    n = int(n)
    return f'<span class="stars" role="img" aria-label="{fa(n)} از ۵ ستاره">{"★" * n}<span class="off">{"★" * (5 - n)}</span></span>'


# ---------- کامپوننت‌ها: {{name arg|arg|...}} ----------
def c_product(img, name, cat, price, old="", tag="", star="5", d="0", key="", stock="1"):
    tag_html = f'<span class="tag {"tag-mute" if stock == "0" else "tag-salmon"} absolute top-3 right-3 z-10">{tag}</span>' if tag else ""
    old_html = f' <del class="text-ink-2 font-normal text-xs">{fa(old)}</del>' if old else ""
    if stock == "0":
        btn = f'<button type="button" class="btn btn-soft btn-sm absolute inset-x-3 bottom-3 z-10 transition-all duration-500 translate-y-[150%] group-hover:translate-y-0 [@media(hover:none)]:translate-y-0" onclick="eaToast(\'پس از موجود شدن خبرتان می‌کنیم\',\'ph-bell\')"><i class="ph-light ph-bell" aria-hidden="true"></i>اطلاع از موجودی</button>'
    else:
        btn = f'<button type="button" class="btn btn-dark btn-sm absolute inset-x-3 bottom-3 z-10 transition-all duration-500 translate-y-[150%] group-hover:translate-y-0 [@media(hover:none)]:translate-y-0" data-add="{name}"><i class="ph-light ph-handbag" aria-hidden="true"></i><span class="add-label">افزودن به سبد</span></button>'
    return f"""<article class="group fx-item" data-product data-price="{price}" data-order="{d}" data-cat="{key or 'all'}" data-reveal="up" style="--d:{int(d) % 4}">
  <div class="relative zoom rounded-xl img-ph aspect-square">
    <a href="product-single.html" data-cursor="مشاهده" aria-label="{name}"><img src="@@{img},600,600@@" alt="{name}" class="size-full object-cover{' grayscale' if stock == '0' else ''}" loading="lazy" width="600" height="600"></a>
    {tag_html}
    <button type="button" class="heart absolute top-3 left-3 z-10" aria-pressed="false" aria-label="افزودن {name} به علاقه‌مندی‌ها"><i class="ph-light ph-heart" aria-hidden="true"></i><i class="ph-fill ph-heart" aria-hidden="true"></i></button>
    {btn}
  </div>
  <div class="pt-4 text-center flex flex-col items-center gap-0.5">
    <p class="text-[11px] font-bold text-copper-ink">{cat}</p>
    <h3 class="font-display text-xl leading-snug"><a href="product-single.html" class="link-u">{name}</a></h3>
    {stars(star)}
    <p class="text-sm font-semibold num">{fa(price)} تومان{old_html}</p>
  </div>
</article>"""


def c_member(img, name, role, d="0"):
    return f"""<figure class="group text-center" data-reveal="up" style="--d:{d}">
  <div class="relative zoom rounded-lg aspect-[4/5] img-ph mb-4">
    <img src="@@{img},500,620@@" alt="عکس نمونهٔ کارشناس" class="size-full object-cover saturate-[.85] transition duration-700 group-hover:saturate-100" loading="lazy" width="500" height="620">
    <div class="absolute inset-x-0 bottom-0 z-10 flex justify-center gap-2 pb-4 translate-y-full opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
      <a href="#" class="round-btn !size-10 bg-white !text-night !border-0 hover:!bg-salmon" aria-label="اینستاگرام {name}"><i class="ph-light ph-instagram-logo" aria-hidden="true"></i></a>
      <a href="gallery.html" class="round-btn !size-10 bg-white !text-night !border-0 hover:!bg-salmon" aria-label="نمونه‌کارهای {name}"><i class="ph-light ph-images" aria-hidden="true"></i></a>
    </div>
  </div>
  <figcaption class="leading-relaxed"><strong class="block font-display text-xl font-medium">{name}</strong><small class="text-xs text-ink-2">{role}</small></figcaption>
</figure>"""


def c_review(name, text, star="5", sub=""):
    return f"""<figure class="snap-start flex-none w-[82vw] sm:w-[330px] flex gap-4 bg-card rounded-xl p-6 select-none">
  <figcaption class="[writing-mode:vertical-rl] text-[11px] font-bold text-copper-ink pe-3 border-e border-line">{name}</figcaption>
  <div class="flex flex-col justify-between gap-4"><blockquote class="text-sm text-ink-2 leading-8">{text}</blockquote><div class="flex items-center justify-between gap-2">{stars(star)}<small class="text-[11px] text-ink-2">{sub}</small></div></div>
</figure>"""


def c_post(img, cat, title, time, d="0", key="", excerpt=""):
    ex = f'<p class="text-sm text-ink-2 line-clamp-2">{excerpt}</p>' if excerpt else ""
    return f"""<a href="blog-single.html" class="group flex flex-col gap-4 fx-item" data-cat="{key or 'all'}" data-reveal="up" style="--d:{d}" data-cursor="بخوانید">
  <div class="zoom rounded-xl aspect-[3/2] img-ph"><img src="@@{img},700,470@@" alt="" class="size-full object-cover" loading="lazy" width="700" height="470"></div>
  <div class="flex flex-col gap-2">
    <p class="flex items-center gap-3 text-xs"><span class="tag tag-soft">{cat}</span><span class="inline-flex items-center gap-1 text-ink-2"><i class="ph-light ph-clock" aria-hidden="true"></i><span>{time} مطالعه</span></span></p>
    <h3 class="font-display text-2xl leading-snug transition group-hover:text-copper-ink">{title}</h3>
    {ex}
  </div>
</a>"""


def c_course(img, title, cat, level, teacher, meta, price, start, cap, star="5", d="0", key="all", full="0"):
    cap_html = '<span class="tag tag-mute">ظرفیت تکمیل</span>' if full == "1" else f'<span class="tag tag-warn">{cap} ظرفیت</span>'
    btn = ('<a href="contact.html" class="btn btn-soft btn-sm w-full">اطلاع از ترم بعد</a>' if full == "1"
           else '<a href="course-single.html" class="btn btn-dark btn-sm w-full">مشاهده و ثبت‌نام <i class="ph-light ph-arrow-left" aria-hidden="true"></i></a>')
    return f"""<article class="group card card-hover overflow-hidden flex flex-col fx-item" data-cat="{key}" data-price="{price}" data-order="{d}" data-reveal="up" style="--d:{int(d) % 3}">
  <a href="course-single.html" class="relative zoom aspect-[16/10] img-ph block" data-cursor="دوره">
    <img src="@@{img},700,440@@" alt="" class="size-full object-cover" loading="lazy" width="700" height="440">
    <span class="tag tag-white absolute top-3 right-3 z-10"><i class="ph-light ph-calendar-blank" aria-hidden="true"></i>{start}</span>
  </a>
  <div class="p-5 flex flex-col gap-3 flex-1">
    <p class="flex flex-wrap gap-2 text-xs"><span class="tag tag-soft">{cat}</span><span class="tag tag-mute">{level}</span></p>
    <h3 class="font-display text-2xl leading-snug"><a href="course-single.html" class="link-u">{title}</a></h3>
    <p class="text-xs text-ink-2">مدرس: {teacher}</p>
    <p class="flex items-center justify-between text-xs text-ink-2">{stars(star)}<span class="inline-flex items-center gap-1"><i class="ph-light ph-clock" aria-hidden="true"></i><span>{meta}</span></span></p>
    <div class="mt-auto pt-4 border-t border-line flex items-center justify-between"><strong class="num">{fa(price)} تومان</strong>{cap_html}</div>
    {btn}
  </div>
</article>"""


def c_service(img, title, dur, expert, price, key, d="0"):
    return f"""<article class="group card card-hover overflow-hidden flex flex-col sm:flex-row fx-item" data-cat="{key}" data-price="{price}" data-order="{d}" data-reveal="up" style="--d:{int(d) % 2}">
  <a href="service-single.html" class="relative zoom sm:w-44 aspect-[16/10] sm:aspect-auto img-ph block shrink-0" data-cursor="جزئیات"><img src="@@{img},500,420@@" alt="" class="size-full object-cover" loading="lazy" width="500" height="420"></a>
  <div class="p-5 flex flex-col gap-2 flex-1">
    <h3 class="font-display text-2xl leading-snug"><a href="service-single.html" class="link-u">{title}</a></h3>
    <p class="text-xs text-ink-2 flex items-center gap-1"><i class="ph-light ph-clock" aria-hidden="true"></i><span>{dur} · کارشناس: {expert}</span></p>
    <div class="mt-auto pt-3 flex items-center justify-between gap-3">
      <strong class="num">{fa(price)} <small class="font-normal text-ink-2">تومان</small></strong>
      <a href="service-single.html" class="link-arrow text-sm">جزئیات <i class="ph-light ph-arrow-left" aria-hidden="true"></i></a>
    </div>
  </div>
</article>"""


STEP_SETS = {
    "shop": ["سبد خرید", "آدرس و ارسال", "پرداخت"],
    "enroll": ["انتخاب دوره", "اطلاعات هنرجو", "پرداخت", "تأیید ثبت‌نام"],
}


def c_steps(kind, current):
    cur = int(current)
    items = []
    for i, label in enumerate(STEP_SETS[kind], 1):
        state = "is-done" if i < cur else "is-current" if i == cur else ""
        mark = "✓" if i < cur else fa(i)
        aria = ' aria-current="step"' if i == cur else ""
        if i > 1:
            items.append('<li class="step-line" aria-hidden="true"></li>')
        items.append(f'<li class="step {state}"{aria}><b>{mark}</b><span class="hidden sm:inline">{label}</span></li>')
    return f'<ol class="steps mt-8 max-w-2xl" aria-label="مراحل" data-reveal="up" style="--d:3">{"".join(items)}</ol>'


COMPONENTS = {
    "steps": c_steps,
    "product": c_product, "member": c_member, "review": c_review, "post": c_post,
    "course": c_course, "service": c_service, "stars": stars,
}


def expand(m):
    name, args = m.group(1), m.group(2)
    if name not in COMPONENTS:
        sys.exit(f"unknown component: {name}")
    return COMPONENTS[name](*[a.strip() for a in args.split("|")])


def page_hero(h):
    crumbs = h.get("crumbs", [])
    parts = []
    for i, (label, href) in enumerate(crumbs):
        if i:
            parts.append('<span aria-hidden="true">/</span>')
        if href:
            parts.append(f'<a href="{href}" class="link-u">{label}</a>')
        else:
            parts.append(f'<span aria-current="page" class="text-white">{label}</span>')
    outline = h.get("outline", "")
    sub = f'<p class="mt-5 max-w-xl text-white/75" data-reveal="up" style="--d:3">{h["sub"]}</p>' if h.get("sub") else ""
    return f"""
<section class="grad spotlight page-hero" data-hero>
  <div class="aurora" aria-hidden="true"><span></span><span></span><span></span></div>
  <span class="outline-text font-display absolute -bottom-6 md:-bottom-12 left-2 md:left-8 text-[26vw] md:text-[15rem] leading-none pointer-events-none select-none" aria-hidden="true" data-parallax="-.12">{outline}</span>
  <div class="wrap relative">
    <nav class="crumbs" aria-label="مسیر صفحه" data-reveal="up">{''.join(parts)}</nav>
    <h1 class="font-display text-5xl md:text-7xl leading-[1.05] mt-4" data-split>{h['title']}</h1>
    {sub}
    {h.get('extra', '')}
  </div>
</section>
"""


def build():
    partial = {p.stem: p.read_text(encoding="utf-8") for p in (SRC / "partials").glob("*.html")}
    pages = sorted((SRC / "pages").glob("*.html"))
    for p in pages:
        raw = p.read_text(encoding="utf-8")
        first, body = raw.split("\n", 1)
        m = re.match(r"<!--META (.*)-->\s*$", first)
        if not m:
            sys.exit(f"{p.name}: first line must be <!--META {{...}}-->")
        meta = json.loads(m.group(1))
        layout = meta.get("layout", "default")

        head = partial["head"]
        head = head.replace("{{title}}", html.escape(meta["title"])).replace("{{desc}}", html.escape(meta.get("desc", "")))
        head = head.replace("{{body_class}}", meta.get("body_class", ""))

        header = partial["header"]
        if meta.get("nav"):
            header = header.replace(f'data-nav="{meta["nav"]}"', f'data-nav="{meta["nav"]}" aria-current="page"')

        hero = page_hero(meta["hero"]) if meta.get("hero") else ""
        main = f'<main id="main">\n{hero}\n{body}\n</main>\n'

        if layout == "bare":
            out = head + main + partial["tail"]
        else:
            out = head + header + main + partial["footer"] + partial["tail"]

        out = re.sub(r"\{\{(\w+) ([^{}]*)\}\}", expand, out)
        out = re.sub(r"@@([a-z_0-9]+),(\d+),(\d+)@@", img_url, out)
        leftover = re.search(r"\{\{|@@", out)
        if leftover:
            sys.exit(f"{p.name}: unexpanded token near: {out[leftover.start():leftover.start() + 60]}")
        # doctype باید اولین خط بماند
        out = out.replace("<!doctype html>\n", f"<!doctype html>\n<!-- ساخته‌شده با _src/build.py از _src/pages/{p.name}؛ این فایل را مستقیم ویرایش نکنید -->\n", 1)
        (OUT / p.name).write_text(out, encoding="utf-8")
        print("built", p.name)


if __name__ == "__main__":
    build()
