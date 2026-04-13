<?php

require_once __DIR__ . '/includes/app.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$file = __DIR__ . '/pages/' . ($path ?: 'home') . '.php';
$routes = [
    'logout' => '/actions/logout.php',
    'login-handler' => '/actions/login.php',
    'register-handler' => '/actions/register.php',
    'add-car-handler' => '/actions/add-car.php',
    'rent-car-handler' => '/actions/rent-car.php',
];

if (isset($routes[$path])) require_once __DIR__ . $routes[$path];
elseif (file_exists($file)) include $file;
else {
    http_response_code(404);
    include __DIR__ . '/pages/404.php';
}
