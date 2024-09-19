<?php
require 'db.php';

// Récupération des animaux
$animals = $pdo->prepare("SELECT * FROM animals");
$animals->execute();
$animalList = $animals->fetchAll();

// Gestion de la suppression
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM animals WHERE id=?");
    $stmt->execute([$id]);

    // Rafraîchir la page après suppression
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les animaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des animaux</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Habitat</th>
                    <th>État de santé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animalList as $animal): ?>
                <tr>
                    <td><?php echo $animal['id']; ?></td>
                    <td><?php echo $animal['name']; ?></td>
                    <td><?php echo $animal['race']; ?></td>
                    <td><?php echo $animal['habitat']; ?></td>
                    <td><?php echo $animal['health']; ?></td>
                    <td>
                        <a href="edit_animal.php?id=<?php echo $animal['id']; ?>" class="btn btn-warning">Modifier</a>
                        
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $animal['id']; ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_animal.php" class="btn btn-success">Ajouter un animal</a>
        <a href="admin_dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
    </div>
</body>
</html>