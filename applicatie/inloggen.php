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
        header('Location: account.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
  <title>Login</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <h2>Login</h2>
    <div style="width: 50vw; margin:10px">
      <form method="post" action="">
        <label for="username">Gebruikersnaam</label>
        <input type="text" id="username" name="username" placeholder="vul hier je gebruikersnaam in" required>
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" placeholder="vul hier je wachtwoord in" required>
        <input type="submit" name="user_login" id="user_login" value="Inloggen">
      </form>
      <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
      <?php endif; ?>
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
