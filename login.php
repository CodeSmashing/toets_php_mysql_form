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
        if ((empty($_POST["optieInlog"]) == true) || ($_POST["optieInlog"] == "2")) { ?>
           <h2>Registratie</h2>
           <?php
        } else { ?>
           <h2>Login</h2>
           <?php
        }
        ?>
        <?php
        if ((empty($_POST["optieInlog"]) == true) || ($_POST["optieInlog"] == "2")) {
            ?>
            <form action="verwerken.php" method="post">
                <div>
                    <div>
                        Welkom tot de registratie pagina, hier kunt u zich een account registreren.<br><br>
                        Gebruikersnaam :<br>
                        <input type="text" name="eName" pattern="[A-z0-9À-ž\s]{2,}" title="Drie of meer characters" required><br>
                    </div>
                </div>
                <div>
                    <div>
                        Paswoord :<br>
                        <input type="password" name="pass" pattern=".{8,}" title="Acht of meer characters" required><br>
                    </div>
                </div>
                <div>
                    <button name="registreren" type="submit" value="1">Registreren</button><br><br>
                </div>
            </form>
            <form method="post">
                <div>
                    Bent u al een gebruiker?<br>
                    <button name="optieInlog" type="submit" value="1" formtarget="_self">Login</button>
                </div>
            </form>
        <?php } else if ($_POST["optieInlog"] == "1") {
            ?>
            <form action="verwerken.php" method="post">
                <div>
                    Welkom tot de inlog pagina, hier kunt u zich inloggen.<br><br>
                    Gebruikersnaam :<br>
                    <input type="text" name="eName" pattern="[A-z0-9À-ž\s]{2,}" title="Drie of meer characters" required><br>
                </div>
                <div>
                    Paswoord :<br>
                    <input type="password" name="pass" pattern=".{8,}" title="Acht of meer characters" required><br><br>
                </div>
                <div>
                    <button name="registreren" type="submit" value="0">Inloggen</button><br><br>
                </div>
            </form>
            <form method="post">
                <div>
                    Heeft u nog geen account?<br>
                    <button name="optieInlog" type="submit" value="2" formtarget="_self">Registreer</button>
                </div>
            </form>
        <?php }
        ?>
        <h3>Menu links</h3>
        <ul>
            <li><a href="home.php">Home</a></li>
            <?php
            if (!empty($_SESSION["loggedIn"]) == true) { ?>
            <li><a href="werknemers_formulier.php">Formulier</a></li>
            <?php }
            if (empty($_SESSION["loggedIn"]) == false) { ?>
            <form method="post">
                <li><input name="logout" type="submit" value="Logout" formtarget="_self" onclick="UnsetLogin()"></input>
            </form>
            <?php }
            ?>
        </ul>
    </body>
</html>