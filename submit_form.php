<?php
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $subject = htmlspecialchars($_POST['subject']);
    $description = htmlspecialchars($_POST['description']);
    $email = htmlspecialchars($_POST['email']);

    // Valider les champs (optionnel, mais recommandé)
    if (!empty($subject) && !empty($description) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Envoyer l'email au zoo
        $to = 'contactarcadia.supp'; // 
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $message = "Vous avez reçu une nouvelle demande de contact.\n\n";
        $message .= "Sujet: $subject\n";
        $message .= "Description: $description\n";
        $message .= "Email: $email\n";
        
        if (mail($to, "Nouvelle demande de contact - Zoo Arcadia", $message, $headers)) {
            echo "Votre message a été envoyé avec succès.";
        } else {
            echo "Échec de l'envoi du message. Veuillez réessayer.";
        }
    } else {
        echo "Veuillez remplir correctement tous les champs.";
    }
} else {
    echo "Méthode de soumission non valide.";
}
?>