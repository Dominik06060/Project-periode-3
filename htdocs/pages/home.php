<?php
require "includes/header.php";
require_once "database/connection.php";

$cars = [];
try {
    $stmt = $conn->query("SELECT idauto, name, typecar, steering, capacity, gasoline, prijs FROM auto ORDER BY name ASC");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $cars = [];
}
?>
<header>
    <div class="advertorials">
        <div class="advertorial">
            <h2>Hét platform om een auto te huren</h2>
            <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
            <a href="#" class="button-primary">Huur nu een auto</a>
            <img src="assets/images/car-rent-header-image-1.png" alt="">
            <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
        </div>
        <div class="advertorial">
            <h2>Wij verhuren ook bedrijfswagens</h2>
            <p>Voor een vaste lage prijs met prettig voordelen.</p>
            <a href="#" class="button-primary">Huur een bedrijfswagen</a>
            <img src="assets/images/car-rent-header-image-2.png" alt="">
            <img src="assets/images/header-block-background.svg" alt="" class="background-header-element">
        </div>
    </div>
</header>

<main>
    <h2 class="section-title">Beschikbare auto's</h2>
    <div class="cars">
        <?php if (empty($cars)) : ?>
            <p>Er zijn momenteel geen auto's beschikbaar.</p>
        <?php else : ?>
            <?php foreach ($cars as $index => $car) : ?>
                <?php $imgIndex = $index % 12; ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars((string)$car['name']) ?></h3>
                        <div class="car-type"><?= htmlspecialchars((string)$car['typecar']) ?></div>
                    </div>
                    <img src="assets/images/products/car%20(<?= $imgIndex ?>).svg" alt="<?= htmlspecialchars((string)$car['name']) ?>">
                    <div class="car-specification">
                        <span><img src="assets/images/icons/gas-station.svg" alt=""><?= (int)$car['gasoline'] ?>L</span>
                        <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars((string)$car['steering']) ?></span>
                        <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= (int)$car['capacity'] ?> Personen</span>
                    </div>
                    <div class="rent-details">
                        <span><span class="font-weight-bold">€<?= number_format((float)$car['prijs'], 2, ',', '.') ?></span> / dag</span>
                        <a href="/car-detail?name=<?= urlencode((string)$car['name']) ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="show-more">
        <a class="button-primary" href="/ons-aanbod">Toon alle</a>
    </div>
</main>

<?php require "includes/footer.php" ?>