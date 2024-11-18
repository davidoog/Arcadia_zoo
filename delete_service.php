<?php
session_start();
require_once 'db.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Accès non autorisé.']);
    exit();
}

// Récupérer les données envoyées par AJAX
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID du service manquant.']);
    exit();
}

$id = $input['id'];

// Connexion à la base de données
$db = new Database();
$pdo = $db->getConnection();

// Préparer et exécuter la requête SQL pour supprimer le service
$stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Vérifier si un service a été supprimé
if ($stmt->rowCount() > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Service non trouvé ou déjà supprimé.']);
}
?>