<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function respond(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function validSecret(string $a, string $b): bool
{
    return hash_equals($b, $a);
}

function run(array $cmd): array
{
    $out = [];
    $code = 0;
    exec(implode(' ', array_map('escapeshellarg', $cmd)) . ' 2>&1', $out, $code);
    return [$code, implode("\n", $out)];
}

$key = $_GET['key'] ?? '';
$host = strtolower(trim($_GET['host'] ?? ''));

if (!validSecret($key, $config['secret'])) {
    respond(403, ['ok' => false, 'error' => 'Invalid key']);
}

if (!isset($config['allowed_hosts'][$host])) {
    respond(400, ['ok' => false, 'error' => 'Host not allowed']);
}

$ip = $_SERVER['REMOTE_ADDR'];

$domain = $config['allowed_hosts'][$host]['domain'];
$sub = $config['allowed_hosts'][$host]['subdomain'];

$cmd = [];

if ($config['use_sudo']) {
    $cmd[] = '/usr/bin/sudo';
}

$cmd = array_merge($cmd, [
    $config['plesk_bin'],
    'bin',
    'dns',
    '--update',
    $domain,
    '-a',
    $sub,
    '-ip',
    $ip
]);

[$code, $out] = run($cmd);

if ($code !== 0) {
    respond(500, [
        'ok' => false,
        'error' => 'Plesk command failed',
        'details' => $out
    ]);
}

respond(200, [
    'ok' => true,
    'changed' => true,
    'host' => $host,
    'ip' => $ip
]);
