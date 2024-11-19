<?php
session_start();

// Charger l'autoloader de Composer
require 'vendor/autoload.php';

// Inclure la connexion à la base de données via db.php
require_once 'db.php';

// Connexion à MySQL via la classe Database
$db = new Database();
$pdo = $db->getConnection(); // Récupération de l'objet PDO

// Connexion à MongoDB si nécessaire
$mongoDb = $db->getMongoDb(); // Récupération de la base de données MongoDB

// Requête pour récupérer les animaux de chaque habitat avec leurs dernières informations d'alimentation et état de santé
function getAnimalsByHabitat($pdo, $habitat) {
    $stmt = $pdo->prepare("
        SELECT a.*,
            af.food_given, af.quantity, af.feeding_date,
            as1.status, as1.status_date, as1.vet_comment
        FROM animals a
        LEFT JOIN (
            SELECT af1.*
            FROM animal_feedings af1
            INNER JOIN (
                SELECT animal_id, MAX(id) AS max_id
                FROM animal_feedings
                GROUP BY animal_id
            ) af2 ON af1.id = af2.max_id
        ) af ON a.id = af.animal_id
        LEFT JOIN (
            SELECT as2.*
            FROM animal_status as2
            INNER JOIN (
                SELECT animal_id, MAX(id) AS max_id
                FROM animal_status
                GROUP BY animal_id
            ) as3 ON as2.id = as3.max_id
        ) as1 ON a.id = as1.animal_id
        WHERE a.habitat = :habitat
        ORDER BY a.name ASC
    ");
    $stmt->execute(['habitat' => $habitat]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Appels pour récupérer les animaux des habitats
$savane = getAnimalsByHabitat($pdo, 'savane');
$jungle = getAnimalsByHabitat($pdo, 'jungle');
$marais = getAnimalsByHabitat($pdo, 'marais');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitats - Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="habitats.css">
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

<main>
    <div class="hero-section">
        <div class="background-image"></div>
        <h1 class="titre-habitat">Les habitats du parc</h1>
    </div>

    <div class="title-habitat">
        <h3 onclick="showAnimals('savane')">La Savane</h3>
        <h3 onclick="showAnimals('jungle')">Jungle Tropicale</h3>
        <h3 onclick="showAnimals('marais')">Les Marais</h3>
    </div>

    <div class="habitat">
        <div class="habitat">
            <img src="./image/savane-card.png" alt="Savane" onclick="showAnimals('savane')">
        </div>
        <div class="habitat" onclick="showAnimals('jungle')">
            <img src="./image/jungle-card.png" alt="Jungle">
        </div>
        <div class="habitat" onclick="showAnimals('marais')">
            <img src="./image/marais-card.png" alt="Marais">
        </div>
    </div>

    <!-- Conteneur général pour texte et liste des animaux -->
    <!-- Habitat Savane -->
    <div class="animal-container hidden" id="animal-info-savane">
        <p class="intro-text">Découvrez l'habitat de la savane, un espace vaste et ouvert recréant les paysages typiques des grandes plaines.</p>
        <h2 class="list-title">Liste d'animaux :</h2>

        <div class="animal-layout">
            <div class="animal-list">
                <?php foreach ($savane as $animal): ?>
                    <div class="animal-item">
                        <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                        <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                        <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                        <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                        <p><strong>Habitat :</strong> <?php echo $animal['habitat']; ?></p>
                    </div>

                        <!-- Détails cachés au départ -->
                        <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                            <p><strong>Nourriture :</strong> <?php echo $animal['food_given'] ?? 'Non défini'; ?></p>
                            <p><strong>Quantité :</strong> <?php echo $animal['quantity'] ?? 'Non défini'; ?></p>
                            <p><strong>Date :</strong> <?php echo $animal['feeding_date'] ?? 'Non défini'; ?></p>
                            <p><strong>État de santé :</strong> <?php echo $animal['status'] ?? 'Non défini'; ?></p>
                            <p><strong>Commentaire du vétérinaire :</strong> <?php echo $animal['vet_comment'] ?? 'Non défini'; ?></p> <!-- Affichage du commentaire uniquement -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Habitat Jungle Tropicale -->
    <div class="animal-container hidden" id="animal-info-jungle">
        <p class="intro-text">Découvrez l'habitat de la jungle tropicale, une zone dense et riche en biodiversité.</p>
        <h2 class="list-title">Liste d'animaux :</h2>

        <div class="animal-layout">
            <div class="animal-list">
                <?php foreach ($jungle as $animal): ?>
                    <div class="animal-item">
                        <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                        <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                        <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                        <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                        <p><strong>Habitat :</strong> <?php echo $animal['habitat']; ?></p>
                    </div>

                        <!-- Détails cachés au départ -->
                        <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                            <p><strong>Nourriture :</strong> <?php echo $animal['food_given'] ?? 'Non défini'; ?></p>
                            <p><strong>Quantité :</strong> <?php echo $animal['quantity'] ?? 'Non défini'; ?></p>
                            <p><strong>Date :</strong> <?php echo $animal['feeding_date'] ?? 'Non défini'; ?></p>
                            <p><strong>État de santé :</strong> <?php echo $animal['status'] ?? 'Non défini'; ?></p>
                            <p><strong>Commentaire du vétérinaire :</strong> <?php echo $animal['vet_comment'] ?? 'Non défini'; ?></p> <!-- Affichage du commentaire uniquement -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Habitat Marais -->
    <div class="animal-container hidden" id="animal-info-marais">
        <p class="intro-text">Découvrez l'habitat des marais, un environnement humide abritant une faune riche et diversifiée.</p>
        <h2 class="list-title">Liste d'animaux :</h2>

        <div class="animal-layout">
            <div class="animal-list">
                <?php foreach ($marais as $animal): ?>
                    <div class="animal-item">
                        <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                        <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                        <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                        <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                        <p><strong>Habitat :</strong> <?php echo $animal['habitat']; ?></p>
                    </div>

                        <!-- Détails cachés au départ -->
                        <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                            <p><strong>Nourriture :</strong> <?php echo $animal['food_given'] ?? 'Non défini'; ?></p>
                            <p><strong>Quantité :</strong> <?php echo $animal['quantity'] ?? 'Non défini'; ?></p>
                            <p><strong>Date :</strong> <?php echo $animal['feeding_date'] ?? 'Non défini'; ?></p>
                            <p><strong>État de santé :</strong> <?php echo $animal['status'] ?? 'Non défini'; ?></p>
                            <p><strong>Commentaire du vétérinaire :</strong> <?php echo $animal['vet_comment'] ?? 'Non défini'; ?></p> 
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script src="script.js"></script>
</body>
</html>