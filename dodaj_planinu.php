<?php
require_once("header.php");
require_once("menu.php");

if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
if ($_SESSION['user_type'] == '1') {
    header("location: index.php");
    exit();
}

$curr = establishDbConnection();
?>
<?php
$error = "";
if (isset($_POST['submit'])) {
    foreach ($_POST as $key => $value) {
        if (strlen($value) == 0) {
            $error = "Sva polja za unos su obavezna";
        }
    }
    if (empty($error)) {
        $id = $_GET['mountainid'];
        $title = $_POST['naziv'];
        $description = $_POST['opis'];
        $location = $_POST['lokacija'];
        $ggs = $_POST['ggs'];
        $ggd = $_POST['ggd'];
        $idKorisnika = $_POST['moderator'];
        if ($id == 0) {
            $query = "INSERT INTO planina (naziv, opis, lokacija, geografska_sirina, geografska_duzina) VALUES ('$title', '$description', '$location', '$ggs', '$ggd');";
        } else {
            $query .= "UPDATE planina SET naziv = '$title', opis = '$description', lokacija = '$location',geografska_sirina = '$ggs',geografska_duzina = '$ggd'WHERE planina_id='$id';";
        }
        executeQuery($curr, $query);
        header("Location:popis_planina.php");
    }
}
if (isset($_GET['mountain'])) {
    $id = $_GET['mountain'];
    $query = "SELECT naziv, opis, lokacija, geografska_sirina, geografska_duzina FROM planina WHERE planina_id='$id'";
    $result = executeQuery($curr, $query);
    list($title, $description, $location, $ggs, $ggd) = mysqli_fetch_array($result);
} else {
    $title = "";
    $location = "";
    $description = "";
    $ggs = "";
    $ggd = "";
    $idKorisnika = "";
}
if (isset($_POST["reset"])) {
    header("location: dodaj_planinu.php");
}

if (isset($_POST['delete'])) {
    $query = "DELETE FROM planina WHERE planina_id='$id'";
    $result = executeQuery($curr, $query);
    header("location: popis_planina.php");
}

?>
<form action="<?php if (isset($_GET['mountain'])) echo "dodaj_planinu.php?mountainid=$id";
                else echo "dodaj_planinu.php"; ?>" method="POST" class="add-wrapper">
    <div>
        <?php
        if (isset($_GET['planina'])) echo "Uredi informacije o planini";
        else echo "Dodaj planinu";
        ?>
    </div>
    <div>
        <input type="hidden" name="nova" value="<?php if (!empty($id)) echo $id;
                                                else echo 0; ?>" />
    </div>
    <div>
        <p><?php if ($error) echo $error;
            ?></p>
    </div>
    <div>
        <label for="naziv">Naziv planine:</label>
        <input type="text" name="naziv" id="naziv" value="<?php if (!isset($_POST['naziv'])) echo $title;
                                                            else echo $_POST['naziv']; ?>">
    </div>
    <div>
        <label for="Opis">Opis:</label>
        <input type="text" name="opis" id="opis" value="<?php if (!isset($_POST['opis'])) echo $description;
                                                        else echo $_POST['opis']; ?>" />
    </div>
    <div>
        <label for="lokacija">Lokacija:</label>
        <input type="text" name="lokacija" id="lokacija" value="<?php if (!isset($_POST['lokacija'])) echo $location;
                                                                else echo $_POST['lokacija']; ?>" />
    </div>
    <div>
        <label for="ggs">Geografska širina:</label>
        <input type="number" name="ggs" id="ggs" step="any" placeholder="Unesite u formatu 00.00" value="<?php if (!isset($_POST['ggs'])) echo $ggs;
                                                                                                            else echo $_POST['ggs']; ?>" />

    </div>
    <div>
        <label for="ggd">Geogradksa dužina:</label>
        <input type="number" name="ggd" id="ggd" step="any" placeholder="Unesite u formatu 00.00" value="<?php if (!isset($_POST['ggd'])) echo $ggd;
                                                                                                            else echo $_POST['ggd']; ?>" />

    </div>
    <div>
        <?php
        if (isset($id) && !empty($id)) {
            echo '
                            <input type="submit" name="submit" value="Pošalji"/>
                            <input type="submit" name="delete" value="Obriši planinu"/>
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
<?php
closeConnection($curr);
include_once("footer.php");
?>