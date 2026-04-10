<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/register-form');
}

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$confirmPassword = (string)($_POST['confirm-password'] ?? '');

set_old_input(['email' => $email]);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash_message('error', 'Vul een geldig e-mailadres in.');
    redirect_to('/register-form');
}

if (strlen($password) < 8) {
    set_flash_message('error', 'Je wachtwoord moet minimaal 8 tekens lang zijn.');
    redirect_to('/register-form');
}

if ($password !== $confirmPassword) {
    set_flash_message('error', 'Wachtwoorden komen niet overeen.');
    redirect_to('/register-form');
}

$checkAccount = $conn->prepare('SELECT id FROM account WHERE email = :email LIMIT 1');
$checkAccount->execute([':email' => $email]);

if ($checkAccount->fetch()) {
    set_flash_message('error', 'Dit e-mailadres is al in gebruik.');
    redirect_to('/register-form');
}

$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

$createAccount = $conn->prepare('INSERT INTO account (email, password) VALUES (:email, :password)');
$createAccount->execute([
    ':email' => $email,
    ':password' => $encryptedPassword,
]);

clear_old_input();
set_flash_message('success', 'Registratie is gelukt. Log nu in met je nieuwe account.');
redirect_to('/login-form');
