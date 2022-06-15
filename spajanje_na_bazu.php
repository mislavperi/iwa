<?php
    define("POSLUZITELJ","localhost");
    define("BAZA","iwa_2020_vz_projekt");
    define("BAZA_KORISNIK","iwa_2020");
    define("BAZA_LOZINKA","foi2020");

    function establishDbConnection() {
        $curr = new mysqli(POSLUZITELJ, BAZA_KORISNIK, BAZA_LOZINKA, BAZA);

        if ($curr->connect_errno) {
            var_dump($curr->connect_errno);
        }
        return $curr;
    }

    function executeQuery($curr, $query) {
        $response = $curr->query($query);
        if ($response) {
            return $response;
        }
        echo "Greška tijekom dohvaćanja podataka iz baze". $response;
    }

    function closeConnection($curr) {
        $curr->close();
    }
?>  