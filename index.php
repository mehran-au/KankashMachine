<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/admin.php';

$path = km_request_path();
$site = &$KM_SITE;

if (strpos($path, '/admin') === 0) {
    km_handle_admin($site, $path);
    exit;
}

function km_visible(array $items): array
{
    $items = array_values(array_filter($items, static fn($i) => !empty($i['visible'])));
    usort($items, static fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    return $items;
}

function km_map_iframe(array $site): string
{
    $q = $site['settings']['map_query'] ?? 'Q83J+MQC District 5, Tehran, Tehran Province, Iran';
    $src = 'https://maps.google.com/maps?q=' . rawurlencode($q) . '&z=16&output=embed';
    return '<div class="map-wrap"><iframe title="map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="' . km_h($src) . '"></iframe></div>';
}

if ($path === '/') {
    $slides = km_visible($site['sliders'] ?? []);
    $sections = km_visible($site['home_sections'] ?? []);
    km_header($site, km_t('home'), km_t('tagline'));
    echo '<section class="hero" data-slider>';
    foreach ($slides as $i => $slide) {
        echo '<article class="slide' . ($i === 0 ? ' is-on' : '') . '" style="background-image:url(' . km_h(km_upload_url($slide['image'] ?? '')) . ')">';
        echo '<div class="slide-inner"><p class="kicker">' . km_h(km_t('hero_kicker')) . '</p>';
        echo '<h1>' . km_h(km_text($slide, 'title')) . '</h1>';
        echo '<p>' . km_h(km_text($slide, 'subtitle')) . '</p>';
        if (km_text($slide, 'cta') !== '') {
            echo '<a class="btn btn-accent" href="' . km_h(km_url($slide['cta_url'] ?? '/contact')) . '">' . km_h(km_text($slide, 'cta')) . '</a>';
        }
        echo '</div></article>';
    }
    if (count($slides) > 1) {
        echo '<div class="dots">';
        foreach ($slides as $i => $_) {
            echo '<button type="button" class="dot' . ($i === 0 ? ' is-on' : '') . '" data-dot="' . $i . '"></button>';
        }
        echo '</div>';
    }
    echo '</section>';
    echo '<section class="section"><div class="caps">';
    foreach ($sections as $sec) {
        echo '<article class="cap"><h2>' . km_h(km_text($sec, 'title')) . '</h2><p>' . km_h(km_text($sec, 'body')) . '</p></article>';
    }
    echo '</div></section>';
    echo '<section class="section"><div class="section-head"><h2>' . km_h(km_t('our_products')) . '</h2><a href="' . km_h(km_url('/products')) . '">' . km_h(km_t('view_all')) . '</a></div>';
    km_cards(array_slice(km_visible($site['products'] ?? []), 0, 3), '/products');
    echo '</section>';
    echo '<section class="section"><div class="section-head"><h2>' . km_h(km_t('our_projects')) . '</h2><a href="' . km_h(km_url('/projects')) . '">' . km_h(km_t('view_all')) . '</a></div>';
    km_cards(array_slice(km_visible($site['projects'] ?? []), 0, 2), '/projects');
    echo '</section>';
    echo '<section class="section"><div class="section-head"><h2>' . km_h(km_t('magazine_title')) . '</h2><a href="' . km_h(km_url('/magazine')) . '">' . km_h(km_t('view_all')) . '</a></div>';
    km_cards(array_slice(km_visible($site['magazine'] ?? []), 0, 2), '/magazine');
    echo '</section>';
    km_footer($site);
    exit;
}

if ($path === '/products') {
    km_header($site, km_t('products'));
    echo '<section class="section page-hero"><h1>' . km_h(km_t('all_products')) . '</h1></section>';
    echo '<section class="section">';
    km_cards(km_visible($site['products'] ?? []), '/products');
    echo '</section>';
    km_footer($site);
    exit;
}

if (preg_match('#^/products/([^/]+)$#', $path, $m)) {
    $item = km_find_by_slug(km_visible($site['products'] ?? []), $m[1]);
    if (!$item) {
        http_response_code(404);
        km_header($site, '404');
        echo '<section class="section"><h1>404</h1></section>';
        km_footer($site);
        exit;
    }
    km_header($site, km_text($item, 'title'), km_text($item, 'excerpt'));
    echo '<article class="detail section">';
    if (!empty($item['image'])) {
        echo '<img src="' . km_h(km_upload_url($item['image'])) . '" alt="">';
    }
    echo '<p class="kicker">' . km_h(km_text($item, 'category')) . '</p>';
    echo '<h1>' . km_h(km_text($item, 'title')) . '</h1><p class="lead">' . km_h(km_text($item, 'excerpt')) . '</p>';
    echo '<div class="prose">' . nl2br(km_h(km_text($item, 'body'))) . '</div>';
    echo '<a class="btn btn-accent" href="' . km_h(km_url('/contact')) . '">' . km_h(km_t('inquire')) . '</a></article>';
    km_footer($site);
    exit;
}

if ($path === '/projects') {
    km_header($site, km_t('projects'));
    echo '<section class="section page-hero"><h1>' . km_h(km_t('all_projects')) . '</h1></section>';
    echo '<section class="section">';
    km_cards(km_visible($site['projects'] ?? []), '/projects');
    echo '</section>';
    km_footer($site);
    exit;
}

if (preg_match('#^/projects/([^/]+)$#', $path, $m)) {
    $item = km_find_by_slug(km_visible($site['projects'] ?? []), $m[1]);
    if (!$item) {
        http_response_code(404);
        km_header($site, '404');
        echo '<section class="section"><h1>404</h1></section>';
        km_footer($site);
        exit;
    }
    km_header($site, km_text($item, 'title'), km_text($item, 'excerpt'));
    echo '<article class="detail section">';
    if (!empty($item['image'])) {
        echo '<img src="' . km_h(km_upload_url($item['image'])) . '" alt="">';
    }
    echo '<h1>' . km_h(km_text($item, 'title')) . '</h1><p class="lead">' . km_h(km_text($item, 'excerpt')) . '</p>';
    echo '<div class="prose">' . nl2br(km_h(km_text($item, 'body'))) . '</div></article>';
    km_footer($site);
    exit;
}

if ($path === '/magazine') {
    km_header($site, km_t('magazine'));
    echo '<section class="section page-hero"><h1>' . km_h(km_t('magazine_title')) . '</h1></section>';
    echo '<section class="section">';
    km_cards(km_visible($site['magazine'] ?? []), '/magazine');
    echo '</section>';
    km_footer($site);
    exit;
}

if (preg_match('#^/magazine/([^/]+)$#', $path, $m)) {
    $item = km_find_by_slug(km_visible($site['magazine'] ?? []), $m[1]);
    if (!$item) {
        http_response_code(404);
        km_header($site, '404');
        echo '<section class="section"><h1>404</h1></section>';
        km_footer($site);
        exit;
    }
    km_header($site, km_text($item, 'title'), km_text($item, 'excerpt'));
    echo '<article class="detail section">';
    if (!empty($item['image'])) {
        echo '<img src="' . km_h(km_upload_url($item['image'])) . '" alt="">';
    }
    echo '<h1>' . km_h(km_text($item, 'title')) . '</h1><p class="lead">' . km_h(km_text($item, 'excerpt')) . '</p>';
    echo '<div class="prose">' . nl2br(km_h(km_text($item, 'body'))) . '</div></article>';
    km_footer($site);
    exit;
}

if ($path === '/about') {
    $about = $site['about'] ?? [];
    km_header($site, km_t('about'), km_text($about, 'subtitle'));
    echo '<section class="about-hero">';
    if (!empty($about['image'])) {
        echo '<img src="' . km_h(km_upload_url($about['image'])) . '" alt="">';
    }
    echo '<div class="about-copy"><p class="kicker">' . km_h(km_t('about')) . '</p>';
    echo '<h1>' . km_h(km_text($about, 'title')) . '</h1>';
    echo '<p class="lead">' . km_h(km_text($about, 'subtitle')) . '</p>';
    echo '<div class="prose">' . nl2br(km_h(km_text($about, 'body'))) . '</div></div></section>';
    echo '<section class="section two-col">';
    echo '<article class="cap"><h2>' . km_h(km_t('mission')) . '</h2><p>' . km_h(km_text($about, 'mission')) . '</p></article>';
    echo '<article class="cap"><h2>' . km_h(km_t('vision')) . '</h2><p>' . km_h(km_text($about, 'vision')) . '</p></article>';
    echo '</section>';
    km_footer($site);
    exit;
}

if ($path === '/contact') {
    $sent = false;
    if (km_is_post()) {
        km_csrf_check();
        $msg = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
            'at' => date('c'),
        ];
        if ($msg['name'] !== '' && $msg['message'] !== '') {
            $site['messages'][] = $msg;
            km_save_site($site);
            $sent = true;
        }
    }
    $s = $site['settings'] ?? [];
    km_header($site, km_t('contact'));
    echo '<section class="section page-hero"><h1>' . km_h(km_t('contact_title')) . '</h1></section>';
    echo '<section class="section contact-grid">';
    echo '<div class="panel contact-card">';
    echo '<p><strong>' . km_h(km_t('address')) . '</strong><br>' . km_h($s['address_' . $GLOBALS['KM_LANG']] ?? '') . '</p>';
    echo '<p><strong>' . km_h(km_t('phone')) . '</strong><br>' . km_h($s['phone'] ?? '') . '</p>';
    echo '<p><strong>' . km_h(km_t('email')) . '</strong><br>' . km_h($s['email'] ?? '') . '</p>';
    echo '<p><strong>' . km_h(km_t('hours')) . '</strong><br>' . km_h($s['hours_' . $GLOBALS['KM_LANG']] ?? '') . '</p>';
    echo km_render_socials($site, 'socials socials-contact');
    echo '</div><div>';
    if ($sent) {
        echo '<p class="flash">' . km_h(km_t('sent')) . '</p>';
    }
    echo '<form class="panel" method="post">' . km_csrf_field();
    echo '<label>' . km_h(km_t('name')) . '<input name="name" required></label>';
    echo '<label>' . km_h(km_t('email')) . '<input type="email" name="email"></label>';
    echo '<label>' . km_h(km_t('message')) . '<textarea name="message" rows="5" required></textarea></label>';
    echo '<button class="btn btn-accent" type="submit">' . km_h(km_t('send')) . '</button></form></div></section>';
    echo '<section class="section">' . km_map_iframe($site) . '</section>';
    km_footer($site);
    exit;
}

http_response_code(404);
km_header($site, '404');
echo '<section class="section"><h1>404</h1></section>';
km_footer($site);
