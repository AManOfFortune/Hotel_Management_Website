<!-- Modal used to confirm an article being deleted-->
<div id="newsConfirm" class="modal">

    <div class="modal-content">

        <span class="close close_newsConfirm">&times;</span>

        <h1>Sind Sie sicher?</h1>
        <div class="space-around row">
            <a class="submit-btn" href="newsverwaltung.php?deactivate=true&newsid=<?= $_GET["newsid"] ?>">Ja</a>
            <a class="submit-btn close_newsConfirm">Nein</a>
        </div>

    </div>

</div>