<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('/login-form');

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');

set_old_input(['email' => $email]);

if (!$email || !$password) {
    redirect_with_flash('/login-form', 'error', 'Vul je e-mailadres en wachtwoord in.');
}

$stmt = $conn->prepare('SELECT id, email, password, role FROM account WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, (string)$user['password'])) {
    redirect_with_flash('/login-form', 'error', 'De inloggegevens kloppen niet.');
}

session_regenerate_id(true);

$_SESSION['id'] = (int)$user['id'];
$_SESSION['email'] = (string)$user['email'];
$_SESSION['role'] = $user['role'];

clear_old_input();
set_flash_message('success', 'Je bent ingelogd.');

redirect_to(pull_intended_url() ?: '/');
