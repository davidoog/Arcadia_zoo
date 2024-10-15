<?php
session_start();
require_once 'db.php'; 

// Connexion à la base de données via la classe Database
$db = new Database();  
$pdo = $db->getConnection();  // Récupérer l'objet PDO  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($password === $user['password']) {
            session_regenerate_id(true); 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            switch ($user['role']) {
                case 'admin':
                    header('Location: admin_dashboard.php');
                    exit();
                case 'vet':
                    header('Location: vet_dashboard.php');
                    exit();
                case 'employee':
                    header('Location: employee_dashboard.php');
                    exit();
                default:
                    header('Location: arcadia_connexion.php?error=role');
                    exit();
            }
        } else {
            header('Location: arcadia_connexion.php?error=password');
            exit();
        }
    } else {
        header('Location: arcadia_connexion.php?error=username');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="connexion.css">
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
                    <li><a href="arcadia_connexion.php">Connexion</a></li>
                </ul>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="arcadia_accueil.php">Retour vers la page d'accueil</a></li>
                    <li><a href="arcadia_habitats.php">Accès à tous les habitats</a></li>
                    <li><a href="arcadia_services.php">Accès à tous les services</a></li>
                    <li><a href="arcadia_contact.html">Contact</a></li>
                    <li class="connexion"><a href="arcadia_connexion.php" class="btn btn-primary">Connexion</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <main> 
        <div class="section-connexion">
            <div class="small-logo-connexion">
                <span class="small-lettre-z-connexion">Z</span>
                <span class="small-lettre-o1-connexion">O</span>
                <span class="small-lettre-o2-connexion">O</span>
            </div>
            <br><span class="small-Arcadia-connexion">Arcadia</span>
            
            <h2><span class="titre-connexion">SE CONNECTER</span></h2>
            
            <!-- Affichage des messages d'erreur -->
            <div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'username') {
                        echo "<p style='color:red;'>Nom d'utilisateur introuvable.</p>";
                    } elseif ($_GET['error'] == 'password') {
                        echo "<p style='color:red;'>Mot de passe incorrect.</p>";
                    } elseif ($_GET['error'] == 'role') {
                        echo "<p style='color:red;'>Rôle non reconnu.</p>";
                    }
                }
                ?>
            </div>

            <!-- Formulaire de connexion (Desktop) -->
            <form action="login.php" method="POST">
                <input type="text" name="username" id="username_input_unique" placeholder="Username (mail)" required>
                <input type="password" name="password" id="password_input_unique" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        </div>
        
        <!-- Formulaire de connexion (Mobile) -->
        <form action="login.php" method="POST" class="login-mobile">
            <!-- Affichage des messages d'erreur (Mobile) -->
            <div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'username') {
                        echo "<p style='color:red;'>Nom d'utilisateur introuvable.</p>";
                    } elseif ($_GET['error'] == 'password') {
                        echo "<p style='color:red;'>Mot de passe incorrect.</p>";
                    } elseif ($_GET['error'] == 'role') {
                        echo "<p style='color:red;'>Rôle non reconnu.</p>";
                    }
                }
                ?>
            </div>

            <input type="text" id="username" name="username" placeholder="Username (mail)" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-dark btn-lg rounded-pill btn-custom">SE CONNECTER</button>
            <p class="text-center mt-4">
                <span class="info-connexion">* Seul les vétérinaires ou les employés peuvent se connecter</span>
            </p>
        </form>
    </main>   
    <script src="connexion.js"></script>
</body>   
</html>