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
        // Récupérer les variables d'environnement depuis Heroku
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
    }
}
?>