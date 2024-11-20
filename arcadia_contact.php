<?php
session_start();

// Charger l'autoloader de Composer
require 'vendor/autoload.php';

// Charger PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fonction pour générer un token unique
function generateToken() {
    return bin2hex(random_bytes(16));
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
        $_SESSION['verification_token'] = $token;
        $_SESSION['form_data'] = [
            'subject' => $subject,
            'description' => $description,
            'email' => $email
        ];

        // Créer une instance de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Hôte SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = getenv('GMAIL_USERNAME'); // Récupère le nom d'utilisateur depuis les variables d'environnement de Heroku
            $mail->Password = getenv('GMAIL_PASSWORD'); // Récupère le mot de passe depuis les variables d'environnement de Heroku
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activez STARTTLS si disponible
            $mail->Port = 587; // Port pour Gmail

            // Configuration du message
            $mail->setFrom('contactarcadia.supp@gmail.com', 'Zoo Arcadia');
            $mail->addAddress($email); // Destinataire : l'utilisateur qui doit vérifier son e-mail
            $mail->Subject = "Vérification de votre adresse e-mail";
            $verificationLink = "https://arcadia-zoo-2024-f65a95602ea5.herokuapp.com/arcadia_contact.php?token=$token";
            $mail->Body = "Bonjour,

Veuillez cliquer sur le lien suivant pour vérifier votre adresse e-mail et envoyer votre message au Zoo Arcadia :

$verificationLink

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
    if (isset($_SESSION['verification_token']) && $token === $_SESSION['verification_token']) {
        // Récupérer les données du formulaire
        $formData = $_SESSION['form_data'];
        $subject = $formData['subject'];
        $description = $formData['description'];
        $email = $formData['email'];

        // Effacer le token et les données du formulaire de la session
        unset($_SESSION['verification_token']);
        unset($_SESSION['form_data']);

        // Créer une instance de PHPMailer pour envoyer le message au zoo
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Hôte SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = getenv('GMAIL_USERNAME'); // Récupère le nom d'utilisateur depuis les variables d'environnement de Heroku
            $mail->Password = getenv('GMAIL_PASSWORD'); // Récupère le mot de passe depuis les variables d'environnement de Heroku
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activez STARTTLS si disponible
            $mail->Port = 587; // Port pour Gmail

            // Configuration du message
            $mail->setFrom($email, 'Visiteur du site Arcadia Zoo');
            $mail->addAddress('contactarcadia.supp@gmail.com'); // Destinataire du message
            $mail->addReplyTo($email, 'Visiteur');  // L'email de l'utilisateur qui envoie le message
            $mail->Subject = $subject;
            $mail->Body = "Titre : $subject\n\nDescription : $description\n\nEmail : $email";

            // Envoyer le message
            $mail->send();
            $message = "Votre demande a été envoyée avec succès au Zoo Arcadia.";
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
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!isset($_GET['token']) || (isset($message) && strpos($message, 'Le token de vérification est invalide') !== false)): ?>
        <form id="contact-form" action="arcadia_contact.php" method="POST" class="mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="contact.js"></script>
</body>
</html>