<?php
include_once("header.php");
include_once("menu.php");
$curr = establishDbConnection();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == '0') {
        echo "
        <a href=\"moderatori.php\">Popis moderatora</a>";
    } else {
        header("location:index.php");
        exit();
    }
} else {
    header("location:index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $mountainid = $_GET['mountainid'];
    $mountain = $_POST['mountain'];
    $moderators = $_POST['moderators'];
    foreach ($moderators as $moderator) {
        $query = "INSERT IGNORE INTO moderator (korisnik_id, planina_id) VALUES ('$moderators', '$mountain')";
        executeQuery($curr, $query);
        $query = "UPDATE korisnik SET tip_korisnika_id='1' WHERE korisnik_id = '$moderators'";
        executeQuery($curr, $query);
    }
    header("location:moderatori.php");
}

if (isset($_POST['reset'])) {
    header("location:dodaj_moderatora.php");
}

?>
<form action="<?php if (isset($_GET['mountainid'])) echo "dodaj_moderatora.php?mountainid=$mountainid&mountain=$mountain&moderator=$userid";
                else echo "dodaj_moderatora.php"; ?>" method="POST">
    <div>
        <?php
        if (isset($_GET['mountainid'])) echo "Uredite moderatore za planinu: {$_GET['nazivPlanine']}";
        else echo "Dodajte moderatora";
        ?>
    </div>
    <div>
        <label for="mountain">Planina:</label>
        <select name="mountain" id="mountain">
            <?php
            $query = "SELECT planina.planina_id, planina.naziv FROM planina";
            $result = executeQuery($curr, $query);
            foreach ($result as $res) {
                if (isset($_GET["mountain"])) {
                    if ($_GET['mountainid'] == $res["planina_id"]) { ?>
                        <option value="<?= $_GET['mountainid'] ?>" selected="selected"><?= $_GET['mountainid'] ?></option>
                    <?php } else { ?>
                        <option value=<?= $res["planina_id"] ?>><?= $res["naziv"] ?></option>
            <?php }
                } else { ?>
                    <option value=<?= $res["planina_id"] ?>><?= $res["naziv"] ?></option>
        <?php }
            }
            ?>
        </select>
    </div>
    <div>
        <label for="moderators">Moderatori:</label>
        <div style="display: flex; flex-direction: column;">
            <?php
            $query = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, moderator.planina_id FROM korisnik LEFT JOIN moderator ON korisnik.korisnik_id = moderator.korisnik_id";
            $result = executeQuery($curr, $query);

            foreach ($result as $res) {
                if (isset($_GET['mountainid'])) {
                    if ($_GET['mountainid'] == $res['planina_id']) { ?>
                    <div style="display: flex; align-items:center; margin: 5px;">
                            <input type="checkbox" name="moderators"  style="width: 30px;" value="<?= $res['korisnik_id'] ?>" checked><label for="moderators"><?= $res['ime'] ?> <?= $res['prezime'] ?></label>

                        </div>
                    <?php  } else { ?>
                        <div style="display: flex; align-items:center; margin: 5px;">
                            <input type="checkbox" name="moderators"  style="width: 30px;" value="<?= $res['korisnik_id'] ?>">
                            <label for="moderators"><?= $res['ime'] ?> <?= $res['prezime'] ?></label>
                        </div>
                    <?php }
                } else { ?>
                    <div style="display: flex; align-items:center; margin: 5px;">
                        <input type="checkbox" name="moderators" style="width: 30px;" value="<?= $res['korisnik_id'] ?>">
                        <label for="moderators"><?= $res['ime'] ?> <?= $res['prezime'] ?></label>

                    </div>
            <?php }
            }
            ?>
        </div>

    </div>
    <div>
        <?php
        if (isset($_GET['mountain']) && !empty($_GET['mountain'])) {
            echo '
                            <input type="submit" name="submit" value="Pošalji"/>
                            ';
        } else {
            echo '
                                <input type="submit" name="reset" value="Očisti odabir"/>
                                <input type="submit" name="submit" value="Pošalji"/>
                            ';
        }
        ?>
    </div>

</form>