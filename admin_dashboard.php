<?php

// Connexion à la base de données et récupération des données
require 'mongo_connection.php'; // Connexion à MongoDB
require 'db.php'; // Connexion à la base MySQL

session_start();

// Vérifier si l'utilisateur est connecté et a le rôle 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Rediriger si l'utilisateur n'est pas admin
    header('Location: arcadia_connexion.php');
    exit();
}

try {
    $visits = $collection->find(); // Trouver toutes les visites dans MongoDB
    $hours = $pdo->query("SELECT * FROM zoo_hours WHERE id = 1")->fetch(); // Récupérer les horaires
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_dashboard.css"> 

    <style>
        .admin-section {
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .admin-section h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Admin Zoo Arcadia</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a> 
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-4">
        <h1 class="text-center">Tableau de bord de José, <?php echo $_SESSION['username']; ?> !</h1>

        <!-- Bloc principal avec les actions admin -->
        <div class="row mt-5">
            <div class="col-md-4">
                <a href="manage_users.php" class="btn btn-primary btn-block">Gérer les utilisateurs</a>
            </div>
            <div class="col-md-4">
                <a href="manage_animals.php" class="btn btn-primary btn-block">Gérer les animaux</a>
            </div>
            <div class="col-md-4">
                <a href="manage_habitats.php" class="btn btn-primary btn-block">Gérer les habitats</a>
            </div>
            <div class="col-md-4">
                <a href="manage_services.php" class="btn btn-primary btn-block">Gérer les services</a> 
            </div>
        </div>

        <!-- Bloc pour afficher les consultations des animaux -->
        <div class="admin-section mt-5">
            <h2>Consultations des Animaux</h2>
            <?php foreach ($visits as $visit): ?>
                <p class="consultation-info"><?php echo $visit['animal_name'] . ' a été consulté ' . $visit['count'] . ' fois.'; ?></p>
            <?php endforeach; ?>
            <button id="resetButton" class="btn btn-danger">Réinitialiser les consultations</button>
        </div>

        <!-- Bloc pour les horaires du zoo -->
        <div class="admin-section mt-5">
            <h2>Horaires du Zoo</h2>
            <p><strong>Heures d'ouverture :</strong> <?php echo $hours['opening_time']; ?></p>
            <p><strong>Heures de fermeture :</strong> <?php echo $hours['closing_time']; ?></p>
            <form id="updateHoursForm">
                <div class="form-group">
                    <label for="opening_hour">Nouvelle heure d'ouverture</label>
                    <input type="time" id="opening_hour" name="opening_hour" class="form-control">
                </div>
                <div class="form-group">
                    <label for="closing_hour">Nouvelle heure de fermeture</label>
                    <input type="time" id="closing_hour" name="closing_hour" class="form-control">
                </div>
                <button type="submit" class="btn btn-success mt-3">Mettre à jour les horaires</button>
            </form>
        </div>

        

<script src="manage_services.js"></script>

    </main>

    <script src="script.js"></script>
</body>
</html>