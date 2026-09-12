<?php
declare(strict_types=1);

function km_social_svg(string $key): string
{
    $icons = [
        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="17.4" cy="6.6" r="1.1" fill="currentColor"/></svg>',
        'telegram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20.7 4.3 3.9 10.7c-1.1.4-1.1 1 0 1.3l4.3 1.3 1.7 5.1c.2.7.4.9 1.1.9.3 0 .6-.1.8-.4l2.4-2.5 4.5 3.3c.8.5 1.4.2 1.6-.7l2.8-13.3c.3-1.2-.4-1.7-1.4-1.4z"/></svg>',
        'whatsapp' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2.1A9.9 9.9 0 0 0 3.4 16.7L2 22l5.5-1.4A9.9 9.9 0 1 0 12 2.1zm5.5 14.1c-.2.7-1.3 1.2-1.8 1.3-.5.1-1 .2-3.3-.7-2.8-1.1-4.6-3.9-4.8-4.1-.1-.2-1.2-1.6-1.2-3.1s.8-2.2 1.1-2.5c.2-.3.6-.4.8-.4h.6c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.7c-.1.2-.3.4-.1.7.4.7 1.1 1.5 1.8 2 .2.1.4.1.6 0l.8-.5c.2-.1.4-.1.6 0l2 1.1c.2.1.3.3.3.5 0 .1 0 .8-.5 1.3z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6.5 9.5H3.7V20h2.8V9.5zM5.1 4A1.6 1.6 0 1 0 5.1 7.2 1.6 1.6 0 0 0 5.1 4zM20.3 20h-2.8v-5.1c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7V20H11V9.5h2.7v1.4h.1A3 3 0 0 1 16.5 9c3 0 3.8 2 3.8 4.6V20z"/></svg>',
        'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22.5 7.2a3 3 0 0 0-2.1-2.1C18.6 4.7 12 4.7 12 4.7s-6.6 0-8.4.4A3 3 0 0 0 1.5 7.2 31 31 0 0 0 1.1 12a31 31 0 0 0 .4 4.8 3 3 0 0 0 2.1 2.1c1.8.4 8.4.4 8.4.4s6.6 0 8.4-.4a3 3 0 0 0 2.1-2.1A31 31 0 0 0 22.9 12a31 31 0 0 0-.4-4.8zM10 15.5v-7l6 3.5-6 3.5z"/></svg>',
        'aparat' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="8" cy="8" r="3" fill="currentColor"/><circle cx="16" cy="8" r="3" fill="currentColor"/><circle cx="8" cy="16" r="3" fill="currentColor"/><circle cx="16" cy="16" r="3" fill="currentColor"/></svg>',
        'x' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M14.7 10.3 21.2 3h-1.6l-5.6 6.4L9.5 3H3.2l6.8 9.9L3.2 21h1.6l6-6.9 4.8 6.9h6.3l-7.2-10.7zm-2.1 2.5-.7-1-5.6-8h2.4l4.5 6.5.7 1 5.9 8.4h-2.4l-4.8-6.9z"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h2.6l.4-3H13v-2c0-.6.4-1 1-1z"/></svg>',
    ];
    return $icons[$key] ?? '';
}

function km_render_socials(array $site, string $class = 'socials'): string
{
    $items = km_active_socials($site);
    if (!$items) {
        return '';
    }
    $html = '<nav class="' . km_h($class) . '" aria-label="' . km_h(km_t('follow_us')) . '">';
    $html .= '<p class="socials-label">' . km_h(km_t('follow_us')) . '</p><ul>';
    foreach ($items as $key => $item) {
        $html .= '<li><a class="social-btn social-' . km_h($key) . '" href="' . km_h($item['url']) . '" target="_blank" rel="noopener noreferrer" title="' . km_h($item['label']) . '" aria-label="' . km_h($item['label']) . '">';
        $html .= km_social_svg($key);
        $html .= '</a></li>';
    }
    $html .= '</ul></nav>';
    return $html;
}

function km_flag_svg(string $code): string
{
    if ($code === 'gb') {
        return '<svg class="flag-svg" viewBox="0 0 60 30" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><rect width="60" height="30" fill="#012169"/><path d="M0,0 60,30 M60,0 0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 60,30 M60,0 0,30" stroke="#C8102E" stroke-width="2"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></svg>';
    }
    return '<svg class="flag-svg" viewBox="0 0 21 14" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><rect width="21" height="4.67" fill="#239F40"/><rect y="4.67" width="21" height="4.66" fill="#fff"/><rect y="9.33" width="21" height="4.67" fill="#DA0000"/><path fill="#DA0000" d="M10.5 5.35l.35 1.05h1.1l-.9.65.35 1.05-.9-.65-.9.65.35-1.05-.9-.65h1.1z"/></svg>';
}

function km_lang_toggle_html(): string
{
    global $KM_LANG;
    $next = $KM_LANG === 'fa' ? 'gb' : 'ir';
    return '<a class="lang-flag" href="' . km_h(km_lang_toggle_url()) . '" aria-label="' . km_h(km_t('lang_switch_aria')) . '" title="' . km_h(km_t('lang_switch')) . '">' . km_flag_svg($next) . '</a>';
}

function km_header(array $site, string $title, string $description = ''): void
{
    global $KM_LANG, $KM_DIR;
    $menus = km_visible_menu($site);
    $path = km_request_path();
    ?>
<!DOCTYPE html>
<html lang="<?= km_h($KM_LANG) ?>" dir="<?= km_h($KM_DIR) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= km_h($title) ?> · <?= km_h(km_t('site_name')) ?></title>
    <meta name="description" content="<?= km_h($description !== '' ? $description : km_t('tagline')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= km_h(km_asset('assets/css/app.css')) ?>">
</head>
<body>
<div class="page">
    <div class="utility">
        <span><?= km_h($site['settings']['address_' . $KM_LANG] ?? '') ?></span>
        <span><?= km_h($site['settings']['phone'] ?? '') ?></span>
    </div>
    <header class="site-header">
        <a class="brand" href="<?= km_h(km_url('/')) ?>">
            <span class="mark">ک</span>
            <span>
                <strong><?= km_h(km_t('site_name')) ?></strong>
                <small>KANKASH MACHINE</small>
            </span>
        </a>
        <button class="nav-toggle" type="button" aria-label="menu" data-nav-toggle>☰</button>
        <nav class="nav" data-nav>
            <?php foreach ($menus as $item): ?>
                <?php $href = km_url($item['url'] ?? '/'); $active = rtrim($path, '/') === rtrim($item['url'] ?? '', '/') ? ' is-active' : ''; ?>
                <a class="nav-link<?= $active ?>" href="<?= km_h($href) ?>"><?= km_h(km_text($item, 'label')) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="header-actions">
            <?= km_lang_toggle_html() ?>
            <a class="btn btn-accent" href="<?= km_h(km_url('/contact')) ?>"><?= km_h(km_t('cta')) ?></a>
        </div>
    </header>
    <main>
    <?php
}

function km_footer(array $site): void
{
    $menus = km_visible_menu($site);
    ?>
    </main>
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <div class="brand footer-brand">
                    <span class="mark">ک</span>
                    <strong><?= km_h(km_t('site_name')) ?></strong>
                </div>
                <p><?= km_h(km_t('footer_note')) ?></p>
                <?= km_render_socials($site, 'socials socials-footer') ?>
            </div>
            <div>
                <h3><?= km_h(km_t('home')) ?></h3>
                <ul class="footer-links">
                    <?php foreach ($menus as $item): ?>
                        <li><a href="<?= km_h(km_url($item['url'] ?? '/')) ?>"><?= km_h(km_text($item, 'label')) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3><?= km_h(km_t('contact')) ?></h3>
                <p><?= km_h($site['settings']['address_fa'] && km_t('address') ? ($site['settings']['address_' . $GLOBALS['KM_LANG']] ?? '') : '') ?></p>
                <p><?= km_h($site['settings']['phone'] ?? '') ?></p>
                <p><?= km_h($site['settings']['email'] ?? '') ?></p>
            </div>
        </div>
        <div class="footer-bottom">© <?= date('Y') ?> <?= km_h(km_t('site_name')) ?></div>
    </footer>
</div>
<script src="<?= km_h(km_asset('assets/js/app.js')) ?>"></script>
</body>
</html>
    <?php
}

function km_cards(array $items, string $basePath): void
{
    echo '<div class="card-grid">';
    foreach ($items as $item) {
        if (empty($item['visible'])) {
            continue;
        }
        $url = km_url($basePath . '/' . ($item['slug'] ?? $item['id']));
        echo '<article class="card"><a href="' . km_h($url) . '">';
        if (!empty($item['image'])) {
            echo '<img src="' . km_h(km_upload_url($item['image'])) . '" alt="' . km_h(km_text($item, 'title')) . '">';
        }
        echo '<div class="card-body"><h3>' . km_h(km_text($item, 'title')) . '</h3>';
        echo '<p>' . km_h(km_text($item, 'excerpt')) . '</p>';
        echo '<span class="more">' . km_h(km_t('read_more')) . '</span></div></a></article>';
    }
    echo '</div>';
}
