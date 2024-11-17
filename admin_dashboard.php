<?php
session_start();

// Connexion à MongoDB
require 'vendor/autoload.php'; // Si vous utilisez Composer pour installer le driver MongoDB
require_once 'db.php'; // Connexion à la base MySQL (si nécessaire)

// Connexion à la base de données via la classe Database
$db = new Database();
$pdo = $db->getConnection();  // Récupérer l'objet PDO (si vous utilisez MySQL)

// Vérifier si l'utilisateur est connecté et a le rôle 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Rediriger si l'utilisateur n'est pas admin
    header('Location: arcadia_connexion.php');
    exit();
}

// Génère un token CSRF si celui-ci n'existe pas encore
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    // Connexion à MongoDB Atlas
    $mongoUri = getenv('MONGODB_URI'); 
    $client = new MongoDB\Client($mongoUri); // Connexion à MongoDB
    $mongoDb = $client->Zoo_Arcadia; // Sélectionner la base de données Zoo_Arcadia
    $collection = $mongoDb->Animals_visits; // Sélectionner la collection Animals_visits

    // Récupérer toutes les visites (si nécessaire)
    $visits = $collection->find(); // Exécuter la requête MongoDB pour récupérer les visites

    // Récupérer les horaires depuis MySQL (si nécessaire)
    $hours = $pdo->query("SELECT * FROM zoo_hours WHERE id = 1")->fetch();

} catch (Exception $e) {
    // Afficher l'erreur si la connexion échoue
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
    die();
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
            background-color: #f9f9f9;
        }
        .admin-section h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .consultation-info {
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .consultation-info strong {
            color: #007bff;
        }
        .alert {
            margin-top: 20px;
        }
        .reset-btn {
            margin-top: 20px;
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
        <?php if (isset($_GET['reset'])): ?>
        <div class="alert 
            <?php echo $_GET['reset'] == 'success' ? 'alert-success' : 'alert-danger'; ?>" 
            role="alert">
            <?php
            if ($_GET['reset'] == 'success') {
                echo "Les consultations des animaux ont été réinitialisées avec succès.";
            } elseif ($_GET['reset'] == 'error_csrf') {
                echo "Erreur CSRF : Requête non autorisée.";
            } else {
                echo "Erreur lors de la réinitialisation des consultations. Veuillez réessayer.";
            }
            ?>
        </div>
        <?php endif; ?>

        <h1 class="text-center">Tableau de bord de José, <?php echo $_SESSION['username']; ?> !</h1>

        <!-- Bloc principal avec les actions admin -->
        <div class="row mt-5">
            <div class="col-md-3">
                <a href="manage_users.php" class="btn btn-primary btn-block">Gérer les utilisateurs</a>
            </div>
            <div class="col-md-3">
                <a href="manage_animals.php" class="btn btn-primary btn-block">Gérer les animaux</a>
            </div>
            <div class="col-md-3">
                <a href="manage_habitats.php" class="btn btn-primary btn-block">Gérer les habitats</a>
            </div>
            <div class="col-md-3">
                <a href="manage_services.php" class="btn btn-primary btn-block">Gérer les services</a> 
            </div>
        </div>

        <!-- Bloc pour afficher les consultations des animaux -->
        <div class="admin-section mt-5">
            <h2>Consultations des Animaux</h2>
            <?php foreach ($visits as $visit): ?>
                <p class="consultation-info">
                    <strong><?php echo $visit['animal_name']; ?></strong> a été consulté <strong><?php echo $visit['count']; ?></strong> fois.
                </p>
            <?php endforeach; ?>

            <!-- Formulaire pour réinitialiser les consultations avec protection CSRF -->
            <form method="POST" action="reset_visits.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="btn btn-danger reset-btn">Réinitialiser les consultations</button>
            </form>
        </div>

        <!-- Bloc pour les horaires du zoo -->
        <div class="admin-section mt-5">
            <h2>Horaires du Zoo</h2>
            <p><strong>Heures d'ouverture :</strong> <?php echo $hours['opening_time']; ?></p>
            <p><strong>Heures de fermeture :</strong> <?php echo $hours['closing_time']; ?></p>
            <form id="updateHoursForm" method="POST" action="update_hours.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
    </main>
</body>
</html>