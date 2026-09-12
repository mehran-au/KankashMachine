# KankashMachine — Function-level QA checklist

**Inspected:** 2026-09-12  
**Workspace:** `C:\Users\Mehran\Projects\KankashMachine`  
**GitHub:** https://github.com/mehran-au/KankashMachine  
**Figma:** https://www.figma.com/design/HYktHyY4LOtnnAEO6jwbRm (fileKey `HYktHyY4LOtnnAEO6jwbRm`)  
**Live:** https://kankashmachine.com  
**DirectAdmin:** https://kankashmachine.com:2223/ (credentials are not stored, printed, or committed)

**Status legend:** PASS | FAIL | PENDING  
PENDING = cannot be verified until code or deploy exists. FAIL = required artifact is missing or currently does not meet the acceptance function.

---

## Inspection snapshot

| Source | Result |
| --- | --- |
| Local workspace | **Empty.** `Get-ChildItem -Force` count = 0. No `.git`, no PHP/HTML/JS/CSS, no `public_html`, no `.env`. |
| Local git | Not a git repository. |
| GitHub `mehran-au/KankashMachine` | **Exists** (public). Created 2026-09-12. Default branch `main`. Tree is **README.md only** (123 bytes). 1 commit: `ffd832ee` “Initial commit”. |
| Figma file | **Exists.** One page: `Website — FA default` (`0:1`). One frame: `Home / خانه` (`2:2`, 1440×960) with eight labeled 120px strips (Header, Hero Slider, Capabilities, Products, Projects, Magazine, Contact Map, Footer). Not a full industrial UI. No products/projects/magazine/contact/admin screens. No EN/LTR page. |
| Live domain | DirectAdmin default placeholder: “Something amazing will be constructed here… upload your website into the public_html directory.” |
| PHP CLI | Not installed / not on PATH (`php` is not recognized). No PHP files to lint. |

**Code greps (workspace):** no matches for language switching, bilingual field names, map embed, or admin routes — because there is no application source.

| Search | Pattern | Result |
| --- | --- | --- |
| Language switch | `lang`, `rtl`, `ltr`, `title_fa`, `title_en` | No files |
| Bilingual admin fields | `title_fa` / `title_en` / `_fa` / `_en` | No files |
| Map embed | `Q83J+MQC`, `google.com/maps`, `iframe`, `maps.google` | No files |
| Admin | `admin`, `login`, `logout`, `session` | No files |
| `php -l` | every `*.php` | **Skipped** — 0 PHP files; PHP CLI absent |

---

## 1. Repository and hosting

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| GH-01 | GitHub repo named `KankashMachine` exists | https://github.com/mehran-au/KankashMachine is reachable and named `KankashMachine` | **PASS** | Public repo `mehran-au/KankashMachine`. Description: bilingual FA/EN CMS. |
| GH-02 | Application source is in the repo | PHP/HTML/JS (or equivalent) for public site + admin is committed | **FAIL** | Tree = `README.md` only. Local folder is empty and not cloned. |
| GH-03 | Local workspace tracks the GitHub repo | `.git` remote `origin` → `mehran-au/KankashMachine` | **FAIL** | `fatal: not a git repository`. |
| DA-01 | Site is deployed to DirectAdmin `public_html` of kankashmachine.com | Live origin serves the company site, not the host default page | **FAIL** | https://kankashmachine.com still shows the DirectAdmin “upload to public_html” placeholder. **Not deployed (this QA pass does not deploy).** |
| DA-02 | DirectAdmin password is not committed to git | No password/token in repo files, history, or docs | **PASS** | GitHub contents = README only. Local workspace has no secret files. This QA pass did not invent, print, or commit credentials. |
| DA-03 | DirectAdmin panel is reachable for operators | https://kankashmachine.com:2223/ is the documented control panel | **PENDING** | URL is known. Login was not attempted (no credentials in repo; QA must not invent them). |

---

## 2. Figma industrial bilingual UI

Required screens: landing/home, projects, products, magazine/blog, contact, admin.

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| FG-01 | Figma file exists and is the design source | fileKey `HYktHyY4LOtnnAEO6jwbRm` opens | **PASS** | File reachable via Figma API. Page: `Website — FA default`. |
| FG-02 | Home / landing industrial UI | Full home composition (header, slider, sections, footer), not labels only | **FAIL** | Frame `Home / خانه` (`2:2`) is a stack of 120px named strips. No real layout, imagery, type system, or industrial visual language. |
| FG-03 | Projects screens | Projects list + project detail frames | **FAIL** | Not in the document. Home has a strip named `Projects` only. |
| FG-04 | Products screens | Products list + product detail frames | **FAIL** | Not in the document. Home has a strip named `Products` only. |
| FG-05 | Magazine / blog screens | Magazine list + article detail frames | **FAIL** | Not in the document. Home has a strip named `Magazine` only. |
| FG-06 | Contact screen | Dedicated contact page (form + map region) | **FAIL** | Not in the document. Home has a strip named `Contact Map` only. |
| FG-07 | Admin screens | Login + CMS editors (slider, home, menu, magazine, projects, products, contact) | **FAIL** | No admin page/frame. |
| FG-08 | English / LTR counterpart | EN layouts or documented LTR variants | **FAIL** | Single page named FA default. No EN page. |
| UI-01 | Implemented UI matches Figma | Public + admin pages implement the Figma screens | **FAIL** | No implementation files. Figma itself is incomplete (wireframe home only). |

---

## 3. Language: Persian default, English switch, persistence

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| LANG-01 | Default language is Persian (RTL) | First visit: `lang="fa"` (or equivalent), `dir="rtl"`, Persian copy | **FAIL** | No pages, no `lang`/`dir` handling. |
| LANG-02 | Flag icon switches to English (LTR) | Clicking the flag sets English, `dir="ltr"`, English copy | **FAIL** | No flag control, no switcher JS/PHP. |
| LANG-03 | Reverse flag returns to Persian | Second click restores FA + RTL | **FAIL** | No switcher. |
| LANG-04 | Language persists across pages | Cookie or session survives navigation (home → products → contact, etc.) | **FAIL** | No cookie/session language store. |
| LANG-05 | Language applies to every public page | Home, products list+detail, projects list+detail, magazine list+detail, contact all honor stored locale | **FAIL** | Public pages do not exist. |

---

## 4. Public pages

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| PUB-01 | Home | Routable home with slider + sections | **FAIL** | No `index.php` / `index.html`. Live is host placeholder. |
| PUB-02 | Products list | List of products, bilingual | **FAIL** | No products list route/file. |
| PUB-03 | Product detail | Single-product page | **FAIL** | No product detail route/file. |
| PUB-04 | Projects list | List of projects, bilingual | **FAIL** | No projects list route/file. |
| PUB-05 | Project detail | Single-project page | **FAIL** | No project detail route/file. |
| PUB-06 | Magazine list | Blog/magazine index | **FAIL** | No magazine list route/file. |
| PUB-07 | Magazine detail | Article page | **FAIL** | No magazine detail route/file. |
| PUB-08 | Contact | Contact page with form/info + map | **FAIL** | No contact route/file. |

---

## 5. Contact map

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| MAP-01 | Map points at the Google address | Embed/link targets **Q83J+MQC District 5, Tehran, Tehran Province, Iran** (Plus Code `Q83J+MQC`) | **FAIL** | No contact page, no iframe, no Plus Code, no Google Maps embed in workspace or GitHub. |

---

## 6. Admin authentication

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| ADM-01 | Admin login | Protected login form; valid session required for editors | **FAIL** | No admin directory, login script, or auth. |
| ADM-02 | Admin logout | Logout destroys session and blocks editors | **FAIL** | No logout. |
| ADM-03 | Admin session | Unauthenticated requests to editors redirect to login | **FAIL** | No session layer. |

---

## 7. Admin CMS editors

Each editor must load, save, and publish the named content.

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| CMS-01 | Slider photos | Upload/reorder/delete home slider images | **FAIL** | No slider editor, no upload pipeline. |
| CMS-02 | Home page sections | Edit home section content | **FAIL** | No home-section editor. |
| CMS-03 | Menu items | Edit navigation labels/URLs | **FAIL** | No menu editor. |
| CMS-04 | Blog / magazine | Create/edit/delete magazine posts | **FAIL** | No magazine CMS. |
| CMS-05 | Projects | Create/edit/delete projects | **FAIL** | No projects CMS. |
| CMS-06 | Products | Create/edit/delete products | **FAIL** | No products CMS. |
| CMS-07 | Contact | Edit contact copy, address, map, and related fields | **FAIL** | No contact CMS. |

---

## 8. Bilingual admin fields (every editor field)

**Rule:** every admin editor field has **both** Persian and English inputs (e.g. `title_fa` / `title_en`, `body_fa` / `body_en`).

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| I18N-01 | Slider captions/alt (if text) have FA + EN | Dual inputs saved and rendered by locale | **FAIL** | No editor. |
| I18N-02 | Home sections FA + EN | Dual inputs per section field | **FAIL** | No editor. |
| I18N-03 | Menu items FA + EN | Dual labels (and any locale-specific URLs) | **FAIL** | No editor. |
| I18N-04 | Magazine FA + EN | Dual title, body, excerpt, SEO as applicable | **FAIL** | No editor. |
| I18N-05 | Projects FA + EN | Dual title, body, and other text fields | **FAIL** | No editor. |
| I18N-06 | Products FA + EN | Dual title, body, specs text | **FAIL** | No editor. |
| I18N-07 | Contact FA + EN | Dual address/hours/copy fields | **FAIL** | No editor. |
| I18N-08 | No FA-only or EN-only text field in any editor | Code review: every user-facing string field is paired | **FAIL** | No admin forms exist to review. |

---

## 9. Uploads and data-file privacy

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| UP-01 | Uploads work | Admin can upload images; files land in a web-served uploads dir and appear on the public site | **FAIL** | No upload handler. |
| UP-02 | JSON/data files are not publicly listable | Directory listing disabled; data stores not browsable via URL; sensitive JSON not in a public listing | **PENDING** | No data directory yet. Cannot verify listing until deploy. Must be implemented (`Options -Indexes` / `.htaccess` / store outside docroot) before go-live. |

---

## 10. Syntax / static checks (when code exists)

| ID | Function | Accept | Status | Evidence |
| --- | --- | --- | --- | --- |
| PHP-01 | Every PHP file passes `php -l` | Zero syntax errors | **PENDING** | 0 PHP files. Local `php` CLI is **not installed**. Re-run after source exists and PHP is on PATH. |
| SEC-01 | No secrets in git | No DirectAdmin password, DB password, or API keys in the tree | **PASS** (vacuously, current tree) | README only. Re-check on every commit. |

---

## Summary counts

| Status | Count |
| --- | --- |
| PASS | 3 (GH-01 repo exists; DA-02 no password in git; SEC-01 no secrets in current tree) |
| FAIL | 39 |
| PENDING | 3 (DA-03 panel login; UP-02 listing once files exist; PHP-01 lint once PHP exists) |

**Go-live:** not ready. Almost every product function is missing because there is no application, no Figma screen set, and no deploy.

---

## Required next engineering (not done in this QA pass)

1. Clone `https://github.com/mehran-au/KankashMachine.git` into the empty workspace.  
2. Complete Figma: home (real industrial UI), products list+detail, projects list+detail, magazine list+detail, contact, admin (login + each editor), plus EN/LTR.  
3. Implement bilingual PHP (or agreed stack) site + CMS with FA default RTL, flag switch, cookie/session persistence.  
4. Contact map embed for Plus Code **Q83J+MQC** (District 5, Tehran).  
5. Dual FA/EN inputs on every admin field; working uploads; deny public listing of JSON/data.  
6. Install PHP CLI for `php -l`. Deploy to DirectAdmin `public_html` only after QA PASSes — **this pass does not deploy.**
