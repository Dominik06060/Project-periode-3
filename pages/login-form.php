<?php
require 'includes/header.php';

$success = get_flash_message('success');
$error = get_flash_message('error');
$email = old_input('email');
clear_old_input();
?>
<main>
    <form action="/login-handler" class="account-form" method="post">
        <h2>Log in</h2>
<?php if ($success) { ?>
            <div class="success-message"><?= h($success) ?></div>
        <?php } ?>
        <?php if ($error) { ?>
            <div class="message"><?= h($error) ?></div>
        <?php } ?>
        <label for="email">Uw e-mail</label>
        <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" value="<?= $email ?>" required autofocus>
        <label for="password">Uw wachtwoord</label>
        <input type="password" name="password" id="password" placeholder="Uw wachtwoord" required>
        <input type="submit" value="Log in" class="button-primary">
        <p class="form-note">Nog geen account? <a href="/register-form">Maak er hier een aan</a>.</p>
    </form>
</main>

<?php require 'includes/footer.php' ?>
