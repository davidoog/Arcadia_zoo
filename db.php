<?php
// Connexion à la base de données JawsDB sur Heroku
$host = 'd6vscs19jtah8iwb.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$db = 'trt076z2um7x570f'; // nom de la base de données JawsDB
$user = 'iagpxxf0qr3pb6i0'; // nom d'utilisateur JawsDB
$pass = 'qtljsvffqzkgeymq'; // mot de passe JawsDB
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