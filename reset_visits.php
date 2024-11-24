<?php
session_start();
require 'vendor/autoload.php';

// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.php');
    exit();
}

// Vérifier le token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: admin_dashboard.php?reset=error_csrf');
    exit();
}

try {
    // Utiliser l'URI MongoDB de Heroku
    $mongoUri = getenv('MONGODB_URI'); // Récupérer l'URI MongoDB depuis les variables d'environnement
    $client = new MongoDB\Client($mongoUri);  // Connexion à MongoDB avec l'URI de Heroku
    $db = $client->Zoo_Arcadia;  // Sélectionner la base de données Zoo_Arcadia
    $animalsCollection = $db->Animals_visits;  // Sélectionner la collection Animals_visits

    // Réinitialiser le compteur pour tous les animaux
    $animalsCollection->updateMany([], ['$set' => ['count' => 0]]);

    // Rediriger vers admin_dashboard.php avec un message de succès
    header("Location: admin_dashboard.php?reset=success");
    exit();
} catch (Exception $e) {
    header("Location: admin_dashboard.php?reset=error");
    exit();
}
?>