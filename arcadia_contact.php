<?php
session_start();

// Charger l'autoloader de Composer
require 'vendor/autoload.php';
require_once 'db.php'; // Assurez-vous que la connexion à la base de données est disponible

// Charger PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fonction pour générer un token unique
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
    // Sécurisation et validation des données
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $email = trim($_POST['email']);

    // Vérification des champs
    if (empty($subject) || empty($description) || empty($email)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validation de l'email
        $message = "L'adresse email n'est pas valide.";
    } else {
        // Générer un token de vérification
        $token = generateToken();
        $createdAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Connexion à la base de données
        $db = new Database();
        $pdo = $db->getConnection();

        // Insérer les données dans la base de données
        $stmt = $pdo->prepare("INSERT INTO contact_verification (email, subject, description, token, created_at, expires_at) VALUES (:email, :subject, :description, :token, :created_at, :expires_at)");
        $stmt->execute([
            ':email' => $email,
            ':subject' => $subject,
            ':description' => $description,
            ':token' => $token,
            ':created_at' => $createdAt,
            ':expires_at' => $expiresAt
        ]);

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
            $mail->addAddress($email); // Destinataire : l'utilisateur qui doit vérifier son e-mail
            $mail->Subject = "Vérification de votre adresse e-mail";
            $verificationLink = "https://votre-domaine.com/arcadia_contact.php?token=$token";
            $mail->Body = "Bonjour,

Veuillez cliquer sur le lien suivant pour vérifier votre adresse e-mail et envoyer votre message au Zoo Arcadia :

$verificationLink

Ce lien expirera dans 1 heure.

Si vous n'avez pas initié cette demande, veuillez ignorer cet e-mail.

Cordialement,
L'équipe du Zoo Arcadia";

            // Envoyer le message
            $mail->send();
            $message = "Un e-mail de vérification a été envoyé à votre adresse e-mail. Veuillez vérifier votre boîte de réception.";
        } catch (Exception $e) {
            // Gestion des erreurs
            $message = "Une erreur est survenue lors de l'envoi de l'e-mail de vérification. Erreur : {$mail->ErrorInfo}";
        }
    }
}

// Vérification du token de vérification
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Connexion à la base de données
    $db = new Database();
    $pdo = $db->getConnection();

    // Rechercher le token dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM contact_verification WHERE token = :token AND expires_at > NOW()");
    $stmt->execute([':token' => $token]);
    $verificationData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($verificationData) {
        // Créer une instance de PHPMailer pour envoyer le message au zoo
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
            $mail->setFrom($verificationData['email'], 'Visiteur du site Arcadia Zoo');
            $mail->addAddress('contactarcadia.supp@gmail.com'); // Destinataire du message
            $mail->addReplyTo($verificationData['email'], 'Visiteur');  // L'email de l'utilisateur qui envoie le message
            $mail->Subject = $verificationData['subject'];
            $mail->Body = "Titre : {$verificationData['subject']}\n\nDescription : {$verificationData['description']}\n\nEmail : {$verificationData['email']}";

            // Envoyer le message
            $mail->send();
            $message = "Votre demande a été envoyée avec succès au Zoo Arcadia.";

            // Supprimer l'entrée de la base de données
            $stmt = $pdo->prepare("DELETE FROM contact_verification WHERE token = :token");
            $stmt->execute([':token' => $token]);

        } catch (Exception $e) {
            // Gestion des erreurs
            $message = "Une erreur est survenue lors de l'envoi de votre demande. Erreur : {$mail->ErrorInfo}";
        }
    } else {
        $message = "Le token de vérification est invalide ou a expiré.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - Zoo Arcadia</title>
    <!-- Vos liens CSS et autres métadonnées -->
</head>
<body>
    <!-- Votre en-tête -->
    <div class="container mt-5">
        <h1 class="text-center">Contactez-nous</h1>
        <p class="text-center">Remplissez le formulaire ci-dessous pour nous contacter</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!isset($_GET['token']) || (isset($message) && strpos($message, 'Le token de vérification est invalide') !== false)): ?>
        <form id="contact-form" action="arcadia_contact.php" method="POST" class="mt-4">
            <!-- Votre formulaire -->
            <div class="mb-3">
                <label for="subject" class="form-label">Titre</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Objet" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Décrivez votre demande" required></textarea>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" required>
            </div>

            <div class="text-center">
                <button type="submit" name="submit_form" class="btn btn-custom">Envoyer</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
    <!-- Vos scripts JavaScript -->
</body>
</html>