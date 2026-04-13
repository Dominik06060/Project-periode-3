<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('/ons-aanbod');

require_login('Log eerst in om een auto te huren.');

$autoId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
$startDate = trim((string)($_POST['beginverhuur'] ?? ''));
$endDate = trim((string)($_POST['eindverhuur'] ?? ''));
$accountId = (int)($_SESSION['id'] ?? 0);

if ($autoId === false || $autoId === null) {
    redirect_with_flash('/ons-aanbod', 'rental_error', 'De gekozen auto is ongeldig.');
}

set_old_input([
    'beginverhuur' => $startDate,
    'eindverhuur' => $endDate,
], 'rental_form_' . $autoId);

if ($accountId < 1) {
    set_intended_url('/car-detail?id=' . $autoId);
    redirect_with_flash('/login-form', 'error', 'Log in om je reservering af te ronden.');
}

$start = DateTimeImmutable::createFromFormat('Y-m-d', $startDate);
$end = DateTimeImmutable::createFromFormat('Y-m-d', $endDate);
$today = new DateTimeImmutable('today');

if (!$start || $start->format('Y-m-d') !== $startDate || !$end || $end->format('Y-m-d') !== $endDate) {
    redirect_with_flash('/car-detail?id=' . $autoId, 'rental_error', 'Kies een geldige start- en einddatum.');
}

if ($start < $today) {
    redirect_with_flash('/car-detail?id=' . $autoId, 'rental_error', 'De huurperiode kan niet in het verleden beginnen.');
}

if ($end < $start) {
    redirect_with_flash('/car-detail?id=' . $autoId, 'rental_error', 'De einddatum moet op of na de startdatum liggen.');
}

$stmt = $conn->prepare('SELECT idauto, name, prijs FROM auto WHERE idauto = :id LIMIT 1');
$stmt->execute([':id' => $autoId]);
$auto = $stmt->fetch();

$stmt = $conn->prepare('SELECT id FROM account WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $accountId]);
$account = $stmt->fetch();

if (!$account) {
    unset($_SESSION['id'], $_SESSION['email'], $_SESSION['role']);
    set_intended_url('/car-detail?id=' . $autoId);
    redirect_with_flash('/login-form', 'error', 'Je account-sessie is niet meer geldig. Log opnieuw in.');
}

if (!$auto) {
    redirect_with_flash('/ons-aanbod', 'rental_error', 'Deze auto bestaat niet meer.');
}

$stmt = $conn->prepare(
    'SELECT id FROM verhuur
     WHERE auto_id = :auto_id AND beginverhuur <= :new_end AND eindverhuur >= :new_start
     LIMIT 1'
);
$stmt->execute([
    ':auto_id' => $autoId,
    ':new_end' => $end->format('Y-m-d'),
    ':new_start' => $start->format('Y-m-d'),
]);

if ($stmt->fetch()) {
    redirect_with_flash('/car-detail?id=' . $autoId, 'rental_error', 'Deze auto is in die periode al verhuurd. Kies andere datums.');
}

$days = (int)$start->diff($end)->format('%a') + 1;
$totalPrice = (int)$auto['prijs'] * $days;

$stmt = $conn->prepare(
    'INSERT INTO verhuur (account_id, auto_id, beginverhuur, eindverhuur, prijs)
     VALUES (:account_id, :auto_id, :beginverhuur, :eindverhuur, :prijs)'
);
$stmt->execute([
    ':account_id' => $accountId,
    ':auto_id' => $autoId,
    ':beginverhuur' => $start->format('Y-m-d'),
    ':eindverhuur' => $end->format('Y-m-d'),
    ':prijs' => $totalPrice,
]);

clear_old_input('rental_form_' . $autoId);
redirect_with_flash(
    '/car-detail?id=' . $autoId,
    'rental_success',
    sprintf(
        'Je reservering voor %s staat vast van %s t/m %s. Totaal: EUR %s.',
        (string)$auto['name'],
        $start->format('d-m-Y'),
        $end->format('d-m-Y'),
        number_format((float)$totalPrice, 0, ',', '.')
    )
);
