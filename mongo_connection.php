<?php
require 'vendor/autoload.php'; // Inclure Composer autoload

// Connexion à MongoDB
try {
    $client = new MongoDB\Client("mongodb://admin:147123C1secret!@localhost:27017/?authSource=admin");
    $db = $client->Zoo_Arcadia; // Remplace par le nom de ta base
    $collection = $db->Animals_visits; // Nom de ta collection
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}
?>