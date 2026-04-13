<?php
require 'includes/header.php';
require 'database/connection.php';

$search = trim((string)($_GET['q'] ?? ''));
$selectedType = trim((string)($_GET['typecar'] ?? ''));
$selectedSteering = trim((string)($_GET['steering'] ?? ''));
$catalogSuccess = get_flash_message('catalog_success');
$catalogError = get_flash_message('catalog_error');

$autos = [];
$typeOptions = [];
$steeringOptions = [];

try {
    $typeOptions = $conn->query("SELECT DISTINCT typecar FROM auto WHERE typecar IS NOT NULL AND typecar <> '' ORDER BY typecar ASC")->fetchAll(PDO::FETCH_COLUMN);
    $steeringOptions = $conn->query("SELECT DISTINCT steering FROM auto WHERE steering IS NOT NULL AND steering <> '' ORDER BY steering ASC")->fetchAll(PDO::FETCH_COLUMN);

    $query = 'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto FROM auto WHERE 1 = 1';
    $params = [];

    if ($search) {
        $query .= ' AND (name LIKE :search_name OR typecar LIKE :search_type)';
        $params[':search_name'] = $params[':search_type'] = "%{$search}%";
    }

    if ($selectedType) {
        $query .= ' AND typecar = :typecar';
        $params[':typecar'] = $selectedType;
    }

    if ($selectedSteering) {
        $query .= ' AND steering = :steering';
        $params[':steering'] = $selectedSteering;
    }

    $query .= ' ORDER BY name ASC';

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $autos = $stmt->fetchAll();
} catch (PDOException $e) {
    $autos = [];
}
?>

<main>
    <h2 class="section-title">Ons aanbod</h2>
    <section class="catalog-tools white-background">
        <form action="/ons-aanbod" method="get" class="catalog-filter">
            <div>
                <label for="catalog-search">Zoek op naam of type</label>
                <input type="search" name="q" id="catalog-search" placeholder="Bijvoorbeeld Audi of SUV" value="<?= h($search) ?>">
            </div>
            <div>
                <label for="catalog-type">Type auto</label>
                <select name="typecar" id="catalog-type">
                    <option value="">Alle types</option>
                    <?php foreach ($typeOptions as $opt) { ?>
                        <option value="<?= h($opt) ?>"<?= selected_option($selectedType, (string)$opt) ?>><?= h($opt) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="catalog-steering">Transmissie</label>
                <select name="steering" id="catalog-steering">
                    <option value="">Alles</option>
                    <?php foreach ($steeringOptions as $opt) { ?>
                        <option value="<?= h($opt) ?>"<?= selected_option($selectedSteering, (string)$opt) ?>><?= h($opt) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="catalog-actions">
                <button type="submit" class="button-primary">Filter</button>
                <a href="/ons-aanbod" class="button-secondary">Reset</a>
            </div>
        </form>
        <?php if ($catalogSuccess) { ?>
            <div class="success-message"><?= h($catalogSuccess) ?></div>
        <?php } ?>
        <?php if ($catalogError) { ?>
            <div class="message"><?= h($catalogError) ?></div>
        <?php } ?>
    </section>

    <div class="cars">
        <?php if (empty($autos)) { ?>
            <p>Er zijn momenteel geen auto's beschikbaar voor deze zoekopdracht.</p>
        <?php } ?>
        <?php foreach ($autos as $auto) render_car_card($auto); ?>
    </div>

    <?php if (is_logged_in()) { ?>
        <section class="white-background car-admin-panel">
            <div class="panel-intro">
                <h3>Voeg een nieuwe auto toe</h3>
                <p>Nieuwe auto's die je hier toevoegt worden direct opgeslagen in de `auto` tabel van je lokale database.</p>
            </div>
            <form action="/add-car-handler" method="post" enctype="multipart/form-data" class="account-form car-form">
                <label for="car-name">Naam</label>
                <input type="text" name="name" id="car-name" value="<?= old_input('name', '', 'car_form') ?>" placeholder="Bijvoorbeeld Tesla Model 3" required>

                <label for="car-type">Type auto</label>
                <input type="text" name="typecar" id="car-type" value="<?= old_input('typecar', '', 'car_form') ?>" placeholder="Sedan, SUV, bedrijfswagen..." required>

                <label for="car-steering">Transmissie</label>
                <select name="steering" id="car-steering" required>
                    <option value="">Kies een transmissie</option>
                    <option value="Automaat"<?= selected_option(old_input_value('steering', '', 'car_form'), 'Automaat') ?>>Automaat</option>
                    <option value="Handgeschakeld"<?= selected_option(old_input_value('steering', '', 'car_form'), 'Handgeschakeld') ?>>Handgeschakeld</option>
                </select>

                <label for="car-capacity">Aantal personen</label>
                <input type="number" name="capacity" id="car-capacity" min="1" value="<?= old_input('capacity', '', 'car_form') ?>" required>

                <label for="car-gasoline">Brandstofinhoud in liters</label>
                <input type="number" name="gasoline" id="car-gasoline" min="1" value="<?= old_input('gasoline', '', 'car_form') ?>" required>

                <label for="car-price">Prijs per dag in euro</label>
                <input type="number" name="prijs" id="car-price" min="1" value="<?= old_input('prijs', '', 'car_form') ?>" required>

                <label for="car-photo">Foto</label>
                <input type="file" name="foto" id="car-photo" accept="image/*" required>

                <button type="submit" class="button-primary">Auto opslaan</button>
            </form>
        </section>
        <?php clear_old_input('car_form'); ?>
    <?php } ?>
</main>

<?php require 'includes/footer.php' ?>
