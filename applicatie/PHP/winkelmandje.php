<?php

require_once '../Applicatie-laag/winkelmandjeAPP.php'; 

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
                <form method="post" action="../Applicatie-laag/verwijderProduct.php" style="display:inline;">
                    <input type="hidden" name="product_index" value="<?php echo $index; ?>">
                    <button type="submit">Verwijder</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <form method="post" action="overzichtBestellingen.php" onsubmit="this.action='account.php';">
        <?php foreach ($producten as $index => $product) : ?>
            <input type="hidden" name="producten[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="producten[<?php echo $index; ?>][price]" value="<?php echo htmlspecialchars($product['price']); ?>">
        <?php endforeach; ?>
        <button type="submit">Bestel</button>
    </form>
    

</body>

<footer>
    <a href="HomeMenu.php">Home</a>
</footer>

</html>