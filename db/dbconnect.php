<?php
    require_once("dbconf.php");

    $db = new mysqli($host, $user, $password, $database);

    //Writes error and success messages to the console with JS
    if ($db->connect_error) {
        echo "<script>console.log('Error connecting to database: $db->connect_error')</script>";
    }
    else {
        echo "<script>console.log('Connection established')</script>";
    }
?>