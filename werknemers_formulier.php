<?php
session_start();
$_SESSION["lastpage"] = $_SERVER["REQUEST_URI"];
function UnsetLogin()
{
   unset($_SESSION["loggedIn"]); 
}
if (empty($_POST["logout"]) != true) {
   UnsetLogin();
   $_POST["logout"] = "";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- mobile metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <!-- site metas -->
        <title>Werknemers</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="">
    </head>
    <!-- body -->
    <body>
        <?php
        if (empty($_SESSION["loggedIn"]) == true || $_SESSION["loggedIn"] != true) {
            echo "U moet u eerst inloggen om op deze pagina mogen.<br>";
        } else { ?>
            <h3>Welkom tot de werknemers formulier!<br></h3>
            <h4>U vult hier enkele gevens in voor het testen van de bedrijf's database.<br><br></h4>

            <form action="verwerken.php" method="post">
                <fieldset>
                    <legend>
                        Persoonlijke info
                    </legend><br>
                    Naam* :<br>
                    <input placeholder="Uw Naam" type="text" name="Naam" required><br><br>

                    Voornaam* :<br>
                    <input placeholder="Uw Voornaam" type="text" name="Voornaam" required><br><br>

                    Straat :<br>
                    <input placeholder="Straatnaam" type="text" name="Straat"><br><br>

                    Huisnummer :<br>
                    <input placeholder="Huisnummer" type="text" name="StraatNum"><br><br>

                    Loon* (in euro):<br>
                    <input placeholder="200" type="number" name="Loon" required><br><br>

                    <button name="formulier" type="submit">Verzenden</button>
                </fieldset><br>
            </form>
        <?php }
        ?>
        <h3>Menu links</h3>
        <ul>
            <li><a href="home.php">Home</a></li>
            <?php
            if (empty($_SESSION["loggedIn"]) == false) { ?>
            <form method="post">
                <li><input name="logout" type="submit" value="Logout" formtarget="_self" onclick="UnsetLogin()"></input>
            </form>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </body>
</html>