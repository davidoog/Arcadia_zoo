<?php
session_start();
// Connexion à la base de données et récupération des données
require_once 'db.php'; // Connexion à la base MySQL

// Connexion à la base de données via la classe Database
$db = new Database();
$pdo = $db->getConnection();

// Vérifier si l'utilisateur est connecté et a le rôle 'employee'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header('Location: arcadia_connexion.php');
    exit();
}

// Générer un token CSRF si celui-ci n'existe pas encore
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    // Récupérer les animaux pour la gestion de l'alimentation
    $query_animals = "SELECT * FROM animals";
    $result_animals = $pdo->query($query_animals);

    // Récupérer les services pour modification
    $query_services = "SELECT * FROM services";
    $result_services = $pdo->query($query_services);

    // Si le formulaire pour l'alimentation est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_feeding'])) {
        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erreur CSRF : requête non autorisée.");
        }

        // Récupérer les données du formulaire
        $animal_id = $_POST['animal_id'];
        $feeding_date = $_POST['feeding_date'];
        $feeding_time = $_POST['feeding_time'];
        $food_given = $_POST['food_given'];
        $quantity = $_POST['quantity'];

        // Insertion ou mise à jour dans la base de données
        $insert_feeding = $pdo->prepare("
            INSERT INTO animal_feedings (animal_id, feeding_date, feeding_time, food_given, quantity)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                feeding_time = VALUES(feeding_time),
                food_given = VALUES(food_given),
                quantity = VALUES(quantity)
        ");
        try {
            $insert_feeding->execute([$animal_id, $feeding_date, $feeding_time, $food_given, $quantity]);
            echo "Alimentation enregistrée avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Employé - Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .employee-section {
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .employee-section h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .table-responsive, .form-section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Employé Zoo Arcadia</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-4">
        <h1 class="text-center">Tableau de bord Employé - Bonjour, <?php echo $_SESSION['username']; ?> !</h1>

        <!-- Bloc pour modifier les services du zoo -->
        <div class="employee-section">
            <h2>Modifier les Services du Zoo</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom du Service</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_services->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['service_name'] ?? 'Nom non défini'); ?></td>
                            <td><?php echo htmlspecialchars($row['service_description'] ?? 'Description non définie'); ?></td>
                            <td><a href="edit_service.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Modifier</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bloc pour ajouter l'alimentation des animaux -->
        <div class="employee-section">
            <h2>Ajouter une Alimentation Quotidienne</h2>
            <form method="POST" class="form-section">
                <!-- Champ caché pour le token CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
                <div class="form-group">
                    <label for="animal_id">Sélectionner l'Animal</label>
                    <select name="animal_id" id="animal_id" class="form-control" required>
                        <?php
                        // Réinitialiser le curseur du résultat
                        $result_animals->execute();
                        while ($row = $result_animals->fetch()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="feeding_date">Date de l'Alimentation</label>
                    <input type="date" id="feeding_date" name="feeding_date" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label for="feeding_time">Heure de l'Alimentation</label>
                    <input type="time" id="feeding_time" name="feeding_time" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label for="food_given">Nourriture Donnée</label>
                    <input type="text" id="food_given" name="food_given" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label for="quantity">Quantité</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" required>
                </div>   
                    
                <button type="submit" name="add_feeding" class="btn btn-success mt-3">Enregistrer l'Alimentation</button>
            </form>
        </div>

        <!-- Bloc pour afficher l'historique des aliments donnés -->
        <div class="employee-section mt-5">
            <h2>Historique des Aliments Donnés</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Nourriture Donnée</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Requête pour récupérer les aliments enregistrés
                    try {
                        $feeding_records = $pdo->query("SELECT af.feeding_date, af.feeding_time, af.food_given, af.quantity, a.name AS animal_name
                                                        FROM animal_feedings af
                                                        JOIN animals a ON af.animal_id = a.id
                                                        ORDER BY af.feeding_date DESC, af.feeding_time DESC");

                        // Vérifiez si la requête retourne des enregistrements
                        if ($feeding_records->rowCount() > 0) {
                            while ($record = $feeding_records->fetch()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($record['animal_name']) . "</td>
                                    <td>" . htmlspecialchars($record['feeding_date']) . "</td>
                                    <td>" . htmlspecialchars($record['feeding_time']) . "</td>
                                    <td>" . htmlspecialchars($record['food_given']) . "</td>
                                    <td>" . htmlspecialchars($record['quantity']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Aucun enregistrement trouvé.</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='5' class='text-center'>Erreur : " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
                
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>