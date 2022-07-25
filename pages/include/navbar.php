<div id="nav_wrapper">

    <!-- Includes Login-Modal; Modal has to be included before navbar, otherwise navbar does not refresh correctly when logging in -->
    <?php include "loginModal.php"; ?>

    <div class="navbar">

        <!-- (Invisible) Checkbox used on mobile devices to toggle menu -->
        <input id="nav_toggle" type="checkbox">

        <!-- Animated hamburger icon, invisible on larger devices-->
        <div id="nav_icon">
            <div id="nav_icon_bar_1" class="nav_icon_bars"></div>
            <div id="nav_icon_bar_2" class="nav_icon_bars"></div>
            <div id="nav_icon_bar_3" class="nav_icon_bars"></div>
        </div>

        <ul class="nav" id="navbar">
            <div>
                <li class="nav_item"><a class="nav_logo nav_link" href="/Semesterprojekt/index.php">Home</a></li>
            </div>
            <div class="nav_right">
                <?php /*Includes link to Serviceticket when logged in as any user*/ if (isset($_SESSION['user'])) { ?> <li class="nav_item"><a class="nav_link" href="/Semesterprojekt/pages/menus/serviceticket.php">Serviceticket</a></li> <?php } ?>
                <?php /*Includes link to Accountverwaltung when logged in as any user*/ if (isset($_SESSION['user'])) { ?> <li class="nav_item"><a class="nav_link" href="/Semesterprojekt/pages/menus/accountverwaltung.php">Accountverwaltung</a></li> <?php } ?>
                <?php /*Includes link to Userverwaltung when logged in as admin-user*/ if (isset($_SESSION['user']) && $_SESSION['user_role'] == 'admin') { ?> <li class="nav_item"><a class="nav_link" href="/Semesterprojekt/pages/menus/userverwaltung.php">Userverwaltung</a></li> <?php } ?>
                <?php /*Includes link to Newsverwaltung when logged in as admin-user*/ if (isset($_SESSION['user']) && $_SESSION['user_role'] == 'admin') { ?> <li class="nav_item"><a class="nav_link" href="/Semesterprojekt/pages/menus/newsverwaltung.php">Newsverwaltung</a></li> <?php } ?>
                
                <!-- Displays Login-Modal with JS onclick-function -->
                <li class="nav_item"><a class="nav_link" onclick="showModal('login', 'close_login');"><?php if (isset($_SESSION['user'])) {echo "Logout";} else {echo "Login";} ?></a></li>
            </div>
        </ul>

    </div>

</div>