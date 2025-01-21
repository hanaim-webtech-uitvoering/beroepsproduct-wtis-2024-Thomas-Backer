<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: ../PHP/inloggen.php');
    exit();
}

// Haal de gegevens van de gebruiker op uit de database
$query = $db->prepare("SELECT first_name, last_name, address, role FROM [dbo].[User] WHERE username = :username");
$query->bindParam(':username', $_SESSION['username']);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['address'] = $user['address'];
    $_SESSION['role'] = $user['role'];
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

?>