<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('/ons-aanbod');
require_login('Log eerst in om een auto toe te voegen.');

$name = trim((string)($_POST['name'] ?? ''));
$typeCar = trim((string)($_POST['typecar'] ?? ''));
$steering = trim((string)($_POST['steering'] ?? ''));
$capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$gasoline = filter_input(INPUT_POST, 'gasoline', FILTER_VALIDATE_INT);
$prijs = filter_input(INPUT_POST, 'prijs', FILTER_VALIDATE_INT);

set_old_input([
    'name' => $name,
    'typecar' => $typeCar,
    'steering' => $steering,
    'capacity' => $capacity !== false ? (string)$capacity : (string)($_POST['capacity'] ?? ''),
    'gasoline' => $gasoline !== false ? (string)$gasoline : (string)($_POST['gasoline'] ?? ''),
    'prijs' => $prijs !== false ? (string)$prijs : (string)($_POST['prijs'] ?? ''),
], 'car_form');

if (!$name || !$typeCar || !$steering) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'Vul naam, type en transmissie van de auto in.');
}

if ($capacity === false || $capacity < 1 || $gasoline === false || $gasoline < 1 || $prijs === false || $prijs < 1) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'Capaciteit, brandstof en prijs moeten geldige positieve waarden zijn.');
}

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'Upload een foto. Elke auto moet een database-afbeelding hebben.');
}

if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'De foto kon niet worden geupload.');
}

if ((int)$_FILES['foto']['size'] > 2 * 1024 * 1024) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'De foto mag maximaal 2 MB groot zijn.');
}

$mime = mime_content_type($_FILES['foto']['tmp_name']) ?: '';
if (strpos($mime, 'image/') !== 0) {
    redirect_with_flash('/ons-aanbod', 'catalog_error', 'Upload een geldige afbeelding voor de auto.');
}

$photoBlob = file_get_contents($_FILES['foto']['tmp_name']);

$conn->prepare(
    'INSERT INTO auto (name, typecar, steering, capacity, gasoline, prijs, foto)
     VALUES (:name, :typecar, :steering, :capacity, :gasoline, :prijs, :foto)'
)->execute([
    ':name' => $name,
    ':typecar' => $typeCar,
    ':steering' => $steering,
    ':capacity' => $capacity,
    ':gasoline' => $gasoline,
    ':prijs' => $prijs,
    ':foto' => $photoBlob,
]);

clear_old_input('car_form');
redirect_with_flash('/ons-aanbod', 'catalog_success', 'De auto is toegevoegd aan de database.');
