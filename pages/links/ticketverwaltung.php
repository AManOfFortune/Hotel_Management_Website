<!DOCTYPE html>
<?php
    $title = 'Ticketverwaltung'; 
    include "../include/head.php"; 
?>
    <body>
        <?php include "../include/navbar.php" ?>
        <div class="content_wrapper">
            <div class="content_box">
                <h1>Ticketverwaltung</h1>
                <?php
                    //Selects the information from the database for the table
                    $sql = "SELECT id, title, file_path, comment, `status`, `datetime` FROM tickets";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    $stmt->bind_result($ticket_id, $ticket_title, $file_path, $comment, $status, $datetime);
                ?>
                <div class="row-centred">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Ticket ID</th>
                        <th scope="col">Bild</th>
                        <th scope="col">Title</th>
                        <th scope="col">Kommentar</th>
                        <th scope="col">Datum</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php while ($stmt->fetch()){ //Fetches information, to then echo it into the table
                            echo "<tr>";
                            echo "<td>" . $ticket_id . "</td>";
                            echo "<td><img src='$file_path' alt='picture' class='img-thumbnail'></td>";
                            echo "<td>" . $ticket_title . "</td>";
                            echo "<td>" . $comment . "</td>";
                            echo "<td>" . $datetime . "</td>";
                            echo "<td>" . $status . "</td>";
                            echo "</tr>";
                        } $stmt->close();?>
                    </tbody>
                    </table>     
                </div>
                <br>
                <a href="../menus/serviceticket.php" class="submit-btn">Go Back</a>
                <br>
                <h1> </h1>
            </div>
        </div>
        <?php include "../include/footer.php" ?>
    </body>
</html>