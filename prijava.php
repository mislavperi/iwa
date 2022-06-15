<?php 
    include("header.php");
    $curr = establishDbConnection();
    var_dump($curr);
    die();
    $error = "";
    if(isset($_POST["submit"])) {
        $_CHECK_IF_USERNAME_PASSWORD_SET = isset($_POST["username"]) && isset($_POST["password"]);
        if($_CHECK_IF_USERNAME_PASSWORD_SET) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $sql_query = "SELECT korisnik_id, tip_korisnika_id, ime, prezime FROM korisnik WHERE korisnicko_ime='$username' AND lozinka='$password'";
            $query_result = executeQuery($curr, $sql_query);
            if (mysqli_num_rows($query_result) != 0) {
                list($id, $type, $username) = mysqli_fetch_array($query_result);
                $_SESSION["user_id"] = $id;
                $_SESSION["user_type"] = $type;
                $_SESSION["username"] = $username;
                $_SESSION["given_name"] = $name . " " . $surename;
            }
            
            header("location:index.php");
            exit();
        } else {
            $error = "username i password nisu ispravni";
        }
    }

    if (isset($_GET["signout"])) {
        session_destroy();
    }
?>
<body>
    <form action="prijava.php" method="POST">
        <label for="username">Korisničko ime:</label>
        <input type="text" name="username" id="username">
        <label for="password">Lozinka:</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Prijavi se u sustav" name="submit">
        <div class="alert">
            <?php 
                if (isset($error)) {
                    echo '<p>'.$error.'</p>';
                }
            ?>
        </div>
    </form>
</body>