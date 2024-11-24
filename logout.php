<?php
session_start();
session_unset();
session_destroy();
header('Location: arcadia_connexion.php');
exit();
?>