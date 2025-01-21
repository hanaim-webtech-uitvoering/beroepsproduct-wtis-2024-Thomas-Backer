<?php 
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();
$melding = '';

//Haal de bestellingen uit de database
$query = $db->prepare("SELECT name, price, type_id FROM [dbo].[Product]");
$query->execute();
$producten = $query->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <div>
        <ul>
            <li>
                <a href="registreren.php">Registreren</a>
            </li>
            <li>
                <a href="inloggen.php">Inloggen</a>
            </li>
            <li>
                <a href="account.php">Account</a>
            </li>
            <li>
                <a href="winkelmandje.php">Winkelmandje</a>
            </li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'personeel') : ?>
                        <li>
                            <a href="overzichtBestellingen.php">Bestellingen</a>
                        </li>
            <?php endif; ?>
        </ul>
    </div>

</head>
    <body>
        <h1>Pizzeria Sole Machina</h1>
        <?php 
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    if ($username) {
        $_SESSION['loggedin'] = true;
    }

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo '<p>Welkom, ' . htmlspecialchars($username) . '! Bij pizzeria sole machina waar je van alle heerlijke gerechten kunt genieten!</p>';
    } else {
        echo '<p>Welkom bij pizzeria sole machina waar je van alle heerlijke gerechten kunt genieten!</p>';
    }
        ?>

    <p> Onze producten:</p>
    <ul>
        <?php foreach ($producten as $product) : ?>
            <li>
            <?php echo htmlspecialchars($product['name']); ?> - €<?php echo htmlspecialchars($product['price']); ?>
            <form action="winkelmandje.php" method="post" style="display:inline;">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['type_id']); ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
            <button type="submit">Toevoegen aan winkelmandje</button>
            </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <footer>   
        <div>
            <a href="privacyverklaring.html">Privacyverklaring</a>
        </div>
    </footer>
    </body>
</html>