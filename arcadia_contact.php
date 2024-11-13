<?php
// Démarrage de la session si nécessaire
session_start();
require_once 'db.php'; // Connexion à la base de données
require 'vendor/autoload.php'; // Autoloader de Composer pour PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // Envoi de l'email au zoo avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // Niveau de débogage
            $mail->Debugoutput = 'html'; // Affichage des erreurs en HTML

            // Configuration SMTP sans utiliser 'sendmail'
            $mail->Host = $_ENV['MAIL_HOST'];  // Utilisation de la variable d'environnement pour l'hôte
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USERNAME']; // Votre nom d'utilisateur (email) pour le serveur SMTP
            $mail->Password = 'gpnqykxkmujiiqgb'; // Le mot de passe d'application généré précédemment
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utilisation de STARTTLS pour sécuriser la connexion
            $mail->Port = $_ENV['MAIL_PORT']; // Le port SMTP (généralement 587 pour STARTTLS)

            // Configuration de l'email
            $mail->setFrom($email, 'Visiteur Zoo Arcadia');
            $mail->addAddress('contactarcadia.supp@gmail.com'); // Email de destination
            $mail->Subject = $subject;
            $mail->Body = "Titre : $subject\n\nDescription : $description\n\nEmail : $email";

            // Envoi de l'email
            $mail->send();
            $message = "Votre demande a été envoyée avec succès.";
        } catch (Exception $e) {
            // Si une erreur survient, afficher l'erreur spécifique
            $message = "Une erreur est survenue lors de l'envoi de votre demande. Erreur : {$mail->ErrorInfo}";
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
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <header>
        <div class="topbar">
            <div class="menu-icon" id="menu-icon">
                <div class="menu-hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="side-menu" id="side-menu">
                <ul>
                    <li><a href="arcadia_accueil.php">Accueil</a></li>
                    <li><a href="arcadia_habitats.php">Habitats</a></li>
                    <li><a href="arcadia_services.php">Services</a></li>
                    <li><a href="arcadia_contact.php">Contact</a></li>
                    <li><a href="arcadia_connexion.php">Connexion</a></li>
                </ul>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="arcadia_accueil.php">Retour vers la page d'accueil</a></li>
                    <li><a href="arcadia_habitats.php">Accès à tous les habitats</a></li>
                    <li><a href="arcadia_services.php">Accès à tous les services</a></li>
                    <li><a href="arcadia_contact.php">Contact</a></li>
                    <li class="connexion"><a href="arcadia_connexion.php" class="btn btn-primary">Connexion</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <div class="container mt-5">
        <h1 class="text-center">Contactez-nous</h1>
        <p class="text-center">Remplissez le formulaire ci-dessous pour nous contacter</p>
        
        <form id="contact-form" action="submit_form.php" method="POST" class="mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="contact.js"></script>
</body>
</html>