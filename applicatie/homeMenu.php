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
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if ($username) {
    $_SESSION['loggedin'] = true;
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo '<p>Welkom, ' . htmlspecialchars($username) . '! Bij pizzeria sole machina waar je van alle heerlijke gerechten kunt genieten!</p>';
} else {
    echo '<p>Welkom bij pizzeria sole machina waar je van alle heerlijke gerechten kunt genieten!</p>';
}
    ?>

    <p> Producten:</p>
    <?php
    $query = "SELECT name, price, type_id FROM [dbo].[Product]";
    $result = $db->query($query);
    echo $result->rowCount();

    if ($result->rowCount() > 0) {
        echo '<ul>';
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>' . $row['name'] . ' - €' . $row['price'] . ' ' . $row['type_id'] . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Geen producten gevonden.</p>';
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