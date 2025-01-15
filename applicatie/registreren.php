<?php
session_start();

require_once 'db_connectie.php'; // Zorgt ervoor dat er verbinding kan worden gemaakt met de database
require_once 'sanitize.php';

$db = maakVerbinding();
$fouten = [];
$melding = '';

// Controleer of de gebruiker succesvol is geregistreerd en redirect naar de inlogpagina
if (isset($_SESSION['geregistreerd']) && $_SESSION['geregistreerd'] === true) {
    unset($_SESSION['geregistreerd']);
    header("Location: inloggen.php");
    exit();
}

if (isset($_POST['registreren'])) {
    // $inputData[] = SanitizeInput()
    // $fouten[] = ValidateInput($inputData)

    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    $passwordcheck = sanitize($_POST['herhaalPassword']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $address = sanitize($_POST['address']);
    $role = sanitize($_POST['role']);
    // valideer de ingevoerde gegevens
    if (strlen($username) < 4 && isset($username)) {
        $fouten[] = 'Username moet minstens 4 karakters lang zijn.';
    }

    if (strlen($username) > 200) {
        $fouten[] = 'Username mag maximaal 200 karakters lang zijn.';
    }

    if (strlen($password) < 8) {
        $fouten[] = 'Password moet minstens 8 karakters lang zijn.';
    }

    if ($passwordcheck != $password) {
        $fouten[] = 'Passwords komen niet overeen.';
    }

    // check of het wachtwoord minimaal 1 hoofdletter, 1 kleine letter, 1 cijfer en 1 speciaal karakter bevat
    // $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/';

    // // controleer of het wachtwoord voldoet aan de eisen
    // if (!preg_match($passwordPattern, $password)) {
    //     $fouten[] = 'Password moet minstens 1 hoofdletter, 1 kleine letter, 1 cijfer en 1 speciaal karakter bevatten.';
    // }


    if (empty($first_name)) {
        $fouten[] = 'First name is verplicht.';
    }

    if (strlen($first_name) > 30) {
        $fouten[] = 'First name mag maximaal 30 karakters lang zijn.';
    }

    if (empty($last_name)) {
        $fouten[] = 'Last name is verplicht.';
    }

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
                header("Location: Inloggen.php");
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
        <label for="herhaalPassword">Herhaal Password:</label>
        <input type="password" id="herhaalPassword" name="herhaalPassword" required><br><br>
        <label for="first_name">First name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Last name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address"><br><br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
        <option value="personeel">Personeel</option>
        <option value="klant">Klant</option>
        </select><br><br>
        <input type="submit" name="registreren" value="Registreren">
    </form>
    <?php if (!empty($melding)) { echo '<p>' . $melding . '</p>'; } ?>
    </div>

</body>

<footer>
    <div>
        <ul>
            <li>
                <a href="homeMenu.php">Home</a>
            </li>
        </ul>
    </div>
</footer>

</html>
