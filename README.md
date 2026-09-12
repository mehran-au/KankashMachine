# Kankash Machine

Bilingual industrial website for [kankashmachine.com](https://kankashmachine.com) — conveyors, sorters, and special-purpose machines.

- Default language: **Persian (RTL)**
- Flag control switches to English (LTR) and back
- CMS at `/admin` for sliders, home sections, menus, products, projects, about, magazine, contact, and social URLs
- Every editable text field stores Persian **and** English
- Social icons in the footer and contact page appear **only** when a URL is saved in admin
- Map: `Q83J+MQC District 5, Tehran, Tehran Province, Iran`

## Admin

- URL: `/admin/login`
- User: `admin`
- Password: `Kankash@2026` — change after first login by editing `data/site.json` hash

Do not commit DirectAdmin credentials.

## Deploy

Copy the project into the domain `public_html` on DirectAdmin (PHP 8+). Ensure `data/` and `uploads/` are writable.

Figma: [KankashMachine — Industrial Website UI](https://www.figma.com/design/HYktHyY4LOtnnAEO6jwbRm)
