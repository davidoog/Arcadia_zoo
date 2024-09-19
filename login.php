<?php
session_start();
require 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Requête SQL pour récupérer l'utilisateur par son nom d'utilisateur
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Utiliser password_verify pour comparer le mot de passe saisi et celui haché en base de données
        if ($password === $user['password']) {
            // Mot de passe valide, créer la session
            session_regenerate_id(true); // Sécuriser la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
        
            // Redirection selon le rôle
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
                    echo "<p style='color:red;'>Rôle non reconnu.</p>";
                    break;
            }
        } else {
            echo "<p style='color:red;'>Mot de passe incorrect.</p>";
        }
    } else {
        echo "<p style='color:red;'>Nom d'utilisateur introuvable.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css"> <!-- Assure-toi d'avoir un fichier CSS lié -->
</head>
<body>
    <form action="login.php" method="POST">
        <h2>Connexion</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Nom d'utilisateur -->
        <label for="username_input">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username_input" placeholder="Username (mail)" required>

        <!-- Mot de passe -->
        <label for="password_input">Mot de passe :</label>
        <input type="password" name="password" id="password_input" placeholder="Mot de passe" required>

        <!-- Bouton de soumission -->
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>