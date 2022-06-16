<?php
if (isset($_SESSION['user_id'])) {
    if ($_SESSION["user_type"] != "0") {
        header("location:index.php");
        exit();
    }
}

require_once("header.php");
require_once("menu.php");
$curr = establishDbConnection();

$query = "SELECT * FROM korisnik ORDER BY korisnik_id ASC";
$result = executeQuery($curr, $query);

if (isset($_SESSION['user_id'])) {
    if ($_SESSION["user_type"] == 0) { ?>
        <div>
            <button onclick="window.location = 'dodaj_korisnika.php'">
                Dodaj novog korisnika</button>
        </div>

<?php }
}
?>
<div class="mountain-wrapper">
    <?php foreach ($result as $user) : ?>
        <div class="image-wrapper" style="margin: 10px;">
            <object data=<?= $user["slika"]; ?> type="image/png" class="mountain-image" style="width: 100px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Missing-image-232x150.png" fallback alt="" class="mountain-image">
            </object>
            <p class="title-paragraph">Ime i prezime:</p>
            <p>
                <?= $user["ime"]; ?> <?= $user["prezime"]; ?>
            </p>
            <p class="title-paragraph">Email:</p>
            <p class="description-wrapper">
                <?= $user["email"]; ?>
            </p>
            <p class="title-paragraph">Tip korisnika:</p>
            <p>
                <?= $user["tip_korisnika_id"]; ?>
            </p>
            <p class="title-paragraph">Blokiran:</p>
            <p>
                <?= $user["blokiran"]; ?>
            </p>
            <p>
                <button onclick="window.location = 'dodaj_korisnika.php?user=<?= $user['korisnik_id'] ?>'">
                    Uredi
                </button>
            </p>
        </div>
    <?php endforeach ?>
</div>
<?php
closeConnection($curr);
require_once("footer.php")
?>
