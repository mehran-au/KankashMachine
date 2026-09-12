<?php
declare(strict_types=1);

function km_password_hash(string $password): string
{
    return hash('sha256', KM_PEPPER . $password);
}

function km_csrf_token(): string
{
    if (empty($_SESSION['km_csrf'])) {
        $_SESSION['km_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['km_csrf'];
}

function km_csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . km_h(km_csrf_token()) . '">';
}

function km_csrf_check(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!hash_equals(km_csrf_token(), (string) $token)) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }
}

function km_admin_logged_in(): bool
{
    return !empty($_SESSION['km_admin']);
}

function km_require_admin(): void
{
    if (!km_admin_logged_in()) {
        km_redirect(km_url('/admin/login'));
    }
}

function km_attempt_login(array $site, string $username, string $password): bool
{
    $admin = $site['admin'] ?? [];
    $userOk = hash_equals((string) ($admin['username'] ?? 'admin'), $username);
    $hash = (string) ($admin['password_hash'] ?? '');
    $passOk = $hash !== '' && hash_equals($hash, km_password_hash($password));
    if ($userOk && $passOk) {
        $_SESSION['km_admin'] = $username;
        session_regenerate_id(true);
        return true;
    }
    return false;
}

function km_logout(): void
{
    unset($_SESSION['km_admin']);
    session_regenerate_id(true);
}
