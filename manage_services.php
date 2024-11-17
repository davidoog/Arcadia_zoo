<?php
session_start();
// Connexion à la base de données MySQL uniquement
require_once 'db.php';
require 'init.php';

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

// Vérification du rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.html');
    exit();
}

// Récupération des services depuis la base de données
try {
    $services = $pdo->query("SELECT * FROM services")->fetchAll();
} catch (Exception $e) {
    echo "Erreur lors de la récupération des services : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Gestion des services</h1>
        
        <a href="add_service.php" class="btn btn-success mb-3">Ajouter un nouveau service</a>
        <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Retour au tableau de bord</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="servicesTable">
                <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= htmlspecialchars($service['title']); ?></td>
                    <td>
                        <?= isset($service['description1']) ? htmlspecialchars($service['description1']) : 'Pas de description'; ?>
                    </td>
                    <td>
                        <a href="edit_service.php?id=<?= $service['id']; ?>" class="btn btn-warning">Modifier</a>
                        <a href="delete_service.php?id=<?= $service['id']; ?>" class="btn btn-danger">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
<script src="manage_services.js"></script>
</html>