<?php

require_once '../Applicatie-laag/overzichtBestellingen.php';

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
