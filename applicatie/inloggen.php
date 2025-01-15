<?php
require_once 'db_connectie.php';

$db = maakVerbinding();
$notification = '';
$username = '';
$password = '';
$query = '';

function login_user() {
  global $db;
  global $username;
  global $password;

  if (isset($_POST['user_login'])) {
    $username = sanitizen($_POST['usename']);
    $password = sanitizen($_POST['password']);

    $db = maakVerbinding();
    $sql = 'SELECT wachtwoord
            FROM Gebruiker
            WHERE gebruikersnaam = :gebruikersnaam';
    $query = $db->prepare($sql);

    $data_array = [
        ':gebruikersnaam' => $username,
    ];
    // get data from database
    $query->execute($data_array);
    verify_password();
  }
}

function verify_password(){
  global $db;
  global $password;
  global $notification;

  global $query;
  if ($rij = $query->fetch()) {
    $password_hash = $rij['wachtwoord'];
    if ($password == $password_hash) {
        header('location: homeMenu.php');
        $_SESSION['username'] = $username;
        $notification = 'Gebruiker is ingelogd';
    } else {
        $notification = 'Fout: incorrecte inloggegevens';
    }
  }
}

   
?>

<!DOCTYPE html>
<html lang="en">
<title>login</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <header>

    </header>
  
           <form method="post" action="">
           <form method="post" action="homeMenu.php">
            <h1>Login</h1>
            <label for="usename"> Gebruikersnaam</label>
            <input type="text" id = "usename" name = "usename" placeholder = "vul hier je gebruikersnaam in" required>
            <label for="password">Wachtwoord</label>
            <input type="submit" name="user_login" id="user_login" value="Inloggen">
            <input type="submit" name="user_login" id="user_login" value="Inloggen" onclick="location">
            <a href="registrerenLogin.php">Geen account? registreer!</a>
           </form>
        </main> 

        <footer>

        </footer>
    
</body>
</html>