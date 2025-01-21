<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Zet de gegevens van de table [dbo].[Pizza_Order_Product] hierin
$query = $db->prepare("SELECT * FROM [dbo].[Pizza_Order_Product]");
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
        <?php foreach ($bestellingen as $bestelling) : ?>
            <li>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($bestelling['order_id']); ?></p>
                <p><strong>Product Naam:</strong> <?php echo htmlspecialchars($bestelling['product_name']); ?></p>
                <p><strong>Aantal:</strong> <?php echo htmlspecialchars($bestelling['quantity']); ?></p>
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
