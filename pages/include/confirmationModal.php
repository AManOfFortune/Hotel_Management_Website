<!-- Modal used to confirm a user being deleted-->
<div id="confirm" class="modal">

    <div class="modal-content">

        <span class="close close_confirm">&times;</span>

        <h1>Sind Sie sicher?</h1>
        <div class="space-around row">
            <a class="submit-btn" href="userverwaltung.php?deactivate=true&userid=<?= $_GET["userid"] ?>">Ja</a>
            <a class="submit-btn close_confirm">Nein</a>
        </div>

    </div>

</div>