<?php

require_once '../Applicatie-laag/inloggenAPP.php';

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
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="vul hier je gebruikersnaam in" required>
        <label for="password">Password</label>
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
      <a href="registreren.php">Registreren</a>
      <a href="homeMenu.php">Home</a>
  </div>
</footer>
</html>
