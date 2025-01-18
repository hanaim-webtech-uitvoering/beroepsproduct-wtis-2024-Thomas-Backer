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
    <p>Mijn bestellingen:</p>

<footer>
    <div>
        <ul>
            <li>
                <a href="logout.php">Uitloggen</a>
            </li>
            <li>
                <a href="HomeMenu.php">Home</a>
            </li>
        </ul>
    </div>
</footer>
</body>
</html>
