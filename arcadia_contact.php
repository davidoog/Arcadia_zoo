<?php
// Démarrage de la session si nécessaire
session_start();
require_once 'db.php'; // Connexion à la base de données
require 'vendor/autoload.php'; // Autoloader de Composer pour Symfony Mailer

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

// Message initial pour l'affichage
$message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Connexion à la base de données via la classe Database
        try {
            $db = new Database();  // Instancier la classe Database
            $pdo = $db->getConnection();  // Récupérer l'objet PDO pour interagir avec la base de données

            // Si la connexion est réussie, tu peux continuer l'envoi de l'email
            // Récupérer les variables d'environnement depuis Heroku pour Mailtrap
            $mailer_dsn = getenv('MAILER_DSN');  // Ex: smtp://fc74c6fbd218:0e8e111fd2b52a@smtp.mailtrap.io:587
            $mailtrap_username = getenv('EMAIL_USERNAME'); // Email utilisateur Mailtrap
            $mailtrap_password = getenv('EMAIL_PASSWORD'); // Mot de passe Mailtrap
            
            // Configuration du transport avec Symfony Mailer
            $transport = new EsmtpTransport($mailer_dsn);  // Utilise le DSN complet ici
            $transport->setUsername($mailtrap_username);  // Utilise l'username de Mailtrap
            $transport->setPassword($mailtrap_password); // Utilise le mot de passe de Mailtrap

            $mailer = new Mailer($transport);

            // Créer l'email
            $emailMessage = (new Email())
                ->from($email)
                ->to('contactarcadia.supp@gmail.com')  // L'email de destination
                ->subject($subject)
                ->text("Titre : $subject\n\nDescription : $description\n\nEmail : $email");

            // Envoi de l'email
            try {
                $mailer->send($emailMessage);
                $message = "Votre demande a été envoyée avec succès.";
            } catch (TransportExceptionInterface $e) {
                // Si une erreur survient, afficher l'erreur spécifique
                $message = "Une erreur est survenue lors de l'envoi de votre demande. Erreur : {$e->getMessage()}";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion à la base de données
            $message = "Erreur de connexion à la base de données: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Contactez-nous</h1>
        <p class="text-center">Remplissez le formulaire ci-dessous pour nous contacter</p>
        
        <form method="POST">
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
                <button type="submit" class="btn btn-custom">Envoyer</button>
            </div>
        </form>

        <!-- Message à l'utilisateur -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>