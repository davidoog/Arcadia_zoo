<?php
// Vérifie si la classe Database n'a pas déjà été déclarée
if (!class_exists('Database')) {
    class Database {
        private $pdo;

        // Méthode pour créer une connexion à la base de données
        public function __construct() {
            // Vérifie si l'application est en local ou sur Heroku
            if (getenv('JAWSDB_URL')) {
                // Connexion à la base de données JawsDB sur Heroku
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url["host"];
                $db = substr($url["path"], 1); // Extraction du nom de la base de données
                $user = $url["user"];
                $pass = $url["pass"]; // Le mot de passe récupéré de la variable d'environnement
            } else {
                // Connexion à la base de données locale
                $host = 'localhost';  // L'adresse de la base de données locale
                $db = 'arcadia_db';   // Nom de la base de données locale
                $user = 'root';       // Nom d'utilisateur de la base de données locale
                $pass = '';           // Mot de passe vide pour la base de données locale
            }

            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                // Connexion à la base de données
                $this->pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // Si une erreur survient, afficher un message d'erreur
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        // Méthode pour récupérer l'instance PDO
        public function getConnection() {
            return $this->pdo;
        }
    }
}
?>