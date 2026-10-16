"""Turns a wget crawl of the WordPress site into a clean static site.

Usage: postprocess.py OUT_DIR LOCAL_ORIGIN PUBLIC_ORIGIN
"""
import os
import re
import sys
from urllib.parse import quote

out, local, public = sys.argv[1], sys.argv[2].rstrip("/"), sys.argv[3].rstrip("/")

# wget saves "style.css?ver=123" as a file with the query in its name; the
# server ignores query strings, so strip it from the filename.
for root, _, files in os.walk(out):
    for name in files:
        if "?" in name:
            clean = name.split("?", 1)[0]
            os.replace(os.path.join(root, name), os.path.join(root, clean))

# The database also has some URLs from the original XAMPP install.
old_origins = [local, "http://localhost/reddyanna"]
origin_variants = []
for old in old_origins:
    origin_variants += [
        (old, public),
        (old.replace("/", "\\/"), public.replace("/", "\\/")),
        (quote(old, safe=""), quote(public, safe="")),
    ]
origin_variants.append(("//" + local.split("//", 1)[1], "//" + public.split("//", 1)[1]))

# Things that only work with a running WordPress backend.
remove_patterns = [
    r'<link rel="alternate" type="application/rss\+xml"[^>]*>\n?',
    r"<link rel='dns-prefetch' href='//localhost' />\n?",
    r'<script id="wp-hooks-js"[^>]*></script>\n?',
    r'<script id="wp-i18n-js"[^>]*></script>\n?',
    r'<script id="wp-i18n-js-after">.*?</script>\n?',
    r'<script id="swv-js"[^>]*></script>\n?',
    r'<script id="contact-form-7-js-before">.*?</script>\n?',
    r'<script id="contact-form-7-js"[^>]*></script>\n?',
]

for root, _, files in os.walk(out):
    for name in files:
        if not name.endswith((".html", ".xml", ".css", ".js", ".txt")):
            continue
        path = os.path.join(root, name)
        with open(path, encoding="utf-8", errors="surrogateescape") as f:
            text = f.read()
        orig = text
        for old, new in origin_variants:
            text = text.replace(old, new)
        if name.endswith(".html"):
            for pat in remove_patterns:
                text = re.sub(pat, "", text, flags=re.S)
            # WordPress search needs PHP; send it to a Google site search.
            text = re.sub(
                r'(<form role="search" method="get" class="search-form") action="[^"]*">',
                r'\1 action="https://www.google.com/search">'
                f'<input type="hidden" name="as_sitesearch" value="{public.split("//", 1)[1]}">',
                text,
            )
            text = re.sub(r'(<input type="search"[^>]*?)name="s"', r'\1name="q"', text)
            # The theme marks localhost builds noindex (inc/seo.php); the
            # published copy lives on the real domain, so use Yoast's default.
            if name != "404.html":
                text = text.replace(
                    "<meta name='robots' content='noindex, nofollow' />",
                    "<meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />",
                )
            if "wpcf7-form" in text:
                text = text.replace("</body>", '<script src="/static-forms.js" defer></script>\n</body>', 1)
        if text != orig:
            with open(path, "w", encoding="utf-8", errors="surrogateescape") as f:
                f.write(text)
