<?php
session_start();
require 'db.php'; // Connexion à la base de données
require 'mongo_connection.php'; // Connexion à MongoDB

// Récupérer les animaux par habitat pour un affichage dynamique
$savane_animals = $pdo->prepare("SELECT * FROM animals WHERE habitat = 'savane'");
$savane_animals->execute();
$savane = $savane_animals->fetchAll();

$jungle_animals = $pdo->prepare("SELECT * FROM animals WHERE habitat = 'jungle'");
$jungle_animals->execute();
$jungle = $jungle_animals->fetchAll();

$marais_animals = $pdo->prepare("SELECT * FROM animals WHERE habitat = 'marais'");
$marais_animals->execute();
$marais = $marais_animals->fetchAll();

// Récupérer tous les habitats pour l'affichage dynamique
$habitats = $pdo->prepare("SELECT * FROM habitats");
$habitats->execute();
$all_habitats = $habitats->fetchAll();

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
                    <li><a href="arcadia_contact.html">Contact</a></li>
                    <li><a href="arcadia_connexion.html">Connexion</a></li>
                </ul>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="arcadia_accueil.php">Retour vers la page d'accueil</a></li>
                    <li><a href="arcadia_habitats.php">Accès à tous les habitats</a></li>
                    <li><a href="arcadia_services.php">Accès à tous les services</a></li>
                    <li><a href="arcadia_contact.html">Contact</a></li>
                    <li class="connexion"><a href="arcadia_connexion.html" class="btn btn-primary">Connexion</a></li>
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
                    

                    <!-- PHP POUR LES ANIMAUX DE LA BDD (FUTUR AJOUT) -->
                    <?php foreach ($savane as $animal): ?>
                        <div class="animal-item">
                        
                            <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                                <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                                <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                                <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                                <p><strong>Habitat :</strong> <?php echo $animal['habitat']; ?></p>
                            </div>
                        
                            <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                                <p><strong>État de l'animal :</strong> <?php echo $animal['health']; ?></p>
                                <p><strong>Nourriture proposée :</strong> <?php echo $animal['food']; ?></p>
                                <p><strong>Grammage de la nourriture :</strong> <?php echo $animal['food_quantity'] . ' ' . $animal['food_unit']; ?></p>
                                <p><strong>Date de passage :</strong> <?php echo $animal['last_checkup']; ?></p>

                                
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
                   

                    <!-- PHP POUR LES ANIMAUX DE LA BDD (FUTUR AJOUT) -->
                    <?php foreach ($jungle as $animal): ?>
                        <div class="animal-item">
                            <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                                <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                                <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                                <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                                <p><strong>Habitat :</strong> Jungle Tropicale</p>
                            </div>
                            <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                                <p><strong>État de l'animal :</strong> <?php echo $animal['health']; ?></p>
                                <p><strong>Nourriture proposée :</strong> <?php echo $animal['food']; ?></p>
                                <p><strong>Grammage de la nourriture :</strong> <?php echo $animal['food_quantity'] . ' ' . $animal['food_unit']; ?></p>
                                <p><strong>Date de passage :</strong> <?php echo $animal['last_checkup']; ?></p>
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
                   

                    <!-- PHP POUR LES ANIMAUX DE LA BDD (FUTUR AJOUT) -->
                    <?php foreach ($marais as $animal): ?>
                        <div class="animal-item">
                            <div class="animal-card" onclick="showAnimalDetails('<?php echo $animal['name']; ?>')">
                                <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['race']; ?>">
                                <p><strong>Prénom :</strong> <?php echo $animal['name']; ?></p>
                                <p><strong>Race :</strong> <?php echo $animal['race']; ?></p>
                                <p><strong>Habitat :</strong> Marais</p>
                            </div>
                            <div id="details-<?php echo $animal['name']; ?>" class="animal-details hidden">
                                <p><strong>État de l'animal :</strong> <?php echo $animal['health']; ?></p>
                                <p><strong>Nourriture proposée :</strong> <?php echo $animal['food']; ?></p>
                                <p><strong>Grammage de la nourriture :</strong> <?php echo $animal['food_quantity'] . ' ' . $animal['food_unit'] . ' par jour'; ?></p>
                                <p><strong>Date de passage :</strong> <?php echo $animal['last_checkup']; ?></p>
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