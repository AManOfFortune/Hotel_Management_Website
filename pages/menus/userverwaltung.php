<!DOCTYPE html>
<?php
    //Errors array is initially filled with dummy content for if-clause to work
    //Only if $_GET['registration'] is set will it be emptied
    //$_GET['registration'] is set when user submits the userForm (either to create or edit a user)
    $errors = array("NotEmpty");

    //Empties, then fills the errors array (in case of errors/missing information)
    if(isset($_GET['registration'])) {
        $anrede = $_POST["anrede"];
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $plz = $_POST["plz"];
        $ort = $_POST["ort"];
        $strasse = $_POST["strasse"];
        $hausnummer = $_POST["hausnummer"];
        $comment = $_POST["comment"];
        $form_username = $_POST["form_username"];
        $form_password = $_POST["form_password"];
        $role = $_POST["role"];
        $email = $_POST["email"];

        //User data is always required, Personal data only for guest-accounts
        $always_required = array("Username" => $form_username, "Password" => $form_password, "Rolle" => $role, "Email" => $email);
        $errors = array();

        //Appends the key (e.g. "Username", "Rolle" etc.) to the errors array if corresponding variable is empty
        foreach ($always_required as $name => $val)
        {
            if (empty($val))
            {
                array_push($errors, $name);
            };
        };

        //Additional fields are required if the account is a guest-account
        if($role == 'user') {
            $user_required = array("Anrede" => $anrede, "Vorname" => $vorname, "Nachname" => $nachname, "Postleitzahl" => $plz, "Ort" => $ort, "Strasse" => $strasse, "Hausnummer" => $hausnummer, "E-Mail" => $email);

            foreach ($user_required as $name => $val)
            {
                if (empty($val))
                {
                    array_push($errors, $name);
                };
            };
        }
    }

    $title = 'Userverwaltung';
    include "../include/head.php"; 
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
                            //Hash the password, and set comment to NULL if no comment was given
                            $form_password = hash('sha256', $form_password . $form_username);
                            $comment = empty($comment) ? NULL : $comment;

                            //Prepare a statement to add user data to the database
                            $add_user_stmt = $db->prepare('INSERT INTO user (username, password, email, rolle, person_id, active) VALUES (?, ?, ?, ?, ?, 1)');

                            //If we have person data (the role is user -> it is a guest account), add it to the database first
                            if($role == 'user') {
                                //Add person data to the database
                                $add_person_stmt = $db->prepare('INSERT INTO person (anrede, vorname, nachname, plz, ort, straße, hausnummer, kommentar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                                $add_person_stmt->bind_param('ssssssss', $anrede, $vorname, $nachname, $plz, $ort, $strasse, $hausnummer, $comment);
                                $done = $add_person_stmt->execute();
                                $add_person_stmt->close();
                                
                                //If successful, search for the automatically created ID of the newly created person
                                if ($done) {
                                    //Select the person_id of the newly created person
                                    $person_id_stmt = $db->prepare("SELECT person_id FROM person WHERE vorname = ? AND nachname = ? AND plz = ? AND ort = ? AND straße = ? AND hausnummer = ?");
                                    $person_id_stmt->bind_param('ssssss', $vorname, $nachname, $plz, $ort, $strasse, $hausnummer);
                                    $person_id_stmt->execute();
                                    $doneAswell = $person_id_stmt->bind_result($person_id);

                                    //If successful, save result (should only be one!) and bind parameters for the add user statement
                                    if ($doneAswell) {
                                        //Flag to ensure only the first result gets fetched (in the unlikely case of multiple results)
                                        $first_run = true;
                                        while($person_id_stmt->fetch() && $first_run){
                                            $add_user_stmt->bind_param('ssssi', $form_username, $form_password, $email, $role, $person_id);
                                            $first_run = false;
                                        }
                                        $person_id_stmt->close();
                                    }
                                }   
                            }
                            //If we have no person data, bind parameters for the add user statement with person_id set to NULL
                            else {
                                $person_id = NULL;
                                $add_user_stmt->bind_param('ssssi', $form_username, $form_password, $email, $role, $person_id);
                            }

                            //Add user data to the database
                            $success = $add_user_stmt->execute();
                            $add_user_stmt->close();

                            $message = $_GET['userid'] == -1 ? 'erstellt' : 'aktualisiert';
                            
                            //If successful, print message
                            if($success) { echo "<h3>". $_POST["form_username"] ." erfolgreich " . $message . "!</h3>"; }

                            //If we are editing a user, deactivate the old user account
                            if($_GET['userid'] != -1) {
                                $update_status_stmt = $db->prepare("UPDATE user SET active = 0 WHERE userid = ?");
                                $update_status_stmt->bind_param('i', $_GET['userid']);
                                $update_status_stmt->execute();
                                $update_status_stmt->close();
                            }
                        }
                    }
                    //If we are deactivating a user (user clicked on 'Deactivate-Button' -> $_GET['deactivate] is set)
                    else if(isset($_GET['deactivate'])) {
                        $userid = $_GET['userid'];

                        //Deactivate user
                        $update_status_stmt = $db->prepare("UPDATE user SET active = 0 WHERE userid = ?");
                        $update_status_stmt->bind_param('i', $userid);
                        $update_status_stmt->execute();
                        $update_status_stmt->close();

                        echo "<h3>Account erfolgreich deaktiviert!</h3>";
                    }
                ?>

                <h1>Userverwaltung</h1>

                <?php
                    //Includes the 'User-Form' if a userid is sent, the errors array is not empty (hence why the errors-array needs to initially be filled with something), and we are not deactivating a user
                    //In simpler words, only shows 'User-Form' if we have errors or if we clicked on an option in the 'Users-Table'
                    if(isset($_GET['userid']) && !empty($errors) && !isset($_GET['deactivate'])) {
                        //Includes a back-button
                        include '../include/backButton.php';
                        //Initializes (and cleans in case of duplicate variable names) variables (also necessary for new user include to work)
                        $userid = $_GET['userid'];
                        $username = '';
                        $email = '';
                        $rolle = '';
                        $anrede = '';
                        $vorname = '';
                        $nachname = '';
                        $plz = '';
                        $ort = '';
                        $straße = '';
                        $hausnummer = '';
                        $kommentar = '';

                        //Includes empty 'User-Form' if we want to create a new user
                        if($userid == -1) {
                            include "../include/userForm.php";
                        }
                        //If we want to edit, select all data of the user in question, bind it to variables, fill (and include) the 'User-Form' with them
                        else {
                            //Select (almost) all data from a specified user
                            $select_user_stmt = $db->prepare("SELECT u.username, u.email, u.rolle, p.anrede, p.vorname, p.nachname, p.plz, p.ort, p.straße, p.hausnummer, p.kommentar FROM user AS u LEFT JOIN person AS p ON u.person_id = p.person_id WHERE u.userid = ?");
                            $select_user_stmt->bind_param('i', $userid);
                            $select_user_stmt->execute();
                            $select_user_stmt->bind_result($username, $email, $rolle, $anrede, $vorname, $nachname, $plz, $ort, $straße, $hausnummer, $kommentar);

                            while($select_user_stmt->fetch()) {
                                include '../include/userForm.php';
                            }
                            $select_user_stmt->close();
                        }
                    }
                    //Shows the 'Users-Table' otherwise
                    else {
                        //Includes a single entry for a new user at the beginning of 'Users-Table'
                        //Note that the user_id gets set to -1, so we can always know that we are creating a new user (and not editing an old one)
                        $selected_user_id = -1;
                        $selected_username = 'NEW USER';
                        $selected_role = 'new';
                        include '../include/userRow.php';

                        //Selects userid, username and rolle from all active accounts
                        $select_users_stmt = $db->prepare("SELECT userid, username, rolle FROM user WHERE active = 1 ORDER BY rolle, username");
                        $select_users_stmt->execute();
                        $select_users_stmt->bind_result($selected_user_id, $selected_username, $selected_role);

                        //Includes a 'Table-Row' for each active user
                        //See userRow.php for information on what each selected value is used for
                        while($select_users_stmt->fetch()) {
                            include '../include/userRow.php';
                        }
                        $select_users_stmt->close();
                    }
                ?>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>