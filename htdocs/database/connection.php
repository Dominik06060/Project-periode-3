<?php

$host = 'localhost';
$dbname = 'rental';
$username = 'root';
$password = '';

if (!function_exists('seed_missing_car_images')) {
    function seed_missing_car_images(PDO $conn): void
    {
        static $seededThisRequest = false;

        if ($seededThisRequest) {
            return;
        }

        $missingCount = (int)$conn->query(
            'SELECT COUNT(*) FROM auto WHERE foto IS NULL OR LENGTH(foto) = 0'
        )->fetchColumn();

        if ($missingCount === 0) {
            $seededThisRequest = true;
            return;
        }

        $imageMap = [
            3 => __DIR__ . '/../assets/images/products/Car (3).svg',
            4 => __DIR__ . '/../assets/images/products/Car (4).svg',
            5 => __DIR__ . '/../assets/images/products/Car (5).svg',
            6 => __DIR__ . '/../assets/images/products/Car (6).svg',
            7 => __DIR__ . '/../assets/images/products/Car (7).svg',
            8 => __DIR__ . '/../assets/images/products/Car (8).svg',
            9 => __DIR__ . '/../assets/images/products/Car (9).svg',
            10 => __DIR__ . '/../assets/images/products/Car (10).svg',
            11 => __DIR__ . '/../assets/images/products/Car (11).svg',
            12 => __DIR__ . '/../assets/images/products/car.svg',
        ];

        foreach ($imageMap as $carId => $imagePath) {
            if (!file_exists($imagePath)) {
                continue;
            }

            $blob = file_get_contents($imagePath);
            if ($blob === false) {
                continue;
            }

            $quotedBlob = $conn->quote($blob);
            $conn->exec(
                "UPDATE auto
                 SET foto = {$quotedBlob}
                 WHERE idauto = {$carId}
                   AND (foto IS NULL OR LENGTH(foto) = 0)"
            );
        }

        $seededThisRequest = true;
    }
}

try {
    $conn = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    seed_missing_car_images($conn);
} catch (PDOException $exception) {
    http_response_code(500);
    exit('Er is geen verbinding met de database mogelijk.');
}
