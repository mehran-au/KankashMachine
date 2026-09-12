<?php
declare(strict_types=1);

define('KM_ROOT', dirname(__DIR__));
define('KM_DATA', KM_ROOT . '/data/site.json');
define('KM_UPLOADS', KM_ROOT . '/uploads');
define('KM_PEPPER', 'kankash-machine-v1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once KM_ROOT . '/includes/store.php';
require_once KM_ROOT . '/includes/i18n.php';
require_once KM_ROOT . '/includes/auth.php';

$KM_SITE = km_load_site();
$KM_LANG = km_resolve_lang();
$KM_T = km_ui_strings($KM_LANG);
$KM_DIR = $KM_LANG === 'fa' ? 'rtl' : 'ltr';

function km_request_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($scriptDir && $scriptDir !== '/' && strpos($uri, $scriptDir) === 0) {
        $uri = substr($uri, strlen($scriptDir));
    }
    $uri = '/' . trim($uri, '/');
    return $uri === '/' ? '/' : rtrim($uri, '/');
}

function km_base(): string
{
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return $scriptDir === '/' ? '' : $scriptDir;
}

function km_url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        return km_base() . '/';
    }
    return km_base() . $path;
}

function km_asset(string $path): string
{
    return km_url('/' . ltrim($path, '/'));
}

function km_upload_url(string $file): string
{
    if ($file === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $file)) {
        return $file;
    }
    return km_url('/uploads/' . ltrim($file, '/'));
}

function km_h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function km_text(array $item, string $field): string
{
    global $KM_LANG;
    $key = $field . '_' . $KM_LANG;
    if (!empty($item[$key])) {
        return (string) $item[$key];
    }
    $fallback = $field . '_fa';
    return (string) ($item[$fallback] ?? $item[$field . '_en'] ?? '');
}

function km_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function km_is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function km_slugify(string $text): string
{
    $text = trim($text);
    $text = preg_replace('/\s+/u', '-', $text) ?? $text;
    $text = preg_replace('/[^\p{L}\p{N}\-_]+/u', '', $text) ?? $text;
    $text = trim($text, '-');
    if ($text === '') {
        $text = 'item-' . substr(bin2hex(random_bytes(4)), 0, 8);
    }
    return mb_strtolower($text);
}

function km_find_by_slug(array $items, string $slug): ?array
{
    foreach ($items as $item) {
        if (($item['slug'] ?? '') === $slug) {
            return $item;
        }
    }
    return null;
}

function km_visible_menu(array $site): array
{
    $items = $site['menus'] ?? [];
    usort($items, static fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    return array_values(array_filter($items, static fn($m) => !empty($m['visible'])));
}

function km_social_networks(): array
{
    return [
        'instagram' => 'Instagram',
        'telegram' => 'Telegram',
        'whatsapp' => 'WhatsApp',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
        'aparat' => 'Aparat',
        'x' => 'X',
        'facebook' => 'Facebook',
    ];
}

function km_fill_template(string $tpl, array $vars): string
{
    foreach ($vars as $k => $v) {
        $tpl = str_replace('{' . $k . '}', (string) $v, $tpl);
    }
    return $tpl;
}

function km_client_ip(): string
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    return preg_match('/^[0-9a-fA-F:.]+$/', $ip) ? $ip : '0.0.0.0';
}

function km_rate_ok(string $bucket, int $max, int $seconds): bool
{
    $file = KM_ROOT . '/data/rate.json';
    $now = time();
    $data = [];
    if (is_file($file)) {
        $raw = (string) file_get_contents($file);
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $key = $bucket . ':' . km_client_ip();
    $hits = array_values(array_filter($data[$key] ?? [], static fn($t) => is_int($t) && $t > $now - $seconds));
    if (count($hits) >= $max) {
        return false;
    }
    $hits[] = $now;
    $data[$key] = $hits;
    if (count($data) > 400) {
        $data = array_slice($data, -200, null, true);
    }
    @file_put_contents($file, json_encode($data), LOCK_EX);
    return true;
}

function km_phone_links(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    $parts = preg_split('/\s*[–—,;|]\s*/u', $raw) ?: [$raw];
    $html = [];
    foreach ($parts as $part) {
        $part = trim((string) $part);
        if ($part === '') {
            continue;
        }
        $digits = preg_replace('/[^\d+]/', '', $part) ?? '';
        if ($digits === '') {
            $html[] = km_h($part);
            continue;
        }
        if (strpos($digits, '021') === 0) {
            $tel = '+98' . substr($digits, 1);
        } elseif (strpos($digits, '98') === 0) {
            $tel = '+' . ltrim($digits, '+');
        } elseif (strpos($digits, '0') === 0) {
            $tel = '+98' . substr($digits, 1);
        } else {
            $tel = $digits;
        }
        $html[] = '<a class="tel" href="tel:' . km_h($tel) . '">' . km_h($part) . '</a>';
    }
    return implode(' <span class="tel-sep">–</span> ', $html);
}

function km_mail(string $to, string $subject, string $body, string $fromEmail, string $fromName = 'Kankash Machine'): bool
{
    $to = trim($to);
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        $fromEmail = 'noreply@kankashmachine.com';
    }
    $encName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
    $encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = implode("\r\n", [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ' . $encName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
    ]);
    return @mail($to, $encSubject, $body, $headers);
}

function km_active_socials(array $site): array
{
    $raw = $site['settings']['socials'] ?? [];
    $out = [];
    foreach (km_social_networks() as $key => $label) {
        $url = trim((string) ($raw[$key] ?? ''));
        if ($url !== '') {
            $out[$key] = ['label' => $label, 'url' => $url];
        }
    }
    return $out;
}
