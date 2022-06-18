<?php
require_once("header.php");
require_once("menu.php");

if (isset($_SESSION['user_id'])) {
    if ($_SESSION["user_type"] != "0") {
        header("location:index.php");
        exit();
    }
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
        $id = $_GET['user'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $passwordu = $_POST['loz'];
        $email = $_POST['email'];
        $type = $_POST['utype'];
        $blocked = $_POST['blokiran'];
        $image = $_POST['slika'];
        if ($id == 0) {
            $query = "INSERT INTO korisnik (tip_korisnika_id, korisnicko_ime, lozinka, ime, prezime, email, blokiran, slika) VALUES ('$type', '$username', '$passwordu', '$name', '$surname', '$email', '0', '$image');";
        } else {
            $query = "UPDATE korisnik SET
            tip_korisnika_id = '$type',
            korisnicko_ime = '$username',
            lozinka = '$passwordu',
            ime = '$name',
            prezime = '$surname',
            email = '$email',
            blokiran = '$blocked',
            slika = '$image' WHERE korisnik_id='$id'
            ";
        }
        executeQuery($curr, $query);
        header("location: korisnici.php");
    }
}
if (isset($_GET['user'])) {
    $id = $_GET['user'];
    $query = "SELECT tip_korisnika_id, korisnicko_ime, lozinka, ime, prezime, email, blokiran, slika FROM korisnik WHERE korisnik_id = '$id'";
    $result = executeQuery($curr, $query);
    list($type, $username, $passwordu, $name, $surname, $email, $blocked, $image) = mysqli_fetch_array($result);
} else {
    $name = "";
    $surname = "";
    $username = "";
    $passwordu = "";
    $email = "";
    $type = "";
    $blocked = "";
    $image = "";
}
if (isset($_POST["reset"])) {
    header("location: dodaj_korisnika.php");
}
if (isset($_POST['delete'])) {
    $sql = "DELETE FROM korisnik WHERE korisnik_id='$id'";
    $rezultat = executeQuery($curr, $query);
    header("location: korisnici.php");
}
?>
<form action="<?php if (isset($_GET['user'])) echo "dodaj_korisnika.php?user=$id";
                else echo "dodaj_korisnika.php"; ?>" class="add-wrapper" method="POST">
    <p><?php
        if (isset($_GET['user'])) echo "Uredi informacije o korisniku";
        else echo "Dodaj korisnika";
        ?></p>
    <div>
        <input type="hidden" name="novi" value="<?php if (!empty($id)) echo $id; else echo 0; ?>" />
    </div>
    <div>
        <p><?php if ($error) echo $error;
            ?></p>
    </div>
    <div>
        <label for="name">Ime:</label>
        <input type="text" name="name" id="name" value="<?php if (!isset($_POST['name'])) echo $name; else echo $_POST['name']; ?>" />
    </div>
    <div>
        <label for="surname">Prezime:</label>
        <input type="text" name="surname" id="surname" value="<?php if (!isset($_POST['surname'])) echo $surname; else echo $_POST['surname']; ?>" />
    </div>
    <div>
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" id="username" value="<?php if (!isset($_POST['username'])) echo $username; else echo $_POST['username']; ?>" />
    </div>
    <div>
        <label for="loz">Lozinka:</label>
        <input type="password" name="loz" id="loz" value="<?php if (!isset($_POST['loz'])) echo $passwordu; else echo $_POST['loz']; ?>" />
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php if (!isset($_POST['email'])) echo $email; else echo $_POST['email']; ?>" />
    </div>
    <div>Tip korisnika:
        <select name="utype" id="utype">
            <?php
            if (isset($_POST['utype'])) {
                echo '<option value="0"';
                if ($_POST['utype'] == 0) echo " selected='selected'";
                echo '>Administrator</option>';
                echo '<option value="1"';
                if ($_POST['utype'] == 1) echo " selected='selected'";
                echo '>Voditelj</option>';
                echo '<option value="2"';
                if ($_POST['utype'] == 2) echo " selected='selected'";
                echo '>Korisnik</option>';
            } else {
                echo '<option value="0"';
                if ($type == 0) echo " selected='selected'";
                echo '>Administrator</option>';
                echo '<option value="1"';
                if ($type == 1) echo " selected='selected'";
                echo '>Voditelj</option>';
                echo '<option value="2"';
                if ($type == 2) echo " selected='selected'";
                echo '>Korisnik</option>';
            }
            ?>
        </select>
    </div>
    <?php
    if (isset($_GET['user'])) {
    ?>
        <div>
            <label for="blokiran">Blokiran:</label>
            <select name="blokiran" id="blokiran">
                <?php
                if (isset($_POST['utype'])) {
                    echo '<option value="0"';
                    if ($_POST['blokiran'] == 0) echo " selected='selected'";
                    echo '>Odblokiran</option>';
                    echo '<option value="1"';
                    if ($_POST['blokiran'] == 1) echo " selected='selected'";
                    echo '>Blokiran</option>';
                } else {
                    echo '<option value="0"';
                    if ($blocked == 0) echo " selected='selected'";
                    echo '>Odblokiran</option>';
                    echo '<option value="1"';
                    if ($blocked == 1) echo " selected='selected'";
                    echo '>Blokiran</option>';
                }
                ?>
            </select>
        </div>
    <?php } ?>
    <div><label for="slika">Slika:</label>
        <?php
        $dir = scandir("korisnici");
        echo '<select id="slika" name="slika">';
        foreach ($dir as $key => $value) {
            if ($key < 2) continue;
            else if (strcmp((isset($_POST['slika']) ? $_POST['slika'] : $image), "korisnici/" . $value) == 0) {
                echo '<option value="' . "korisnici/" . $value . '"';
                echo ' selected="selected">' . "korisnici/" . $value;
                echo '</option>';
            } else {
                echo '<option value="' . "korisnici/" . $value . '">';
                echo "korisnici/" . $value;
                echo '</option>';
            }
        }
        echo '</select>';
        ?>
    </div>
    <div>
        <?php
        if (isset($id) && !empty($id)) {
            echo '
                            <input type="submit" name="submit" value="Pošalji"/>
                            <input type="submit" name="delete" value="Obriši korisnika"/>
                            ';
        } else {
            echo '
                                <input type="submit" name="reset" value="Izbriši"/>
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