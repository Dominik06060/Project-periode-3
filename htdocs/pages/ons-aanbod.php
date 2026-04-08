<?php
require "includes/header.php";
require_once "database/connection.php";

$auto = [];
try {
    $stmt = $conn->query("SELECT name, typecar, steering, capacity, gasoline, prijs FROM auto ORDER BY name ASC");
    $auto = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $auto = [];
}
?>

<main>
    <h2 class="section-title">Ons aanbod</h2>
    <div class="cars">
        <?php if (empty($auto)) : ?>
            <p>Er zijn momenteel geen auto's beschikbaar.</p>
        <?php else : ?>
            <?php foreach ($auto as $index => $car) : ?>
                <?php $imgIndex = $index % 12; ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars((string)$auto['name']) ?></h3>
                        <div class="car-type"><?= htmlspecialchars((string)$auto['typecar']) ?></div>
                    </div>
                    <img src="assets/images/products/car%20(<?= $imgIndex ?>).svg" alt="<?= htmlspecialchars((string)$auto['name']) ?>">
                    <div class="car-specification">
                        <span><img src="assets/images/icons/gas-station.svg" alt=""><?= (int)$auto['gasoline'] ?>L</span>
                        <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars((string)$car['steering']) ?></span>
                        <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= (int)$auto['capacity'] ?> Personen</span>
                    </div>
                    <div class="rent-details">
                        <span><span class="font-weight-bold">€<?= number_format((float)$auto['prijs'], 2, ',', '.') ?></span> / dag</span>
                        <a href="/car-detail?name=<?= urlencode((string)$car['name']) ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php require "includes/footer.php" ?>

