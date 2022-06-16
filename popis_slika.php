<?php
require_once("header.php");
require_once("menu.php");

if (sizeof($_SESSION) == 0) {
    header("location:index.php");
    exit();
}

$curr = establishDbConnection();

$query = "SELECT blokiran FROM korisnik WHERE korisnik_id={$_SESSION['user_id']}";
$result = executeQuery($curr, $query);

list($blocked) = mysqli_fetch_array($result);

if ($blocked != '1') {
    echo "
            <div>
                <a href=\"dodaj_sliku.php\"> Dodajte novu sliku</a>
            </div>
        ";
} else {
    header("location:index.php");
    exit();
}
?>
<?php
$query = "SELECT slika.slika_id, slika.korisnik_id, planina.naziv, korisnik.ime, korisnik.prezime, slika.datum_vrijeme_slikanja, slika.url, planina.opis, slika.status, planina.planina_id FROM planina, korisnik, slika WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id";

if ($IS_LEADER) {
    $query = "SELECT slika.slika_id, slika.url, slika.datum_vrijeme_slikanja, slika.status, planina.naziv, korisnik.ime, korisnik.prezime, korisnik.korisnik_id, planina.planina_id FROM slika INNER JOIN korisnik ON slika.korisnik_id=korisnik.korisnik_id INNER JOIN planina ON slika.planina_id=planina.planina_id INNER JOIN moderator ON planina.planina_id=moderator.planina_id WHERE moderator.korisnik_id={$_SESSION['user_id']}";
}

if (isset($_GET["nazivPlanine"])) {
    $mountain = mysqli_real_escape_string($curr, $_GET["nazivPlanine"]);
    $query = $query . " AND (planina.naziv LIKE '%$mountain%')";
}

$query = $query . " ORDER BY datum_vrijeme_slikanja ASC";
$result = executeQuery($curr, $query);
?>
<div class="mountain-wrapper">
    <p>
        <?php
        if ($IS_ADMIN) {
            echo "Sve slike dostupne u galeriji";
        } else {
            echo "Slike u galeriji";
        }
        ?>
    </p>
    <?php
    if ($IS_ADMIN) {
        echo "<div class='mountain-wrapper'>";
        foreach ($result as $mountain) : ?>
            <div class="image-container">
                <object data=<?= $mountain["url"]; ?> type="image/png" class="mountain-image">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Missing-image-232x150.png" fallback alt="" class="mountain-image">
                </object>
                <p class="title-paragraph">Naziv:</p>
                <p>
                    <?= $mountain["naziv"]; ?>
                </p>
                <p class="title-paragraph">Opis:</p>
                <p class="description-wrapper">
                    <?= $mountain["opis"]; ?>
                </p>
                <p class="title-paragraph">Lokacija:</p>
                <p>
                    <?= $mountain["lokacija"]; ?>
                </p>
                <p class="title-paragraph">Autor:</p>
                <p>
                    <?= $mountain["ime"]; ?> <?= $mountain["prezime"]; ?>
                </p>
                <p class="title-paragraph">Status:</p>
                <p>
                    <?php
                    if ($mountain["status"] == 1) {
                        echo "<p>Javno</p>";
                    } else {
                        echo "<p>Privatno</p>";
                    }
                    ?>
                </p>
                <button id="edit" onclick="window.location = 'dodaj_sliku.php?image=<?= $mountain['slika_id'] ?>&mountainid=<?= $mountain['planina_id'] ?>'">
                    Uredi planinu
                </button>
            </div>
    <?php endforeach;
        echo "</div>";
    } ?>
</div>
<?php
closeConnection($curr);
require_once("footer.php");
?>