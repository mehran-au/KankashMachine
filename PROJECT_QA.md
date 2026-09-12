# KankashMachine — Function-level QA checklist (post-deploy)

**Inspected:** 2026-09-12 (post-deploy pass)  
**Workspace:** `C:\Users\Mehran\Projects\KankashMachine`  
**GitHub:** https://github.com/mehran-au/KankashMachine (`main` @ `9d5dcaa`)  
**Figma:** https://www.figma.com/design/HYktHyY4LOtnnAEO6jwbRm (fileKey `HYktHyY4LOtnnAEO6jwbRm`)  
**Live:** https://kankashmachine.com  
**DirectAdmin:** https://kankashmachine.com:2223/ (credentials are not stored, printed, or committed)

**Status legend:** PASS | FAIL | PENDING

---

## Inspection snapshot

| Source | Result |
| --- | --- |
| Local workspace | Full PHP site: `index.php`, `includes/*.php`, `assets/`, `data/site.json`, `uploads/`. Tracks `origin` `https://github.com/mehran-au/KankashMachine.git`, `main...origin/main`. |
| GitHub | Application code is on `main` (commit `9d5dcaa` — bilingual industrial website with CMS, about page, conditional social icons). Tree includes PHP, CSS, JS, `.htaccess`, uploads, `data/site.json`. |
| Figma | Still one page `Website — FA default` (`0:1`) with wireframe frame `Home / خانه` (`2:2`) — labeled 120px strips only. No products/projects/magazine/contact/admin/EN screens. |
| Live domain | Company site is live (not the DirectAdmin placeholder). nginx + PHP. |
| PHP CLI (local) | Still not on PATH. Lint not run locally. Live PHP is executing. |

**Code greps (workspace)**

| Search | Result |
| --- | --- |
| Language | `includes/i18n.php` `km_resolve_lang()`; cookie `km_lang`; default `fa`. Layout `<html lang="<?= $KM_LANG ?>" dir="<?= $KM_DIR ?>">`. Flag `km_lang_toggle_url()`. |
| Bilingual admin | `km_bi_fields()` / `km_collect_bi()` in `includes/admin.php`. Content keys `*_fa` / `*_en`. |
| Map | `index.php` `km_map_iframe()` → `maps.google.com/maps?q=` + `map_query`. Default/live query contains `Q83J+MQC`. |
| Socials | `km_active_socials()` skips empty URLs; `km_render_socials()` returns `''` if none. Admin contact has `social_*` URL inputs. |
| Admin routes | `/admin/login`, logout, dashboard, sliders, home, menus, products, projects, about, magazine, contact. |
| `php -l` | **Skipped** — local `php` CLI absent. Live pages parse and return 200. |

---

## 1. Repository and hosting

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| GH-01 | GitHub repo named `KankashMachine` exists | Repo reachable | **PASS** | https://github.com/mehran-au/KankashMachine |
| GH-02 | Application source is in the repo | Public site + admin committed | **PASS** | Tree has `index.php`, `includes/`, `assets/`, `data/`, `uploads/`, `.htaccess`. |
| GH-03 | Local workspace tracks GitHub | `origin` → `mehran-au/KankashMachine` | **PASS** | `git status`: `main...origin/main`. |
| DA-01 | Deployed to `public_html` of kankashmachine.com | Live origin serves the company site | **PASS** | https://kankashmachine.com/ returns the bilingual site (html lang=fa), not the host default page. |
| DA-02 | DirectAdmin password is not committed | No DA password/token in repo | **PASS** | No DirectAdmin password in tree. README only says not to commit it. |
| DA-03 | DirectAdmin panel reachable | :2223 documented | **PENDING** | Panel URL known. Login not attempted (no DA credentials in repo). |

---

## 2. Figma industrial bilingual UI

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| FG-01 | Figma file exists | fileKey opens | **PASS** | File reachable. |
| FG-02 | Home / landing industrial UI | Full home composition | **FAIL** | Still wireframe strips only (`Home / خانه` `2:2`). |
| FG-03 | Projects screens | List + detail frames | **FAIL** | Not in Figma. Live `/projects` and `/projects/food-sorting-line` exist (200). |
| FG-04 | Products screens | List + detail frames | **FAIL** | Not in Figma. Live `/products` and `/products/belt-conveyor` exist (200). |
| FG-05 | Magazine screens | List + article frames | **FAIL** | Not in Figma. Live `/magazine` and `/magazine/conveyor-maintenance` exist (200). |
| FG-06 | Contact screen | Dedicated contact | **FAIL** | Not in Figma. Live `/contact` exists (200). |
| FG-07 | Admin screens | Login + editors | **FAIL** | Not in Figma. Live `/admin/login` and CMS exist. |
| FG-08 | English / LTR counterpart | EN layouts in Figma | **FAIL** | Single FA-default page. Live EN works via `?lang=en`. |
| UI-01 | Implemented UI matches Figma | Pixel/design parity | **FAIL** | Figma is incomplete wireframe; live is a custom industrial implementation, not a Figma match. |

---

## 3. Language

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| LANG-01 | Default Persian RTL | `/` → `lang="fa"` `dir="rtl"` | **PASS** | Live `/`: `<html lang="fa" dir="rtl">`. |
| LANG-02 | Flag switches to English LTR | Flag → EN, `dir="ltr"` | **PASS** | Home flag `href="/?lang=en"`. Live `/?lang=en`: `<html lang="en" dir="ltr">`. Cookie `km_lang=en` set. |
| LANG-03 | Reverse flag returns to Persian | EN flag → FA | **PASS** | EN page flag `href="/?lang=fa"` (label FA). |
| LANG-04 | Language persists across pages | Cookie/session survives navigation | **PASS** | After `/?lang=en`, request `/products` with session cookie → `<html lang="en" dir="ltr">`. `km_resolve_lang()` reads `km_lang` cookie. |
| LANG-05 | Applies to every public page | All public routes honor locale | **PASS** | Same header/layout. `/about?lang=en` h1 `About Kankash Machine`, Mission, Vision. |

---

## 4. Public pages

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| PUB-01 | Home `/` | 200, slider + sections | **PASS** | Live 200. |
| PUB-02 | Products list `/products` | 200 | **PASS** | Live 200. |
| PUB-03 | Product detail | 200 | **PASS** | `/products/belt-conveyor` 200. |
| PUB-04 | Projects list `/projects` | 200 | **PASS** | Live 200. |
| PUB-05 | Project detail | 200 | **PASS** | `/projects/food-sorting-line` 200. |
| PUB-06 | Magazine list `/magazine` | 200 | **PASS** | Live 200. |
| PUB-07 | Magazine detail | 200 | **PASS** | `/magazine/conveyor-maintenance` 200. |
| PUB-08 | Contact `/contact` | 200, form + map | **PASS** | Live 200. |
| PUB-09 | About `/about` | 200, bilingual about | **PASS** | Live 200. FA title from `about.title_fa`. EN h1 `About Kankash Machine`. |

---

## 5. Contact map

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| MAP-01 | Map iframe query contains Q83J+MQC | Google embed targets Plus Code | **PASS** | Live `/contact` iframe `src="https://maps.google.com/maps?q=Q83J%2BMQC%20District%205%2C%20Tehran%2C%20Tehran%20Province%2C%20Iran&z=16&output=embed"`. |

---

## 6. Admin authentication

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| ADM-01 | Admin login | Valid session after POST | **PASS** | Live `/admin/login` 200. POST with CSRF → 302 `Location: /admin`. Follow-up `/admin` 200 `stat-grid`. Unauthenticated `/admin` 302 → `/admin/login`. |
| ADM-02 | Admin logout | Session destroyed | **PASS** | `/admin/logout` 302 → `/admin/login`. Subsequent `/admin` 302 login. |
| ADM-03 | Admin session required | Editors blocked when logged out | **PASS** | `km_require_admin()` redirects. Confirmed live. |

---

## 7. Admin CMS editors

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| CMS-01 | Slider photos | `/admin/sliders` | **PASS** | Editor live; `km_handle_upload`; seed `uploads/hero-factory.jpg` 200. |
| CMS-02 | Home sections | `/admin/home` | **PASS** | Route + `km_bi_fields` title/body in `includes/admin.php`. |
| CMS-03 | Menu items | `/admin/menus` | **PASS** | Dual `label_fa`/`label_en` + URL. |
| CMS-04 | Magazine | `/admin/magazine` | **PASS** | Shared collection editor. |
| CMS-05 | Projects | `/admin/projects` | **PASS** | Shared collection editor. |
| CMS-06 | Products | `/admin/products` | **PASS** | Title/category/excerpt/body bilingual. |
| CMS-07 | Contact | `/admin/contact` | **PASS** | Address/hours bilingual; phone, email, plus code, map query; `social_*` URL fields. |
| CMS-08 | About | `/admin/about` | **PASS** | Live form: `title_fa`/`title_en`, `subtitle_*`, `body_*`, `mission_*`, `vision_*`, image. |

---

## 8. Bilingual admin fields

**Rule:** every **content** editor field has FA + EN via `km_bi_fields` / `title_fa`/`title_en` (or `label_*`, `body_*`, etc.). Shared identifiers (slug, URL, phone, email, plus code, map query, social URLs, image, order, visible) are language-neutral.

| ID | Function | Status | Evidence |
| --- | --- | --- | --- |
| I18N-01 | Slider title/subtitle/cta FA+EN | **PASS** | Live `/admin/sliders`: `title_fa/en`, `subtitle_fa/en`, `cta_fa/en`. |
| I18N-02 | Home sections FA+EN | **PASS** | `km_collect_bi('title')`, `km_collect_bi('body')`. |
| I18N-03 | Menu labels FA+EN | **PASS** | `km_collect_bi('label')`. |
| I18N-04 | Magazine FA+EN | **PASS** | title/excerpt/body. |
| I18N-05 | Projects FA+EN | **PASS** | title/excerpt/body. |
| I18N-06 | Products FA+EN | **PASS** | title/category/excerpt/body. |
| I18N-07 | Contact copy FA+EN | **PASS** | Live: `address_fa/en`, `hours_fa/en`. |
| I18N-08 | About FA+EN | **PASS** | Live `/admin/about` dual inputs as listed in CMS-08. |
| I18N-09 | No unpaired content string field | **PASS** | All `type === 'bi'` fields go through `km_bi_fields`. |

---

## 9. Social icons

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| SOC-01 | `km_active_socials` / `km_render_socials` only if URL non-empty | Empty URL omitted | **PASS** | `includes/bootstrap.php` skips `trim($url) === ''`. `km_render_socials` returns `''` when no items. |
| SOC-02 | Homepage has no `social-btn` while socials empty | No public icons | **PASS** | `data/site.json` socials all `""`. Live `/` `social-btn` count = **0**. |
| SOC-03 | Admin contact has `social_*` URL fields | Instagram…Facebook inputs | **PASS** | Live `/admin/contact` form includes `social_*` URL inputs (with preview `social-btn` spans in admin only). |

---

## 10. Uploads and data privacy

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| UP-01 | Uploads work | Images served | **PASS** | `https://kankashmachine.com/uploads/hero-factory.jpg` 200. Upload handler `km_handle_upload()`. |
| UP-02 | JSON/data not publicly listable | `/data/` and `/data/site.json` denied | **PASS** | Live `/data/` **403**, `/data/site.json` **403**, `/uploads/` **403**. Root `.htaccess` `RewriteRule ^data/ - [F,L]`; `data/.htaccess` `Require all denied`; `Options -Indexes`. |

---

## 11. Syntax / secrets

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| PHP-01 | `php -l` every PHP file | Zero syntax errors | **PENDING** | Local PHP CLI still missing. Live PHP routes 200, so files parse on the server. |
| SEC-01 | No DirectAdmin password in git | No DA secret | **PASS** | Not present. |
| SEC-02 | No CMS credentials in git | Default admin password not in repo | **FAIL** | `README.md` documents the default CMS username and password in plaintext. `data/site.json` stores `password_hash`; `includes/bootstrap.php` has a hardcoded `KM_PEPPER`. |

---

## Summary counts

| Status | Count |
| --- | --- |
| PASS | 46 |
| FAIL | 8 (FG-02…FG-08, UI-01, SEC-02) |
| PENDING | 2 (DA-03, PHP-01) |

**Go-live (functions):** public site, i18n, map, admin CMS, socials-if-URL, GitHub, deploy — **accepted**.  
**Not accepted:** Figma completeness / design parity; default CMS password in README.

---

## Remaining defects

1. Figma is still a home wireframe; required screens and EN/LTR are missing (`FG-02`–`FG-08`, `UI-01`).  
2. Default CMS password is committed in `README.md`; pepper is hardcoded (`SEC-02`). Rotate the CMS password and stop documenting it in git.  
3. Local `php` CLI is absent, so `php -l` was not run (`PHP-01`).  
4. `includes/*.php` are web-reachable (HTTP 200, empty body — source not leaked). Harden with deny rules.  
5. Contact phone in `site.json` is still the placeholder `+98 21 0000 0000`.
