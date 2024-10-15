<?php
// Connexion à la base de données
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

// Vérifier si l'ID est bien passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparation de la requête SQL pour supprimer l'habitat
    $stmt = $pdo->prepare("DELETE FROM habitats WHERE id = ?");
    $stmt->execute([$id]);

    // Rediriger vers la page de gestion des habitats après suppression
    header('Location: manage_habitats.php');
    exit();
} else {
    // Si l'ID n'est pas défini, renvoyer un message d'erreur ou rediriger vers une page
    echo "ID de l'habitat non défini.";
    exit();
}