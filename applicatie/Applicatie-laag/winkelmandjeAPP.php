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