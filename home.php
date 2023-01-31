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
        <h3>Hoofdpagina</h3><br>
        Welkom welkom.<br><br>
        Op het moment is hier niet veel te beleven.<br>
        <?php
        echo "Vandaag is het " . date("d/m/Y") . ".<br>";
        ?>
        <?php
        if (!empty($_SESSION["loggedIn"])) {
            echo "U bent op het moment ingelogd.<br><br>";
        } else {
            echo "U bent op het moment niet ingelogd.<br><br>";
        }
        ?>
        Gebruik de menu links hieronder om te navigeren.
        <h3>Menu links</h3>
        <ul>
            <?php
            if (empty($_SESSION["loggedIn"]) == false) { ?>
            <form method="post">
                <li><input name="logout" type="submit" value="Logout" formtarget="_self" onclick="UnsetLogin()"></input>
            </form>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php }
            
            if (!empty($_SESSION["loggedIn"]) == true) { ?>
            <li><a href="werknemers_formulier.php">Formulier</a></li>
            <?php }
            ?>
        </ul>
    </body>
</html>