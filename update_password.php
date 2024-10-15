<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Hacher le nouveau mot de passe
    $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

    // Mise à jour du mot de passe dans la base de données
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE username = ?');
    $stmt->execute([$hashedPassword, $username]);

    echo "Mot de passe mis à jour avec succès pour l'utilisateur $username.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="update_password.php" method="POST">
        <h2>Changer le mot de passe</h2>

        <!-- Nom d'utilisateur -->
        <label for="username_input">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username_input" required>

        <!-- Nouveau mot de passe -->
        <label for="password_input">Nouveau mot de passe :</label>
        <input type="password" name="new_password" id="password_input" required>

        <!-- Bouton de soumission -->
        <button type="submit">Mettre à jour le mot de passe</button>
    </form>
</body>
</html>