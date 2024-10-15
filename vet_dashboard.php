<?php
session_start();
require_once 'db.php'; // Connexion à la base de données
require 'mongo_connection.php'; // Connexion à MongoDB (pour les consultations et les aliments)

// Vérifier si l'utilisateur est un vétérinaire
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'vet') {
    header('Location: arcadia_connexion.html');
    exit();
}

// Récupération des animaux et des habitats
try {
    $animals = $pdo->query("SELECT * FROM animals")->fetchAll();
    $habitats = $pdo->query("SELECT * FROM habitats")->fetchAll();
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Vétérinaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vet_dashboard.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Vétérinaire - Zoo Arcadia</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-4">
        <h1 class="text-center">Tableau de bord de <?php echo $_SESSION['username']; ?></h1>


       

        <!-- Section pour les commentaires sur les habitats -->
        <div class="admin-section mt-5">
            <h2>Commentaires sur les Habitats</h2>
            <form action="submit_habitat_comment.php" method="POST">
                <div class="form-group">
                    <label for="habitat_id">Sélectionnez un habitat :</label>
                    <select class="form-control" id="habitat_id" name="habitat_id">
                        <?php foreach ($habitats as $habitat): ?>
                            <option value="<?= $habitat['id']; ?>"><?= $habitat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="habitat_comment">Commentaire :</label>
                    <textarea class="form-control" id="habitat_comment" name="habitat_comment" rows="4" placeholder="Écrire un commentaire"></textarea>
                </div>
                <button type="submit" class="btn btn-success mt-3">Enregistrer le commentaire</button>
            </form>
        </div>

        <!-- Section pour visualiser les aliments consommés -->
        <div class="admin-section mt-5">
    <h2>Aliments consommés par les animaux</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Animal</th>
                <th>Aliment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Récupérer les informations des aliments depuis MongoDB
            $aliments = $collection->find(); 
            foreach ($aliments as $aliment): ?>
                <tr>
                    <td><?= isset($aliment['animal_name']) ? htmlspecialchars($aliment['animal_name']) : 'Non défini'; ?></td>
                    <td><?= isset($aliment['food']) ? htmlspecialchars($aliment['food']) : 'Non défini'; ?></td>
                    <td><?= isset($aliment['date']) ? htmlspecialchars($aliment['date']) : 'Non défini'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    </main>
</body>
</html>