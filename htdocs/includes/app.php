<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function redirect_to(string $path): never
{
    header("Location: {$path}");
    exit;
}

function set_flash_message(string $key, string $message): void
{
    $_SESSION['_flash'][$key] = $message;
}

function get_flash_message(string $key): ?string
{
    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $message = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    if (empty($_SESSION['_flash'])) {
        unset($_SESSION['_flash']);
    }

    return $message;
}

function set_old_input(array $input, string $bag = 'default'): void
{
    $_SESSION['_old'][$bag] = $input;
}

function old_input(string $key, string $default = '', string $bag = 'default'): string
{
    return htmlspecialchars((string)($_SESSION['_old'][$bag][$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function old_input_value(string $key, string $default = '', string $bag = 'default'): string
{
    return (string)($_SESSION['_old'][$bag][$key] ?? $default);
}

function clear_old_input(string $bag = 'default'): void
{
    unset($_SESSION['_old'][$bag]);

    if (empty($_SESSION['_old'])) {
        unset($_SESSION['_old']);
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION['id']);
}

function require_login(string $message = 'Log eerst in om deze actie uit te voeren.'): void
{
    if (is_logged_in()) {
        return;
    }

    set_flash_message('error', $message);
    redirect_to('/login-form');
}

function set_intended_url(string $path): void
{
    $_SESSION['_intended_url'] = $path;
}

function pull_intended_url(): ?string
{
    if (!isset($_SESSION['_intended_url'])) {
        return null;
    }

    $path = (string)$_SESSION['_intended_url'];
    unset($_SESSION['_intended_url']);

    return $path;
}

function car_image_src(mixed $blob): ?string
{
    if (is_string($blob) && $blob !== '') {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($blob) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode($blob);
    }

    return null;
}

function selected_option(string $currentValue, string $optionValue): string
{
    return $currentValue === $optionValue ? ' selected' : '';
}
