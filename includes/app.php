<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function h(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $path): never {
    header("Location: {$path}");
    exit;
}

function redirect_with_flash(string $path, string $key, string $message): never {
    $_SESSION['_flash'][$key] = $message;
    redirect_to($path);
}

function set_flash_message(string $key, string $message): void {
    $_SESSION['_flash'][$key] = $message;
}

function get_flash_message(string $key): ?string {
    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);

    if (empty($_SESSION['_flash'])) unset($_SESSION['_flash']);

    return $message;
}

function set_old_input(array $input, string $bag = 'default'): void {
    $_SESSION['_old'][$bag] = $input;
}

function old_input(string $key, string $default = '', string $bag = 'default'): string {
    return h($_SESSION['_old'][$bag][$key] ?? $default);
}

function old_input_value(string $key, string $default = '', string $bag = 'default'): string {
    return (string)($_SESSION['_old'][$bag][$key] ?? $default);
}

function clear_old_input(string $bag = 'default'): void {
    unset($_SESSION['_old'][$bag]);

    if (empty($_SESSION['_old'])) unset($_SESSION['_old']);
}

function is_logged_in(): bool {
    return isset($_SESSION['id']);
}

function require_login(string $message = 'Log eerst in om deze actie uit te voeren.'): void {
    if (!is_logged_in()) redirect_with_flash('/login-form', 'error', $message);
}

function set_intended_url(string $path): void {
    $_SESSION['_intended_url'] = $path;
}

function pull_intended_url(): ?string {
    $path = $_SESSION['_intended_url'] ?? null;
    unset($_SESSION['_intended_url']);
    return $path ? (string)$path : null;
}

function car_image_src(mixed $blob): ?string {
    static $finfo;

    if (!is_string($blob) || $blob === '') return null;

    $finfo ??= new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($blob) ?: 'image/jpeg';

    return "data:{$mime};base64," . base64_encode($blob);
}

function selected_option(string $current, string $option): string {
    return $current === $option ? ' selected' : '';
}

function format_price(int|float|string $price): string {
    return number_format((float)$price, 0, ',', '.');
}

function format_date(string $date): string {
    return date('d-m-Y', strtotime($date));
}

function render_car_card(array $auto): void {
    $name = h($auto['name'] ?? '');
    $type = h($auto['typecar'] ?? '');
    $steering = h($auto['steering'] ?? '');
    $image = car_image_src($auto['foto'] ?? null);
    ?>
    <div class="car-details">
        <div class="car-brand">
            <h3><?= $name ?></h3>
            <div class="car-type"><?= $type ?></div>
        </div>
        <?php if ($image) { ?>
            <img src="<?= $image ?>" alt="<?= $name ?>">
        <?php } else { ?>
            <div class="car-image-empty">Geen databasefoto beschikbaar</div>
        <?php } ?>
        <div class="car-specification">
            <span><img src="assets/images/icons/gas-station.svg" alt=""><?= (int)($auto['gasoline'] ?? 0) ?>L</span>
            <span><img src="assets/images/icons/car.svg" alt=""><?= $steering ?></span>
            <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= (int)($auto['capacity'] ?? 0) ?> personen</span>
        </div>
        <div class="rent-details">
            <span><span class="font-weight-bold">&euro;<?= format_price($auto['prijs'] ?? 0) ?></span> / dag</span>
            <a href="/car-detail?id=<?= (int)($auto['idauto'] ?? 0) ?>" class="button-primary">Bekijk nu</a>
        </div>
    </div>
    <?php
}
