<a class="row-user <?= /*Adds the role as a css class (so each row can be colored according to the user's role)*/ $selected_role ?>" href="userverwaltung.php?userid=<?= /*Sends the userid as a GET variable to the userverwaltung page*/ $selected_user_id ?>">
    <h2><?= $selected_username ?></h2>
    <img src="/Semesterprojekt/pictures/icons/chevron-right.png">
</a>