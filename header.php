
<footer>
    <div class="brand">
        <h2>Rydr.</h2>
        <p>Stap in. Rij weg. Simpel.</p>
    </div>
    <div class="footer-links">
        <div class="links">
            <h3>Over ons</h3>
            <ul>
                <li><a href="/over-ons">Het team</a></li>
                <li><a href="/over-ons">Onze visie</a></li>
                <li><a href="/ons-aanbod">Ons aanbod</a></li>
            </ul>
        </div>
        <div class="links">
            <h3>Community</h3>
            <ul>
                <li><a href="/over-ons">Events</a></li>
                <li><a href="/over-ons">Blog</a></li>
                <li><a href="/over-ons">Podcast</a></li>
                <li><a href="/register-form">Account aanmaken</a></li>
            </ul>
        </div>
        <div class="links">
            <h3>Contact</h3>
            <ul>
                <li><a href="mailto:info@rydr.local">info@rydr.local</a></li>
                <li><a href="tel:+31101234567">010 123 45 67</a></li>
                <li><a href="/over-ons">Rotterdam Centraal</a></li>
            </ul>
        </div>
    </div>
</footer>
<div class="legal-footer">
    <div class="legal">
        <div class="copyright">
            &copy; <?= date('Y') ?> Rydr. All rights reserved
        </div>
    </div>
    <div class="legal-links">
        <ul>
            <li><a href="/over-ons">Privacy &amp; Policy</a></li>
            <li><a href="/over-ons">Terms &amp; Conditions</a></li>
        </ul>
    </div>
</div>
<div id="loginModal" class="modal hidden">
    <div class="modal-content">
        <h2>Welkom bij Rydr</h2>
        <p>Kies hoe je verder wilt gaan:</p>
        <div class="modal-actions">
            <a href="/login-form" class="button-secondary">Inloggen</a>
            <a href="/register-form" class="button-primary">Account aanmaken</a>
        </div>
        <button class="modal-close">&times;</button>
    </div>
</div>
<script src="assets/javascript/main.js?v=<?= h($jsVersion) ?>"></script>

</body>
</html>
