<?php
// Vérifie si une session est déjà active avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers nécessaires
require 'db.php'; 
?>