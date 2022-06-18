<?php
require_once("header.php");
require_once("menu.php");
$curr = establishDbConnection();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == '0') { ?>
        <button onclick="window.location = 'dodaj_planinu.php'">
            Dodaj novu planinu
        </button>
        <button onclick="window.location = 'dodaj_moderatora.php'">
            Dodavanje moderatora
        </button>
<?php }
    if ($_SESSION['user_type'] == '2') {
        header("location:index.php");
        exit();
    }
} else {
    header("location:index.php");
    exit();
}
?>
<div class="mountain-wrapper">
    <?php
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['active_user_type'] == '0') {
            echo "Sve planine:";
        } else {
            echo "Vaše planine:";
        }
    }
    ?>
    <?php
    $query = "SELECT planina.planina_id, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina FROM planina LEFT JOIN moderator ON planina.planina_id=moderator.planina_id WHERE moderator.korisnik_id={$_SESSION['active_user_id']}";
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['user_type'] == '0') {
            $query = "SELECT planina.planina_id, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina FROM planina LEFT JOIN moderator ON planina.planina_id=moderator.planina_id";
        }
    }
    $result = executeQuery($curr, $query);
    foreach ($result as $mountain) : ?>
        <div class="image-container">
            <p><?= $mountain["naziv"] ?></p>
            <p><?= $mountain["opis"] ?></p>
            <p><?= $mountain["lokacija"] ?></p>
            <p><?= $mountain["geografska_duzina"] ?></p>
            <p><?= $mountain["geogradska_sirina"] ?></p>
            <button onclick="window.location = 'popis_slika.php?nazivPlanine=<?= $mountain['naziv'] ?>'">Popis slika za planinu</button>
            <button onclick="window.location = 'dodaj_planinu.php?mountain=<?= $mountain['planina_id'] ?>'">Uredi planinu</button>
        </div>

    <?php endforeach;
    ?>
</div>