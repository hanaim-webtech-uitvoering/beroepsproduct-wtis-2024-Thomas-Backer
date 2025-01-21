<?php
session_start();
session_destroy();
header('Location: ../PHP/inloggen.php');
exit();
?>
