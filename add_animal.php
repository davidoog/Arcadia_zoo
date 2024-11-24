<?php
session_start();
require_once 'db.php';
require 'animal.php';

// Connexion à la base de données
$db = new Database();
$pdo = $db->getConnection();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.php');
    exit();
}

// Gestion de l'ajout d'un animal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $race = $_POST['race'];
    $habitat = $_POST['habitat'];
    $health = $_POST['health'];
    $food = $_POST['food'];
    $food_quantity = floatval($_POST['food_quantity']);
    $food_unit = $_POST['food_unit'];
    $last_checkup = $_POST['last_checkup'];

    // Gestion de l'upload de l'image
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    }

    // Ajouter l'animal avec la classe Animal
    $animalManager = new Animal($pdo);
    $animalManager->addAnimal($name, $race, $habitat, $health, $food, $food_quantity, $food_unit, $last_checkup, $image_path);

    // Redirection vers la page de gestion des animaux
    header('Location: manage_animals.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter un nouvel animal</h1>
        <form action="add_animal.php" method="POST" enctype="multipart/form-data">
            <!-- Formulaire d'ajout d'animal -->
            
            <div class="mb-3">
                <label for="name" class="form-label">Nom de l'animal</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="race" class="form-label">Race</label>
                <input type="text" class="form-control" id="race" name="race" required>
            </div>
            
            <div class="mb-3">
                <label for="habitat" class="form-label">Habitat</label>
                <select class="form-select" id="habitat" name="habitat" required>
                    <option value="savane">Savane</option>
                    <option value="jungle">Jungle Tropicale</option>
                    <option value="marais">Marais</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="health" class="form-label">État de santé</label>
                <input type="text" class="form-control" id="health" name="health" required>
            </div>
            
            <div class="mb-3">
                <label for="food" class="form-label">Nourriture proposée</label>
                <input type="text" class="form-control" id="food" name="food" required>
            </div>

            <div class="mb-3">
                <label for="food_quantity" class="form-label">Quantité de nourriture</label>
                <div class="input-group">
                     <input type="number" class="form-control" id="food_quantity" name="food_quantity" required>
                        <select class="form-select" id="food_unit" name="food_unit" required>
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                        </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="last_checkup" class="form-label">Date du dernier check-up</label>
                <input type="date" class="form-control" id="last_checkup" name="last_checkup" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image de l'animal</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Ajouter l'animal</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Retour au tableau de bord</a>
    </div>
</body>
</html>