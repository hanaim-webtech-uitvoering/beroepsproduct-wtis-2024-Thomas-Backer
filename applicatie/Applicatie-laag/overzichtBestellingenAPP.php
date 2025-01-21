<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Controleer of de gebruiker een account heeft en is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: ../PHP/inloggen.php');
    exit();
}

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

// Haal het adres van de gebruiker op uit de tabel User
$username = $_SESSION['username'];
$query = $db->prepare("SELECT address FROM [dbo].[User] WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

$address = $user ? $user['address'] : '';

//Pak alle gegevens van de tabel Pizza_Order 
$query = $db->prepare("SELECT status FROM [dbo].[Pizza_Order] WHERE order_id = 1");
$query->execute();
$bestellingen = $query->fetchAll(PDO::FETCH_ASSOC);

?>