<?php
session_start();

require_once 'db_connectie.php';
require_once 'sanitize.php';

$db = maakVerbinding();
$username = '';
$password = '';
$error_message = '';

function login_user() {
  global $db, $username, $password, $error_message;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_login'])) {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    $sql = 'SELECT password FROM [dbo].[User] WHERE username = :username';
    $query = $db->prepare($sql);

    $data_array = [':username' => $username];
    $query->execute($data_array); // Voer de query uit met de gegevens

    if ($row = $query->fetch()) {
      $password_hash = $row['password'];
      if (password_verify($password, $password_hash)) {
        $_SESSION['username'] = $username;
        header('Location: ../PHP/account.php');
        exit();
      } else {
        $error_message = 'Incorrecte gebruikersnaam of wachtwoord';
      }
    } else {
      $error_message = 'Incorrecte gebruikersnaam of wachtwoord';
    }
  }
}

login_user();

// Als de user al is ingelogd stuur hem dan door naar de account pagina
if (isset($_SESSION['username'])) {
  header('Location: ../PHP/account.php');
  exit();
}

?>