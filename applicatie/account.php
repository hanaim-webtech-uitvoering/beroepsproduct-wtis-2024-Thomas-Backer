<!DOCTYPE html>
<html>
    <head>
        <title>Account</title>
    </head>
    <body>
        <div class="header">
            <h2>Account</h2>
        </div>
        <div class="content">
            <div class="user-info">
                <p>Gebruikersnaam: <?php echo $_SESSION['user']['username']; ?></p>
                <p>Wachtwoord: <?php echo $_SESSION['user']['password']; ?></p>
                <p><a href="logout.php">Uitloggen</a></p>
            </div>
        </div>
    </body>
</html>
<label for="username">Gebruikersnaam:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="firstname">Voornaam:</label>
            <input type="text" id="firstname" name="firstname" required><br><br>
            <label for="lastname">Achternaam:</label>
            <input type="text" id="lastname" name="lastname" required><br><br>
            <label for="role">Rol:</label>
            <select id="role" name="role" required>
                <option value="personeel">Personeel</option>
                <option value="klant">Klant</option>
            </select><br><br>
            <input type="submit" value="Registreren">