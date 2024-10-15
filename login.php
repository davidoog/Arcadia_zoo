<?php
session_start();
require 'db.php'; 

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($password === $user['password']) {
            session_regenerate_id(true); 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

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
                    header('Location: arcadia_connexion.php?error=role');
                    exit();
            }
        } else {
            header('Location: arcadia_connexion.php?error=password');
            exit();
        }
    } else {
        header('Location: arcadia_connexion.php?error=username');
        exit();
    }
}