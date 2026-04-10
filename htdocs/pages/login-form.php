<?php
require 'includes/header.php';

$successMessage = get_flash_message('success');
$errorMessage = get_flash_message('error');
$emailValue = old_input('email');
clear_old_input();
?>
<main>
    <form action="/login-handler" class="account-form" method="post">
        <h2>Log in</h2>
        <?php if ($successMessage !== null) { ?>
            <div class="success-message"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>
        <?php if ($errorMessage !== null) { ?>
            <div class="message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>
        <label for="email">Uw e-mail</label>
        <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" value="<?= $emailValue ?>" required autofocus>
        <label for="password">Uw wachtwoord</label>
        <input type="password" name="password" id="password" placeholder="Uw wachtwoord" required>
        <input type="submit" value="Log in" class="button-primary">
        <p class="form-note">Nog geen account? <a href="/register-form">Maak er hier een aan</a>.</p>
    </form>
</main>

<?php require 'includes/footer.php' ?>
