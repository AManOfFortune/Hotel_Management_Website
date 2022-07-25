<form action="<?= $curPageName ?>?registration=true&userid=<?= $userid ?>" method="post">
    
    <div class="row">
        <div class="form-group col-sm-2">
            <label for="anrede">Anrede</label>
            
            <select class="form-control" id="anrede" name="anrede">
                <?php /*Changes the selected dropdown menu option depending on value of $anrede*/ if($anrede == 'm') {?> <option value="m" selected>Herr</option><option value="w">Frau</option><option value="o">Andere</option> 
                <?php } else if($anrede == 'w') {?> <option value="m">Herr</option><option value="w" selected>Frau</option><option value="o">Andere</option>
                <?php } else {?> <option value="m">Herr</option><option value="w">Frau</option><option value="o" selected>Andere</option> <?php } ?>
            </select>
        </div>

        <div class="form-group col-sm-5">
            <label for="vorname">Vorname</label>
            <input type="text" class="form-control" id="vorname" placeholder="Sam" name="vorname" value="<?= $vorname ?>">
        </div>

        <div class="form-group col-sm-5">
            <label for="nachname">Nachname</label>
            <input type="text" class="form-control" id="nachname" name="nachname" placeholder="Sample" value="<?= $nachname ?>">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3">
            <label for="plz">Postleitzahl</label>
            <input type="text" class="form-control" id="plz" name="plz" placeholder="1234" value="<?= $plz ?>">
        </div>

        <div class="form-group col-sm-9">
            <label for="ort">Ort</label>
            <input type="text" class="form-control" id="ort" name="ort" placeholder="Samcity" value="<?= $ort ?>">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-9">
            <label for="strasse">Straße</label>
            <input type="text" class="form-control" id="strasse" name="strasse" placeholder="Samsampleweg" value="<?= $straße ?>">
        </div>

        <div class="form-group col-sm-3">
            <label for="hausnummer">Hausnummer</label>
            <input type="text" class="form-control" id="hausnummer" name="hausnummer" placeholder="1" value="<?= $hausnummer ?>">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="comment">Kommentar</label>
            <textarea class="form-control" rows="7" id="comment" name="comment" placeholder="Very bad humor, very self-centred"><?= $kommentar ?></textarea>
        </div>
    </div>

    <h2>Userdaten</h2>

    <div class="row">
        <div class="form-group col-sm-4">
            <label for="form_username">Username</label>
            <input type="text" class="form-control" id="form_username" name="form_username" placeholder="Sam" value="<?= $username ?>" required>
        </div>

        <div class="form-group col-sm-4">
            <label for="form_password">Passwort</label>
            <input type="text" class="form-control" id="form_password" name="form_password" placeholder="Password123" required>
        </div>

        <div class="form-group col-sm-4">
            <label for="role">Rolle</label>
            <select class="form-control" id="role" name="role" required>
                <?php /*Changes the selected dropdown menu option depending on value of $rolle*/ if($rolle == 'admin') {?> <option value="user">Gast</option><option value="admin" selected>Admin</option><option value="technician">Techniker</option>
                <?php } else if($rolle == 'technician') {?> <option value="user">Gast</option><option value="admin">Admin</option><option value="technician" selected>Techniker</option>
                <?php } else {?> <option value="user" selected>Gast</option><option value="admin">Admin</option><option value="technician">Techniker</option> <?php } ?>
            </select>
        </div>
    </div>

    <div class="row form-group col-sm-12">
        <label for="email">E-Mail Adresse</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="samsample@beispiel.com" value="<?= $email ?>"required>
    </div>

    <div class="space-around row-centred">
        <?php /*Adds a 'Deactivate-Button' with a confirmation-modal if an existing user is being edited*/ if ($userid != -1) { include 'confirmationModal.php' ?><button type="button" onclick="showModal('confirm', 'close_confirm')" class="submit-btn danger">User deaktivieren</button><?php } ?>
        <input type="submit" class="submit-btn" value="<?php /* Changes button message depending if a new user is being added, or an old one edited */ if($userid == -1) { echo 'Hinzufügen';} else {echo 'Änderungen speichern';}?>">
    </div>

</form>