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
        echo "Cet e-mail est déjà utilisé.";
        exit();
    }

    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email) VALUES (:username, :password, :role, :email)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashed_password,
        'role' => $role,
        'email' => $email
    ]);

    // Envoi de l'e-mail au nouvel utilisateur
    // Créer une instance de PHPMailer
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
        // Vous pouvez afficher un message de confirmation si nécessaire
    } catch (Exception $e) {
        // Gestion des erreurs
        echo "Une erreur est survenue lors de l'envoi de l'e-mail. Erreur : {$mail->ErrorInfo}";
    }

    // Rediriger vers la page de gestion des utilisateurs
    header('Location: manage_users.php');
    exit();
}
?>