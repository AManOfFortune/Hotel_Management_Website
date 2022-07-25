<!DOCTYPE html>
<?php
    $title = 'Hotelverwaltung';
    include "pages/include/head.php"; 
?>
    <body>
        <?php include "pages/include/navbar.php"?>

        <div class="content_wrapper">
            <div class="page_title">
                <h1>Willkommen!</h1>
            </div>

            <div class="content_box_wrapper">
                <div class="content_box">
                    
                    <h1>News</h1>
                    
                    <?php
                        $select_all_news_stmt = $db->prepare("SELECT `news_id`, `title`, `thumbnail_path`, `datetime` FROM `news` WHERE active = 1 ORDER BY `datetime` DESC");
                        $select_all_news_stmt->execute();
                        $select_all_news_stmt->bind_result($news_id, $news_title, $news_thumbnail_path, $news_datetime);

                        while($select_all_news_stmt->fetch()) {
                            $news_datetime = date("d.m.Y H:i", strtotime($news_datetime));
                            include "pages/include/newsCard.php";
                        }
                        $select_all_news_stmt->close();
                    ?>

                </div>
            </div>
        </div>
        <?php include "pages/include/footer.php" ?>
    </body>
</html>