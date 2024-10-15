<?php
session_start();
require 'mongo_connection.php'; // Inclusion de la connexion MongoDB
require_once 'db.php'; // Connexion à la base de données relationnelle pour récupérer l'animal

// Vérifier si un animal est cliqué
if (isset($_GET['animal_name'])) {
    $animalName = $_GET['animal_name'];
    
    // Chercher l'animal dans MongoDB et incrémenter le compteur
    $animal = $animalsCollection->findOne(['name' => $animalName]);

    if ($animal) {
        // Incrémenter le compteur de visites
        $animalsCollection->updateOne(
            ['name' => $animalName],
            ['$inc' => ['visits' => 1]]
        );
    } else {
        // Si l'animal n'existe pas, l'ajouter avec un compteur de visites initialisé à 1
        $animalsCollection->insertOne([
            'name' => $animalName,
            'visits' => 1
        ]);
    }
}
?>