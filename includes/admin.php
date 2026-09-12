<?php
declare(strict_types=1);

function km_admin_nav_items(): array
{
    return [
        '/admin' => km_t('dashboard'),
        '/admin/sliders' => km_t('sliders'),
        '/admin/home' => km_t('home_sections'),
        '/admin/menus' => km_t('menus'),
        '/admin/products' => km_t('products'),
        '/admin/projects' => km_t('projects'),
        '/admin/about' => km_t('about'),
        '/admin/magazine' => km_t('magazine'),
        '/admin/contact' => km_t('contact'),
    ];
}

function km_admin_header(string $title): void
{
    global $KM_LANG, $KM_DIR;
    $path = km_request_path();
    ?>
<!DOCTYPE html>
<html lang="<?= km_h($KM_LANG) ?>" dir="<?= km_h($KM_DIR) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= km_h($title) ?> · <?= km_h(km_t('admin')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= km_h(km_asset('assets/css/app.css')) ?>">
    <link rel="stylesheet" href="<?= km_h(km_asset('assets/css/admin.css')) ?>">
</head>
<body class="admin-body">
<aside class="admin-side">
    <a class="brand" href="<?= km_h(km_url('/admin')) ?>"><span class="mark">ک</span><?= km_h(km_t('admin')) ?></a>
    <nav>
        <?php foreach (km_admin_nav_items() as $href => $label): ?>
            <a class="<?= $path === $href ? 'is-active' : '' ?>" href="<?= km_h(km_url($href)) ?>"><?= km_h($label) ?></a>
        <?php endforeach; ?>
    </nav>
    <a href="<?= km_h(km_url('/')) ?>"><?= km_h(km_t('admin_home')) ?></a>
    <a href="<?= km_h(km_url('/admin/logout')) ?>"><?= km_h(km_t('logout')) ?></a>
</aside>
<div class="admin-main">
    <header class="admin-top"><h1><?= km_h($title) ?></h1>
        <a class="lang-flag" href="<?= km_h(km_lang_toggle_url()) ?>"><?= $KM_LANG === 'fa' ? '🇬🇧 EN' : '🇮🇷 FA' ?></a>
    </header>
    <?php
}

function km_admin_footer(): void
{
    echo '</div></body></html>';
}

function km_bi_fields(string $base, array $item, bool $area = false, string $faKey = 'title_fa', string $enKey = 'title_en'): void
{
    $faName = $base . '_fa';
    $enName = $base . '_en';
    $faVal = $item[$faKey] ?? $item[$faName] ?? '';
    $enVal = $item[$enKey] ?? $item[$enName] ?? '';
    echo '<div class="bi-grid"><label class="bi-col"><span>' . km_h(km_t('fa_label')) . '</span>';
    if ($area) {
        echo '<textarea name="' . km_h($faName) . '" rows="6">' . km_h($faVal) . '</textarea>';
    } else {
        echo '<input type="text" name="' . km_h($faName) . '" value="' . km_h($faVal) . '">';
    }
    echo '</label><label class="bi-col ltr"><span>' . km_h(km_t('en_label')) . '</span>';
    if ($area) {
        echo '<textarea name="' . km_h($enName) . '" rows="6" dir="ltr">' . km_h($enVal) . '</textarea>';
    } else {
        echo '<input type="text" name="' . km_h($enName) . '" value="' . km_h($enVal) . '" dir="ltr">';
    }
    echo '</label></div>';
}

function km_flash(): void
{
    if (!empty($_SESSION['km_flash'])) {
        echo '<p class="flash">' . km_h($_SESSION['km_flash']) . '</p>';
        unset($_SESSION['km_flash']);
    }
}

function km_set_flash(string $msg): void
{
    $_SESSION['km_flash'] = $msg;
}

function km_collect_bi(string $base): array
{
    return [
        $base . '_fa' => trim((string) ($_POST[$base . '_fa'] ?? '')),
        $base . '_en' => trim((string) ($_POST[$base . '_en'] ?? '')),
    ];
}

function km_save_collection(array &$site, string $key, callable $builder): void
{
    if (isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];
        $site[$key] = array_values(array_filter($site[$key] ?? [], static fn($i) => (int) $i['id'] !== $id));
        return;
    }
    $id = (int) ($_POST['id'] ?? 0);
    $row = $builder($id);
    $items = $site[$key] ?? [];
    $found = false;
    foreach ($items as $i => $item) {
        if ((int) $item['id'] === $id && $id > 0) {
            $items[$i] = array_merge($item, $row);
            $found = true;
            break;
        }
    }
    if (!$found) {
        $row['id'] = km_next_id($items);
        $items[] = $row;
    }
    $site[$key] = $items;
}

function km_admin_login(array $site): void
{
    global $KM_LANG, $KM_DIR;
    $error = false;
    if (km_is_post()) {
        km_csrf_check();
        if (km_attempt_login($site, (string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''))) {
            km_redirect(km_url('/admin'));
        }
        $error = true;
    }
    ?>
<!DOCTYPE html>
<html lang="<?= km_h($KM_LANG) ?>" dir="<?= km_h($KM_DIR) ?>">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= km_h(km_t('login')) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= km_h(km_asset('assets/css/app.css')) ?>">
    <link rel="stylesheet" href="<?= km_h(km_asset('assets/css/admin.css')) ?>">
</head>
<body class="login-body">
<form class="login-card" method="post">
    <?= km_csrf_field() ?>
    <h1><?= km_h(km_t('admin')) ?></h1>
    <?php if ($error): ?><p class="error"><?= km_h(km_t('login_error')) ?></p><?php endif; ?>
    <label><?= km_h(km_t('username')) ?><input name="username" required></label>
    <label><?= km_h(km_t('password')) ?><input type="password" name="password" required></label>
    <button class="btn btn-accent" type="submit"><?= km_h(km_t('login')) ?></button>
</form>
</body></html>
    <?php
}

function km_item_form_list(array $items, string $action, array $fields): void
{
    km_flash();
    echo '<p class="hint">' . km_h(km_t('bilingual_hint')) . '</p>';
    echo '<div class="admin-split">';
    echo '<section class="panel"><h2>' . km_h(km_t('add')) . ' / ' . km_h(km_t('edit')) . '</h2>';
    $edit = null;
    $eid = (int) ($_GET['id'] ?? 0);
    foreach ($items as $it) {
        if ((int) $it['id'] === $eid) {
            $edit = $it;
        }
    }
    $edit = $edit ?? ['id' => 0, 'visible' => true, 'order' => count($items) + 1];
    echo '<form method="post" enctype="multipart/form-data">';
    echo km_csrf_field();
    echo '<input type="hidden" name="id" value="' . (int) $edit['id'] . '">';
    foreach ($fields as $field) {
        $type = $field['type'] ?? 'bi';
        if ($type === 'bi') {
            echo '<p class="field-label">' . km_h($field['label']) . '</p>';
            km_bi_fields($field['name'], $edit, !empty($field['area']), $field['name'] . '_fa', $field['name'] . '_en');
        } elseif ($type === 'text') {
            echo '<label>' . km_h($field['label']) . '<input name="' . km_h($field['name']) . '" value="' . km_h($edit[$field['name']] ?? '') . '"></label>';
        } elseif ($type === 'image') {
            if (!empty($edit['image'])) {
                echo '<p>' . km_h(km_t('current_image')) . '<br><img class="thumb" src="' . km_h(km_upload_url($edit['image'])) . '" alt=""></p>';
            }
            echo '<label>' . km_h(km_t('image')) . '<input type="file" name="image" accept="image/*"></label>';
        } elseif ($type === 'order') {
            echo '<label>' . km_h(km_t('order')) . '<input type="number" name="order" value="' . (int) ($edit['order'] ?? 1) . '"></label>';
            echo '<label class="check"><input type="checkbox" name="visible" value="1"' . (!empty($edit['visible']) || !$edit['id'] ? ' checked' : '') . '> ' . km_h(km_t('visible')) . '</label>';
        }
    }
    echo '<button class="btn btn-accent" type="submit">' . km_h(km_t('save')) . '</button></form></section>';
    echo '<section class="panel"><h2>' . km_h(km_t('view_all')) . '</h2>';
    if (!$items) {
        echo '<p>' . km_h(km_t('no_items')) . '</p>';
    }
    foreach ($items as $it) {
        echo '<div class="row-item"><div><strong>' . km_h(km_text($it, 'title') ?: km_text($it, 'label')) . '</strong>';
        echo '<small>' . km_h($it['slug'] ?? '') . '</small></div><div class="row-actions">';
        echo '<a href="' . km_h($action . '?id=' . (int) $it['id']) . '">' . km_h(km_t('edit')) . '</a>';
        echo '<form method="post" onsubmit="return confirm(\'' . km_h(km_t('confirm_delete')) . '\')">' . km_csrf_field();
        echo '<input type="hidden" name="delete_id" value="' . (int) $it['id'] . '"><button type="submit">' . km_h(km_t('delete')) . '</button></form></div></div>';
    }
    echo '</section></div>';
}

function km_handle_admin(array &$site, string $path): void
{
    if ($path === '/admin/login') {
        if (km_admin_logged_in()) {
            km_redirect(km_url('/admin'));
        }
        km_admin_login($site);
        return;
    }
    if ($path === '/admin/logout') {
        km_logout();
        km_redirect(km_url('/admin/login'));
    }
    km_require_admin();

    if ($path === '/admin') {
        km_admin_header(km_t('dashboard'));
        echo '<div class="stat-grid">';
        foreach ([['sliders', $site['sliders'] ?? []], ['products', $site['products'] ?? []], ['projects', $site['projects'] ?? []], ['magazine', $site['magazine'] ?? []]] as $pair) {
            echo '<div class="stat"><strong>' . count($pair[1]) . '</strong><span>' . km_h(km_t($pair[0] === 'sliders' ? 'sliders' : $pair[0])) . '</span></div>';
        }
        echo '</div>';
        km_admin_footer();
        return;
    }

    if ($path === '/admin/sliders') {
        if (km_is_post()) {
            km_csrf_check();
            km_save_collection($site, 'sliders', static function ($id) {
                $img = km_handle_upload('image');
                $row = array_merge(
                    km_collect_bi('title'),
                    km_collect_bi('subtitle'),
                    km_collect_bi('cta'),
                    [
                        'cta_url' => trim((string) ($_POST['cta_url'] ?? '')),
                        'order' => (int) ($_POST['order'] ?? 1),
                        'visible' => !empty($_POST['visible']),
                    ]
                );
                if ($img) {
                    $row['image'] = $img;
                }
                return $row;
            });
            km_save_site($site);
            km_set_flash(km_t('saved'));
            km_redirect(km_url('/admin/sliders'));
        }
        km_admin_header(km_t('sliders'));
        km_item_form_list($site['sliders'] ?? [], km_url('/admin/sliders'), [
            ['type' => 'bi', 'name' => 'title', 'label' => km_t('title')],
            ['type' => 'bi', 'name' => 'subtitle', 'label' => km_t('subtitle')],
            ['type' => 'bi', 'name' => 'cta', 'label' => km_t('cta_label')],
            ['type' => 'text', 'name' => 'cta_url', 'label' => km_t('cta_url')],
            ['type' => 'image'],
            ['type' => 'order'],
        ]);
        km_admin_footer();
        return;
    }

    if ($path === '/admin/home') {
        if (km_is_post()) {
            km_csrf_check();
            km_save_collection($site, 'home_sections', static function () {
                return array_merge(km_collect_bi('title'), km_collect_bi('body'), [
                    'order' => (int) ($_POST['order'] ?? 1),
                    'visible' => !empty($_POST['visible']),
                ]);
            });
            km_save_site($site);
            km_set_flash(km_t('saved'));
            km_redirect(km_url('/admin/home'));
        }
        km_admin_header(km_t('home_sections'));
        km_item_form_list($site['home_sections'] ?? [], km_url('/admin/home'), [
            ['type' => 'bi', 'name' => 'title', 'label' => km_t('title')],
            ['type' => 'bi', 'name' => 'body', 'label' => km_t('body'), 'area' => true],
            ['type' => 'order'],
        ]);
        km_admin_footer();
        return;
    }

    if ($path === '/admin/menus') {
        if (km_is_post()) {
            km_csrf_check();
            km_save_collection($site, 'menus', static function () {
                return array_merge(km_collect_bi('label'), [
                    'url' => trim((string) ($_POST['url'] ?? '/')),
                    'order' => (int) ($_POST['order'] ?? 1),
                    'visible' => !empty($_POST['visible']),
                ]);
            });
            km_save_site($site);
            km_set_flash(km_t('saved'));
            km_redirect(km_url('/admin/menus'));
        }
        km_admin_header(km_t('menus'));
        km_item_form_list($site['menus'] ?? [], km_url('/admin/menus'), [
            ['type' => 'bi', 'name' => 'label', 'label' => km_t('title')],
            ['type' => 'text', 'name' => 'url', 'label' => km_t('url')],
            ['type' => 'order'],
        ]);
        km_admin_footer();
        return;
    }

    foreach (['products' => '/admin/products', 'projects' => '/admin/projects', 'magazine' => '/admin/magazine'] as $key => $route) {
        if ($path === $route) {
            if (km_is_post()) {
                km_csrf_check();
                km_save_collection($site, $key, static function ($id) use ($site, $key) {
                    $img = km_handle_upload('image');
                    $title = km_collect_bi('title');
                    $slug = trim((string) ($_POST['slug'] ?? ''));
                    if ($slug === '') {
                        $slug = km_slugify($title['title_en'] !== '' ? $title['title_en'] : $title['title_fa']);
                    }
                    $row = array_merge($title, km_collect_bi('excerpt'), km_collect_bi('body'), [
                        'slug' => $slug,
                        'order' => (int) ($_POST['order'] ?? 1),
                        'visible' => !empty($_POST['visible']),
                    ]);
                    if ($key === 'products') {
                        $row = array_merge($row, km_collect_bi('category'));
                    }
                    if ($img) {
                        $row['image'] = $img;
                    }
                    return $row;
                });
                km_save_site($site);
                km_set_flash(km_t('saved'));
                km_redirect(km_url($route));
            }
            km_admin_header(km_t($key));
            $fields = [
                ['type' => 'bi', 'name' => 'title', 'label' => km_t('title')],
                ['type' => 'text', 'name' => 'slug', 'label' => km_t('slug')],
                ['type' => 'bi', 'name' => 'excerpt', 'label' => km_t('excerpt'), 'area' => true],
                ['type' => 'bi', 'name' => 'body', 'label' => km_t('body'), 'area' => true],
                ['type' => 'image'],
                ['type' => 'order'],
            ];
            if ($key === 'products') {
                array_splice($fields, 2, 0, [[['type' => 'bi', 'name' => 'category', 'label' => km_t('category')]]]);
                $fields = [
                    ['type' => 'bi', 'name' => 'title', 'label' => km_t('title')],
                    ['type' => 'text', 'name' => 'slug', 'label' => km_t('slug')],
                    ['type' => 'bi', 'name' => 'category', 'label' => km_t('category')],
                    ['type' => 'bi', 'name' => 'excerpt', 'label' => km_t('excerpt'), 'area' => true],
                    ['type' => 'bi', 'name' => 'body', 'label' => km_t('body'), 'area' => true],
                    ['type' => 'image'],
                    ['type' => 'order'],
                ];
            }
            km_item_form_list($site[$key] ?? [], km_url($route), $fields);
            km_admin_footer();
            return;
        }
    }

    if ($path === '/admin/about') {
        if (km_is_post()) {
            km_csrf_check();
            $about = $site['about'] ?? [];
            $about = array_merge($about, km_collect_bi('title'), km_collect_bi('subtitle'), km_collect_bi('body'), km_collect_bi('mission'), km_collect_bi('vision'));
            $img = km_handle_upload('image');
            if ($img) {
                $about['image'] = $img;
            }
            $site['about'] = $about;
            km_save_site($site);
            km_set_flash(km_t('saved'));
            km_redirect(km_url('/admin/about'));
        }
        $about = $site['about'] ?? [];
        km_admin_header(km_t('about'));
        km_flash();
        echo '<p class="hint">' . km_h(km_t('bilingual_hint')) . '</p><form class="panel" method="post" enctype="multipart/form-data">';
        echo km_csrf_field();
        echo '<p class="field-label">' . km_h(km_t('title')) . '</p>';
        km_bi_fields('title', $about, false, 'title_fa', 'title_en');
        echo '<p class="field-label">' . km_h(km_t('subtitle')) . '</p>';
        km_bi_fields('subtitle', $about, false, 'subtitle_fa', 'subtitle_en');
        echo '<p class="field-label">' . km_h(km_t('body')) . '</p>';
        km_bi_fields('body', $about, true, 'body_fa', 'body_en');
        echo '<p class="field-label">' . km_h(km_t('mission')) . '</p>';
        km_bi_fields('mission', $about, true, 'mission_fa', 'mission_en');
        echo '<p class="field-label">' . km_h(km_t('vision')) . '</p>';
        km_bi_fields('vision', $about, true, 'vision_fa', 'vision_en');
        if (!empty($about['image'])) {
            echo '<p>' . km_h(km_t('current_image')) . '<br><img class="thumb" src="' . km_h(km_upload_url($about['image'])) . '" alt=""></p>';
        }
        echo '<label>' . km_h(km_t('image')) . '<input type="file" name="image" accept="image/*"></label>';
        echo '<button class="btn btn-accent" type="submit">' . km_h(km_t('save')) . '</button></form>';
        km_admin_footer();
        return;
    }

    if ($path === '/admin/contact') {
        if (km_is_post()) {
            km_csrf_check();
            $s = $site['settings'] ?? [];
            $s = array_merge($s, km_collect_bi('address'), km_collect_bi('hours'));
            $s['phone'] = trim((string) ($_POST['phone'] ?? ''));
            $s['email'] = trim((string) ($_POST['email'] ?? ''));
            $s['plus_code'] = trim((string) ($_POST['plus_code'] ?? ''));
            $s['map_query'] = trim((string) ($_POST['map_query'] ?? ''));
            $s['socials'] = $s['socials'] ?? [];
            foreach (km_social_networks() as $key => $_label) {
                $s['socials'][$key] = trim((string) ($_POST['social_' . $key] ?? ''));
            }
            $site['settings'] = $s;
            km_save_site($site);
            km_set_flash(km_t('saved'));
            km_redirect(km_url('/admin/contact'));
        }
        $s = $site['settings'] ?? [];
        km_admin_header(km_t('contact'));
        km_flash();
        echo '<p class="hint">' . km_h(km_t('bilingual_hint')) . '</p><form class="panel" method="post">';
        echo km_csrf_field();
        echo '<p class="field-label">' . km_h(km_t('address')) . '</p>';
        km_bi_fields('address', $s, true, 'address_fa', 'address_en');
        echo '<p class="field-label">' . km_h(km_t('hours')) . '</p>';
        km_bi_fields('hours', $s, false, 'hours_fa', 'hours_en');
        echo '<label>' . km_h(km_t('phone')) . '<input name="phone" value="' . km_h($s['phone'] ?? '') . '" dir="ltr"></label>';
        echo '<label>' . km_h(km_t('email')) . '<input name="email" value="' . km_h($s['email'] ?? '') . '" dir="ltr"></label>';
        echo '<label>' . km_h(km_t('plus_code')) . '<input name="plus_code" value="' . km_h($s['plus_code'] ?? '') . '" dir="ltr"></label>';
        echo '<label>' . km_h(km_t('map')) . '<input name="map_query" value="' . km_h($s['map_query'] ?? '') . '" dir="ltr"></label>';
        echo '<h2>' . km_h(km_t('socials')) . '</h2>';
        echo '<p class="hint">آیکون در فوتر و صفحه تماس فقط وقتی نمایش داده می‌شود که آدرس پر شده باشد. Icons appear only when a URL is saved.</p>';
        foreach (km_social_networks() as $key => $label) {
            $val = $s['socials'][$key] ?? '';
            echo '<label class="social-admin"><span class="social-btn social-' . km_h($key) . '">' . km_social_svg($key) . '</span> ' . km_h($label) . '<input name="social_' . km_h($key) . '" value="' . km_h($val) . '" placeholder="https://..." dir="ltr"></label>';
        }
        echo '<button class="btn btn-accent" type="submit">' . km_h(km_t('save')) . '</button></form>';
        $msgs = $site['messages'] ?? [];
        echo '<section class="panel"><h2>' . km_h(km_t('messages')) . '</h2>';
        if (!$msgs) {
            echo '<p>' . km_h(km_t('empty_inbox')) . '</p>';
        }
        foreach (array_reverse($msgs) as $m) {
            echo '<div class="row-item"><div><strong>' . km_h($m['name'] ?? '') . '</strong> · ' . km_h($m['email'] ?? '') . '<p>' . km_h($m['message'] ?? '') . '</p></div></div>';
        }
        echo '</section>';
        km_admin_footer();
        return;
    }

    http_response_code(404);
    echo 'Not found';
}
