<?php
declare(strict_types=1);

function km_smtp_read($fp): string
{
    $data = '';
    while (($line = fgets($fp, 1024)) !== false) {
        $data .= $line;
        if (isset($line[3]) && $line[3] === ' ') {
            break;
        }
    }
    return $data;
}

function km_smtp_cmd($fp, string $cmd): string
{
    fwrite($fp, $cmd . "\r\n");
    return km_smtp_read($fp);
}

function km_smtp_ok(string $resp, string $expect = '2'): bool
{
    return $resp !== '' && str_starts_with(trim($resp), $expect);
}

function km_smtp_send(array $s, string $to, string $subject, string $body, string $fromEmail, string $fromName): bool
{
    $host = trim((string) ($s['smtp_host'] ?? ''));
    $port = (int) ($s['smtp_port'] ?? 587);
    $secure = (string) ($s['smtp_secure'] ?? 'tls');
    $user = trim((string) ($s['smtp_user'] ?? ''));
    $pass = (string) ($s['smtp_pass'] ?? '');
    if ($host === '' || $user === '' || $pass === '') {
        return false;
    }
    if ($port < 1) {
        $port = 587;
    }
    $remote = ($secure === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
    $ctx = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ]);
    $fp = @stream_socket_client($remote, $errno, $errstr, 20, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) {
        return false;
    }
    stream_set_timeout($fp, 20);
    $greet = km_smtp_read($fp);
    if (!km_smtp_ok($greet)) {
        fclose($fp);
        return false;
    }
    $ehlo = km_smtp_cmd($fp, 'EHLO kankashmachine.com');
    if ($secure === 'tls') {
        $start = km_smtp_cmd($fp, 'STARTTLS');
        if (!km_smtp_ok($start)) {
            fclose($fp);
            return false;
        }
        if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($fp);
            return false;
        }
        $ehlo = km_smtp_cmd($fp, 'EHLO kankashmachine.com');
    }
    if ($ehlo === '') {
        fclose($fp);
        return false;
    }
    $auth = km_smtp_cmd($fp, 'AUTH LOGIN');
    if (!km_smtp_ok($auth, '3')) {
        fclose($fp);
        return false;
    }
    $uok = km_smtp_cmd($fp, base64_encode($user));
    $pok = km_smtp_cmd($fp, base64_encode($pass));
    if (!km_smtp_ok($uok, '3') || !km_smtp_ok($pok)) {
        fclose($fp);
        return false;
    }
    $encName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
    $encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $reply = trim((string) ($s['mail_reply_to'] ?? $fromEmail));
    $fromLine = $encName . ' <' . $fromEmail . '>';
    if (!km_smtp_ok(km_smtp_cmd($fp, 'MAIL FROM:<' . $fromEmail . '>'))) {
        fclose($fp);
        return false;
    }
    if (!km_smtp_ok(km_smtp_cmd($fp, 'RCPT TO:<' . $to . '>'))) {
        fclose($fp);
        return false;
    }
    if (!km_smtp_ok(km_smtp_cmd($fp, 'DATA'), '3')) {
        fclose($fp);
        return false;
    }
    $payload = implode("\r\n", [
        'Date: ' . date('r'),
        'From: ' . $fromLine,
        'To: <' . $to . '>',
        'Reply-To: ' . $reply,
        'Subject: ' . $encSubject,
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        '',
        str_replace(["\r\n.", "\n."], ["\r\n..", "\n.."], str_replace("\r\n", "\n", $body)),
        '.',
    ]);
    fwrite($fp, $payload . "\r\n");
    $dataOk = km_smtp_read($fp);
    km_smtp_cmd($fp, 'QUIT');
    fclose($fp);
    return km_smtp_ok($dataOk);
}
