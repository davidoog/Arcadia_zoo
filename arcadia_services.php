<?php
session_start(); // Démarre la session
require 'db.php';

// Récupérer tous les services depuis la base de données
$services = $pdo->query("SELECT * FROM services")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="services.css">
</head>
<body>
    <div class="topbar">
        <div class="menu-icon">
            <div class="menu-hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
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

    <main>
        

        <div class="section-principale">
            <div class="small-logo">
                <span class="small-lettre-z">Z</span>
                <span class="small-lettre-o1">O</span>
                <span class="small-lettre-o2">O</span>
                <br><span class="small-Arcadia">Arcadia</span>
            </div>
        
            <h1><span class="titre-services">Les services du parc</span></h1>
        </div>


        <img src="./image/singe-service.png" alt="Singe" class="singe-service">

        <div class="all-card">
        <?php foreach ($services as $service): ?>
    <div class="card-group">
        <div class="card-restauration">
            <h1 class="h1-restauration"><?= htmlspecialchars($service['title']); ?></h1>

            <?php if (isset($service['subtitle1'])): ?>
    <h3 class="subtitle"><?= htmlspecialchars($service['subtitle1']); ?></h3>
    <p class="description"><?= isset($service['description1']) ? htmlspecialchars($service['description1']) : ''; ?></p>
<?php endif; ?>

<?php if (isset($service['subtitle2'])): ?>
    <h3 class="subtitle"><?= htmlspecialchars($service['subtitle2']); ?></h3>
    <p class="description"><?= isset($service['description2']) ? htmlspecialchars($service['description2']) : ''; ?></p>
<?php endif; ?>

<?php if (isset($service['subtitle3'])): ?>
    <h3 class="subtitle"><?= htmlspecialchars($service['subtitle3']); ?></h3>
    <p class="description"><?= isset($service['description3']) ? htmlspecialchars($service['description3']) : ''; ?></p>
<?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
        </div>
    </main>
</body>
</html>