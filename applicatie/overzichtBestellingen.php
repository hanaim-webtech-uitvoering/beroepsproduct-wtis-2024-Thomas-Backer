<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

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

// Haal het adres van de gebruiker op uit de tabel User
$username = $_SESSION['username'];
$query = $db->prepare("SELECT address FROM [dbo].[User] WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

$address = $user ? $user['address'] : '';

//Pak alle gegevens van de tabel Pizza_Order 
$query = $db->prepare("SELECT status FROM [dbo].[Pizza_Order] WHERE order_id = 1");
$query->execute();
$bestellingen = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <?php foreach ($bestellingen as $index => $product) : ?>
        
        <p>Besteld door: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p>Status: <?php echo htmlspecialchars($product['status']); ?></p>
        <p>Adres: <?php echo htmlspecialchars($address); ?></p>

        <li>
            <?php
            // Haal de productgegevens op uit de tabel Product
            $productQuery = $db->prepare("SELECT name, price FROM [dbo].[Product]");
            $productQuery->execute();
            $productDetails = $productQuery->fetch(PDO::FETCH_ASSOC);
            ?>

            <?php echo htmlspecialchars($productDetails['name']); ?> - €<?php echo htmlspecialchars($productDetails['price']); ?>
            <form method="post" action="overzichtBestellingen.php" style="display:inline;">
                <?php if ($_SESSION['role'] === 'personeel') : ?>
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
    </div>
</footer>
</body>
</html>
