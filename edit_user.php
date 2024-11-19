<?php
session_start();
require_once 'db.php'; // Connexion à la base de données

// Connexion à la base de données via la classe Database
$db = new Database();
$pdo = $db->getConnection(); // Récupérer l'objet PDO

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.html');
    exit();
}

// Récupérer les informations de l'utilisateur à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: manage_users.php');
        exit();
    }

    // Empêcher la modification si l'utilisateur est un administrateur
    if ($user['role'] === 'admin') {
        // Rediriger ou afficher un message d'erreur
        echo "<script>alert('Vous ne pouvez pas modifier l\'administrateur.'); window.location.href='manage_users.php';</script>";
        exit();
    }
} else {
    header('Location: manage_users.php');
    exit();
}

// Mettre à jour l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Préparer les paramètres pour la requête de mise à jour
    $params = [
        'username' => $username,
        'role' => $role,
        'id' => $id
    ];

    // Vérifier si un nouveau mot de passe a été soumis
    if (!empty($_POST['password'])) {
        // Utiliser password_hash pour sécuriser le mot de passe
        $params['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE users SET username = :username, password = :password, role = :role WHERE id = :id";
    } else {
        $query = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    }

    // Exécuter la requête de mise à jour
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Rediriger vers la page de gestion des utilisateurs
    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier un utilisateur</h1>
        <form action="edit_user.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select" id="role" name="role" required>
                    <!-- Suppression de l'option admin -->
                    <option value="vet" <?php if ($user['role'] == 'vet') echo 'selected'; ?>>Vétérinaire</option>
                    <option value="employee" <?php if ($user['role'] == 'employee') echo 'selected'; ?>>Employé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Modifier</button>
        </form>
        <a href="manage_users.php" class="btn btn-secondary mt-3">Retour</a>
    </div>
</body>
</html>