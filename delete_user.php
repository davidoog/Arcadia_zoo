<?php
session_start();
require_once 'db.php'; // Connexion à la base de données

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: manage_users.php');
exit();
?>