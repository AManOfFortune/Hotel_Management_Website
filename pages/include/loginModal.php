<?php
    //Variable used to POST username & password OR logout to the current page so login-logic can happen on all pages
    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    //Does log-out logic if user presses the 'logout-button' (which sends a GET variable named 'logout' to the current page)
    if(isset($_GET['logout']))
    {
        session_destroy();
        //Reroutes to index page on logout (so user cannot stay on login-only pages if he logs out)
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/Semesterprojekt/index.php";
        header("Location: $url");
        exit();
    }

    //Does log-in logic if user enters a username and password and presses the 'login-button' (which POSTS username & password to the current page)
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        //Fetches all active accounts from database, compares that with the credentials provided, sets username and role as session variables if login successful
        $sql = "SELECT userid, username, password, rolle FROM user WHERE active = 1";
        $result = $db->query($sql);

        while ($row = $result->fetch_array()) {
            if($username == $row['username'] && hash('sha256', $password . $username) == $row['password']) {
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['user'] = $row['username'];
                $_SESSION['user_role'] = $row['rolle'];
            }
        }
    }
?> 

<div id="login" class="modal">

    <div class="modal-content">

        <span class="close close_login">&times;</span>

        <?php

        //Includes the login form if user is not logged in, or the Logout-confirmation prompt if he is
        if (isset($_SESSION['user']))
        {
            include "loginSuccess.php";
        }
        else
        {
            include "loginForm.php";
        }
        ?>

    </div>

</div>