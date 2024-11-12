<?php
// Démarrage de la session si nécessaire
session_start();
require_once 'db.php'; // Connexion à la base de données

// Inclusion du fichier de connexion à la base de données si vous enregistrez les messages
require_once 'db.php'; // Assurez-vous que le fichier 'db.php' existe et fonctionne correctement

// Vérifiez que la requête est bien POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sécurisation et validation des données
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $email = trim($_POST['email']);

    // Vérification des champs
    if (empty($subject) || empty($description) || empty($email)) {
        echo "Tous les champs sont obligatoires.";
        exit;
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'adresse email n'est pas valide.";
        exit;
    }

    // Envoi de l'email au zoo
    $to = "contactarcadia.supp@gmail.com"; 
    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $message = "Titre : $subject\n\nDescription : $description\n\nEmail : $email";

    // Envoi de l'email
    if (mail($to, $subject, $message, $headers)) {
        echo "Votre demande a été envoyée avec succès.";
    } else {
        echo "Une erreur est survenue lors de l'envoi de votre demande.";
    }

    // Optionnel : Enregistrement du message dans la base de données
    /*
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("INSERT INTO contact_requests (subject, description, email) VALUES (?, ?, ?)");
    $stmt->execute([$subject, $description, $email]);
    */
} else {
    echo "Requête non valide.";
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="contact.js"></script>
</body>
</html>