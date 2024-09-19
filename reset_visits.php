<?php
require 'vendor/autoload.php';

header('Content-Type: application/json');

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->Zoo_Arcadia;
    $animalsCollection = $db->Animals_visits;

    // Réinitialiser le compteur pour tous les animaux
    $result = $animalsCollection->updateMany([], ['$set' => ['count' => 0]]);
    echo json_encode(["status" => "success", "message" => "Tous les compteurs ont été réinitialisés."]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la réinitialisation : " . $e->getMessage()]);
    die();
}
?>