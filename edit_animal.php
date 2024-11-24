<?php
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();
$pdo = $db->getConnection();

// Récupérer l'ID de l'animal
$id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];

// Vérifier que l'ID est bien défini
if (!$id) {
    echo "ID de l'animal non spécifié.";
    exit();
}

// Récupérer les données actuelles de l'animal
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id=?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

// Vérifier que l'animal existe
if (!$animal) {
    echo "Animal non trouvé.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $race = $_POST['race'];
    $habitat = $_POST['habitat'];
    $health = $_POST['health'];
    $food = $_POST['food'];
    $food_quantity = $_POST['food_quantity'];

    // Définir l'image path comme la valeur actuelle dans la base de données
    $image_path = $animal['image'];

    // Gestion de l'image (si une nouvelle image est uploadée)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "image/"; // Assurez-vous que ce répertoire existe
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Mettre à jour l'image si l'upload a réussi
                $image_path = $target_file;
            } else {
                echo "Erreur lors du téléchargement de l'image.";
            }
        } else {
            echo "Le fichier sélectionné n'est pas une image valide.";
        }
    }

    // Début de la transaction
    $pdo->beginTransaction();

    try {
        // Mise à jour dans la table animals
        $stmt = $pdo->prepare("UPDATE animals SET name=?, race=?, habitat=?, health=?, food=?, food_quantity=?, image=? WHERE id=?");
        $stmt->execute([$name, $race, $habitat, $health, $food, $food_quantity, $image_path, $id]);

        // Insertion ou mise à jour dans la table animal_feedings
        // Vérifier s'il existe déjà une entrée pour cet animal
        $stmt = $pdo->prepare("SELECT id FROM animal_feedings WHERE animal_id=? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$id]);
        $feeding = $stmt->fetch();

        if ($feeding) {
            // Mise à jour de la dernière entrée
            $stmt = $pdo->prepare("UPDATE animal_feedings SET food_given=?, quantity=?, feeding_date=NOW() WHERE id=?");
            $stmt->execute([$food, $food_quantity, $feeding['id']]);
        } else {
            // Insertion d'une nouvelle entrée
            $stmt = $pdo->prepare("INSERT INTO animal_feedings (animal_id, food_given, quantity, feeding_date) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$id, $food, $food_quantity]);
        }

        // Validation de la transaction
        $pdo->commit();

        // Redirection après la mise à jour
        header('Location: manage_animals.php');
        exit();
    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur
        $pdo->rollBack();
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier un Animal</h1>
        <form method="POST" action="edit_animal.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($animal['id']); ?>">

            <div class="mb-3">
                <label>Nom:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Espèce (Race):</label>
                <input type="text" name="race" value="<?php echo htmlspecialchars($animal['race']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Habitat:</label>
                <input type="text" name="habitat" value="<?php echo htmlspecialchars($animal['habitat']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>État de santé:</label>
                <input type="text" name="health" value="<?php echo htmlspecialchars($animal['health']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="food" class="form-label">Nourriture proposée:</label>
                <input type="text" class="form-control" name="food" value="<?php echo htmlspecialchars($animal['food']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="food_quantity" class="form-label">Quantité de nourriture:</label>
                <input type="number" class="form-control" name="food_quantity" value="<?php echo htmlspecialchars($animal['food_quantity']); ?>" required>
            </div>

            <div class="mb-3">
                <label>Changer l'image (optionnel):</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
        <a href="manage_animals.php" class="btn btn-secondary mt-3">Retour à la liste des animaux</a>
    </div>
</body>
</html>