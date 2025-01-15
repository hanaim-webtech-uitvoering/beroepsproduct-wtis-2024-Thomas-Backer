<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: inloggen.php');
    exit();
}

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
    <h2>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Je bent ingelogd.</p>
    <a href="logout.php">Uitloggen</a>
    <a href="HomeMenu.php">Home</a>
</body>
</html>
