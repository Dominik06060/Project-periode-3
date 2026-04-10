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

    $query = 'SELECT idauto, name, typecar, steering, capacity, gasoline, prijs, foto
              FROM auto
              WHERE 1 = 1';
    $parameters = [];

    if ($search !== '') {
        $query .= ' AND (name LIKE :search_name OR typecar LIKE :search_type)';
        $parameters[':search_name'] = '%' . $search . '%';
        $parameters[':search_type'] = '%' . $search . '%';
    }

    if ($selectedType !== '') {
        $query .= ' AND typecar = :typecar';
        $parameters[':typecar'] = $selectedType;
    }

    if ($selectedSteering !== '') {
        $query .= ' AND steering = :steering';
        $parameters[':steering'] = $selectedSteering;
    }

    $query .= ' ORDER BY name ASC';

    $statement = $conn->prepare($query);
    $statement->execute($parameters);
    $autos = $statement->fetchAll();
} catch (PDOException $exception) {
    $autos = [];
}
?>

<main>
    <h2 class="section-title">Ons aanbod</h2>
    <section class="catalog-tools white-background">
        <form action="/ons-aanbod" method="get" class="catalog-filter">
            <div>
                <label for="catalog-search">Zoek op naam of type</label>
                <input type="search" name="q" id="catalog-search" placeholder="Bijvoorbeeld Audi of SUV" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div>
                <label for="catalog-type">Type auto</label>
                <select name="typecar" id="catalog-type">
                    <option value="">Alle types</option>
                    <?php foreach ($typeOptions as $typeOption) { ?>
                        <option value="<?= htmlspecialchars((string)$typeOption, ENT_QUOTES, 'UTF-8') ?>"<?= selected_option($selectedType, (string)$typeOption) ?>><?= htmlspecialchars((string)$typeOption, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="catalog-steering">Transmissie</label>
                <select name="steering" id="catalog-steering">
                    <option value="">Alles</option>
                    <?php foreach ($steeringOptions as $steeringOption) { ?>
                        <option value="<?= htmlspecialchars((string)$steeringOption, ENT_QUOTES, 'UTF-8') ?>"<?= selected_option($selectedSteering, (string)$steeringOption) ?>><?= htmlspecialchars((string)$steeringOption, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="catalog-actions">
                <button type="submit" class="button-primary">Filter</button>
                <a href="/ons-aanbod" class="button-secondary">Reset</a>
            </div>
        </form>
        <?php if ($catalogSuccess !== null) { ?>
            <div class="success-message"><?= htmlspecialchars($catalogSuccess, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>
        <?php if ($catalogError !== null) { ?>
            <div class="message"><?= htmlspecialchars($catalogError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>
    </section>

    <div class="cars">
        <?php if (empty($autos)) { ?>
            <p>Er zijn momenteel geen auto's beschikbaar voor deze zoekopdracht.</p>
        <?php } ?>

        <?php foreach ($autos as $auto) { ?>
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
