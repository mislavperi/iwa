<?php
require_once("header.php");
require_once("menu.php");

if (sizeof($_SESSION) == 0) {
    header("location:index.php");
    exit();
}

$curr = establishDbConnection();
?>

<?php
$error = "";
$query = "";
if (isset($_POST["submit"])) {
    foreach ($_POST as $k => $v) {
        if (strlen($v) == 0) {
            $error = "Sva polja su obavezna";
        }
    }
    if ($error == "") {
        $id = $_GET['image'];
        $mountainid = $_POST['mountainid'];
        $userid = $_SESSION['user_id'];
        $title = $_POST['title'];
        $url = $_POST['url'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $date = strtotime($date);
        $date = date("Y-m-d H:i:s", $date);
        $status = $_POST['status'];
        if ($id == 0) {
            $query = "INSERT INTO slika (planina_id, korisnik_id, naziv, url, opis, datum_vrijeme_slikanja, status) VALUES ('$mountainid', '$userid', '$title', '$url', '$description', '$date', '1');";
        } else {
            $query = "UPDATE slika SET 
            planina_id = '$mountainid',
            korisnik_id = '$userid',
            naziv = '$title',
            url = '$url',
            opis = '$description',
            datum_vrijeme_slikanja = '$date',
            status = '$status' WHERE slika_id='$id'
            ";
        }
        executeQuery($curr, $query);
        header("location:galerija.php");
    }
}
if(isset($_GET['image'])){
    $id = $_GET['image'];
    $query = "SELECT planina_id, korisnik_id, naziv, url, datum_vrijeme_slikanja, opis, status FROM slika WHERE slika_id='$id'";
    $result = executeQuery($curr, $query);
    list($mountainid, $userid, $title, $url, $date, $description, $status)=mysqli_fetch_array($result);
    $date = strtotime($date);
    $date = date("d.m.Y. H:i:s", $date);
}
else {
    $title = "";
    $url = "";
    $date = "";
    $description = "";
    $mountainid = "";
}
?>
<form action="<?php if (isset($_GET['image'])) echo "dodaj_sliku.php?image=$id&mountainid=$mountainid";
                else echo "dodaj_sliku.php"; ?>" class="add-wrapper" method="POST">
    <p>
        <?php
        if (isset($_GET["image"])) echo "Uredi informacije o slici";
        else echo "Dodaj novu sliku";
        ?>
    </p>
    <div>
        <input type="hidden" name="id" value=<?php if (empty($id)) echo 0;
                                                else echo $id; ?>>
    </div>
    <div>
        <p><?php if ($error) echo $error;
            ?></p>
    </div>
    <div>
        <label for="title">Naziv slike:</label>
        <input type="text" name="title" id="title" value=<?php if ($title) echo $title;
                                                            else echo $_POST["title"]; ?>>
    </div>
    <div>
        <label for="url">Link slike:</label>
        <input type="url" name="url" id="url" value=<?php if ($url) echo $url;
                                                    else echo $_POST["url"]; ?>>
    </div>
    <div>
        <label for="date">Datum i vrijeme slikanja:</label>
        <input type="text" name="date" id="date" value="<?php if (!isset($_POST['date'])) echo $date;
                                                        else echo $_POST['date'];
                                                        $date = strtotime($date);
                                                        $date = date("Y-m-d H:i:s", $date);
                                                        ?>">
    </div>
    <div>
        <label for="description">Opis slike:</label>
        <input type="text" name="description" id="description" size="120" value="<?php if(!isset($_POST['description']))echo $description; else echo $_POST['description']; ?>">
    </div>
    <div>
        <label for="mountainid">Naziv planine:</label>

        <select name="mountainid" id="mountainid">
            <?php
            $query = "SELECT planina_id, naziv FROM planina";
            $result = executeQuery($curr, $query);
            foreach ($result as $mountaintype) : ?>
                <?php
                if (isset($_GET["mountainid"])) {
                    if ($_GET['mountainid'] == $mountaintype["planina_id"]);
                    else ?>
                    <option value=<?= $mountaintype["planina_id"]; ?>><?= $mountaintype["naziv"]; ?></option>

                <?php } else ?>
                <option value=<?= $mountaintype["planina_id"]; ?>><?= $mountaintype["naziv"]; ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <?php if (isset($id) && $blocked != '1') { ?>
        <div>
            <label for="status">Status:</label>
            <input type="number" name="status" id="status" min="0" max="1" value="<?php if (!isset($_POST['status'])) echo $status;
                                                                                    else echo $_POST['status']; ?>">
        </div>

    <?php } ?>
    <div>
        <?php
        if (isset($id) && $_SESSION['user_id'] == $userid || !empty($id)) {
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