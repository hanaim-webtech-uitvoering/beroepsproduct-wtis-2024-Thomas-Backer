<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Controleer of het winkelmandje bestaat, zo niet, maak het aan
if (!isset($_SESSION['winkelmandje'])) {
    $_SESSION['winkelmandje'] = [];
}

//Haal de bestellingen uit de database
$query = $db->prepare("SELECT name, price, type_id FROM [dbo].[Product]");
$query->execute();
$producten = $query->fetchAll(PDO::FETCH_ASSOC);

// //Haal de name, price en type_id op uit de homeMenu.php pagina en zorg dat alleen de producten die je hebt besteld in het winkelmandje komen te staan
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
//     $product_id = sanitize($_POST['product_id']);
//     $query = $db->prepare("SELECT name, price, type_id FROM [dbo].[Product] WHERE type_id = :type_id");
//     $query->bindParam(':type_id', $product_id, PDO::PARAM_INT);
//     $query->execute();
//     $product = $query->fetch(PDO::FETCH_ASSOC);

//     if ($product) {
//         echo '<ul>';
//         echo '<li><h1>' . htmlspecialchars($product['name']) . '</h1>';
//         echo '<p>Prijs: €' . htmlspecialchars($product['price']) . '</p></li>';
//         echo '</ul>';
//     } else {
//         echo '<p>Product niet gevonden.</p>';
//     }
// } else {
//     echo '<p>Ongeldig product-ID.</p>';
// }

//Hier
//             //Haal de name, price en type_id op uit de homeMenu.php pagina en zorg dat alleen de producten die je hebt besteld in het winkelmandje komen te staan
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
//     $product_id = sanitize($_POST['product_id']);
//     $query = $db->prepare("SELECT name, price, type_id FROM [dbo].[Product] WHERE type_id = :type_id");
//     $query->bindParam(':type_id', $product_id, PDO::PARAM_INT);
//     $query->execute();
//     $product = $query->fetch(PDO::FETCH_ASSOC);

//     if ($product) {
//         echo '<ul>';
//         echo '<li><h1>' . htmlspecialchars($product['name']) . '</h1>';
//         echo '<h2>Prijs: €' . htmlspecialchars($product['price']) . '</h2>';
//         echo '<h2>Type: ' . htmlspecialchars($product['type_id']) . '</h2></li>';
//         echo '</ul>';
//     } else {
//         echo '<p>Product niet gevonden.</p>';
//     }
// } else {
//     echo '<p>Ongeldig product-ID.</p>';
// }

// if (isset($product_id)) {
//     echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product_id) . '">';
//     echo '<label for="quantity">Aantal:</label>';
//     echo '<input type="number" id="quantity" name="quantity" min="1" max="9" value="1">';
//     echo '</form>';
// }

// if (isset($_SESSION['user_id'])) {
//     echo '<form method="POST" action="bestellingoverzichtPersoneel.php">';
//     echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product_id) . '">';
//     echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($_SESSION['user_id']) . '">';
//     echo '<button type="submit">Bestel</button>';
//     echo '</form>';
// } else {
//     echo '<p>Je moet ingelogd zijn om een bestelling te plaatsen. <a href="inloggen.php">Log in</a></p>';
// }

// Verwerk de POST-aanvraag om een product toe te voegen aan het winkelmandje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = sanitize($_POST['product_id']);
    $product_name = sanitize($_POST['product_name']);
    $product_price = sanitize($_POST['product_price']);

    // Voeg het product toe aan het winkelmandje
    $_SESSION['winkelmandje'][] = [
        'name' => $product_name,
        'price' => $product_price
    ];
}

// Haal de producten uit het winkelmandje op
$producten = $_SESSION['winkelmandje'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
</head>
<body>
    
<?php 
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    if ($username) {
        $_SESSION['loggedin'] = true;
    }

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo '<h1>Winkelmandje van: ' . htmlspecialchars($username) . '</h1>';
    } else {
        echo '<h1>Winkelmandje</h1>';
    }
        ?>

    <p> Hier komen alle producten die je in het winkelmandje hebt gezet:</p>
    
    <ul>     
        <?php foreach ($producten as $index => $product) : ?>
            <li>
                <?php echo htmlspecialchars($product['name']); ?> - €<?php echo htmlspecialchars($product['price']); ?>
                <form method="post" action="verwijderProduct.php" style="display:inline;">
                    <input type="hidden" name="product_index" value="<?php echo $index; ?>">
                    <button type="submit">Verwijder</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

<footer>
    <a href="HomeMenu.php">Home</a>
</footer>

</html>