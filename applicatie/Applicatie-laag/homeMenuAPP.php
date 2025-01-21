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