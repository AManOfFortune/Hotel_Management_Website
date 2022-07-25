<!-- Form included in the Login-Modal if the user is not logged in -->
<form action="<?= $curPageName ?>" method="post">

    <h1>Login</h1>

    <div class="row-centred">
        <div class="form-group col-sm-12">
            <label for="username">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" required>
            </select>
        </div>
    </div>

    <div class="row-centred">
        <div class="form-group col-sm-12">
            <label for="password">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
    </div>

    <div class="row">
        <a class="col-sm-4" id="registrierung_link" href="/Semesterprojekt/pages/links/kein_account.php">Kein Account?</a>
        <input type="submit" class="submit-btn col-sm-4" value="login">
    </div>

</form>