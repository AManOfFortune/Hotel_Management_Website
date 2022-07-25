<!DOCTYPE html>
<?php
    $title = 'Serviceticket'; 
    include "../include/head.php"; 
?>
    <body> 
        <?php include "../include/navbar.php" ?>
        <div class="content_wrapper">
            <div class="content_box">
                <h1>Haben Sie ein Problem? Erstellen Sie ein Service Ticket!</h1>
                <div class="row space-around">
                    <a href="../links/ticket_erstellen.php" class="submit-btn">Ticket erstellen</a>
                    <a href="../links/ticketverwaltung.php" class="submit-btn">Tickets verwalten</a>
                </div>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>