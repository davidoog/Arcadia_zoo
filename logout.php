<?php
session_start();
session_unset();
session_destroy();
header('Location: arcadia_connexion.html');
exit();
?>