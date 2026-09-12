# KankashMachine — project status (post-deploy)

**Date:** 2026-09-12  
**QA:** live + local PHP + GitHub. No DirectAdmin credentials printed or committed.

Full checklist: [PROJECT_QA.md](PROJECT_QA.md) — **46 PASS / 8 FAIL / 2 PENDING**.

---

## What is done

- GitHub `mehran-au/KankashMachine` has the application (`main` @ `9d5dcaa`). Local workspace tracks `origin`.
- Live https://kankashmachine.com serves the bilingual industrial site (not the host placeholder).
- Default `/` is `lang="fa"` `dir="rtl"`. `/?lang=en` is `lang="en"` `dir="ltr"`; flag href `/?lang=fa`. Cookie `km_lang` persists on `/products`.
- HTTP 200: `/`, `/products`, `/projects`, `/about`, `/magazine`, `/contact`, `/admin/login`, plus product/project/magazine details.
- About page live; `/admin/about` has dual FA/EN fields (`title_*`, `subtitle_*`, `body_*`, `mission_*`, `vision_*`).
- Contact map iframe query contains `Q83J+MQC` (`Q83J%2BMQC District 5, Tehran…`).
- Socials empty in `site.json` → homepage has **no** `social-btn`. `km_active_socials` / `km_render_socials` skip empty URLs. Admin contact has `social_*` fields.
- Admin login (CSRF POST → `/admin`), logout, session gate verified on live.
- Every content editor uses `km_bi_fields` / `*_fa`+`*_en`.
- `/data/` and `/data/site.json` 403; upload listing 403; sample upload image 200.
- DirectAdmin password is not in git.

---

## What is broken

- **Figma** still one FA wireframe home; no implementable screen set. Live UI is not a Figma match.
- **README.md** contains the default CMS password in plaintext (plus hash + pepper in repo).
- Local **PHP CLI missing** — `php -l` not run.
- **`includes/*.php`** return HTTP 200 (empty). Should be denied.
- Contact **phone is placeholder**.

---

## Fix next

1. Expand Figma to real industrial screens (home, products ×2, projects ×2, magazine ×2, about, contact, admin, EN/LTR) or drop Figma parity from acceptance.  
2. Rotate CMS password; remove plaintext password from `README.md`; keep DA secrets out of git.  
3. Deny web access to `includes/`. Install PHP CLI and run `php -l`.  
4. Replace placeholder phone when the real number is known.
