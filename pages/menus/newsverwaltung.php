<!DOCTYPE html>
<?php
    //Errors array is initially filled with dummy content for if-clause to work
    //Only if $_GET['registration'] is set will it be emptied
    //$_GET['registration'] is set when user submits the form (either to create or edit an article)
    $errors = array("NotEmpty");

    //Empties, then fills the errors array (in case of errors/missing information)
    if(isset($_GET['registration'])) {
        $news_title = $_POST["title"];
        $news_content = $_POST["content"];

        //Title and content are required, not pictures
        $required = array("Titel" => $news_title, "Inhalt" => $news_content);
        $errors = array();

        //Appends the key to the errors array if corresponding variable is empty
        foreach ($required as $name => $val)
        {
            if (empty($val))
            {
                array_push($errors, $name);
            };
        };

        //Checks for the allowed filetypes if we have pictures
        if(!empty($_FILES["pictures"]["tmp_name"][0])) {
            //Allowed are jpg and png
            $allowed = array('jpg', 'png');

            for($i = 0; $i < count($_FILES["pictures"]["name"]); $i++) {

                $filename = $_FILES["pictures"]["name"][$i];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if (!in_array($filetype, $allowed)) {
                    array_push($errors, "Fehler, die Datei $filename ist nicht im Format .jpg | .png !");
                }
            }
        }
    }

    $title = 'Newsverwaltung';
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
                        //If errors array is empty, and we are creating a new article, add all information to the database
                        else if ($_GET['newsid'] == -1)
                        {
                            //Set thumbnailpath to either the correct path (although the thumbnail has not yet been created) or to NULL if we have no files
                            $thumbnail_path = !empty($_FILES["pictures"]["tmp_name"][0]) ? "/Semesterprojekt/pictures/thumbnails/" . strstr($_FILES["pictures"]["name"][0], ".".pathinfo($_FILES["pictures"]["name"][0], PATHINFO_EXTENSION), true) . "_t.jpg" : NULL;

                            //Add the article to the database
                            $add_article_stmt = $db->prepare('INSERT INTO news (title, text_content, thumbnail_path, active) VALUES (?, ?, ?, 1)');
                            $add_article_stmt->bind_param('sss', $news_title, $news_content, $thumbnail_path);
                            $add_article_stmt->execute();
                            $add_article_stmt->close();

                            //If we have pictures, deal with them
                            if(!empty($_FILES["pictures"]["tmp_name"][0])) {
                                //Find out what news_id was just created and save it into a variable
                                $select_news_id_stmt = $db->prepare('SELECT news_id FROM news WHERE title = ? AND text_content = ? AND thumbnail_path = ?');
                                $select_news_id_stmt->bind_param('sss', $news_title, $news_content, $thumbnail_path);
                                $select_news_id_stmt->execute();
                                $select_news_id_stmt->bind_result($selected_news_id);

                                $news_id = 0;
                                while($select_news_id_stmt->fetch()) {
                                    $news_id = $selected_news_id;
                                }
                                $select_news_id_stmt->close();

                                //The directory the pictures get added into is uploads/news
                                $dirName = "../../uploads/news";

                                //Prepare statement to add all pictures to the database
                                $add_pictures_stmt = $db->prepare("INSERT INTO pictures (picture_path, news_id) VALUES (?, ?)");

                                //Loop through all pictures, add them to uploads folder & their path to database
                                for($i = 0; $i < count($_FILES["pictures"]["name"]); $i++) {
                                    
                                    $tempName = $_FILES["pictures"]["tmp_name"][$i];
                                    $filename = $_FILES["pictures"]["name"][$i];

                                    $path = $dirName."/". $filename;

                                    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                                    $filenameWithoutExt = strstr($filename, ".$filetype", true);

                                    //Very unsecure moving of files, check https://www.php.net/manual/de/function.move-uploaded-file.php for better security
                                    //Good enough for now
                                    $ok = move_uploaded_file($tempName, "$dirName/$filename");
                                    //Creates a thumbnail for the first picture uploaded
                                    if($i == 0) {
                                        //Thumbnail size is 700px x 300px
                                        makeThumbnails("../../pictures/thumbnails", $path, $filenameWithoutExt, 700, 300);
                                        $thumbnail_path = "/Semesterprojekt/pictures/thumbnails/$filenameWithoutExt"."_t.jpg";
                                    }
                                    
                                    //If moving worked, add picture to database
                                    if($ok) {
                                        $add_pictures_stmt->bind_param('si', $path, $news_id);
                                        $add_pictures_stmt->execute();
                                    }
                                }
                                $add_pictures_stmt->close();
                            }

                            echo "<h3>Artikel erfolgreich veröffentlicht!</h3>";
                        }
                        //Else means we are editing, so we update the information in the database
                        else {
                            //Update the article in the database
                            $update_article_stmt = $db->prepare('UPDATE news SET title = ?, text_content = ? WHERE news_id = ?');
                            $update_article_stmt->bind_param('ssi', $news_title, $news_content, $_GET['newsid']);
                            $update_article_stmt->execute();
                            $update_article_stmt->close();

                            //If we have pictures, add them
                            //Very similar code to above else-if-statement, for explanations look there
                            if(!empty($_FILES["pictures"]["tmp_name"][0])) {
                                $dirName = "../../uploads/news";

                                $add_pictures_stmt = $db->prepare("INSERT INTO pictures (picture_path, news_id) VALUES (?, ?)");

                                for($i = 0; $i < count($_FILES["pictures"]["name"]); $i++) {
                                    
                                    $tempName = $_FILES["pictures"]["tmp_name"][$i];
                                    $filename = $_FILES["pictures"]["name"][$i];

                                    $path = $dirName."/". $filename;

                                    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                                    $filenameWithoutExt = strstr($filename, ".$filetype", true);

                                    $ok = move_uploaded_file($tempName, "$dirName/$filename");
                                    
                                    if($ok) {
                                        $add_pictures_stmt->bind_param('si', $path, $_GET['newsid']);
                                        $add_pictures_stmt->execute();
                                    }
                                }
                                $add_pictures_stmt->close();

                                echo "<h3>Artikel erfolgreich aktualisiert!</h3>";
                            }
                        }
                    }
                    //If we are deactivating an article (user clicked on 'Deactivate-Button' -> $_GET['deactivate] is set), update status
                    else if(isset($_GET['deactivate'])) {
                        //Update article status
                        $update_status_stmt = $db->prepare("UPDATE news SET active = 0 WHERE news_id = ?");
                        $update_status_stmt->bind_param('i', $_GET['newsid']);
                        $update_status_stmt->execute();
                        $update_status_stmt->close();

                        echo "<h3>Artikel erfolgreich deaktiviert!</h3>";
                    }
                ?>

                <h1>Newsverwaltung</h1>

                <?php
                    //Includes the 'News-Form' if a newsid is sent, the errors array is not empty (hence why the errors-array needs to initially be filled with something), and we are not deactivating an article
                    //In simpler words, only shows 'News-Form' if we have errors or if we clicked on an option in the 'News-Table'
                    if(isset($_GET['newsid']) && !empty($errors) && !isset($_GET['deactivate'])) {
                        //Includes a back-button
                        include '../include/backButton.php';
                        //Initializes (and cleans in case of duplicate variable names) variables (also necessary for new article include to work)
                        $newsid = $_GET['newsid'];
                        $news_title = '';
                        $text_content = '';

                        //If we want to edit, select all data of the article in question, bind it to variables, fill (and include) the 'News-Form' with them
                        //Select (almost) all data from a specified article
                        $select_article_stmt = $db->prepare("SELECT news_id, title, text_content, `datetime` FROM news WHERE news_id = ?");
                        $select_article_stmt->bind_param('i', $newsid);
                        $select_article_stmt->execute();
                        $select_article_stmt->bind_result($newsid, $news_title, $text_content, $datetime);

                        //If we are creating a new article, include empty 'News-Form'
                        if($newsid == -1) { 
                            include '../include/newsForm.php';
                        }

                        while($select_article_stmt->fetch()) {
                            include '../include/newsForm.php';
                        }
                        $select_article_stmt->close();

                        //Select all pictures associated with an article, print them in table format
                        $select_pictures_stmt = $db->prepare("SELECT `picture_id`, `picture_path` FROM `pictures` WHERE `news_id` = ?");
                        $select_pictures_stmt->bind_param('i', $newsid);
                        $select_pictures_stmt->execute();
                        $select_pictures_stmt->bind_result($picture_id, $picture_path);

                        $select_pictures_stmt->store_result();
                        $number_of_pictures = $select_pictures_stmt->num_rows;

                        echo "<h2>Anzahl Bilder: $number_of_pictures</h2>";

                        while ($select_pictures_stmt->fetch()) {
                            $array = explode("/", $picture_path);
                            $picture_name = $array[count($array) - 1];

                            include '../include/pictureRow.php';
                        }
                        ?>
                        <br>
                        <div class="space-around col-sm-12 row-centred">
                            <?php /*Adds a 'Deactivate-Button' with a confirmation-modal if an existing article is being edited*/ if ($newsid != -1) { include '../include/newsConfirmationModal.php' ?><button type="button" onclick="showModal('newsConfirm', 'close_newsConfirm')" class="submit-btn danger">Artikel löschen</button><?php } ?>
                            <input type="submit" class="submit-btn" value="<?php /* Changes button message depending if a new article is being published, or an old one edited */ if($newsid == -1) { echo 'Veröffentlichen';} else {echo 'Änderungen speichern';}?>">
                        </div>

                        </form>

                        <?php
                    }
                    //Shows the 'News-Table' otherwise
                    else {
                        //Includes a single entry for a new article at the beginning of 'News-Table'
                        //Note that the news_id gets set to -1, so we can always know that we are creating a new article (and not editing an old one)
                        $selected_news_id = -1;
                        $selected_news_title = 'NEW ARTICLE';
                        $class = 'new';
                        include '../include/newsRow.php';

                        //Selects news_id and title from all active news articles
                        $select_news_stmt = $db->prepare("SELECT news_id, title FROM news WHERE active = 1 ORDER BY `datetime` DESC");
                        $select_news_stmt->execute();
                        $select_news_stmt->bind_result($selected_news_id, $selected_news_title);

                        //Includes a 'Table-Row' for each active article
                        //See newsRow.php for information on what each selected value is used for
                        while($select_news_stmt->fetch()) {
                            $class = 'user';
                            include '../include/newsRow.php';
                        }
                        $select_news_stmt->close();
                    }
                ?>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>