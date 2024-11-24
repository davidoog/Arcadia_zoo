<?php
require 'db.php';

$id = $_POST['serviceId'];
$title = $_POST['serviceTitle'];
$description = $_POST['serviceDescription'];
$image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = 'uploads/'; // Dossier où les images sont stockées
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    // Déplacement du fichier téléchargé dans le répertoire défini
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // Insertion du chemin de l'image dans la base de données
        $stmt = $pdo->prepare("INSERT INTO services (title, description, image_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $uploadFile]);
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}

if ($id) {
    $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $description, $image, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO services (title, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $image]);
}

echo json_encode(['status' => 'success']);
?>