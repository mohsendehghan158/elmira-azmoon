#!/usr/bin/env python3
"""
ساخت inc/demo-posts.php: مقاله‌ها، دسته‌ها و دیدگاه‌های نمونهٔ مجله برای راه‌اندازی یک‌کلیکی.
متن مقالهٔ کامل همان blog-single.html قالب HTML است، این‌بار با بلاک‌های هستهٔ وردپرس.

اجرا:  python3 demo_posts.py
"""
import pathlib
import sys

HERE = pathlib.Path(__file__).resolve().parent
sys.path.insert(0, str(HERE.parent.parent / "elmira-theme" / "_src"))
import build as B  # noqa: E402


def img(n, w=1400, h=900):
    return f"https://images.unsplash.com/photo-{B.IMAGES[n]}?auto=format&fit=crop&w={w}&h={h}&q=70"


def P(t, cls=None):
    if cls:
        return f'<!-- wp:paragraph {{"className":"{cls}"}} -->\n<p class="{cls}">{t}</p>\n<!-- /wp:paragraph -->'
    return f'<!-- wp:paragraph -->\n<p>{t}</p>\n<!-- /wp:paragraph -->'


def H(t):
    return f'<!-- wp:heading -->\n<h2 class="wp-block-heading">{t}</h2>\n<!-- /wp:heading -->'


def IMG(n, alt, cap):
    return (f'<!-- wp:image {{"sizeSlug":"large"}} -->\n<figure class="wp-block-image size-large"><img src="{img(n, 900, 500)}" alt="{alt}"/>'
            f'<figcaption class="wp-element-caption">{cap}</figcaption></figure>\n<!-- /wp:image -->')


def Q(t, cite):
    return (f'<!-- wp:quote -->\n<blockquote class="wp-block-quote"><!-- wp:paragraph -->\n<p>{t}</p>\n<!-- /wp:paragraph -->'
            f'<cite>{cite}</cite></blockquote>\n<!-- /wp:quote -->')


def L(items, style=None):
    cls = f" is-style-{style}" if style else ""
    attrs = f' {{"className":"is-style-{style}"}}' if style else ""
    lis = "".join(f"<!-- wp:list-item -->\n<li>{i}</li>\n<!-- /wp:list-item -->" for i in items)
    return f'<!-- wp:list{attrs} -->\n<ul class="wp-block-list{cls}">{lis}</ul>\n<!-- /wp:list -->'


CTA = '<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->'

FULL = "\n\n".join([
    P("[متن نمونه] پاکسازی پوست منافذ را باز و لایهٔ بیرونی را حساس‌تر می‌کند. کاری که در دو روز بعد انجام می‌دهید تعیین می‌کند نتیجه چقدر بماند.", "is-style-lead"),
    H("۱. چرا ۴۸ ساعت اول مهم است"),
    P("بعد از فیشیال، پوست در حال ترمیم است و نسبت به نور، گرما و مواد فعال واکنش بیشتری نشان می‌دهد. اگر در این مدت فشار زیادی به آن بیاید، قرمزی و جوش‌های ریز ظاهر می‌شوند."),
    IMG("cream_swipe", "رد کرم مراقبت پوست روی سطح روشن", "یک کرم آبرسان ساده، بهترین دوست پوست بعد از پاکسازی است."),
    H("۲. چه کارهایی انجام بدهید"),
    P("صورت را فقط با آب ولرم و شویندهٔ ملایم بشویید، آبرسان بزنید و اگر بیرون می‌روید حتماً ضدآفتاب استفاده کنید."),
    Q("«بعد از پاکسازی، کمتر بیشتر است: شست‌وشوی ملایم، آبرسانی و ضدآفتاب.»", "— المیرا آزمون"),
    H("۳. از چه چیزهایی دوری کنید"),
    L(["لایه‌بردارها و اسکراب‌ها تا دست‌کم ۴۸ ساعت", "سونا، استخر و ورزش سنگین در روز اول", "آرایش سنگین و کرم‌پودرهای غلیظ"], "avoid"),
    H("۴. جمع‌بندی"),
    P("دو روز مراقبت ساده، نتیجهٔ یک جلسهٔ پاکسازی را هفته‌ها نگه می‌دارد. اگر سؤال خاصی دربارهٔ پوستتان دارید، در نوبت بعد از کارشناس بپرسید."),
    CTA,
])


def short(title):
    return "\n\n".join([
        P(f"[متن نمونه] این مقاله نمونه است؛ متن واقعی «{title}» را این‌جا بنویسید.", "is-style-lead"),
        H("۱. نکتهٔ اول"), P("[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است."),
        H("۲. نکتهٔ دوم"), P("[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم."),
        L(["[مورد نمونه ۱]", "[مورد نمونه ۲]", "[مورد نمونه ۳]"], "check"),
        H("۳. جمع‌بندی"), P("[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن."), CTA])


POSTS = [
    dict(slug="autumn-skincare-routine", title="روتین مراقبت پوست در پاییز: از شست‌وشو تا ضدآفتاب", cat="skin", image=img("cosmetics"), sticky=True,
         excerpt="[خلاصهٔ نمونه] با سرد شدن هوا، پوست زودتر آب از دست می‌دهد. در این مقاله قدم‌به‌قدم روتین صبح و شب را مرور می‌کنیم.",
         content=short("روتین مراقبت پوست در پاییز"), tags=["مراقبت پوست", "پاییز"]),
    dict(slug="after-facial-routine", title="روتین پوست بعد از فیشیال: ۴۸ ساعت اول", cat="skin", image=img("facial"),
         excerpt="چه کارهایی را انجام بدهید و از چه چیزهایی دوری کنید تا نتیجهٔ پاکسازی بماند.", content=FULL,
         tags=["مراقبت پوست", "پاکسازی", "پوست چرب", "سرم ویتامین C"],
         comments=[["کاربر نمونه", "ممنون، دقیقاً همین سؤال را داشتم. ضدآفتاب معدنی بهتر است یا شیمیایی؟",
                    "بعد از پاکسازی، ضدآفتاب معدنی معمولاً ملایم‌تر است."]]),
    dict(slug="make-hair-color-last", title="چطور رنگ مو دیرتر بشوید؟", cat="hair", image=img("wash"),
         excerpt="دمای آب، نوع شامپو و فاصلهٔ شست‌وشو؛ سه چیزی که عمر رنگ را تعیین می‌کند.", content=short("چطور رنگ مو دیرتر بشوید؟")),
    dict(slug="clean-makeup-brushes", title="برس‌های آرایشی را چطور بشوییم", cat="makeup", image=img("brushes"),
         excerpt="روش ساده و هفتگی برای تمیز نگه داشتن برس‌ها و اسفنج‌ها.", content=short("برس‌های آرایشی را چطور بشوییم")),
    dict(slug="student-to-pro-artist", title="از هنرجو تا آرایشگر حرفه‌ای: ۵ قدم اول", cat="career", image=img("tools"),
         excerpt="تجربهٔ هنرجوهای آموزشگاه از ماه‌های اول کار.", content=short("از هنرجو تا آرایشگر حرفه‌ای")),
    dict(slug="brittle-nails", title="ناخن‌های شکننده: علت‌ها و راه‌حل‌ها", cat="nail", image=img("nailart"),
         excerpt="چرا ناخن می‌شکند و چه عادت‌هایی کمک می‌کند.", content=short("ناخن‌های شکننده")),
    dict(slug="vitamin-c-serum-guide", title="سرم ویتامین C را کِی و چطور بزنیم؟", cat="skin", image=img("dropper"),
         excerpt="ترتیب درست لایه‌ها و ترکیب‌هایی که بهتر است کنار هم نباشند.", content=short("سرم ویتامین C")),
]

CATEGORIES = {"skin": "مراقبت پوست", "hair": "مو و رنگ", "makeup": "آرایش", "nail": "ناخن", "career": "آموزش و شغل"}


def php(v, ind=1):
    t = "\t" * ind
    if isinstance(v, bool):
        return "true" if v else "false"
    if isinstance(v, str):
        return "'" + v.replace("\\", "\\\\").replace("'", "\\'") + "'"
    if isinstance(v, list):
        return "array( " + ", ".join(php(x, ind + 1) for x in v) + " )"
    return "array(\n" + "".join(f"{t}\t{php(k)} => {php(x, ind + 1)},\n" for k, x in v.items()) + t + ")"


if __name__ == "__main__":
    out = ("<?php\n/**\n * محتوای نمونهٔ مجله برای راه‌اندازی یک‌کلیکی.\n * ساخته‌شده با wordpress/build/demo_posts.py؛ مستقیم ویرایش نکنید.\n *\n"
           " * @package elmira\n */\n\ndefined( 'ABSPATH' ) || exit;\n\nreturn " + php({"categories": CATEGORIES, "posts": POSTS}, 0) + ";\n")
    (HERE.parent / "elmira-azmoon" / "inc" / "demo-posts.php").write_text(out, encoding="utf-8")
    print("demo posts:", len(POSTS))
