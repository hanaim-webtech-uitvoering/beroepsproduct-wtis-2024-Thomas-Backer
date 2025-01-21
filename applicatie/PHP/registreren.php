<?php

require_once '../Applicatie-laag/registrerenAPP.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
</head>
<body>
    <h2>Registreren</h2>
    <div style="width: 50vw; margin:10px">
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="repeatPassword">Repeat Password:</label>
        <input type="password" id="repeatPassword" name="repeatPassword" required><br><br>
        <label for="first_name">First name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Last name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address"><br><br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
        <option value="personnel">Personnel</option>
        <option value="client">Client</option>
        </select><br><br>
        <input type="submit" name="registreren" value="Registreren">
    </form>
    <?php if (!empty($melding)) { echo '<p>' . $melding . '</p>'; } ?>
    </div>

</body>

<footer>
    <div>
        <a href="inloggen.php">Inloggen</a>
        <a href="homeMenu.php">Home</a>
    </div>
</footer>

</html>
