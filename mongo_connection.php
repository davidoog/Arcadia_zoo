<?php
require 'vendor/autoload.php'; // Inclure Composer autoload
use Dotenv\Dotenv;

// Charger les variables d'environnement depuis le fichier .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connexion à MongoDB avec la variable d'environnement
try {
    $client = new MongoDB\Client(getenv('MONGODB_URI')); // Utiliser MONGODB_URI depuis les variables d'environnement
    $db = $client->Zoo_Arcadia; // Base de données
    $collection = $db->Animals_visits; // Collection
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}
?>