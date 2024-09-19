<?php
session_start();
if ($_SESSION['role'] != 'employee') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Employé</title>
</head>
<body>
    <h1>Bienvenue, <?php echo $_SESSION['username']; ?> !</h1>
    <p>Ceci est le tableau de bord des employés.</p>
</body>
</html>