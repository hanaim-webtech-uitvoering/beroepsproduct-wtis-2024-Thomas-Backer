<?php
session_start();
session_destroy();
header('Location: inloggen.php');
exit();
?>
