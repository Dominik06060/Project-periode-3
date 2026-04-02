<?php

$host     = 'localhost';
$dbname   = 'rental';   // database that contains the accounts table
$username = 'root';
$password = '';

try {
    // 1. Make the connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Get email & password from the form (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        if ($email !== '' && $password !== '') {
            // 3. Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 4. Insert into accounts table (prepared statement)
            $stmt = $conn->prepare("
                INSERT INTO account (email, password)
                VALUES (:email, :password)
            ");
            $stmt->execute([
                ':email'    => $email,
                ':password' => $hashedPassword,
            ]);

            echo 'New account saved in database.';
        } else {
            echo 'Email and password are required.';
        }
    }

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}