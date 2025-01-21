<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Controleer of de gebruiker een personeel is. Zo niet dan wordt de gebruiker geredirect naar de homepagina


// Zet de gegevens van de table [dbo].[Pizza_Order_Product] hierin
$query = $db->prepare("SELECT * FROM [dbo].[Pizza_Order_Product]");
$query->execute();
$bestellingen = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Bestellingen</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<h1>Overzicht Bestellingen</h1>
<ul>
    <?php foreach ($bestellingen as $index => $product) : ?>
        <li>
            <?php echo htmlspecialchars($product['name']); ?> - €<?php echo htmlspecialchars($product['price']); ?>
            <form method="post" action="overzichtBestellingen.php" style="display:inline;">
                <input type="hidden" name="verwijder_bestelling" value="1">
                <button type="submit">Verwijder Bestelling</button>
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
