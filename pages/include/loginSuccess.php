<h1>Sind Sie sicher?</h1>
<h2>Eingeloggt als: <?= $_SESSION['user']; ?></h2>
<div class="space-around row">
    <a class="submit-btn" href="<?= $curPageName ?>?logout=true">Ja</a>
    <a class="submit-btn close_login">Nein</a>
</div>