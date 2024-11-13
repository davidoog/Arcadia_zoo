<?php
// Vérifie si la classe Database n'a pas déjà été déclarée
if (!class_exists('Database')) {
    class Database {
        private $pdo;

        // Méthode pour créer une connexion à la base de données
        public function __construct() {
            // Vérifie si l'application est sur Heroku ou en local
            if (getenv('JAWSDB_URL')) {
                // Si l'application est sur Heroku, récupérer les informations de connexion depuis l'URL
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url["host"];
                $db = substr($url["path"], 1); // Enlève le "/" au début du nom de la base de données
                $user = $url["user"];
                $pass = $url["pass"];
            } else {
                // Si l'application est en local, utiliser les paramètres locaux
                $host = 'localhost';
                $db = 'arcadia_db'; // Nom de ta base de données locale
                $user = 'root';
                $pass = ''; // Si tu n'as pas de mot de passe local
            }

            $charset = 'utf8mb4'; // Encodage de la base de données
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Afficher les erreurs sous forme d'exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération des résultats
                PDO::ATTR_EMULATE_PREPARES => false, // Désactiver la préparation émulée
            ];

            try {
                // Créer la connexion PDO
                $this->pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // Si la connexion échoue, afficher l'erreur et lancer une exception
                echo "Erreur de connexion à la base de données: " . $e->getMessage();
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