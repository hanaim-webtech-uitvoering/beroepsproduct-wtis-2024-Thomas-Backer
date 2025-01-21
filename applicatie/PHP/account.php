<?php

require_once '../Applicatie-laag/accountAPP.php';
require_once '../Applicatie-laag/logout.php';
require_once '../Applicatie-laag/verwijderProduct.php';

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
        <a href="../Applicatie-laag/logout.php">Uitloggen</a>         
    </div>
</footer>
</body>
</html>
