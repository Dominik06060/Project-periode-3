<?php
require 'includes/header.php';
require 'database/connection.php';

try {
    $popular = $conn->query('SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto FROM auto ORDER BY idauto ASC LIMIT 4')->fetchAll();
    $recommended = $conn->query('SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto FROM auto ORDER BY idauto ASC LIMIT 8 OFFSET 4')->fetchAll();
} catch (PDOException $e) {
    $popular = $recommended = [];
}
?>

<header>
    <div class="advertorials">
        <div class="advertorial">
            <h2>Het platform om snel een auto te huren</h2>
            <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
            <a href="/ons-aanbod" class="button-primary">Huur nu een auto</a>
            <img src="assets/images/car-rent-header-image-1.webp" alt="Sportieve huurauto">
            <img src="assets/images/header-circle-background.webp" alt="" class="background-header-element">
        </div>
        <div class="advertorial">
            <h2>Wij verhuren ook bedrijfswagens</h2>
            <p>Voor een vaste lage prijs met prettige voordelen.</p>
            <a href="/ons-aanbod" class="button-primary">Huur een bedrijfswagen</a>
            <img src="assets/images/car-rent-header-image-2.webp" alt="Bedrijfswagen">
            <img src="assets/images/header-block-background.webp" alt="" class="background-header-element">
        </div>
    </div>
</header>

<main>
    <?php if ($success = get_flash_message('success')) { ?>
        <div class="success-message"><?= h($success) ?></div>
    <?php } ?>

    <h2 class="section-title">Populaire auto's</h2>
    <div class="cars">
        <?php foreach ($popular as $auto) render_car_card($auto); ?>
    </div>

    <h2 class="section-title">Aanbevolen auto's</h2>
    <div class="cars">
        <?php foreach ($recommended as $auto) render_car_card($auto); ?>
    </div>

    <div class="show-more">
        <a class="button-primary" href="/ons-aanbod">Toon alle</a>
    </div>
</main>

<?php require 'includes/footer.php' ?>
