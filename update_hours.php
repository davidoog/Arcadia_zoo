<?php
// Connexion à la base de données
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opening_hour = $_POST['opening_hour'] ?? null;
    $closing_hour = $_POST['closing_hour'] ?? null;

    // Validation simple pour s'assurer que les horaires sont bien reçus
    if ($opening_hour && $closing_hour) {
        try {
            // Mise à jour des horaires dans la base de données
            $stmt = $pdo->prepare("UPDATE zoo_hours SET opening_time = ?, closing_time = ? WHERE id = 1");
            $stmt->execute([$opening_hour, $closing_hour]);

            echo json_encode(["status" => "success", "message" => "Les horaires ont été mis à jour."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Erreur lors de la mise à jour des horaires : " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Heure d'ouverture ou de fermeture manquante."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Méthode non autorisée."]);
}
?>