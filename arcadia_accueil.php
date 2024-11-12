<?php
require_once 'db.php';

// Connexion à la base de données via la classe Database
$db = new Database();  // Créer une instance de la classe Database
$pdo = $db->getConnection(); // Récupérer l'objet PDO 

$hours = $pdo->query("SELECT * FROM zoo_hours WHERE id = 1")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Arcadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
        <img src="./image/peroquet_1.png" alt="péroquet" class="peroquet_accueil">
        <img src="./image/singe_accueil.png" alt="singe" class="singe_accueil">

        <div class="hero-section">
            <h1 class="zoo-name">
                <span class="lettre-z">Z</span>
                <span class="lettre-o1">O</span> 
                <span class="lettre-o2">O</span>
                <br><span class="Arcadia">Arcadia</span>
            </h1>
            <p class="zoo-description">Venez découvrir le monde des animaux <br> au zoo Arcadia !</p>
            <p class="zoo-hours">Le zoo Arcadia est ouvert tous les jours de <?php echo $hours['opening_time']; ?> à <?php echo $hours['closing_time']; ?>.</p>
        </div>

        <img src="./image/button-arrow.png" alt="button-arrow" class="button-arrow">
        <img src="./image/guepard-accueil.png" alt="guepard" class="guepard_accueil">
        <img src="./image/oiseau2_accueil.png" alt="oiseau" class="oiseau2_accueil">
        <img src="./image/tigre_accueil.png" alt="tigre" class="tigre_accueil" id="tigre-accueil">
        <img src="./image/tortue_accueil.png" alt="tortue" class="tortue_accueil">

        <div class="welcome-text">
            <p>Bienvenue au <span class="highlight">Zoo Arcadia</span>, situé près de la forêt de Brocéliande en Bretagne depuis 1960. Découvrez nos divers habitats : la savane avec ses lions, girafes, guépards, éléphants et rhinocéros, la jungle tropicale avec tigres, singes et oiseaux tropicaux, et les marais peuplés d’alligators, tortues.</p>
            <p>Profitez de visites guidées éducatives et d’ateliers interactifs pour enfants. Dégustez des repas délicieux au restaurant. Nos vétérinaires dévoués viennent chaque jour pour assurer le bien-être des animaux.</p>
            <p>De plus, le <span class="highlight">Zoo Arcadia</span> est fier d’être entièrement indépendant au niveau des énergies, utilisant des sources renouvelables pour réduire notre empreinte écologique.</p>
            <p>Venez découvrir la richesse de la faune au <span class="highlight">Zoo Arcadia</span>, un lieu où nature, conservation et écologie se rencontrent pour offrir une aventure inoubliable pour toute la famille.</p>
        </div>

        <img src="./image/feuille_haut_gauche_accueil.png" alt="feuille" class="feuille_haut_gauche_accueil">
        <img src="./image/feuille_bas_droite_accueil.png" alt="feuille" class="feuille_bas_droite_accueil">
    </main>
    
    <footer>
        

        <div class="section-avis">
            <div class="avis-visiteur">
                <h2>Derniers avis visiteur</h2>
                <div class="avis">
                    <div class="note">
                        <p>5/5</p>
                        <div class="etoiles">
                            ★★★★★
                        </div>
                    </div>
                    <div class="contenu-avis">
                        <p>“Le Zoo Arcadia est tout simplement incroyable ! Les habitats sont parfaitement recréés, et les animaux semblent vraiment heureux. Nous avons passé une journée merveilleuse en famille, et les enfants ont adoré les ateliers interactifs. À refaire absolument !”</p>
                        <p class="auteur-avis">Écrit par <span>MarieD</span> le 18 Juin 2024</p>
                    </div>
                </div>
            </div>
            <div class="donner-avis">
                <a href="#" class="btn btn-avis">Donnez votre avis ici !</a>
                <div class="icone-avis">💬</div>
            </div>
        </div>
        <img src="./image/elephant-accueil.png" alt="elephants" class="elephant_accueil">    
    </footer>

    <script src="accueil.js"></script>
</body>
</html>