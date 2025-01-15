<?php 
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();
$melding = '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <div>
        <ul>
            <li>
                <a href="registreren.php">Registreren</a>
            </li>
            <li>
                <a href="inloggen.php">Inloggen</a>
            </li>
            <li>
                <a href="account.php">Account</a>
            </li>
        </ul>
    </div>

</head>
<body>
    <h1>Pizzeria Sole Machina</h1>
    <?php 
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo 'Je bent ingelogd!';
    } else {
        echo 'Welkom bij pizzeria sole machina waar van alle heerlijke gerechten kunt genieten!';
    }
    ?>
</body>
<footer>   
    <div>
        <ul>
            <li>
                <a href="privacyverklaring.html">Privacyverklaring</a>
            </li>
        </ul>
    </div>
</footer>
</html>