<?php include 'passwordModal.php'; ?>

<form action="<?= $curPageName ?>?registration=true" method="post">
    
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

    <h2>Userdaten</h2>

    <div class="row">
        <div class="form-group col-sm-5">
            <label for="form_username">Username</label>
            <input type="text" class="form-control" id="form_username" name="form_username" placeholder="Sam" value="<?= $username ?>" required>
        </div>
        <div class="form-group col-sm-7">
            <label for="email">E-Mail Adresse</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="samsample@beispiel.com" value="<?= $email ?>"required>
        </div>
    </div>

    <div class="space-around row-centred">
        <button type="button" onclick="showModal('changePassword', 'close_changePassword')" class="submit-btn danger">Passwort ändern</button>
        <input type="submit" class="submit-btn" value="Änderungen speichern">
    </div>

</form>