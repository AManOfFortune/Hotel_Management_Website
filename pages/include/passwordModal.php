<!-- Modal used to confirm a user being deleted-->
<div id="changePassword" class="modal">

    <div class="modal-content">

        <span class="close close_changePassword">&times;</span>

        <h1>Passwort ändern</h1>
        <form action="<?= $curPageName ?>?changePassword=true" method="post">

            <div class="row-centred">
                <div class="form-group col-sm-12">
                    <label for="old_password">Altes Passwort:</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </select>
                </div>
            </div>

            <div class="row-centred">
                <div class="form-group col-sm-12">
                    <label for="new_password">Neues Passwort:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
            </div>

            <div class="row-centred">
                <div class="form-group col-sm-12">
                    <label for="new_password_confirm">Bestätigung neues Passwort:</label>
                    <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>
                </div>
            </div>

            <div class="row-centred">
                <input type="submit" class="submit-btn" value="Ändern">
            </div>

        </form>

    </div>

</div>