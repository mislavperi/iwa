<?php 
    require_once("header.php");
    require_once("menu.php");

    if(!isset($_SESSION['user_id'])){
        header("location:index.php");
        exit();
    }

    $curr = establishDbConnection();

    $query = "SELECT k.ime AS ime, k.prezime AS prezime, COUNT(*) AS broj_slika FROM korisnik k, slika s WHERE status=1 AND k.korisnik_id=s.korisnik_id GROUP BY k.korisnik_id ORDER BY k.prezime";
    $result = executeQuery($curr, $query);


?>
<table border>
    <caption>Statistika javnih slika korisnika u galeriji</caption>
    <thead>
        <tr>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Broj slika u javno dostupnoj galeriji:</th>
        </tr>
    </thead>
    <tbody>
        <?php while(list($name, $surname, $imgnum)=mysqli_fetch_array($result)){
            echo "<tr>
                <td>$name</td>
                <td>$surname</td>
                <td>$imgnum</td>
            </tr>";
        }?>
    </tbody>
</table> <br>
<?php 
     $query = "SELECT k.ime AS ime, k.prezime AS prezime, COUNT(*) AS broj_slika FROM korisnik k, slika s WHERE status=0 AND k.korisnik_id=s.korisnik_id GROUP BY k.korisnik_id ORDER BY k.prezime";
     $result = executeQuery($curr, $query);
?> <br>
<table border>
    <caption>Statistika privatnih slika korisnika</caption>
    <thead>
        <tr>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Broj privatnih slika</th>
        </tr>
    </thead>
    <tbody>
        <?php while(list($name, $surname, $imgnum)=mysqli_fetch_array($result)){
            echo "<tr>
                <td>$name</td>
                <td>$surname</td>
                <td>$imgnum</td>
            </tr>";
        }?>
    </tbody>
</table>
<?php 
require_once("footer.php");
closeConnection($curr); 
?>