<?php
require_once("header.php");
require_once("menu.php");

$curr = establishDbConnection();

if (isset($_SESSION["user_id"])) {
    $query = "SELECT blokiran FROM korisnik WHERE korisnik_id={$_SESSION['user_id']}";
    $result = executeQuery($curr, $query);
    list($blocked) = mysqli_fetch_array($result);
}

if (isset($_SESSION["user_id"])) {
    if ($IS_ADMIN_OR_LEADER) {
        if (isset($_GET['surname'])) { ?>
            <button onclick="window.location = 'galerija.php?userid=<?= $_GET['userid'] ?>&surname=<?= $_GET['surname'] ?>&blocked=1'">
                Blokiraj korisnika
            </button>
        <?php } else { ?>
            <button onclick="window.location = 'dodaj_sliku.php'">
                Dodaj novi sliku
            </button>
        <?php }
    } else if ($blocked != '1') { ?>
        <button onclick="window.location = 'dodaj_sliku.php'">
            Dodaj novi sliku
        </button>
<?php }
}
if (isset($_GET["blocked"])) {
    $surname = $_GET['surname'];
    $userid = $_GET['userid'];
    $blocked = $_GET['blocked'];
    $query = "UPDATE korisnik SET blokiran = '$blocked' WHERE korisnik_id='$userid'";
    $result = executeQuery($curr, $query);
    $query = "UPDATE slika SET status = '0' WHERE korisnik_id='$userid'";
    $result = executeQuery($curr, $query);
    header("location: galerija.php");
}
?>
<div class="description">
    <h4>Način korištenja filtera</h4>
    <p>Ukoliko pretražujete po datumu, unesite datum u sljdećem formatu DD.MM.YYYY. (Dan, mjesec, godina)
        <br>
        Ukoliko pretražujtete koristeći slova, unesite slovo ili naziv planine.
    </p>
</div>
<form action="galerija.php" method="GET">
    <table>
        <caption>Filtriranje</caption>
        <tbody>
            <tr>
                <td><label for="mountain">Naziv planine:</label>
                    <input type="text" value="<?php if (isset($_GET['mountain']) && !isset($_GET['reset'])) echo $_GET['mountain']; ?>" name="mountain" id="mountain">
                </td>
                <td><label for="od">Datum od:</label>
                    <input type="text" value="<?php if (isset($_GET['from']) && !isset($_GET['reset'])) echo $_GET['from']; ?>" size="10" id="from" name="from">
                </td>
                <td><label for="do">Datum do:</label>
                    <input type="text" value="<?php if (isset($_GET['to']) && !isset($_GET['reset'])) echo $_GET['to']; ?>" size="10" id="to" name="to">
                </td>
                <td><input type="submit" name="submit" value="Filter"></td>
                <td><input type="submit" name="reset" value="Očisti filter"></td>
            </tr>
        </tbody>
    </table>
</form>
<?php
$query = "SELECT slika.slika_id, slika.korisnik_id, planina.naziv, korisnik.ime, korisnik.prezime, slika.datum_vrijeme_slikanja, slika.url, planina.opis FROM planina, korisnik, slika WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id AND slika.status=1";
if (!isset($_GET['reset'])) {
    if (isset($_GET['mountain'])) {
        $mountain = mysqli_real_escape_string($curr, $_GET['mountain']);
        $query = $query . " AND (planina.naziv like '%$mountain%')";
    }

    if (isset($_GET['from']) && strlen($_GET['from'] > 0)) {
        $from = strtotime($_GET['from']);
        $from = date('Y-m-d H:i:s', $from);
        $query = $query . " AND datum_vrijeme_slikanja > '$from'";
    }
    if (isset($_GET['to']) && strlen($_GET['to'] > 0)) {
        $to = strtotime($_GET['to']);
        $to = date('Y-m-d H:i:s', $to);
        $query = $query . " AND datum_vrijeme_slikanja < '$to'";
    }
    if (isset($_GET['surname'])) {
        $surname = mysqli_real_escape_string($curr, $_GET['surname']);
        $query = $query . " AND (korisnik.prezime like '%$surname%')";
    }
}
$query = $query . " ORDER BY datum_vrijeme_slikanja ASC ";
$result = executeQuery($curr, $query);
?>
    <h2>Galerija slika</h2>

<div class='mountain-wrapper'>
    <?php foreach ($result as $mountain) : ?>
        <div class="image-container">

        <div>
            <object data=<?= $mountain["url"]; ?>  style="cursor: pointer;" type="image/png" class="mountain-image"
            onclick="window.location = 'slika.php?id=<?= $mountain['slika_id'] ?>'"
            >
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Missing-image-232x150.png" fallback alt="" class="mountain-image">
            </object>
        </div>
        <div>
            <p class="title-paragraph">Ime i prezime autora</p>
            <p>
                <?= $mountain["ime"] ?> <?= $mountain["prezime"] ?>
            </p>
            <button
                onclick="window.location = 'galerija.php?userid=<?= $mountain['korisnik_id'] ?>&surname=<?= $mountain['prezime'] ?>'"
            >Filtriraj po korisniku</button>
        </div>
        <div>
            <p class="title-paragraph">Planina</p>
            <p>
                <?= $mountain["naziv"] ?>
            </p>
        </div>

        <div>
            <p class="title-paragraph">Datum i vrijeme slikanja</p>
            <p>
                <?php
                $date = strtotime($mountain["datum_vrijeme_slikanja"]);
                $date = date("d.m.Y. H:i", $date);
                echo "<p> $date </p>"
                ?>
            </p>
        </div>
        </div>

    <?php endforeach ?>
</div>
<?php
closeConnection($curr);
include_once("footer.php");
?>