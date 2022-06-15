<?php {
    $IS_LOGGED_IN = isset($_SESSION["user_id"]);
    $IS_ADMIN_OR_LEADER = isset($_SESSION["user_type"]) && ($_SESSION["user_type"] == "0" || $_SESSION["user_type"] == "1");
    $IS_ADMIN = isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "0";
} ?>

<nav>
    <div class="navigation">
        <ul>
            <li><a href="index.php">Početna stranica</a></li>
            <?php if ($IS_LOGGED_IN) { ?>
                <li><a href="popis_slika.php">Popis slika</a></li>
            <?php } ?>
            <?php
            if ($IS_LOGGED_IN && $IS_ADMIN_OR_LEADER) { ?>
                <li><a href="korisnici.php">Korisnici</a></li>
            <?php }
            ?>
            <?php
            if ($IS_LOGGED_IN && $IS_ADMIN) { ?>
                <li><a href="statistika_galerije.php">Statistika</a></li>
            <?php }
            ?>
        </ul>
    </div>
    <div class="user-wrapper">
            <?php include("prijava.php") ?>
    </div>
</nav>