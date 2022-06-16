<?php {
    $IS_LOGGED_IN = isset($_SESSION["user_id"]);
    $IS_ADMIN_OR_LEADER = isset($_SESSION["user_type"]) && ($_SESSION["user_type"] == "0" || $_SESSION["user_type"] == "1");
    $IS_ADMIN = isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "0";
    $IS_LEADER = isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "1";
} ?>

<nav>
    <div style="width: 50%; height: 100%; display: flex; align-items: center;">
        <ul class="navigation">
            <li><a href="html/o_autoru.html">Informacije o autoru</a></li>
            <li><a href="index.php">Početna stranica</a></li>
            <li><a href="galerija.php">Galerija</a></li>
            <?php if ($IS_LOGGED_IN) { ?>
                <li><a href="popis_slika.php">Popis slika</a></li>
            <?php } ?>
            <?php if ($IS_LOGGED_IN) { ?>
                <li><a href="popis_planina.php">Popis planina</a></li>
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
        <?php
        if (isset($_SESSION["user_id"])) {
            echo "<p>{$_SESSION["username"]} </p>";
            echo "<button id='signout'>Odjava</button>";
        } else {
            include("prijava.php");
        }
        ?>
    </div>
</nav>

<script>
    document.getElementById("signout").onclick = () => {
        location.href = "prijava.php?signout=1"
    };
</script>