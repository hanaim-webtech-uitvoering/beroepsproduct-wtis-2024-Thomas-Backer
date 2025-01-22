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

// Haal de producten op uit de POST-aanvraag en sla ze op in de sessie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producten'])) {
    $_SESSION['bestellingen'] = $_POST['producten'];
}

// Haal de bestellingen op uit de sessie
$bestellingen = isset($_SESSION['bestellingen']) ? $_SESSION['bestellingen'] : [];

// Verwijder de bestelling als de verwijderknop is ingedrukt
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verwijder_bestelling'])) {
    unset($_SESSION['bestellingen']);
    $bestellingen = [];
}

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
<h1>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

<h2>Mijn gegevens:</h2>
<ul>
    <li>Voornaam: <?php echo htmlspecialchars($_SESSION['first_name']); ?></li>
    <li>Achternaam: <?php echo htmlspecialchars($_SESSION['last_name']); ?></li>
    <li>Adres: <?php echo htmlspecialchars($_SESSION['address']); ?></li>
    <li>Rol: <?php echo htmlspecialchars($_SESSION['role']); ?></li>
</ul>

<h2>Mijn Bestellingen:</h2>
<ul>
    <?php foreach ($bestellingen as $index => $product) : ?>
        <li>
            <?php echo htmlspecialchars($product['name']); ?> - €<?php echo htmlspecialchars($product['price']); ?>
            <form method="post" action="account.php" style="display:inline;">
                <input type="hidden" name="verwijder_bestelling" value="1">
                <button type="submit">Verwijder Bestelling</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>


<footer>
    <div>
        <a href="HomeMenu.php">Home</a>
        <a href="logout.php">Uitloggen</a>         
    </div>
</footer>
</body>
</html>
