<?php

$config = [
    'host' => 'localhost',
    'dbname' => 'rental',
    'username' => 'root',
    'password' => '',
];

if (!function_exists('seed_missing_car_images')) {
    function seed_missing_car_images(PDO $conn): void {
        static $seeded = false;
        if ($seeded) return;

        $missing = (int)$conn
            ->query('SELECT COUNT(*) FROM auto WHERE foto IS NULL OR LENGTH(foto) = 0')
            ->fetchColumn();

        if (!$missing) {
            $seeded = true;
            return;
        }

        $images = [
            3 => 'Car (3).svg', 4 => 'Car (4).svg', 5 => 'Car (5).svg',
            6 => 'Car (6).svg', 7 => 'Car (7).svg', 8 => 'Car (8).svg',
            9 => 'Car (9).svg', 10 => 'Car (10).svg', 11 => 'Car (11).svg',
            12 => 'car.svg',
        ];

        $update = $conn->prepare(
            'UPDATE auto
             SET foto = :foto
             WHERE idauto = :id AND (foto IS NULL OR LENGTH(foto) = 0)'
        );

        foreach ($images as $id => $file) {
            $path = __DIR__ . "/../assets/images/products/{$file}";
            if (!file_exists($path)) continue;

            $blob = file_get_contents($path);
            if (!$blob) continue;

            $update->execute([
                ':foto' => $blob,
                ':id' => $id,
            ]);
        }

        $seeded = true;
    }
}

try {
    $conn = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    seed_missing_car_images($conn);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Er is geen verbinding met de database mogelijk.');
}
