<?php 

$melding = '';
require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
$db = maakVerbinding();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <div>
        <ul>
            <li>
                <a href="registreren.php">Inloggen</a>
            </li>
        </ul>
    </div>

</head>
<body>
    <h1>Pizzeria Sole Machina</h1>
    <?php echo('Welkom bij pizzeria sole machina waar van alle heerlijke gerechten kunt genieten!'); ?>
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