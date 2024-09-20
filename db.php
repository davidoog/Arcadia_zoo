<?php
// Vérifie si l'application est en local ou sur Heroku
if (getenv('JAWSDB_URL')) {
    // Connexion à la base de données JawsDB sur Heroku
    $url = parse_url(getenv('JAWSDB_URL'));
    $host = $url["host"];
    $db = substr($url["path"], 1);
    $user = $url["user"];
    $pass = $url["pass"];
} else {
    // Connexion à la base de données locale
    $host = 'localhost'; // L'adresse de ta base de données locale
    $db = 'arcadia_db';  // Nom de ta base de données locale
    $user = 'root';      // Nom d'utilisateur de ta base de données locale
    $pass = '';          // Mot de passe pour la base de données locale (souvent vide pour localhost)
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>