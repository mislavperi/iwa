<?php 
    require_once("header.php");
    require_once("menu.php");

    if(isset($_SESSION['active_user_id'])){
        if($_SESSION['active_user_type'] == '0') { ?>
        <button onclick="window.location = 'dodaj_moderatora.php'" >
            Dodajte novog moderatora
        </button>
        <?php }
        else {
            header("location:index.php");
            exit();
        }
    } else {
        header("location:index.php");
        exit();
    }

    $curr = establishDbConnection();

    $query = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, planina.naziv, planina.planina_id FROM korisnik LEFT JOIN moderator ON korisnik.korisnik_id=moderator.korisnik_id LEFT JOIN planina ON moderator.planina_id=planina.planina_id WHERE planina.naziv IS NOT NULL ORDER BY planina.naziv ASC";
    $result = executeQuery($curr, $query);
    
?>
    <p>Moderatori</p>

<div style="display: flex; flex-wrap: wrap;" >
    <?php
        foreach($result as $res): ?>
        <div class="image-container">
            <p class="title-paragraph">Naziv planine</p>
            <p><?= $res['naziv'] ?></p>
            <p class="title-paragraph">Ime i prezime</p>
            <p><?= $res['ime'] ?> <?= $res['prezime'] ?></p>
            </div>
        <?php endforeach; ?>
</div>
<?php 
 require_once("footer.php");
 closeConnection($curr);
?>