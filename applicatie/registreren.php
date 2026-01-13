<?php

session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();

function sanitizeInput($data) {
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        $sanitizedData[$key] = sanitize($value);
    }
    return $sanitizedData;
}

function validateInput($data) {
    $errors = [];
    if (strlen($data['username']) > 200) {
        $errors[] = 'Gebruikersnaam mag maximaal 200 karakters lang zijn.';
    }
    if (strlen($data['password']) < 8) {
        $errors[] = 'Wachtwoord moet minstens 8 karakters lang zijn.';
    }
    if ($data['password'] !== $data['repeatPassword']) {
        $errors[] = 'Wachtwoorden komen niet overeen.';
    }
    if (empty($data['first_name'])) {
        $errors[] = 'Voornaam is verplicht.';
    }
    if (strlen($data['first_name']) > 30) {
        $errors[] = 'Voornaam mag maximaal 30 karakters lang zijn.';
    }
    if (empty($data['last_name'])) {
        $errors[] = 'Achternaam is verplicht.';
    }
    if (strlen($data['last_name']) > 30) {
        $errors[] = 'Achternaam mag maximaal 30 karakters lang zijn.';
    }
    if (strlen($data['username']) < 4) {
        $errors[] = 'Gebruikersnaam moet minstens 4 karakters lang zijn.';
    }
    return $errors;
}

$fouten = [];
$melding = '';

// Controleer of de gebruiker succesvol is geregistreerd en redirect naar de inlogpagina
if (isset($_SESSION['geregistreerd']) && $_SESSION['geregistreerd'] === true) {
    unset($_SESSION['geregistreerd']);
    header("Location: inloggen.php");
    exit();
}

if (isset($_POST['registreren'])) {
    $inputData = sanitizeInput($_POST);
    $fouten = validateInput($inputData);

    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    $passwordcheck = sanitize($_POST['repeatPassword']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $address = sanitize($_POST['address']);
    $role = sanitize($_POST['role']);

    if (empty($fouten)) {
        // hash het wachtwoord
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // maak de query om de gebruiker toe te voegen
        $sql = 'INSERT INTO [dbo].[User] (username, first_name, last_name, address, role, password)
                                    VALUES (:username, :first_name, :last_name, :address, :role, :password)';
        
        // controleer of de gebruikersnaam al bestaat
        $checkquery = "SELECT COUNT(username) AS COUNT FROM [dbo].[User] WHERE username = :username";

        // prepareer de queries
        $check = $db->prepare($checkquery);
        $query = $db->prepare($sql);

        // voer de checkquery uit
        $check->execute(['username' => $username]);

        // haal het resultaat op
        $result = $check->fetch();
        echo $result['COUNT'];	

        // controleer of de gebruikersnaam al bestaat, voeg de gebruiker toe aan de database of geef een foutmelding
        if ($result['COUNT'] > 0) {
            $melding = 'Gebruikersnaam bestaat al reeds in de database!';
        } else {
            $success = $query->execute([
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'address' => $address,
                'role' => $role,
                'password' => $hash
            ]);

            if ($success) {
                $_SESSION['geregistreerd'] = true;
                $melding = 'Registratie succesvol!';
                header("Location: inloggen.php");
                exit();
            } else {
                $melding = 'Er is een fout opgetreden bij het registreren.';
            }
        }
    } else {
        $melding = "Er waren fouten in de invoer:<ul>";
        foreach ($fouten as $fout) {
            $melding .= '<li>' . $fout . '</li>';
        }
        $melding .= "</ul>";
    }
}

//Als de user al is ingelogd stuur hem dan door naar de account pagina
if (isset($_SESSION['username'])) {
    header('Location: account.php');
    exit();
  }

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
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" ><br><br>
        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="repeatPassword">Herhaal wachtwoord:</label>
        <input type="password" id="repeatPassword" name="repeatPassword" required><br><br>
        <label for="first_name">Voornaam:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Achternaam:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="address">Adres:</label>
        <input type="text" id="address" name="address"><br><br>
        <label for="role">Rol:</label>
        <select id="role" name="role" required>
        <option value="Personnel">Personeel</option>
        <option value="Client">Klant</option>
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
