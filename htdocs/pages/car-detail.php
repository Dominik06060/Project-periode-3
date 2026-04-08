<?php
require "includes/header.php";
require_once "database/connection.php";

$car = null;
$carName = trim($_GET["name"] ?? "");

if ($carName !== "") {
    $stmt = $conn->prepare("
        SELECT idauto, name, typecar, steering, capacity, gasoline, prijs
        FROM auto
        WHERE name = :name
        LIMIT 1
    ");
    $stmt->execute([":name" => $carName]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<main class="car-detail">
    <?php if (!$car) : ?>
        <div class="white-background" style="padding: 24px;">
            <h2>Auto niet gevonden</h2>
            <p>Kies een auto via de homepagina om de details te bekijken.</p>
            <a href="/" class="button-primary">Terug naar home</a>
        </div>
    <?php else : ?>
        <?php $imgIndex = ((int)$car['idauto']) % 12; ?>
        <div class="grid">
            <div class="row">
                <div class="advertorial">
                    <h2><?= htmlspecialchars((string)$car['name']) ?> <?= htmlspecialchars((string)$car['typecar']) ?></h2>
                    <p>Bekijk alle details van deze auto voordat je gaat huren.</p>
                    <img src="assets/images/products/car%20(<?= $imgIndex ?>).svg" alt="<?= htmlspecialchars((string)$car['name']) ?>">
                    <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
                </div>
            </div>
            <div class="row white-background">
                <h2><?= htmlspecialchars((string)$car['name']) ?> <?= htmlspecialchars((string)$car['typecar']) ?></h2>
                <p>Specificaties van de gekozen auto.</p>
                <div class="car-type">
                    <div class="grid">
                        <div class="row"><span class="accent-color">Type Car</span><span><?= htmlspecialchars((string)$car['typecar']) ?></span></div>
                        <div class="row"><span class="accent-color">Capacity</span><span><?= (int)$car['capacity'] ?> Personen</span></div>
                    </div>
                    <div class="grid">
                        <div class="row"><span class="accent-color">Steering</span><span><?= htmlspecialchars((string)$car['steering']) ?></span></div>
                        <div class="row"><span class="accent-color">Gasoline</span><span><?= (int)$car['gasoline'] ?>L</span></div>
                    </div>
                    <div class="call-to-action">
                        <div class="row"><span class="font-weight-bold">€<?= number_format((float)$car['prijs'], 2, ',', '.') ?></span> / dag</div>
                        <div class="row"><a href="#" class="button-primary">Huur nu</a></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require "includes/footer.php" ?>
