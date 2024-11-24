<?php
session_start();
require_once 'db.php';

// Inclure l'autoloader de Composer
require 'vendor/autoload.php';

// Charger PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: arcadia_connexion.php');
    exit();
}

// Activer l'affichage des erreurs en environnement de développement (à commenter en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Récupérer l'email du formulaire
    $username = $email; // Le nom d'utilisateur est l'adresse e-mail
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connexion à la base de données
    $db = new Database();
    $pdo = $db->getConnection();

    // Vérifier si l'e-mail existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        $message = "Cet e-mail est déjà utilisé.";
    } else {
        // Insérer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email) VALUES (:username, :password, :role, :email)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'role' => $role,
            'email' => $email
        ]);

        // Envoi de l'e-mail au nouvel utilisateur
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Hôte SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = getenv('GMAIL_USERNAME'); // Récupère le nom d'utilisateur depuis les variables d'environnement
            $mail->Password = getenv('GMAIL_PASSWORD'); // Récupère le mot de passe depuis les variables d'environnement
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activez STARTTLS si disponible
            $mail->Port = 587; // Port pour Gmail

            // Configuration du message
            $mail->setFrom('contactarcadia.supp@gmail.com', 'Zoo Arcadia');
            $mail->addAddress($email); // Destinataire : le nouvel utilisateur
            $mail->Subject = 'Votre compte sur le site du Zoo Arcadia';
            $mail->Body = "Bonjour,

Votre compte sur le site du Zoo Arcadia a été créé avec succès.

Votre nom d'utilisateur est : $username

Veuillez contacter l'administrateur pour obtenir votre mot de passe.

Cordialement,
L'équipe du Zoo Arcadia";

            // Envoyer le message
            $mail->send();
            $message = "L'utilisateur a été créé avec succès et un e-mail a été envoyé.";
        } catch (Exception $e) {
            // Gestion des erreurs
            $message = "Une erreur est survenue lors de l'envoi de l'e-mail. Erreur : {$mail->ErrorInfo}";
        }

        // Rediriger vers la page de gestion des utilisateurs avec un message de succès
        header('Location: manage_users.php?message=' . urlencode($message));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter un utilisateur</h1>

        <!-- Afficher le message d'erreur ou de succès -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="add_user.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="vet">Vétérinaire</option>
                    <option value="employee">Employé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
        <a href="manage_users.php" class="btn btn-secondary mt-3">Retour</a>
    </div>
</body>
</html>