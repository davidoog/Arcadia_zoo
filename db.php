<?php
// Récupérer l'URL de la base de données depuis les variables d'environnement
$url = getenv('JAWSDB_URL');

if ($url) {
    $dbparts = parse_url($url);

    $host = $dbparts['host'];
    $user = $dbparts['user'];
    $pass = $dbparts['pass'];
    $db = ltrim($dbparts['path'], '/');
} else {
    // Configuration pour l'environnement local
    $host = 'localhost';
    $db = 'arcadia_db';
    $user = 'root';
    $pass = '';
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
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>