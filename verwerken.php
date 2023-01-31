<?php
ob_start();
session_start();
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
        // Als de laatste pagina /6IB/toetsen/login.php is en de gebruiker is niet ingelogd :
        if (($_SESSION['lastpage'] == "/6IB/toetsen/login.php") && (empty($_SESSION["loggedIn"]) == true || $_SESSION["loggedIn"] != true)) {
            // Gebruikersnaam word gedeclareerd voor verder gebruik
            if (isset($_REQUEST["eName"]) === true) {
                $_SESSION["gbr"] = trim($_REQUEST["eName"]);
            } else {
               echo "Volgens ons is er geen gebruikersnaam ingegeven.<br>
               U zal worden herleidt naar de registratie pagina.<br>";
               header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
               exit();
            }
            
            // Paswoord word gedeclareerd voor verder gebruik
            if (isset($_REQUEST["pass"]) === true) {
                $_SESSION["pwd"] = trim($_REQUEST["pass"]);
            } else {
                echo "Volgens ons is er geen paswoord ingegeven.<br>
                U zal worden herleidt naar de registratie pagina.<br>";
                header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                exit();
            }
            // Connectie creëeren
            $conn = new mysqli("localhost", "root", "", "toets_php_mysql"); 
            // Connectie checken
            if ($conn->connect_errno) {
                die("Connectie mislukt: " . $conn->connect_error);
            }
            // Als de gebruiker heeft aangeduid dat die wilt registreren (default optie)
            if ($_REQUEST["registreren"] == "1") {
                // Een insert statement declareren
                $sql = "SELECT Gebruikers_ID FROM werknemer_login WHERE Gebruikers_Naam = ?";
                // Een insert statement voorbereiden
                if ($stmt = $conn->prepare($sql)) {
                    // Variabelen binden aan de voorbereidde insert als parameters
                    $stmt->bind_param("s", $param_username);
                    // Parameters bepalen
                    $param_username = trim($_SESSION["gbr"]);
                    // Proberen de voorbereidde statement uit te voeren
                    if ($stmt->execute()) {
                        // Resultaat bewaren
                        $stmt->store_result();
                        // Als het resultaat al één of meerdere keren voorkomt
                        if ($stmt->num_rows >= 1) {
                            echo "Deze gebruikersnaam is al in gebruik.<br>
                            U zal worden herleidt naar de registratie pagina.<br>";
                            header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                            exit();
                        }
                    } else {
                        echo "Oops! Iets ging mis met het controleren van de gebruikersnaam, u word terug gestuurd.";
                        header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                        exit();
                    } 
                    // Statement sluiten
                    $stmt->close();
                }
                // Zien als het paswoord leeg is of niet
                if (empty($_SESSION["pwd"])) {
                    echo "U heeft geen passwoord ingegeven.<br>
                    U zal worden herleidt naar de registratie pagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                }
                // Zien als er input errors zijn voordat we iets in de database steken
                if (!(empty($_SESSION["pwd"]) && empty($_SESSION["gbr"]))) {   
                    // Een insert statement declareren
                    $sql = "INSERT INTO werknemer_login (Gebruikers_Naam, Gebruikers_Pass) VALUES (?, ?)";
                    // Een insert statement voorbereiden
                    if ($stmt = $conn->prepare($sql)) {
                        // Variabelen binden aan de voorbereidde insert als parameters
                        $stmt->bind_param("ss", $param_username, $param_password);
                        // Parameters declareren
                        $param_username = $_SESSION["gbr"];
                        $param_password = password_hash($_SESSION["pwd"], PASSWORD_DEFAULT); // Creëert een paswoord hash
                        
                        // Proberen de voorbereidde statement uit te voeren
                        if ($stmt->execute()) {
                            echo "Bedankt om te registreren.<br>
                            U zal worden herleidt naar de registratie pagina.<br>";
                            header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                            exit();
                        } else {
                            echo "Oops! Iets ging fout bij het registreren.<br>
                            U zal worden herleidt naar de registratie pagina.<br>";
                            header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                            exit();
                        }  
                        // Statement sluiten
                        $stmt->close();
                    }
                } else {
                    echo "Oops! Ofwel is er geen paswoord, ofwel geen gebruikersnaam ingegeven.<br>
                    U zal worden herleidt naar de registratie pagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                }  
                // Connectie beëindigen
                $conn->close();
            } // Als de gebruiker heeft aangeduid dat die niet wilt registreren
            else if ($_REQUEST["registreren"] == "0") {
                // Een insert statement declareren
                $sql = "SELECT Gebruikers_Pass FROM werknemer_login WHERE Gebruikers_Naam = ?";
                // Een insert statement voorbereiden
                if ($stmt = $conn->prepare($sql)) {
                    // Variabelen binden aan de voorbereidde insert als 'parameters'
                    $stmt->bind_param("s", $param_username);
                    // Parameters bepalen
                    $param_username = trim($_REQUEST["eName"]);
                    // Proberen de voorbereidde statement uit te voeren
                    if ($stmt->execute()) {
                        // Resultaat bewaren
                        $stmt->store_result();
                        // Resultaat binden (voor tijdelijke 'opslag')
                        $stmt->bind_result($result);
                        // Als de 'fetch' lukt, bepalen we de te vergelijken met hash
                        if ($stmt->fetch()){
                           $hash = $result;
                        }
                        // Resultaat vrij laten
                        $stmt->free_result();
                    } else {
                        echo "Oops! Iets ging mis met het controleren van het passwoord, u word terug gestuurd.";
                        header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                        exit();
                    } 
                    // Statement sluiten
                    $stmt->close();
                }
                // De $_REQUEST["pass"] vergelijken we nu met onze hash
                if (password_verify($_REQUEST["pass"], $hash) == true) {
                    echo "Bedankt om in te loggen.<br>";
                    // De gebruiker zijn session word aangeduid als ingelogged
                    $_SESSION["loggedIn"] = true;
                    echo "U zal worden herleidt naar de registratie pagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                } else {
                    echo "Sorry, maar iets ging fout bij de paswoord verificatie.<br>
                    U zal worden herleidt naar de registratie pagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                }
                // Connectie beëindigen
                $conn->close();
            }
        }
        else if ($_SESSION['lastpage'] == "/6IB/toetsen/werknemers_formulier.php") {
            // Connectie creëeren
            $conn = new mysqli("localhost", "root", "", "toets_php_mysql"); 
            // Connectie checken
            if ($conn->connect_errno) {
                die("Connectie mislukt: " . $conn->connect_error);
            }
            // Een insert statement declareren
            $sql = "INSERT INTO werknemers (Naam, Voornaam, Straat, Huisnummer, Loon) VALUES (?, ?, ?, ?, ?)";
            // Een insert statement voorbereiden
            if ($stmt = $conn->prepare($sql)) {
                // Variabelen binden aan de voorbereidde insert als parameters
                $stmt->bind_param("sssss", $param_naam, $param_voornaam, $param_straat, $param_straatNum, $param_loon);
                // Parameters declareren
                $param_naam = $_REQUEST["Naam"];
                $param_voornaam = $_REQUEST["Voornaam"];
                $param_straat = $_REQUEST["Straat"];
                $param_straatNum = $_REQUEST["StraatNum"];
                $param_loon = $_REQUEST["Loon"];
                // Proberen de voorbereidde statement uit te voeren
                if ($stmt->execute()) {
                    echo "Uw formulier is succesvol ingevuld in de database.<br>
                    U zal worden herleidt naar de hoofdpagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                } else {
                    echo "Oops! Iets ging fout bij het invoegen in de database.<br>
                    U zal worden herleidt naar de hoofdpagina.<br>";
                    header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
                    exit();
                }
                // Statement sluiten
                $stmt->close();
            }
            // Connectie beëindigen
            $conn->close();
        }
        else {
            echo "Sorry, maar dit mag niet.<br>";
            header("Refresh: 4; url=/6IB/toetsen/home.php", true, 0);
            exit();
        }
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>