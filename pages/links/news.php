<!DOCTYPE html>
<?php
    $title = 'Newsartikel';
    include "../include/head.php"; 
?>
    <head>
        <!-- Includes extra css for a potential image carousel -->
        <link rel="stylesheet" href="/Semesterprojekt/css/imagecarousel.css">
    </head>

    <body>
        <?php include "../include/navbar.php" ?>
        <div class="content_wrapper">
            <div class="content_box">
                <?php
                    //Only displays content if it get an id
                    if(isset($_GET['id'])) {
                        $news_id = $_GET['id'];

                        //Selects the article content from the database
                        $select_news_stmt = $db->prepare("SELECT `title`, `text_content`, `thumbnail_path`, `datetime` FROM `news` WHERE `news_id` = ?");
                        $select_news_stmt->bind_param('i', $news_id);
                        $select_news_stmt->execute();
                        $select_news_stmt->bind_result($news_title, $news_text_content, $news_thumbnail_path, $news_datetime);

                        //Displays the title, date and saves the news text_content into a variable
                        $temporary_news_text_content = '';
                        while($select_news_stmt->fetch()){
                            $has_pictures = $news_thumbnail_path == '' ? 0 : 1;
                            //Formats the date
                            $news_datetime = date("d.m.Y H:i", strtotime($news_datetime));

                            include '../include/backButton.php';
                            echo "<h1>$news_title</h1>";

                            echo "<h4>$news_datetime</h4>";
                            $temporary_news_text_content = $news_text_content;
                        }
                        $select_news_stmt->close();
                        
                        //Includes carousel with pictures if we have a thumbnail --> We have at least one picture
                        if($has_pictures) {

                            echo "<!-- Slideshow container -->";
                            echo "<div class='slideshow-container'>";

                            //Selects all pictures associated with the article
                            $select_pictures_stmt = $db->prepare("SELECT `picture_path` FROM `pictures` WHERE `news_id` = ?");
                            $select_pictures_stmt->bind_param('i', $news_id);
                            $select_pictures_stmt->execute();
                            $select_pictures_stmt->bind_result($picture_path);
                    
                            $select_pictures_stmt->store_result();
                            $number_of_pictures = $select_pictures_stmt->num_rows;
                            $count = 1;
                    
                            while($select_pictures_stmt->fetch()) {
                                //Includes relevant html code with picture for each picture
                                ?>
                    
                                <div class="Containers fade">
                                    <div class="MessageInfo"><?= $count ?> / <?= $number_of_pictures ?></div>
                                    <img src="<?= $picture_path ?>" onclick="showPicture('<?= $picture_path ?>')">
                                </div>
                    
                                <?php
                    
                                $count = $count + 1;
                            }

                            ?>

                            <!-- Back and forward buttons -->
                            <a class="Back" onclick="plusSlides(-1)">&#10094;</a>
                            <a class="forward" onclick="plusSlides(1)">&#10095;</a>

                            <!-- The circles/dots -->
                            <div class="dot-wrapper">
                                <?php /* Includes a dot for each picture */for ($i = 1; $i < $count; $i++) {
                                echo "<span class='dots' onclick='currentSlide($i)'></span>";
                                }
                            ?>
                            </div>

                            </div>

                            <?php
                            $select_pictures_stmt->close();
                        }
                        //Displays the news content after the pictures
                        echo "<p>$temporary_news_text_content</p>";
                    }
                ?>

            </div>
        </div>
        <?php include "../include/footer.php" ?>

        <?php /*Only includes the javascript file if we have pictures --> a picture carousel is created*/ if($has_pictures) {echo "<script src='/Semesterprojekt/js/imagecarousel.js'></script>";} ?>
    </body>
</html>