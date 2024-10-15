<?php
// Vérifie si la classe Database n'a pas déjà été déclarée
if (!class_exists('Database')) {
    class Database {
        private $pdo;

        // Méthode pour créer une connexion à la base de données
        public function __construct() {
            // Vérifie si l'application est en local ou sur Heroku
            if (getenv('JAWSDB_URL')) {
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url["host"];
                $db = substr($url["path"], 1);
                $user = $url["user"];
                $pass = $url["pass"];
            } else {
                // Connexion à la base de données locale
                $host = 'localhost';
                $db = 'arcadia_db';
                $user = 'root';
                $pass = '';
            }

            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $this->pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
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