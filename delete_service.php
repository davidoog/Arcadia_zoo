<?php
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

$id = $_GET['id'];
$pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);

echo json_encode(['status' => 'success']);
?>