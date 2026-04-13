<?php 
require_once __DIR__ . '/app.php';

$cssVersion = file_exists(__DIR__ . '/../assets/css/main.css') 
    ? (string)filemtime(__DIR__ . '/../assets/css/main.css') : '1';
$jsVersion = file_exists(__DIR__ . '/../assets/javascript/main.js') 
    ? (string)filemtime(__DIR__ . '/../assets/javascript/main.js') : '1';
$search = trim((string)($_GET['q'] ?? ''));
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rydr</title>
    <link rel="stylesheet" href="assets/css/main.css?v=<?= h($cssVersion) ?>">
    <link rel="icon" type="image/webp" href="assets/images/favicon.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body>
<div class="topbar">
    <div class="logo">
        <a href="/">Rydr.</a>
    </div>
    <form action="/ons-aanbod" method="get">
        <input type="search" name="q" id="site-search" placeholder="Welke auto wilt u huren?" value="<?= h($search) ?>">
        <img src="assets/images/icons/search-normal.svg" alt="" class="search-icon">
    </form>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/ons-aanbod">Ons aanbod</a></li>
            <li><a href="/over-ons">Over ons</a></li>
        </ul>
    </nav>
    <div class="menu">
        <?php if (is_logged_in()) { ?>
            <div class="account">
                <img src="assets/images/Profil.webp" alt="Profiel">
                <div class="account-dropdown">
                    <ul>
                        <li><img src="assets/images/icons/setting.svg" alt=""><a href="/ons-aanbod">Beheer aanbod</a></li>
                        <li><img src="assets/images/icons/logout.svg" alt=""><a href="/logout">Uitloggen</a></li>
                    </ul>
                </div>
            </div>
        <?php } else { ?>
            <a href="/login-form" class="button-primary js-login-trigger">Start met huren</a>
        <?php } ?>
    </div>
</div>
<div class="content">
