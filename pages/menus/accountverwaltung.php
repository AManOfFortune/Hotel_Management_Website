<!DOCTYPE html>
<?php
    $title = 'Accountverwaltung';
    include "../include/head.php"; 

    $errors = array();

    //$_GET['registration'] is set when user submits the userForm (either to create or edit a user)
    //Empties, then fills the errors array (in case of errors/missing information)
    if(isset($_GET['registration'])) {
        $anrede = $_POST["anrede"];
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $plz = $_POST["plz"];
        $ort = $_POST["ort"];
        $strasse = $_POST["strasse"];
        $hausnummer = $_POST["hausnummer"];
        $form_username = $_POST["form_username"];
        $email = $_POST["email"];

        //User data is always required, Personal data only for guest-accounts
        $always_required = array("Username" => $form_username, "Email" => $email);

        //Appends the key (e.g. "Username", "Rolle" etc.) to the errors array if corresponding variable is empty
        foreach ($always_required as $name => $val)
        {
            if (empty($val))
            {
                array_push($errors, $name);
            };
        };

        //Additional fields are required if the account is a guest-account
        if($_SESSION['user_role'] == 'user') {
            $user_required = array("Anrede" => $anrede, "Vorname" => $vorname, "Nachname" => $nachname, "Postleitzahl" => $plz, "Ort" => $ort, "Strasse" => $strasse, "Hausnummer" => $hausnummer);

            foreach ($user_required as $name => $val)
            {
                if (empty($val))
                {
                    array_push($errors, $name);
                };
            };
        }
    }
    else if (isset($_GET['changePassword'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $new_password_confirm = $_POST['new_password_confirm'];

        $select_password = "SELECT password FROM user WHERE userid =" . $_SESSION['userid'];
        $result = $db->query($select_password);

        while ($row = $result->fetch_array()) {
            if(hash('sha256', $old_password . $_SESSION['user']) != $row['password']) {
                array_push($errors, 'The alte Passwort ist falsch!');
            }
        }

        if($new_password != $new_password_confirm) { array_push($errors, "Die neuen Passwörter stimmen nicht überein!"); }
    }
?>
    <body>
        <?php include "../include/navbar.php" ?>
        <div class="content_wrapper">
            <div class="content_box">
                <?php
                    //If user submits form ($_GET['registration'] is set), check if errors array is empty
                    if(isset($_GET['registration'])) {
                        //If errors array is not empty, echo it's content (the missing information) to the page
                        if (!empty($errors))
                        {
                            echo "<h3 class='error'>Fehler, bitte füllen Sie die folgenden Felder ebenfalls aus:</h3>";
                            echo "<ul class='error'>";
                            foreach($errors as $error)
                            {
                                echo "<li>$error</li>";
                            }
                            echo "</ul>";
                        }
                        //If errors array is empty, add all information to the database
                        else
                        {
                            //Prepare a statement to update user data to the database
                            $update_user_stmt = $db->prepare('UPDATE user SET username = ?, email = ? WHERE userid = ?');

                            //If we have person data (the role is user -> it is a guest account), add it to the database first
                            if($_SESSION['user_role'] == 'user') {
                                //Prepate to update person data in the database
                                $update_person_stmt = $db->prepare('UPDATE person SET anrede = ?, vorname = ?, nachname = ?, plz = ?, ort = ?, straße = ?, hausnummer = ? WHERE person_id = ?');
                                
                                //Select the person_id related to the userid
                                $person_id_stmt = "SELECT person_id FROM user WHERE userid = ". $_SESSION['userid'];
                                $result = $db->query($person_id_stmt);

                                while ($row = $result->fetch_array()) {
                                    $update_person_stmt->bind_param('sssssssi', $anrede, $vorname, $nachname, $plz, $ort, $strasse, $hausnummer, $row['person_id']);
                                }
                                
                                $update_person_stmt->execute();
                                $update_person_stmt->close();   
                            }

                            $update_user_stmt->bind_param('sss', $form_username, $email, $_SESSION['user_role']);

                            //Update user data in the database
                            $success = $update_user_stmt->execute();
                            $update_user_stmt->close();
                            
                            //If successful, print message
                            if($success) { echo "<h3>Account erfolgreich aktualisiert!</h3>"; }
                        }
                    }
                    //If we are deactivating a user (user clicked on 'Deactivate-Button' -> $_GET['deactivate] is set)
                    else if(isset($_GET['changePassword'])) {
                        if(!empty($errors)) { foreach($errors as $error) {echo "<h3 class='error'>$error<h3>";} }
                        else {
                            $new_password_hash = hash('sha256', $new_password . $_SESSION['user']);

                            //Update password in database
                            $update_password_stmt = $db->prepare("UPDATE user SET password = ? WHERE userid = ?");
                            $update_password_stmt->bind_param('si', $new_password_hash, $_SESSION['userid']);
                            $success = $update_password_stmt->execute();
                            $update_password_stmt->close();

                            if($success) {  echo "<h3>Passwort erfolgreich geändert!</h3>"; }
                        }
                    }
                ?>

                <h1>Accountverwaltung</h1>

                <?php
                    //Initializes (and cleans in case of duplicate variable names) variables (also necessary for new user include to work)
                    $userid = $_SESSION['userid'];
                    $username = '';
                    $email = '';
                    $anrede = '';
                    $vorname = '';
                    $nachname = '';
                    $plz = '';
                    $ort = '';
                    $straße = '';
                    $hausnummer = '';

                    //If we want to edit, select all data of the user in question, bind it to variables, fill (and include) the 'User-Form' with them
                    //Select (almost) all data from a specified user
                    $select_user_stmt = $db->prepare("SELECT u.username, u.email, p.anrede, p.vorname, p.nachname, p.plz, p.ort, p.straße, p.hausnummer FROM user AS u LEFT JOIN person AS p ON u.person_id = p.person_id WHERE u.userid = ?");
                    $select_user_stmt->bind_param('i', $userid);
                    $select_user_stmt->execute();
                    $select_user_stmt->bind_result($username, $email, $anrede, $vorname, $nachname, $plz, $ort, $straße, $hausnummer);

                    while($select_user_stmt->fetch()) {
                        include '../include/accountForm.php';
                    }
                    $select_user_stmt->close();
                ?>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>