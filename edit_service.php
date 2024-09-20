<?php
session_start();
require 'db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.html');
    exit();
}

// Récupérer le service à modifier via son ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch();

    if (!$service) {
        header('Location: manage_services.php');
        exit();
    }
} else {
    header('Location: manage_services.php');
    exit();
}

// Mettre à jour le service
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle1 = $_POST['subtitle1'];
    $description1 = $_POST['description1'];
    $subtitle2 = $_POST['subtitle2'];
    $description2 = $_POST['description2'];
    $subtitle3 = $_POST['subtitle3'];
    $description3 = $_POST['description3'];

    // Préparation de la requête SQL
    $stmt = $pdo->prepare("UPDATE services SET title = :title, subtitle1 = :subtitle1, description1 = :description1, subtitle2 = :subtitle2, description2 = :description2, subtitle3 = :subtitle3, description3 = :description3 WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'subtitle1' => $subtitle1,
        'description1' => $description1,
        'subtitle2' => $subtitle2,
        'description2' => $description2,
        'subtitle3' => $subtitle3,
        'description3' => $description3,
        'id' => $id
    ]);

    header('Location: manage_services.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier un service</h1>
        <form action="edit_service.php?id=<?= $id ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre du service</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= isset($service['title']) ? htmlspecialchars($service['title']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="subtitle1" class="form-label">Sous-titre 1</label>
                <input type="text" class="form-control" id="subtitle1" name="subtitle1" value="<?= isset($service['subtitle1']) ? htmlspecialchars($service['subtitle1']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="description1" class="form-label">Description 1</label>
                <textarea class="form-control" id="description1" name="description1" rows="3"><?= isset($service['description1']) ? htmlspecialchars($service['description1']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="subtitle2" class="form-label">Sous-titre 2</label>
                <input type="text" class="form-control" id="subtitle2" name="subtitle2" value="<?= isset($service['subtitle2']) ? htmlspecialchars($service['subtitle2']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="description2" class="form-label">Description 2</label>
                <textarea class="form-control" id="description2" name="description2" rows="3"><?= isset($service['description2']) ? htmlspecialchars($service['description2']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="subtitle3" class="form-label">Sous-titre 3</label>
                <input type="text" class="form-control" id="subtitle3" name="subtitle3" value="<?= isset($service['subtitle3']) ? htmlspecialchars($service['subtitle3']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="description3" class="form-label">Description 3</label>
                <textarea class="form-control" id="description3" name="description3" rows="3"><?= isset($service['description3']) ? htmlspecialchars($service['description3']) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Modifier le service</button>
        </form>
        <a href="manage_services.php" class="btn btn-secondary mt-3">Retour</a>
    </div>
</body>
</html>