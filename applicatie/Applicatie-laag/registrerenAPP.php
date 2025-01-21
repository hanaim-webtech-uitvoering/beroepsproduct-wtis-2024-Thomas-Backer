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
        $errors[] = 'Username mag maximaal 200 karakters lang zijn.';
    }
    if (strlen($data['password']) < 8) {
        $errors[] = 'Password moet minstens 8 karakters lang zijn.';
    }
    if ($data['password'] !== $data['repeatPassword']) {
        $errors[] = 'Passwords komen niet overeen.';
    }
    if (empty($data['first_name'])) {
        $errors[] = 'First name is verplicht.';
    }
    if (strlen($data['first_name']) > 30) {
        $errors[] = 'First name mag maximaal 30 karakters lang zijn.';
    }
    if (empty($data['last_name'])) {
        $errors[] = 'Last name is verplicht.';
    }
    if (strlen($data['last_name']) > 30) {
        $errors[] = 'Last name mag maximaal 30 karakters lang zijn.';
    }
    if (strlen($data['username']) < 4) {
        $errors[] = 'Username moet minstens 4 karakters lang zijn.';
    }
    return $errors;
}

$fouten = [];
$melding = '';

// Controleer of de gebruiker succesvol is geregistreerd en redirect naar de inlogpagina
if (isset($_SESSION['geregistreerd']) && $_SESSION['geregistreerd'] === true) {
    unset($_SESSION['geregistreerd']);
    header("Location: ../PHP/inloggen.php");
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
                header("Location: ../PHP/inloggen.php");
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
    header('Location: ../PHP/account.php');
    exit();
  }

?>