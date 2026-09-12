# KankashMachine — project status

**Date:** 2026-09-12  
**Role of this pass:** Project Manager QA only. No implementation, no deploy, no credentials.

Full function checklist: [PROJECT_QA.md](PROJECT_QA.md) — **3 PASS / 39 FAIL / 3 PENDING**. Not go-live ready.

---

## What is done

- GitHub repo **exists**: https://github.com/mehran-au/KankashMachine (`mehran-au/KankashMachine`, public, `main`, created 2026-09-12).
- Repo description and README state the product: industrial special machines, bilingual FA/EN CMS.
- Figma file **exists**: https://www.figma.com/design/HYktHyY4LOtnnAEO6jwbRm — page `Website — FA default` (`0:1`).
- Domain **resolves**: https://kankashmachine.com (currently the host default page).
- DirectAdmin URL is documented: https://kankashmachine.com:2223/. No DirectAdmin password is in git (tree is README-only).

---

## What is broken / missing

### Workspace
- `C:\Users\Mehran\Projects\KankashMachine` is **empty** (0 files besides this QA output). Not a git repo. GitHub is not cloned locally.
- GitHub tree is **README.md only** (commit `ffd832ee`). No PHP, HTML, JS, CSS, admin, data, or uploads.

### Figma (design is not implementable as a full site)
- Only one frame: `Home / خانه` (`2:2`) — eight 120px **label strips** (Header, Hero Slider, Capabilities, Products, Projects, Magazine, Contact Map, Footer).
- Missing frames: products list/detail, projects list/detail, magazine list/detail, contact, admin, English LTR.

### Product functions (none implemented)
- No public pages (home, products, projects, magazine, contact).
- No FA default RTL, no flag language switch, no cookie/session persistence.
- No map embed for **Q83J+MQC District 5, Tehran, Tehran Province, Iran**.
- No admin login/logout/session.
- No CMS editors (slider, home sections, menu, magazine, projects, products, contact).
- No bilingual `title_fa`/`title_en` (or equivalent) fields.
- No uploads; JSON listing privacy not applicable yet.

### Runtime / live
- Live site is DirectAdmin placeholder: “upload your website into the public_html directory.”
- Local `php` CLI is **not installed** — `php -l` cannot be run.

---

## Blockers

1. **No application source** in workspace or GitHub.  
2. **Figma is a home wireframe only** — designers/dev cannot implement the required screen set from current frames.  
3. **PHP not on PATH** — lint and local run blocked.  
4. **Workspace not cloned** — local work cannot push until `git clone` (or `git init` + remote).  
5. **DirectAdmin credentials** are correctly absent from git; deploy needs operator-held secrets (do not commit them).

---

## Fix next (priority)

1. Clone the GitHub repo into this folder so local work tracks `mehran-au/KankashMachine`.  
2. Expand Figma to real industrial UI for all required screens (home, products ×2, projects ×2, magazine ×2, contact, admin) plus EN/LTR.  
3. Scaffold the bilingual site + admin CMS (FA RTL default, flag switch, session/cookie, dual FA/EN editor fields).  
4. Contact page: Google Maps / Plus Code **Q83J+MQC**.  
5. Install PHP CLI; `php -l` every PHP file; deny public listing of JSON/data; then deploy to `public_html` (not in this QA pass).
