<?php

// Charger l'autoloader de Composer
require 'vendor/autoload.php';

if (!class_exists('Database')) {
    class Database {
        private $pdo;
        private $mongoClient;  // Propriété pour le client MongoDB
        private $mongoDb;      // Propriété pour la base de données MongoDB

        // Méthode pour créer une connexion à la base de données
        public function __construct() {
            // Connexion à MySQL
            if (getenv('JAWSDB_URL')) {
                // Connexion à la base de données JawsDB sur Heroku
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url["host"];
                $db = substr($url["path"], 1); // Extraction du nom de la base de données
                $user = $url["user"];
                $pass = $url["pass"]; // Le mot de passe récupéré de la variable d'environnement
            } else {
                // Connexion à la base de données locale via les variables d'environnement
                $host = getenv('DB_HOST') ?: 'localhost';  // Utilise la variable d'environnement ou localhost par défaut
                $db = getenv('DB_NAME') ?: 'arcadia_db';   // Utilise la variable d'environnement ou arcadia_db par défaut
                $user = getenv('DB_USER') ?: 'root';       // Utilise la variable d'environnement ou root par défaut
                $pass = getenv('DB_PASS') ?: '';           // Utilise la variable d'environnement ou mot de passe vide par défaut
            }

            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                // Connexion à la base de données MySQL
                $this->pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // Si une erreur survient, afficher un message d'erreur
                die("Erreur de connexion à MySQL: " . $e->getMessage());
            }

            // Connexion à MongoDB
            try {
                $mongoUri = getenv('MONGODB_URI'); // Récupère la variable d'environnement MONGODB_URI
                if ($mongoUri) {
                    // Utiliser simplement l'URI sans le mécanisme MONGODB-CR si vous n'avez pas de sécurité SSL
                    $this->mongoClient = new MongoDB\Client($mongoUri);  // Crée l'objet MongoDB\Client
                    $this->mongoDb = $this->mongoClient->selectDatabase('arcadia_zoo');  // Sélectionne la base de données MongoDB
                } else {
                    throw new Exception("MongoDB URI n'est pas défini dans la variable d'environnement.");
                }
            } catch (Exception $e) {
                die("Erreur de connexion à MongoDB: " . $e->getMessage());
            }
        }

        // Méthode pour récupérer l'instance PDO
        public function getConnection() {
            return $this->pdo;
        }

        // Méthode pour récupérer l'instance MongoDB
        public function getMongoDb() {
            if ($this->mongoDb) {
                return $this->mongoDb;
            } else {
                throw new Exception("La connexion MongoDB n'a pas été établie.");
            }
        }
    }
}
?>