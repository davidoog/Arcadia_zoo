<?php
require 'vendor/autoload.php'; // Inclure Composer autoload

// Connexion à MongoDB avec la variable d'environnement
try {
    $mongoUri = getenv('MONGODB_URI');  // Utilisation de la variable d'environnement MONGODB_URI
    $client = new MongoDB\Client($mongoUri);  // Connexion avec l'URI défini dans la variable d'environnement
    $db = $client->Zoo_Arcadia; // Remplace par le nom de ta base
    $collection = $db->Animals_visits; // Nom de ta collection
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}
?>