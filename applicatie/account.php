<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: inloggen.php');
    exit();
}

// Haal de gegevens van de gebruiker op uit de database
$query = $db->prepare("SELECT first_name, last_name, address, role FROM [dbo].[User] WHERE username = :username");
$query->bindParam(':username', $_SESSION['username']);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['address'] = $user['address'];
    $_SESSION['role'] = $user['role'];
}

//Haal de bestellingen van de gebruiker op uit de database

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <title>Account</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <h2>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <p>Mijn gegevens:</p>
    <ul>
        <li>Voornaam: <?php echo htmlspecialchars($_SESSION['first_name']); ?></li>
        <li>Achternaam: <?php echo htmlspecialchars($_SESSION['last_name']); ?></li>
        <li>Adres: <?php echo htmlspecialchars($_SESSION['address']); ?></li>
        <li>Rol: <?php echo htmlspecialchars($_SESSION['role']); ?></li>
    </ul>

    <p>Mijn bestellingen:</p>

<footer>
    <div>
        <a href="HomeMenu.php">Home</a>
        <a href="logout.php">Uitloggen</a>         
    </div>
</footer>
</body>
</html>
