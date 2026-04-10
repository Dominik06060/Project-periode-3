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

if ($autoId !== false && $autoId !== null) {
    $statement = $conn->prepare(
        'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto
         FROM auto
         WHERE idauto = :id
         LIMIT 1'
    );
    $statement->execute([':id' => $autoId]);
    $auto = $statement->fetch();
} elseif ($autoNaam !== '') {
    $statement = $conn->prepare(
        'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto
         FROM auto
         WHERE name = :name
         LIMIT 1'
    );
    $statement->execute([':name' => $autoNaam]);
    $auto = $statement->fetch();
}

if ($auto) {
    $upcomingStatement = $conn->prepare(
        'SELECT beginverhuur, eindverhuur, prijs
         FROM verhuur
         WHERE auto_id = :auto_id
           AND eindverhuur >= :today
         ORDER BY beginverhuur ASC
         LIMIT 5'
    );
    $upcomingStatement->execute([
        ':auto_id' => (int)$auto['idauto'],
        ':today' => $today,
    ]);
    $upcomingRentals = $upcomingStatement->fetchAll();

    if (is_logged_in()) {
        $myRentalsStatement = $conn->prepare(
            'SELECT beginverhuur, eindverhuur, prijs
             FROM verhuur
             WHERE auto_id = :auto_id
               AND account_id = :account_id
             ORDER BY beginverhuur DESC
             LIMIT 3'
        );
        $myRentalsStatement->execute([
            ':auto_id' => (int)$auto['idauto'],
            ':account_id' => (int)$_SESSION['id'],
        ]);
        $myRentals = $myRentalsStatement->fetchAll();
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
                    <h2><?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string)$auto['typecar'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p>Bekijk alle details van deze auto voordat je gaat huren.</p>
                    <?php if ($imageSrc !== null) { ?>
                        <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php } else { ?>
                        <div class="car-image-empty">Geen databasefoto beschikbaar</div>
                    <?php } ?>
                    <img src="assets/images/header-circle-background.webp" alt="" class="background-header-element">
                </div>
            </div>
            <div class="row white-background">
                <h2><?= htmlspecialchars((string)$auto['name'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string)$auto['typecar'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p>Specificaties van de gekozen auto.</p>
                <?php if ($rentalSuccess !== null) { ?>
                    <div class="success-message"><?= htmlspecialchars($rentalSuccess, ENT_QUOTES, 'UTF-8') ?></div>
                <?php } ?>
                <?php if ($rentalError !== null) { ?>
                    <div class="message"><?= htmlspecialchars($rentalError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php } ?>
                <div class="car-type">
                    <div class="grid">
                        <div class="row"><span class="accent-color">Type auto</span><span><?= htmlspecialchars((string)$auto['typecar'], ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div class="row"><span class="accent-color">Capaciteit</span><span><?= (int)$auto['capacity'] ?> personen</span></div>
                    </div>
                    <div class="grid">
                        <div class="row"><span class="accent-color">Transmissie</span><span><?= htmlspecialchars((string)$auto['steering'], ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div class="row"><span class="accent-color">Brandstof</span><span><?= (int)$auto['gasoline'] ?>L</span></div>
                    </div>
                    <div class="call-to-action">
                        <div class="row"><span class="font-weight-bold">&euro;<?= number_format((float)$auto['prijs'], 0, ',', '.') ?></span> / dag</div>
                        <div class="row"><a href="/ons-aanbod" class="button-primary">Terug naar aanbod</a></div>
                    </div>
                </div>

                <section class="rental-panel">
                    <div class="booking-grid">
                        <div class="white-background booking-card">
                            <h3>Huur deze auto</h3>
                            <p>Prijs per dag: <strong>&euro;<?= number_format((float)$auto['prijs'], 0, ',', '.') ?></strong></p>
                            <form action="/rent-car-handler" method="post" class="account-form compact-form">
                                <input type="hidden" name="car_id" value="<?= (int)$auto['idauto'] ?>">
                                <label for="beginverhuur">Startdatum</label>
                                <input type="date" name="beginverhuur" id="beginverhuur" min="<?= $today ?>" value="<?= old_input('beginverhuur', '', 'rental_form_' . (int)$auto['idauto']) ?>" required>
                                <label for="eindverhuur">Einddatum</label>
                                <input type="date" name="eindverhuur" id="eindverhuur" min="<?= $today ?>" value="<?= old_input('eindverhuur', '', 'rental_form_' . (int)$auto['idauto']) ?>" required>
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
                                            Verhuurd van <?= htmlspecialchars(date('d-m-Y', strtotime((string)$rental['beginverhuur'])), ENT_QUOTES, 'UTF-8') ?>
                                            t/m <?= htmlspecialchars(date('d-m-Y', strtotime((string)$rental['eindverhuur'])), ENT_QUOTES, 'UTF-8') ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                            <?php if (!empty($myRentals)) { ?>
                                <h4>Jouw reserveringen voor deze auto</h4>
                                <ul class="availability-list">
                                    <?php foreach ($myRentals as $rental) { ?>
                                        <li>
                                            <?= htmlspecialchars(date('d-m-Y', strtotime((string)$rental['beginverhuur'])), ENT_QUOTES, 'UTF-8') ?>
                                            t/m <?= htmlspecialchars(date('d-m-Y', strtotime((string)$rental['eindverhuur'])), ENT_QUOTES, 'UTF-8') ?>
                                            voor &euro;<?= number_format((float)$rental['prijs'], 0, ',', '.') ?>
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
