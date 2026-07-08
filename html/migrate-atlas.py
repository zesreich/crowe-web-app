#!/usr/bin/env python3
"""Migrate all HTML pages to HSY Atlas (ecosystem-mockup) shell."""

from pathlib import Path
import re

ROOT = Path(__file__).resolve().parent

FOUC = """    <script>
    (function(){try{var d=localStorage.getItem('darkmode');var a=localStorage.getItem('hsy-atlas-theme');var dark=d==='active'||(d===null&&a==='dark')||(d===null&&a===null&&window.matchMedia('(prefers-color-scheme: dark)').matches);if(dark){document.documentElement.setAttribute('data-theme','dark');document.documentElement.classList.add('darkmode');}else{document.documentElement.setAttribute('data-theme','light');}}catch(e){}})();
    </script>"""

HEAD_ASSETS = """
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="{base}atlas-shell.css" rel="stylesheet">
    <link href="{base}atlas-compat.css" rel="stylesheet">"""

NAV = [
    ("dashboard", "dashboard.html", "ph-squares-four", "Kontrol paneli"),
    ("clients", "client-list.html", "ph-buildings", "Müşteriler"),
    ("offers", "offers.html", "ph-file-text", "Teklifler"),
    ("contracts", "contracts.html", "ph-signature", "Sözleşmeler"),
    ("payments", "payments.html", "ph-money", "Ödemeler"),
    ("reports", "reports.html", "ph-chart-line", "Raporlar"),
    ("users", "users.html", "ph-users-three", "Kullanıcılar"),
    ("online", "online-users.html", "ph-pulse", "Çevrimiçi"),
    ("ecosystem", "ecosystem-mockup/", "ph-folder-simple", "Ekosistem"),
    ("auditor", "auditor-dashboard.html", "ph-compass", "Denetçi"),
]

ADMIN = {
    "dashboard.html": ("Kontrol paneli", "dashboard", "Genel bakış", True),
    "client-list.html": ("Müşteriler", "clients", "Operasyon", False),
    "client-detail.html": ("Müşteri detayı", "clients", "Detay", False),
    "offers.html": ("Teklifler", "offers", "Operasyon", False),
    "contracts.html": ("Sözleşmeler", "contracts", "Operasyon", False),
    "payments.html": ("Gelen ödemeler", "payments", "Operasyon", False),
    "reports.html": ("Raporlar", "reports", "Raporlama", False),
    "users.html": ("Kullanıcılar", "users", "Yönetim", False),
    "online-users.html": ("Çevrimiçi kullanıcılar", "online", "Raporlama", False),
    "denetim-rehberi.html": ("Denetim rehberi", "ecosystem", "Araçlar", False),
    "auditor-dashboard.html": ("Denetçi paneli", "auditor", "Denetçi", False),
}


def build_rail(active: str, base: str = "") -> str:
    items = []
    for nav_id, href, icon, label in NAV:
        cur = ' aria-current="page"' if nav_id == active else ""
        items.append(
            f'        <a class="nav-btn" href="{base}{href}"{cur}>\n'
            f'          <i class="ph {icon}"></i><span>{label}</span>\n'
            f"        </a>"
        )
    nav_html = "\n".join(items)
    return f"""    <aside class="rail" aria-label="Ana menü">
      <a class="brand" href="{base}dashboard.html" style="text-decoration:none;color:inherit;">
        <div class="brand-mark" aria-hidden="true"><i class="ph ph-compass-tool"></i></div>
        <div class="brand-text">
          <strong>HSY Atlas</strong>
          <span>Denetim ekosistemi</span>
        </div>
      </a>
      <nav class="nav" aria-label="Modüller">
{nav_html}
      </nav>
      <div class="rail-foot">
        <div class="persona">
          <div class="avatar" id="userAvatarInitial" aria-hidden="true">HS</div>
          <div class="persona-meta">
            <strong id="personaName">Kullanıcı</strong>
            <span id="personaRole">Yönetici</span>
          </div>
        </div>
        <button class="btn btn-ghost btn-sm" type="button" id="atlasLogout" style="width:100%;justify-content:center;">
          <i class="ph ph-sign-out"></i> Çıkış
        </button>
      </div>
    </aside>"""


def build_top(title: str, section: str, base: str = "", sync: bool = False) -> str:
    sync_html = ""
    if sync:
        sync_html = """
      <div class="sync-pill" data-state="idle" title="Senkron durumu">
        <i class="ph ph-arrows-clockwise" aria-hidden="true"></i>
        <span>3 cihaz · güncel</span>
      </div>"""
    return f"""    <header class="top">
      <div class="crumb">
        <span>Atlas</span>
        <i class="ph ph-caret-right" aria-hidden="true"></i>
        <span>{section}</span>
        <i class="ph ph-caret-right" aria-hidden="true"></i>
        <strong>{title}</strong>
      </div>
      <label class="search" aria-label="Kısayol ara">
        <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
        <input type="search" id="atlasSearch" placeholder="Modül ara… Enter" autocomplete="off">
      </label>{sync_html}
      <span id="adminEnvBadge" class="badge" style="display:none;font-size:0.6875rem;"></span>
      <button class="icon-btn" type="button" id="themeToggle" aria-label="Tema">
        <i class="ph ph-moon" id="themeIcon"></i>
      </button>
    </header>"""


def extract_title(html: str) -> str:
    m = re.search(r"<title>(.*?)</title>", html, re.I | re.S)
    return re.sub(r"\s+", " ", m.group(1).strip()) if m else "HSY Atlas"


def extract_main(html: str) -> str:
    for pat in (
        r'<main[^>]*id="main-content"[^>]*>(.*?)</main>',
        r'<main[^>]*class="[^"]*notika-main[^"]*"[^>]*>(.*?)</main>',
        r"<main[^>]*>(.*?)</main>",
    ):
        m = re.search(pat, html, re.S | re.I)
        if m:
            return m.group(1).strip()
    m = re.search(r"<body[^>]*>(.*)</body>", html, re.S | re.I)
    if not m:
        return "<p>İçerik yüklenemedi.</p>"
    body = m.group(1)
    # Strip old shells
    body = re.sub(r'<a class="skip-link"[^>]*>.*?</a>\s*', "", body, flags=re.S)
    body = re.sub(r"<header class=\"notika-header.*?</header>\s*", "", body, flags=re.S)
    body = re.sub(r"<nav class=\"navbar.*?</nav>\s*", "", body, flags=re.S)
    body = re.sub(r'<div class="notika-breadcomb".*?</div>\s*', "", body, flags=re.S)
    body = re.sub(r'<div id="wrapper">.*?<div id="content-wrapper">\s*', "", body, flags=re.S)
    body = re.sub(r"</div>\s*</div>\s*(?=<!--|\s*<script)", "", body, flags=re.S)
    return body.strip()


def extract_tail(html: str) -> str:
    idx = html.lower().rfind("</main>")
    if idx == -1:
        idx = html.find("<!-- jQuery -->")
    if idx == -1:
        scripts = list(re.finditer(r"<script[\s>]", html, re.I))
        if scripts:
            idx = scripts[0].start()
        else:
            return '<script src="atlas-shell.js"></script>\n'
    tail = html[idx:]
    tail = re.sub(r"</main>\s*", "", tail, count=1)
    tail = re.sub(r"<link[^>]*(notika-hsy|darkmode|atlas)\.css[^>]*>\s*", "", tail, flags=re.I)
    tail = re.sub(r'<script[^>]*darkmode\.js[^>]*>\s*', "", tail, flags=re.I)
    if "jquery" not in tail.lower():
        tail = '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>\n' + tail
    if "bootstrap.bundle" not in tail.lower() and "bootstrap" not in tail.lower():
        tail += '\n<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>\n'
    if "atlas-shell.js" not in tail:
        tail = tail.replace("</body>", '<script src="atlas-shell.js"></script>\n</body>')
        if "</body>" not in tail:
            tail += '\n<script src="atlas-shell.js"></script>\n'
    # Deduplicate repeated script blocks (migration safety)
    seen = set()
    lines = tail.split("\n")
    deduped = []
    for line in lines:
        key = line.strip()
        if key.startswith("<script") and key in seen:
            continue
        if key.startswith("<script"):
            seen.add(key)
        deduped.append(line)
    tail = "\n".join(deduped)
    return tail.strip()


def extract_page_styles(html: str) -> str:
    head = html.split("</head>", 1)[0] if "</head>" in html else ""
    styles = re.findall(r"<style[^>]*>(.*?)</style>", head, re.S | re.I)
    keep = []
    skip = ("notika", "sidebar", "topbar", "navbar", "login-card", "webgl")
    for s in styles:
        if any(k in s.lower() for k in skip):
            continue
        if len(s.strip()) > 20:
            keep.append(s.strip())
    if not keep:
        return ""
    return "    <style>\n" + "\n".join(keep) + "\n    </style>"


def build_admin_page(name: str, html: str, base: str = "") -> str:
    title, nav_id, section, sync = ADMIN[name]
    page_title = extract_title(html)
    extra_style = extract_page_styles(html)
    content = extract_main(html)
    tail = extract_tail(html)
    tail = tail.replace('src="atlas-shell.js"', f'src="{base}atlas-shell.js"')
    tail = tail.replace('src="config.js"', f'src="{base}config.js"')
    tail = tail.replace('src="auth.js"', f'src="{base}auth.js"')
    tail = tail.replace('src="supabase-config.js"', f'src="{base}supabase-config.js"')
    tail = tail.replace('src="prevent-active-link-reload.js"', f'src="{base}prevent-active-link-reload.js"')
    tail = tail.replace('src="i18n.js"', f'src="{base}i18n.js"')
    tail = tail.replace('src="darkmode.js"', "")

    return f"""<!DOCTYPE html>
<html lang="tr">
<head>
{FOUC}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{page_title}</title>
{HEAD_ASSETS.format(base=base)}
{extra_style}
</head>
<body data-atlas-page="{nav_id}">
  <a class="skip-link" href="#main-content" style="position:absolute;left:-9999px;">İçeriğe atla</a>
  <div class="app" id="app">
{build_rail(nav_id, base)}
{build_top(title, section, base, sync)}
    <main class="main page-content" id="main-content" tabindex="-1">
{content}
    </main>
  </div>
{tail}
</body>
</html>
"""


def extract_auth_parts(html: str) -> tuple[str, str]:
    m = re.search(r"<body[^>]*>(.*)</body>", html, re.S | re.I)
    if not m:
        return html, ""
    body = m.group(1).strip()
    cut = re.search(r"(<!-- jQuery -->|<script[\s>])", body, re.I)
    if cut:
        return body[: cut.start()].strip(), body[cut.start() :].strip()
    return body, ""


def build_auth_page(name: str, html: str, wide: bool = False, has_canvas: bool = False) -> str:
    title = extract_title(html)
    body_content, tail = extract_auth_parts(html)
    body_class = "atlas-auth"
    if wide:
        body_class += " atlas-auth-wide"
    if has_canvas:
        body_class += " has-canvas"

    theme_btn = """
  <div class="atlas-auth-top">
    <button class="icon-btn" type="button" id="themeToggle" aria-label="Tema"><i class="ph ph-moon" id="themeIcon"></i></button>
  </div>"""

    if tail and "atlas-shell.js" not in tail:
        tail += '\n<script src="atlas-shell.js"></script>\n'

    fa = '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">\n'

    return f"""<!DOCTYPE html>
<html lang="tr">
<head>
{FOUC}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{title}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {fa}    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="atlas-shell.css" rel="stylesheet">
    <link href="atlas-auth.css" rel="stylesheet">
</head>
<body class="{body_class}">
{theme_btn}
{body_content}
{tail}
</body>
</html>
"""


def migrate_denetim(html: str) -> str:
    """Bootstrap 5 tool — keep extra head scripts, wrap body in Atlas shell."""
    title = extract_title(html)
    head_extra = ""
    hm = re.search(r"<head[^>]*>(.*?)</head>", html, re.S | re.I)
    if hm:
        head = hm.group(1)
        for tag in re.findall(r"<(script|link)[^>]+>", head, re.I):
            pass
        links = re.findall(r'<link[^>]+>', head, re.I)
        scripts_head = re.findall(r'<script[^>]+src=[^>]+></script>', head, re.I)
        head_extra = "\n".join(links + scripts_head)
        head_extra = re.sub(r"bootstrap[^\"']*\.css", "", head_extra)  # drop bs5 css, use compat
    content = extract_main(html)
    tail = extract_tail(html)
    return build_admin_page("denetim-rehberi.html", html)  # uses ADMIN config


def main():
    for name in ADMIN:
        path = ROOT / name
        if not path.exists():
            print("skip missing", name)
            continue
        html = path.read_text(encoding="utf-8")
        out = build_admin_page(name, html)
        path.write_text(out, encoding="utf-8")
        print("admin", name)

    auth_map = {
        "login.html": (False, False),
        "password-change.html": (False, False),
        "auditor-password-change.html": (False, False),
        "index.html": (True, False),
        "home.html": (True, False),
        "auditor-login.html": (False, True),
    }
    for name, (wide, canvas) in auth_map.items():
        path = ROOT / name
        if not path.exists():
            continue
        html = path.read_text(encoding="utf-8")
        path.write_text(build_auth_page(name, html, wide, canvas), encoding="utf-8")
        print("auth", name)

    # ecosystem mockup — point to shared css
    mock = ROOT / "ecosystem-mockup" / "index.html"
    if mock.exists():
        t = mock.read_text(encoding="utf-8")
        t = t.replace('href="styles.css"', 'href="../atlas-shell.css"')
        if "atlas-compat" not in t:
            t = t.replace(
                '<link rel="stylesheet" href="../atlas-shell.css">',
                '<link rel="stylesheet" href="../atlas-shell.css">\n  <link rel="stylesheet" href="../atlas-compat.css">',
            )
        t = t.replace('id="themeToggle"', 'id="themeToggle"')
        if "../atlas-shell.js" not in t:
            t = t.replace(
                '<script src="app.js"></script>',
                '<script src="../atlas-shell.js"></script>\n  <script src="app.js"></script>',
            )
        mock.write_text(t, encoding="utf-8")
        print("mockup updated")

    print("done")


if __name__ == "__main__":
    main()
