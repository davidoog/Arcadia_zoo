<?php
session_start();
require 'db.php'; 

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Récupérer l'utilisateur avec le nom d'utilisateur donné
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifie que le mot de passe correspond
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo "Authentification réussie. Redirection...";

            // Rediriger en fonction du rôle
            switch ($user['role']) {
                case 'admin':
                    header('Location: admin_dashboard.php');
                    exit();
                case 'vet':
                    header('Location: vet_dashboard.php');
                    exit();
                case 'employee':
                    header('Location: employee_dashboard.php');
                    exit();
                default:
                    echo "Erreur : rôle non reconnu.";
                    exit();
            }
        } else {
            echo "Erreur : mot de passe incorrect.";
            exit();
        }
    } else {
        echo "Erreur : nom d'utilisateur incorrect.";
        exit();
    }
}
?>