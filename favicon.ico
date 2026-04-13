<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('/register-form');

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$confirmPassword = (string)($_POST['confirm-password'] ?? '');

set_old_input(['email' => $email]);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_flash('/register-form', 'error', 'Vul een geldig e-mailadres in.');
}

if (strlen($password) < 8) {
    redirect_with_flash('/register-form', 'error', 'Je wachtwoord moet minimaal 8 tekens lang zijn.');
}

if ($password !== $confirmPassword) {
    redirect_with_flash('/register-form', 'error', 'Wachtwoorden komen niet overeen.');
}

$stmt = $conn->prepare('SELECT id FROM account WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);

if ($stmt->fetch()) {
    redirect_with_flash('/register-form', 'error', 'Dit e-mailadres is al in gebruik.');
}

$stmt = $conn->prepare('INSERT INTO account (email, password) VALUES (:email, :password)');
$stmt->execute([
    ':email' => $email,
    ':password' => password_hash($password, PASSWORD_DEFAULT),
]);

clear_old_input();
redirect_with_flash('/login-form', 'success', 'Registratie is gelukt. Log nu in met je nieuwe account.');
