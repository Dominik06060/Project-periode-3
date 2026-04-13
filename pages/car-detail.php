<?php
require 'includes/header.php';
require_once 'database/connection.php';

$auto = null;
$upcomingRentals = [];
$myRentals = [];
$autoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$autoNaam = trim((string)($_GET['name'] ?? ''));
$rentalSuccess = get_flash_message('rental_success');
$rentalError = get_flash_message('rental_error');
$today = date('Y-m-d');
$bookingForm = 'rental_form_' . (int)$autoId;

if ($autoId !== false && $autoId !== null) {
    $stmt = $conn->prepare(
        'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto
         FROM auto
         WHERE idauto = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $autoId]);
    $auto = $stmt->fetch();
} elseif ($autoNaam !== '') {
    $stmt = $conn->prepare(
        'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto
         FROM auto
         WHERE name = :name
         LIMIT 1'
    );
    $stmt->execute([':name' => $autoNaam]);
    $auto = $stmt->fetch();
}

if ($auto) {
    $stmt = $conn->prepare(
        'SELECT beginverhuur, eindverhuur, prijs
         FROM verhuur
         WHERE auto_id = :auto_id
           AND eindverhuur >= :today
         ORDER BY beginverhuur ASC
         LIMIT 5'
    );
    $stmt->execute([
        ':auto_id' => (int)$auto['idauto'],
        ':today' => $today,
    ]);
    $upcomingRentals = $stmt->fetchAll();

    if (is_logged_in()) {
        $stmt = $conn->prepare(
            'SELECT beginverhuur, eindverhuur, prijs
             FROM verhuur
             WHERE auto_id = :auto_id
               AND account_id = :account_id
             ORDER BY beginverhuur DESC
             LIMIT 3'
        );
        $stmt->execute([
            ':auto_id' => (int)$auto['idauto'],
            ':account_id' => (int)$_SESSION['id'],
        ]);
        $myRentals = $stmt->fetchAll();
    }
}
?>
<main class="car-detail">
    <?php if (!$auto) { ?>
        <div class="white-background" style="padding: 24px;">
            <h2>Auto niet gevonden</h2>
            <p>Kies een auto via de homepagina of het aanbod om de details te bekijken.</p>
            <a href="/" class="button-primary">Terug naar home</a>
        </div>
    <?php } else { ?>
        <?php $imageSrc = car_image_src($auto['foto'] ?? null); ?>
        <div class="grid">
            <div class="row">
                <div class="advertorial">
                    <h2><?= h($auto['name']) ?> <?= h($auto['typecar']) ?></h2>
                    <p>Bekijk alle details van deze auto voordat je gaat huren.</p>
                    <?php if ($imageSrc !== null) { ?>
                        <img src="<?= $imageSrc ?>" alt="<?= h($auto['name']) ?>">
                    <?php } else { ?>
                        <div class="car-image-empty">Geen databasefoto beschikbaar</div>
                    <?php } ?>
                    <img src="assets/images/header-circle-background.webp" alt="" class="background-header-element">
                </div>
            </div>
            <div class="row white-background">
                <h2><?= h($auto['name']) ?> <?= h($auto['typecar']) ?></h2>
                <p>Specificaties van de gekozen auto.</p>
                <?php if ($rentalSuccess !== null) { ?>
                    <div class="success-message"><?= h($rentalSuccess) ?></div>
                <?php } ?>
                <?php if ($rentalError !== null) { ?>
                    <div class="message"><?= h($rentalError) ?></div>
                <?php } ?>
                <div class="car-type">
                    <div class="grid">
                        <div class="row"><span class="accent-color">Type auto</span><span><?= h($auto['typecar']) ?></span></div>
                        <div class="row"><span class="accent-color">Capaciteit</span><span><?= (int)$auto['capacity'] ?> personen</span></div>
                    </div>
                    <div class="grid">
                        <div class="row"><span class="accent-color">Transmissie</span><span><?= h($auto['steering']) ?></span></div>
                        <div class="row"><span class="accent-color">Brandstof</span><span><?= (int)$auto['gasoline'] ?>L</span></div>
                    </div>
                    <div class="call-to-action">
                        <div class="row"><span class="font-weight-bold">&euro;<?= format_price($auto['prijs']) ?></span> / dag</div>
                        <div class="row"><a href="/ons-aanbod" class="button-primary">Terug naar aanbod</a></div>
                    </div>
                </div>

                <section class="rental-panel">
                    <div class="booking-grid">
                        <div class="white-background booking-card">
                            <h3>Huur deze auto</h3>
                            <p>Prijs per dag: <strong>&euro;<?= format_price($auto['prijs']) ?></strong></p>
                            <form action="/rent-car-handler" method="post" class="account-form compact-form">
                                <input type="hidden" name="car_id" value="<?= (int)$auto['idauto'] ?>">
                                <label for="beginverhuur">Startdatum</label>
                                <input type="date" name="beginverhuur" id="beginverhuur" min="<?= $today ?>" value="<?= old_input('beginverhuur', '', $bookingForm) ?>" required>
                                <label for="eindverhuur">Einddatum</label>
                                <input type="date" name="eindverhuur" id="eindverhuur" min="<?= $today ?>" value="<?= old_input('eindverhuur', '', $bookingForm) ?>" required>
                                <button type="submit" class="button-primary"><?= is_logged_in() ? 'Reserveer nu' : 'Log in en reserveer' ?></button>
                            </form>
                            <?php if (!is_logged_in()) { ?>
                                <p class="booking-note">Je kunt de datums alvast kiezen. Na inloggen kom je terug op deze auto om de reservering af te ronden.</p>
                            <?php } ?>
                        </div>

                        <div class="white-background booking-card">
                            <h3>Beschikbaarheid</h3>
                            <?php if (empty($upcomingRentals)) { ?>
                                <p>Er zijn nog geen geplande verhuurperiodes voor deze auto.</p>
                            <?php } else { ?>
                                <ul class="availability-list">
                                    <?php foreach ($upcomingRentals as $rental) { ?>
                                        <li>
                                            Verhuurd van <?= h(format_date((string)$rental['beginverhuur'])) ?>
                                            t/m <?= h(format_date((string)$rental['eindverhuur'])) ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                            <?php if (!empty($myRentals)) { ?>
                                <h4>Jouw reserveringen voor deze auto</h4>
                                <ul class="availability-list">
                                    <?php foreach ($myRentals as $rental) { ?>
                                        <li>
                                            <?= h(format_date((string)$rental['beginverhuur'])) ?>
                                            t/m <?= h(format_date((string)$rental['eindverhuur'])) ?>
                                            voor &euro;<?= format_price($rental['prijs']) ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    <?php } ?>
</main>

<?php require 'includes/footer.php' ?>
