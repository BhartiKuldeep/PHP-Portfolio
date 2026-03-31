<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}

function baseUrl(): string
{
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    return $scheme . '://' . $host;
}

function currentUrl(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    return baseUrl() . $uri;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return is_string($value) ? $value : null;
}

function old(string $key, string $default = ''): string
{
    return isset($_SESSION['old'][$key]) ? (string) $_SESSION['old'][$key] : $default;
}

function setOldInput(array $data): void
{
    $_SESSION['old'] = $data;
}

function clearOldInput(): void
{
    unset($_SESSION['old']);
}

function storagePath(string $file): string
{
    return __DIR__ . '/../storage/' . ltrim($file, '/');
}

function saveMessage(array $message): bool
{
    $file = storagePath('messages.json');

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    $json = file_get_contents($file);
    $messages = json_decode($json ?: '[]', true);

    if (!is_array($messages)) {
        $messages = [];
    }

    $messages[] = $message;

    return file_put_contents(
        $file,
        json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    ) !== false;
}
