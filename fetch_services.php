<?php
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();  
$pdo = $db->getConnection();  // Récupérer l'objet PDO

$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['services' => $services]);
?>