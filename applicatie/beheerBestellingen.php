<?php

session_start();

require_once 'db_connectie.php';
require_once 'sanitize.php';

$db = maakVerbinding();

// Status updaten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = (int)$_POST['status']; // forceer integer

    $update = $db->prepare("UPDATE [dbo].[Pizza_Order] SET status = :status WHERE order_id = :order_id");
    $update->bindParam(':status', $newStatus, PDO::PARAM_INT);
    $update->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $update->execute();
}



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
<h1>Beheer Bestellingen</h1>

<body>
<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query uitvoeren met INNER JOIN
$query = $db->prepare("SELECT po.*, pop.product_name FROM [dbo].[Pizza_Order] po INNER JOIN [dbo].[Pizza_Order_Product] pop ON po.order_id = pop.order_id ORDER BY po.order_id");
$query->execute();

// Resultaten ophalen
$databaseBestellingen = $query->fetchAll(PDO::FETCH_ASSOC);
$bestellingenGrouped = [];

foreach ($databaseBestellingen as $bestelling) {
    $bestellingenGrouped[$bestelling['order_id']][] = $bestelling;
}

foreach ($bestellingenGrouped as $orderId => $bestellingen) {
    echo "<form method='POST'>";
    echo "<p>";
    echo "Order ID: " . $orderId . "<br>";
    echo "Address: " . $bestellingen[0]['address'] . "<br>";
    
    echo "Producten:<br>";
    foreach ($bestellingen as $bestelling) {
        echo "- " . $bestelling['product_name'] . "<br>";
    }

    echo "Status: ";
    echo "<select name='status'>";
    echo "<option value='1' " . ($bestellingen[0]['status'] == 1 ? 'selected' : '') . ">Afwachten</option>";
    echo "<option value='2' " . ($bestellingen[0]['status'] == 2 ? 'selected' : '') . ">Voorbereiden</option>";
    echo "<option value='3' " . ($bestellingen[0]['status'] == 3 ? 'selected' : '') . ">Verzonden</option>";
    echo "<option value='4' " . ($bestellingen[0]['status'] == 4 ? 'selected' : '') . ">Voltooid</option>";

    echo "<input type='hidden' name='order_id' value='" . $orderId . "'>";
    echo "<button type='submit' name='update_status'>Update Status</button>";
    echo "</p>";
    echo "</form>";
}

?>
</body>

<footer>
    <div>
        <a href="HomeMenu.php">Home</a>
        <a href="overzichtBestellingen.php">Overzicht bestellingen</a>
    </div>
</footer>
</body>
</html>

