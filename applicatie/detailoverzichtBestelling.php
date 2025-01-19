<?php
session_start();

require_once 'db_connectie.php';
require_once 'sanitize.php';

$db = maakVerbinding();
$melding = '';

// //Haal de bestellingen uit de database
// $query = $db->prepare("SELECT name, price, type_id FROM [dbo].[Product]");
// $query->execute();
// $producten = $query->fetchAll(PDO::FETCH_ASSOC);

// Haal de product-ID op uit de URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : NULL;

if ($product_id > 0) {
    // Haal de productdetails op uit de database
    $query = $db->prepare("SELECT name, price, description FROM [dbo].[Product] WHERE type_id = :type_id");
    $query->bindParam(':type_id', $product_id, PDO::PARAM_INT);
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Toon de productdetails
        echo '<h1>' . htmlspecialchars($product['name']) . '</h1>';
        echo '<p>Prijs: €' . htmlspecialchars($product['price']) . '</p>';

        // Voeg een optie toe om dit product te bestellen
        echo '<form method="post" action="winkelmandje.php">';
        echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
        echo '<input type="submit" value="Bestel dit product">';
        echo '</form>';
    } else {
        echo '<p>Product niet gevonden.</p>';
    }
} else {
    echo '<p>Ongeldig product-ID.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

</head>
    <body>
        <h1>Pizzeria Sole Machina</h1>
        <?php 
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    if ($username) {
        $_SESSION['loggedin'] = true;
    }

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo '<p>Welkom, ' . htmlspecialchars($username) . '! Bekijk hier al onze producten om te bestellen.</p>';
    } else {
        echo '<p>Bekijk hier al onze producten om te bestellen.</p>';
    }
        ?>

    <p> Onze producten:</p>
    <ul>
        <?php foreach ($producten as $product) : ?>
            <li>
                <a href="detailoverzichtBestelling.php; ?>">
                    <?php echo $product['name']; ?> - €<?php echo $product['price']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    </body>

    <footer>   
        <div>
            <a href="homeMenu.php">Home</a>
        </div>
    </footer>
</html>