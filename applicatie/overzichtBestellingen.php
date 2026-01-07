<?php

session_start();

require_once 'db_connectie.php';
require_once 'sanitize.php';

$db = maakVerbinding();

// Haal de producten op uit de POST-aanvraag van winkelmandje.php en sla ze op in de sessie
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

// Haal gegevens op alleen als gebruiker is ingelogd
$address = '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (isset($_SESSION['username'])) {
    $query = $db->prepare("SELECT address FROM [dbo].[User] WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $address = $user ? $user['address'] : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <title>Bestellingen</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<h1>Overzicht Bestellingen</h1>
<ul>
    <?php if (!empty($bestellingen)): ?>
        <p>Besteld door: <?php echo htmlspecialchars($username ?: 'Gast'); ?></p>
        <p>Status: <?php echo htmlspecialchars(isset($databaseBestellingen[0]['status']) ? $databaseBestellingen[0]['status'] : '1'); ?></p>
        <p>Adres: 
            <?php if (isset($_SESSION['username']) && !empty($address)): ?>
                <?php echo htmlspecialchars($address); ?>
            <?php else: ?>
                <?php echo htmlspecialchars(isset($_POST['adres']) ? $_POST['adres'] : ''); ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>
    <?php foreach ($bestellingen as $index => $product) : ?>

        <li>
            <?php
            $productName = $product['name'] ?? 'Onbekend product';
            $productPrice = $product['price'] ?? '0.00';
            echo htmlspecialchars($productName) . ' - €' . htmlspecialchars($productPrice);
            ?>
            <form method="post" action="overzichtBestellingen.php" style="display:inline;">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'personnel') : ?>
            <input type="hidden" name="verwijder_bestelling" value="1">
            <button type="submit">Verwijder Bestelling</button>
            <?php endif; ?>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<footer>
    <div>
        <a href="HomeMenu.php">Home</a>
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'personnel' || $_SESSION['role'] === 'Personnel')) : ?>
        <a href="beheerBestellingen.php">Beheer bestellingen</a>
        <?php endif; ?>
    </div>
</footer>
</body>
</html>
