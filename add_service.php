<?php
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();  
$pdo = $db->getConnection();  // Récupérer l'objet PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $title = $_POST['title'] ?? null;
    $subtitle1 = $_POST['subtitle1'] ?? null;
    $description1 = $_POST['description1'] ?? null;
    $subtitle2 = $_POST['subtitle2'] ?? null;
    $description2 = $_POST['description2'] ?? null;
    $subtitle3 = $_POST['subtitle3'] ?? null;
    $description3 = $_POST['description3'] ?? null;

    // Vérifie si le champ titre est rempli car c'est un champ obligatoire
    if (empty($title)) {
        echo "Le titre est obligatoire.";
    } else {
        // Insertion dans la base de données
        try {
            $sql = "INSERT INTO services (title, subtitle1, description1, subtitle2, description2, subtitle3, description3) 
                    VALUES (:title, :subtitle1, :description1, :subtitle2, :description2, :subtitle3, :description3)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':subtitle1' => $subtitle1,
                ':description1' => $description1,
                ':subtitle2' => $subtitle2,
                ':description2' => $description2,
                ':subtitle3' => $subtitle3,
                ':description3' => $description3,
            ]);
            echo "Service ajouté avec succès";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter un nouveau service</h1>

        <!-- Bouton de retour au tableau de bord -->
        <div class="d-flex justify-content-center mb-3">
            <a href="admin_dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
        </div>
        
        <!-- Formulaire d'ajout de service -->
        <form method="POST" action="add_service.php" class="row g-3">
            <div class="col-12 mb-3">
                <label for="title" class="form-label">Titre du service</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Titre du service" required>
            </div>

            <div class="col-md-6">
                <label for="subtitle1" class="form-label">Sous-titre 1</label>
                <input type="text" class="form-control" id="subtitle1" name="subtitle1" placeholder="Sous-titre 1">
            </div>
            <div class="col-md-6">
                <label for="description1" class="form-label">Description 1</label>
                <textarea class="form-control" id="description1" name="description1" rows="3" placeholder="Description 1"></textarea>
            </div>

            <div class="col-md-6">
                <label for="subtitle2" class="form-label">Sous-titre 2</label>
                <input type="text" class="form-control" id="subtitle2" name="subtitle2" placeholder="Sous-titre 2">
            </div>
            <div class="col-md-6">
                <label for="description2" class="form-label">Description 2</label>
                <textarea class="form-control" id="description2" name="description2" rows="3" placeholder="Description 2"></textarea>
            </div>

            <div class="col-md-6">
                <label for="subtitle3" class="form-label">Sous-titre 3</label>
                <input type="text" class="form-control" id="subtitle3" name="subtitle3" placeholder="Sous-titre 3">
            </div>
            <div class="col-md-6">
                <label for="description3" class="form-label">Description 3</label>
                <textarea class="form-control" id="description3" name="description3" rows="3" placeholder="Description 3"></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">Ajouter</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>