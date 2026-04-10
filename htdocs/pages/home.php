<?php
require 'includes/header.php';
require 'database/connection.php';

$popularAutos = [];
$recommendedAutos = [];

try {
    $popularStatement = $conn->query('SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto FROM auto ORDER BY idauto ASC LIMIT 4');
    $popularAutos = $popularStatement->fetchAll();

    $recommendedStatement = $conn->query('SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto FROM auto ORDER BY idauto ASC LIMIT 8 OFFSET 4');
    $recommendedAutos = $recommendedStatement->fetchAll();
} catch (PDOException $exception) {
    $popularAutos = [];
    $recommendedAutos = [];
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
    <?php if ($successMessage = get_flash_message('success')) { ?>
        <div class="success-message"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
    <?php } ?>

    <h2 class="section-title">Populaire auto's</h2>
    <div class="cars">
        <?php foreach ($popularAutos as $auto) { ?>
            <?php $imageSrc = car_image_src($auto['foto'] ?? null); ?>
            <div class="car-details">
                <div class="car-brand">
                    <h3><?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div class="car-type"><?= htmlspecialchars((string)$auto['typecar'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <?php if ($imageSrc !== null) { ?>
                    <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?>">
                <?php } else { ?>
                    <div class="car-image-empty">Geen databasefoto beschikbaar</div>
                <?php } ?>
                <div class="car-specification">
                    <span><img src="assets/images/icons/gas-station.svg" alt=""><?= (int)$auto['gasoline'] ?>L</span>
                    <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars((string)$auto['steering'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= (int)$auto['capacity'] ?> personen</span>
                </div>
                <div class="rent-details">
                    <span><span class="font-weight-bold">&euro;<?= number_format((float)$auto['prijs'], 0, ',', '.') ?></span> / dag</span>
                    <a href="/car-detail?id=<?= (int)$auto['idauto'] ?>" class="button-primary">Bekijk nu</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <h2 class="section-title">Aanbevolen auto's</h2>
    <div class="cars">
        <?php foreach ($recommendedAutos as $auto) { ?>
            <?php $imageSrc = car_image_src($auto['foto'] ?? null); ?>
            <div class="car-details">
                <div class="car-brand">
                    <h3><?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div class="car-type"><?= htmlspecialchars((string)$auto['typecar'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <?php if ($imageSrc !== null) { ?>
                    <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?>">
                <?php } else { ?>
                    <div class="car-image-empty">Geen databasefoto beschikbaar</div>
                <?php } ?>
                <div class="car-specification">
                    <span><img src="assets/images/icons/gas-station.svg" alt=""><?= (int)$auto['gasoline'] ?>L</span>
                    <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars((string)$auto['steering'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= (int)$auto['capacity'] ?> personen</span>
                </div>
                <div class="rent-details">
                    <span><span class="font-weight-bold">&euro;<?= number_format((float)$auto['prijs'], 0, ',', '.') ?></span> / dag</span>
                    <a href="/car-detail?id=<?= (int)$auto['idauto'] ?>" class="button-primary">Bekijk nu</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="show-more">
        <a class="button-primary" href="/ons-aanbod">Toon alle</a>
    </div>
</main>

<?php require 'includes/footer.php' ?>
