<?php
require 'mongo_connection.php'; // Inclure la connexion MongoDB

// Récupérer le contenu JSON de la requête POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name'])) {
    $animalName = $data['name'];

    try {
        // Vérifier si l'animal existe déjà dans la collection
        $animal = $collection->findOne(['animal_name' => $animalName]);

        if ($animal) {
            // Si l'animal existe, incrémenter le compteur de visites
            $newCount = $animal['count'] + 1;
            $collection->updateOne(
                ['animal_name' => $animalName],
                ['$set' => ['count' => $newCount]]
            );
        } else {
            // Si l'animal n'existe pas, créer une nouvelle entrée avec un compteur à 1
            $collection->insertOne([
                'animal_name' => $animalName,
                'count' => 1
            ]);
        }

        // Envoyer une réponse JSON
        echo json_encode(['status' => 'success', 'message' => 'Visite mise à jour pour ' . $animalName]);

    } catch (Exception $e) {
        // En cas d'erreur, retourner une réponse d'erreur
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    // Si le nom de l'animal n'est pas présent dans la requête
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nom de l\'animal manquant']);
}
?>