<?php
require 'includes/header.php';

$success = get_flash_message('success');
$error = get_flash_message('error');
$email = old_input('email');
clear_old_input();
?>
<main>
    <form action="/register-handler" method="post" class="account-form">
        <h2>Maak een account aan</h2>
<?php if ($success) { ?>
            <div class="success-message"><?= h($success) ?></div>
        <?php } ?>
        <?php if ($error) { ?>
            <div class="message"><?= h($error) ?></div>
        <?php } ?>
        <label for="email">Uw e-mail</label>
        <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" value="<?= $email ?>" required autofocus>
        <label for="password">Uw wachtwoord</label>
        <input type="password" name="password" id="password" placeholder="Minimaal 8 tekens" required>
        <label for="confirm-password">Herhaal wachtwoord</label>
        <input type="password" name="confirm-password" id="confirm-password" placeholder="Uw wachtwoord" required>
        <input type="submit" value="Maak account aan" class="button-primary">
        <p class="form-note">Heb je al een account? <a href="/login-form">Log dan hier in</a>.</p>
    </form>
</main>

<?php require 'includes/footer.php' ?>
