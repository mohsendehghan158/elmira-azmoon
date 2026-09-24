#!/usr/bin/env python3
"""
تبدیل قالب HTML المیرا آزمون به قالب بلاکی وردپرس.

ورودی:  elmira-theme/_src  (صفحه‌ها، پارشیال‌ها و کامپوننت‌های build.py)
         wordpress/build/src (قالب‌های وردپرسی: single، archive، 404 و ...)
خروجی:  wordpress/elmira-azmoon/patterns، templates، parts و inc/chrome

هر عنصر HTML به یک بلاک «elmira/el» تبدیل می‌شود که تگ، کلاس‌ها و همهٔ
data-attributeها را نگه می‌دارد؛ پس ظاهر و رفتار JS دقیقاً مثل قالب HTML است و
متن‌ها، تصاویر و پیوندها در گوتنبرگ قابل ویرایش‌اند.

اجرا:  python3 convert.py
"""
import json
import pathlib
import re
import sys

from bs4 import BeautifulSoup, Comment, NavigableString, Tag
from bs4.dammit import EntitySubstitution
from bs4.formatter import HTMLFormatter

HERE = pathlib.Path(__file__).resolve().parent
REPO = HERE.parent.parent
SRC = REPO / "elmira-theme" / "_src"
THEME = HERE.parent / "elmira-azmoon"
WP_SRC = HERE / "src"

sys.path.insert(0, str(SRC))
import build as B  # noqa: E402  (کامپوننت‌ها و تصاویر قالب HTML)

# ---------------------------------------------------------------------------
# صفحه‌ها: فایل HTML → مسیر برگه در وردپرس
# ---------------------------------------------------------------------------
PAGES = {
    # name: (path, title, group, template)
    "index": ("", "خانه", "general", "page-canvas"),
    "booking": ("services", "خدمات سالن", "salon", "page-canvas"),
    "service-single": ("services/bridal-makeup", "میکاپ عروس", "salon", "page-canvas"),
    "gallery": ("gallery", "گالری نمونه‌کار", "salon", "page-canvas"),
    "shop": ("shop", "فروشگاه", "shop", "page-canvas"),
    "product-single": ("shop/vitamin-c-serum", "سرم روشن‌کنندهٔ پوست", "shop", "page-canvas"),
    "cart": ("cart", "سبد خرید", "shop", "page-canvas"),
    "checkout": ("checkout", "تسویه حساب", "shop", "page-canvas"),
    "academy": ("academy", "آموزشگاه", "academy", "page-canvas"),
    "course-single": ("academy/pro-makeup", "دورهٔ جامع میکاپ حرفه‌ای", "academy", "page-canvas"),
    "enroll": ("academy/enroll", "ثبت‌نام دوره", "academy", "page-canvas"),
    "about": ("about", "درباره ما", "general", "page-canvas"),
    "contact": ("contact", "تماس با ما", "general", "page-canvas"),
    "auth": ("auth", "ورود / ثبت‌نام", "general", "page-blank"),
    "account": ("account", "پنل کاربری", "general", "page-canvas"),
}
# صفحه‌هایی که در وردپرس قالب (template) شده‌اند و الگوی برگه ندارند
LINK_ONLY = {"blog": "blog", "blog-single": "blog", "404": "page-not-found"}

GROUPS = {
    "general": "المیرا: عمومی",
    "salon": "المیرا: سالن",
    "shop": "المیرا: فروشگاه",
    "academy": "المیرا: آموزشگاه",
}


def page_url(name):
    path = PAGES[name][0] if name in PAGES else LINK_ONLY[name]
    return "/" + (path + "/" if path else "")


def map_href(h):
    """پیوندهای x.html → مسیر نسبی به ریشهٔ سایت (در PHP به نشانی کامل تبدیل می‌شود)."""
    m = re.fullmatch(r"([a-z0-9-]+)\.html(#.*)?", h or "")
    if not m or (m.group(1) not in PAGES and m.group(1) not in LINK_ONLY):
        return h
    return page_url(m.group(1)) + (m.group(2) or "")


def map_links_in_html(s):
    return re.sub(r'href="([a-z0-9-]+\.html(?:#[^"]*)?)"', lambda m: f'href="{map_href(m.group(1))}"', s)


# ---------------------------------------------------------------------------
# سریال‌سازی بلاک (همان قواعد گوتنبرگ برای JSON داخل کامنت)
# ---------------------------------------------------------------------------
def attrs_json(a):
    s = json.dumps(a, ensure_ascii=False, separators=(",", ":"))
    s = s.replace("--", "\\u002d\\u002d").replace("<", "\\u003c").replace(">", "\\u003e")
    s = s.replace("&", "\\u0026").replace('\\"', "\\u0022")
    return s


def block(name, attrs=None, inner=None):
    name = name.removeprefix("core/")
    head = f"<!-- wp:{name}" + (f" {attrs_json(attrs)}" if attrs else "")
    if not inner:
        return head + " /-->"
    return head + " -->\n" + "\n".join(inner) + "\n<!-- /wp:" + name + " -->"


# ---------------------------------------------------------------------------
# HTML → elmira/el
# ---------------------------------------------------------------------------
VOID = {"img", "input", "br", "hr", "source", "wbr", "meta", "link"}
RAW = {"svg", "select", "textarea", "script", "style", "noscript", "output", "video", "iframe", "picture"}
INLINE = {"strong", "b", "em", "i", "small", "span", "a", "br", "del", "ins", "sup", "sub", "u",
          "mark", "code", "abbr", "time", "kbd", "q", "s", "bdi", "cite"}
INTERACTIVE = {"img", "input", "select", "textarea", "button", "a", "iframe", "video", "form", "label", "wp-block"}

ALL_CLASSES = set()


def text_of(node):
    return node.get_text() if isinstance(node, Tag) else str(node)


def has_text(node):
    return bool(text_of(node).strip())


def is_decor(node):
    """عنصر بی‌متن (آیکن، اسپن تزئینی) که می‌تواند قبل یا بعد از متن بیاید."""
    return isinstance(node, Tag) and not has_text(node) and node.name not in ("img", "br")


def inline_only(node):
    """آیا زیرشاخه فقط متن و تگ‌های درون‌خطی متن‌دار است؟ (قابل ویرایش با RichText)"""
    if isinstance(node, NavigableString):
        return not isinstance(node, Comment)
    if node.name not in INLINE:
        return False
    if node.name != "br" and not has_text(node):
        return False
    return all(inline_only(c) for c in node.children)


def significant(children):
    return [c for c in children
            if not isinstance(c, Comment) and not (isinstance(c, NavigableString) and not c.strip())]


# فقط & و < و > نویسه‌گردانی شود تا نیم‌فاصله و متن فارسی خوانا بماند
FMT = HTMLFormatter(entity_substitution=EntitySubstitution.substitute_xml, void_element_close_prefix=None,
                    empty_attributes_are_booleans=True)


def html5(node_or_nodes):
    if isinstance(node_or_nodes, list):
        return "".join(n.decode(formatter=FMT) if isinstance(n, Tag) else str(n) for n in node_or_nodes) \
            if node_or_nodes else ""
    return node_or_nodes.decode_contents(formatter=FMT)


def clean_ws(s):
    return re.sub(r"\s+", " ", s)


def el_attrs(tag):
    a = {"tag": tag.name} if tag.name != "div" else {}
    extra = {}
    for k, v in tag.attrs.items():
        if isinstance(v, list):
            v = " ".join(v)
        if k == "class":
            v = clean_ws(v).strip()
            ALL_CLASSES.update(v.split())
            if v:
                a["className"] = v
        else:
            if k == "href":
                v = map_href(v)
            extra[k] = v
    if extra:
        a["attrs"] = extra
    return a


LABELS = [
    ("data-product", "کارت محصول"),
    ("data-hero", "هیرو"),
    ("data-carousel", "اسلایدر نظرات"),
    ("data-acc", "آکاردئون"),
    ("data-filter", "فیلتر دسته‌ها"),
    ("data-marquee", "نوار متحرک"),
    ("data-rotator", "نکته‌های چرخشی"),
    ("data-validate", "فرم"),
]


def auto_name(tag):
    for attr, label in LABELS:
        if tag.has_attr(attr):
            return label
    return None


def is_post_grid(tag):
    kids = [c for c in significant(tag.children)]
    return (len(kids) >= 2 and all(isinstance(c, Tag) and c.name == "a" and map_href(c.get("href")) == "/blog/"
                                   and "fx-item" in (c.get("class") or []) for c in kids))


def convert(tag, name=None):
    """یک Tag → رشتهٔ بلاک."""
    # جای‌نگهدار بلاک‌های دیگر: <wp-block name="elmira/posts" attrs='{...}'>
    if tag.name == "wp-block":
        attrs = json.loads(tag.get("attrs", "{}") or "{}")
        inner = convert_children(tag)
        return block(tag["name"], attrs, inner)

    # شبکهٔ کارت‌های مقاله → بلاک پویای آخرین مقاله‌ها
    if is_post_grid(tag):
        cls = " ".join(tag.get("class") or [])
        ALL_CLASSES.update(cls.split())
        return block("elmira/posts", {"count": len(significant(tag.children)), "gridClass": cls,
                                      "metadata": {"name": "آخرین مقاله‌ها"}})

    a = el_attrs(tag)
    name = name or auto_name(tag)
    if name:
        a["metadata"] = {"name": name}
    kids = significant(tag.children)

    if tag.name in VOID:
        a["mode"] = "void"
        return block("elmira/el", a)

    if tag.name in RAW or not kids:
        a["mode"] = "html"
        a["html"] = map_links_in_html(html5(tag)) if kids else ""
        return block("elmira/el", a)

    # زیرشاخهٔ کاملاً تزئینی (بدون متن و بدون عنصر تعاملی) → یک بلاک HTML
    if not has_text(tag) and not tag.find(list(INTERACTIVE)):
        a["mode"] = "html"
        a["html"] = html5(tag).strip()
        return block("elmira/el", a)

    lead = 0
    while lead < len(kids) and is_decor(kids[lead]):
        lead += 1
    trail = len(kids)
    while trail > lead and is_decor(kids[trail - 1]):
        trail -= 1
    middle = kids[lead:trail]

    text_nodes = [c for c in middle if isinstance(c, NavigableString) and c.strip()]
    if middle and (text_nodes or (len(middle) == 1 and middle[0].name in INLINE)) and all(inline_only(c) for c in middle):
        # متن قابل ویرایش + آیکن‌های ابتدا و انتها
        children = [c for c in tag.children if not isinstance(c, Comment)]
        first, last = children.index(middle[0]), children.index(middle[-1])
        before = html5(children[:first])
        after = html5(children[last + 1:])
        content = html5(children[first:last + 1])
        a["mode"] = "text"
        a["content"] = map_links_in_html(clean_ws(content).strip())
        if before.strip():
            a["before"] = clean_ws(before).lstrip()
        if after.strip():
            a["after"] = clean_ws(after).rstrip()
        for t in tag.find_all(True):
            ALL_CLASSES.update(t.get("class") or [])
        return block("elmira/el", a)

    if not text_nodes and all(isinstance(c, Tag) for c in kids):
        inner = convert_children(tag)
        return block("elmira/el", a, inner)

    # محتوای درهم (متن + عنصرهای پیچیده) → HTML خام
    a["mode"] = "html"
    a["html"] = map_links_in_html(clean_ws(html5(tag)).strip())
    for t in tag.find_all(True):
        ALL_CLASSES.update(t.get("class") or [])
    return block("elmira/el", a)


def section_name(comment):
    return re.sub(r"^[=\s-]+|[=\s-]+$", "", str(comment)).strip() or None


def convert_children(parent):
    out, pending = [], None
    for c in parent.children:
        if isinstance(c, Comment):
            pending = section_name(c)
        elif isinstance(c, Tag):
            out.append(convert(c, pending))
            pending = None
    return out


def top_level(parent):
    """[(نام بخش، بلاک)] برای عنصرهای سطح اول."""
    out, pending = [], None
    for c in parent.children:
        if isinstance(c, Comment):
            pending = section_name(c)
        elif isinstance(c, Tag):
            out.append((pending or auto_name(c), convert(c, pending)))
            pending = None
    return out


def soup(s):
    return BeautifulSoup(s, "html.parser")


def expand_tokens(s):
    s = re.sub(r"\{\{(\w+) ([^{}]*)\}\}", B.expand, s)
    s = re.sub(r"@@([a-z_0-9]+),(\d+),(\d+)@@", B.img_url, s)
    left = re.search(r"\{\{|@@", s)
    if left:
        sys.exit(f"unexpanded token near: {s[left.start():left.start() + 60]}")
    return s


def read_page(name):
    raw = (SRC / "pages" / f"{name}.html").read_text(encoding="utf-8")
    first, body = raw.split("\n", 1)
    meta = json.loads(re.match(r"<!--META (.*)-->\s*$", first).group(1))
    hero = B.page_hero(meta["hero"]) if meta.get("hero") else ""
    return meta, expand_tokens(hero + "\n" + body)


# ---------------------------------------------------------------------------
# نوشتن فایل‌ها
# ---------------------------------------------------------------------------
def php_str(s):
    return s.replace("\\", "\\\\").replace("'", "\\'")


def write(path, text):
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(text, encoding="utf-8")


def pattern_file(slug, title, cats, content, extra_headers="", desc=""):
    headers = [f" * Title: {title}", f" * Slug: elmira/{slug}", f" * Categories: {', '.join(cats)}"]
    if desc:
        headers.append(f" * Description: {desc}")
    headers += [h for h in extra_headers.split("\n") if h]
    return ("<?php\n/**\n" + "\n".join(headers) + "\n *\n * ساخته‌شده با wordpress/build/convert.py؛ مستقیم ویرایش نکنید.\n *\n"
            " * @package elmira\n */\n?>\n" + content + "\n")


def build_pages():
    out_dir = THEME / "patterns"
    for old in out_dir.glob("*.php"):
        old.unlink()
    manifest = []
    for name, (path, title, group, template) in PAGES.items():
        meta, html = read_page(name)
        parts = top_level(soup(html))
        content = "\n\n".join(b for _, b in parts)
        slug = f"page-{name}"
        write(out_dir / f"{slug}.php", pattern_file(
            slug, f"برگهٔ {title}", ["elmira-pages"], content,
            " * Block Types: core/post-content\n * Post Types: page\n * Viewport Width: 1400",
            meta.get("desc", "")))
        manifest.append({"name": name, "path": path, "title": title, "template": template,
                         "pattern": f"elmira/{slug}", "excerpt": meta.get("desc", "")})
        # بخش‌های نام‌دار هر صفحه به‌عنوان الگوی جدا در دستهٔ همان بخش
        n = 0
        for sec_name, b in parts:
            if not sec_name or sec_name == "هیرو" and name != "index":
                continue
            n += 1
            write(out_dir / f"{name}-{n:02d}.php", pattern_file(
                f"{name}-{n:02d}", f"{title}: {sec_name}", [f"elmira-{group}"], b,
                " * Viewport Width: 1400"))
    # فراخوان رزرو داخل مقاله، برای استفاده در نوشته‌ها
    cta = (WP_SRC / "patterns" / "booking-cta.html").read_text(encoding="utf-8")
    write(out_dir / "booking-cta.php", pattern_file(
        "booking-cta", "فراخوان رزرو (کارت گرادیانی)", ["elmira-general", "call-to-action"],
        "\n".join(b for _, b in top_level(soup(expand_tokens(cta)))), " * Viewport Width: 900"))
    write(THEME / "inc" / "demo-pages.json",
          json.dumps(manifest, ensure_ascii=False, indent=1) + "\n")


def build_templates():
    for f in sorted((WP_SRC / "templates").glob("*.html")):
        html = f.read_text(encoding="utf-8")
        html = re.sub(r"\{\{\{page:([a-z0-9-]+)\}\}\}", lambda m: read_page(m.group(1))[1], html)
        html = re.sub(r"\{\{\{include:([a-z0-9-]+)\}\}\}", lambda m: (WP_SRC / "includes" / f"{m.group(1)}.html").read_text(encoding="utf-8"), html)
        html = expand_tokens(html)
        blocks = [b for _, b in top_level(soup(html))]
        write(THEME / "templates" / f.name, "\n\n".join(blocks) + "\n")
    for f in sorted((WP_SRC / "parts").glob("*.html")):
        html = expand_tokens(f.read_text(encoding="utf-8"))
        write(THEME / "parts" / f.name, "\n\n".join(b for _, b in top_level(soup(html))) + "\n")


def build_chrome():
    """اجزای مشترک که با PHP چاپ می‌شوند (کشوها، لایت‌باکس، ...)."""
    partials = {p.stem: p.read_text(encoding="utf-8") for p in (SRC / "partials").glob("*.html")}
    tail = partials["tail"].split("<script", 1)[0]
    write(THEME / "inc" / "chrome" / "tail.html", map_links_in_html(expand_tokens(tail)).strip() + "\n")
    footer = map_links_in_html(expand_tokens(partials["footer"]))
    write(WP_SRC / "parts" / "footer.html", "<!-- ساخته‌شده از elmira-theme/_src/partials/footer.html -->\n" + footer)


def collect_classes():
    files = list((SRC / "pages").glob("*.html")) + list((SRC / "partials").glob("*.html")) + list(WP_SRC.rglob("*.html"))
    for p in files:
        s = re.sub(r"\{\{(\w+) ([^{}]*)\}\}", B.expand, p.read_text(encoding="utf-8"))
        for m in re.finditer(r'class=["\']([^"\']*)["\']', s):
            ALL_CLASSES.update(m.group(1).split())
    write(HERE / "classes.txt", "\n".join(sorted(ALL_CLASSES)) + "\n")


if __name__ == "__main__":
    build_chrome()
    build_pages()
    build_templates()
    collect_classes()
    print("patterns:", len(list((THEME / "patterns").glob("*.php"))),
          "templates:", len(list((THEME / "templates").glob("*.html"))),
          "classes:", len(ALL_CLASSES))
