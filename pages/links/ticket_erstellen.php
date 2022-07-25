<!DOCTYPE html>
<?php
    $title = 'Serviceticket erstellen'; 
    include "../include/head.php"; 
?>

<?php
    if (isset($_POST['comment'])) {//When a comment is submitted
        //Variables definition
        $ticket_title = $_POST['title'];
        $file_path = (!empty($_FILES['picture']['name'])) ? "/Semesterprojekt/pictures/thumbnails/".strstr($_FILES["picture"]["name"], ".".pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION), true)."_t.jpg" : NULL;
        $comment = $_POST['comment'];
        $user_id = $_SESSION['userid'];

        //Adds a ticket to the database
        $add_ticket_stmt = $db->prepare("INSERT INTO tickets (title, file_path, comment, `user_id`) VALUES (?, ?, ?, ?)");
        $add_ticket_stmt->bind_param("sssi", $ticket_title, $file_path, $comment, $user_id);
        $add_ticket_stmt->execute();
        $add_ticket_stmt->close();
        
        if(!empty($_FILES['picture']['name'])) {//When a picture is also submitted
            $tempName = $_FILES["picture"]["tmp_name"];
            $filename = $_FILES["picture"]["name"];
            $dirName = "../../uploads/" . $_SESSION['user'];
            
            $allowed = array('jpg', 'PNG', 'png');
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            $filenameWithoutExt = strstr($filename, ".$filetype", true);

            if (!in_array($filetype, $allowed)) {
                $ok = false;
            } 
            else {
                if (!file_exists($dirName)) {
                    mkdir($dirName, 0777, true);
                }
                $ok = move_uploaded_file($tempName, "$dirName/$filename");
                //Creates a thumbnail
                makeThumbnails("../../pictures/thumbnails", "$dirName/$filename", $filenameWithoutExt, 100, 100);
            }
            $picture_path = "$dirName/$filename";

            //Selects the ticket ID
            $ticket_id_stmt = $db->prepare("SELECT id FROM tickets WHERE title = ? AND file_path = ? AND comment = ? AND `user_id` = ?");
            $ticket_id_stmt->bind_param("sssi", $ticket_title, $file_path, $comment, $user_id);
            $ticket_id_stmt->execute();
            $ticket_id_stmt->bind_result($selected_ticket_id);

            //Fetches the ticket ID
            $ticket_id = 0;
            while($ticket_id_stmt->fetch()) {
                $ticket_id = $selected_ticket_id;
            }
            $ticket_id_stmt->close();

            //Adds picture_path and ticket_id into the database
            $add_picture_stmt = $db->prepare("INSERT INTO pictures (picture_path, ticket_id) VALUES (?, ?)");
            $add_picture_stmt->bind_param("si", $picture_path, $ticket_id);
            $add_picture_stmt->execute();
            $add_picture_stmt->close();
        }
    }
?>

    <body>
        <?php include "../include/navbar.php" ?>
        <div class="content_wrapper">
            <div class="content_box">

                <?php if (!isset($ok)) {?>
                <h1>Haben Sie ein Problem? Erstellen Sie ein Service Ticket!</h1>

                <form action="ticket_erstellen.php" method="post" enctype="multipart/form-data">
                    <div class="row-centred">
                        <div class="form-group col-sm-6">
                            <label for="title">Titel:</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                    </div>
                    <br>
                    <div class="row-centred">
                        <div class="form-group col-sm-12">
                            <label for="comment">Beschreiben Sie ihr Problem:</label>
                            <textarea class="form-control" name="comment" id="comment" rows="5" required></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row-centred">
                        <div class="form-group col-sm-12-centred">
                            <label for="picture">Laden Sie ein Foto hoch (optional):</label>
                            <input class="form-control" type="file" name="picture" id="picture">
                        </div>
                    </div>
                    <a href="../menus/serviceticket.php" class="submit-btn">Go Back</a>
                    <input type="submit" class="submit-btn" name="upload" id="upload" value="Submit">
                </form>

                <?php } else if ($ok) { ?>
                    <h1>Ein Serviceticket wurde erfolgreich erstellt!</h1>              
                    <a href="../menus/serviceticket.php" class="submit-btn">Go Back</a>
                    <a href="../links/ticketverwaltung.php" class="submit-btn">Tickets verwalten</a>
                <?php } else { ?>
                    <h1>Irgendetwas ist schief gelaufen!</h1>
                    <h4>Prüfen Sie ihr Dateiformat!</h4>
                    <br>
                    <a href="../menus/serviceticket.php" class="submit-btn">Go Back</a>
                    <a href="../links/ticketverwaltung.php" class="submit-btn">Tickets verwalten</a>
                    <br>
                    <h1> </h1>
                <?php } ?>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>