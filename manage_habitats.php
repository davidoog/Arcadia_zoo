<?php
require 'db.php'; // Connexion à la base de données

// Récupérer les habitats depuis la base de données
$stmt = $pdo->query("SELECT * FROM habitats");
$habitats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Habitats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des Habitats</h1>
        <a href="add_habitat.php" class="btn btn-success mb-3">Ajouter un habitat</a>
        <a href="admin_dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($habitats) {
                    foreach ($habitats as $habitat) {
                        echo "<tr>
                            <td>{$habitat['id']}</td>
                            <td>{$habitat['name']}</td>
                            <td>{$habitat['description']}</td>
                            <td>
                                <a href='edit_habitat.php?id={$habitat['id']}' class='btn btn-primary'>Modifier</a>
                                <a href='delete_habitat.php?id={$habitat['id']}' class='btn btn-danger'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun habitat trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>