<?php
session_start();
require_once 'db.php'; // Connexion à la base de données MySQL

// Connexion à la base de données via la classe Database
$db = new Database();
$pdo = $db->getConnection(); // Récupération de l'objet PDO

// Vérifier si l'utilisateur est un vétérinaire
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'vet') {
    header('Location: arcadia_connexion.php');
    exit();
}

// Générer un token CSRF si celui-ci n'existe pas encore
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traitement du formulaire d'ajout de l'alimentation et de l'état de l'animal
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
    $animal_status = $_POST['animal_status'];  // L'état de l'animal
    $vet_comment = $_POST['vet_comment'];      // Commentaire du vétérinaire

    // Insertion ou mise à jour dans la base de données MySQL (Alimentation)
    $insert_feeding = $pdo->prepare("
        INSERT INTO animal_feedings (animal_id, feeding_date, feeding_time, food_given, quantity)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        feeding_time = VALUES(feeding_time),
        food_given = VALUES(food_given),
        quantity = VALUES(quantity)
    ");
    $insert_feeding->execute([$animal_id, $feeding_date, $feeding_time, $food_given, $quantity]);

    // Enregistrer l'état de l'animal et le commentaire du vétérinaire
    $insert_status = $pdo->prepare("
    INSERT INTO animal_status (animal_id, status, status_date, vet_id, vet_comment)
    VALUES (?, ?, ?, ?, ?)
    ");
    try {
    $insert_status->execute([$animal_id, $animal_status, $feeding_date, $_SESSION['user_id'], $vet_comment]);
    echo "Alimentation et état de l'animal enregistrés avec succès.";
    } catch (PDOException $e) {
    echo "Erreur lors de l'enregistrement de l'état de l'animal : " . $e->getMessage();
    }
}

// Récupérer la liste des animaux pour le formulaire
$query_animals = "SELECT * FROM animals";
$result_animals = $pdo->query($query_animals);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Vétérinaire - Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <!-- Bloc pour ajouter l'alimentation des animaux -->
        <div class="admin-section mt-5">
            <h2>Ajouter une Alimentation Quotidienne</h2>
            <form method="POST">
                <!-- Champ caché pour le token CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
                <div class="form-group">
                    <label for="animal_id">Sélectionner l'Animal</label>
                    <select name="animal_id" id="animal_id" class="form-control" required>
                        <?php while ($row = $result_animals->fetch()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name'] ?? 'Non défini'); ?></option>
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

                <!-- Section pour l'état de l'animal -->
                <div class="form-group mt-3">
                    <label for="animal_status">État de l'animal</label>
                    <textarea id="animal_status" name="animal_status" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Section pour les commentaires du vétérinaire -->
                <div class="form-group mt-3">
                    <label for="vet_comment">Commentaire du vétérinaire</label>
                    <textarea id="vet_comment" name="vet_comment" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" name="add_feeding" class="btn btn-success mt-3">Enregistrer l'Alimentation et l'État</button>
            </form>
        </div>

        <!-- Bloc pour afficher l'historique des alimentations -->
        <div class="admin-section mt-5">
            <h2>Historique des Aliments Donnés</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Nourriture Donnée</th>
                        <th>Quantité</th>
                        <th>État de l'animal</th>
                        <th>Commentaire du vétérinaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer l'historique des alimentations et des états de l'animal
                    $feeding_records = $pdo->query("
                        SELECT a.name AS animal_name, af.feeding_date, af.feeding_time, af.food_given, af.quantity, as1.status, as1.vet_comment
                        FROM animal_feedings af
                        JOIN animals a ON af.animal_id = a.id
                        LEFT JOIN animal_status as1 ON a.id = as1.animal_id
                        ORDER BY af.feeding_date DESC, af.feeding_time DESC
                    ");

                    while ($record = $feeding_records->fetch()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($record['animal_name'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['feeding_date'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['feeding_time'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['food_given'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['quantity'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['status'] ?? 'Non défini') . "</td>
                                <td>" . htmlspecialchars($record['vet_comment'] ?? 'Non défini') . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>