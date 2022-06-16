<?php
require_once("header.php");
require_once("menu.php");
$curr = establishDbConnection();

$id = $_GET['id'];
$query = "SELECT slika.slika_id, slika.naziv, planina.naziv, korisnik.ime, korisnik.prezime, slika.datum_vrijeme_slikanja, slika.url, slika.opis FROM planina, korisnik, slika WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id AND slika.slika_id=$id";
$result = executeQuery($curr, $query);
list($id, $title, $mtitle, $name, $surname, $date, $url, $description) = mysqli_fetch_array($result);
?>
<div class="mountain-wrapper">
    <div class="image-container">
        <object data=<?= $url; ?> type="image/png" class="mountain-image">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Missing-image-232x150.png" fallback alt="" class="mountain-image">
        </object>
        <p class="title-paragraph">Naziv:</p>
        <p>
            <?= $title; ?>
        </p>
        <p class="title-paragraph">Opis:</p>
        <p class="description-wrapper">
            <?= $description; ?>
        </p>
        <p class="title-paragraph">Autor:</p>
        <p>
            <?= $name; ?> <?= $surname; ?>
        </p>
        <div>
            <p class="title-paragraph">Datum i vrijeme slikanja</p>
            <p>
                <?php
                $date = strtotime($date);
                $date = date("d.m.Y. H:i", $date);
                echo "<p> $date </p>"
                ?>
            </p>
        </div>
    </div>
</div>