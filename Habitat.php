<?php
class Habitat {
    private $pdo;

    // Constructeur pour initialiser l'objet avec la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer tous les habitats
    public function getAllHabitats() {
        $stmt = $this->pdo->query("SELECT * FROM habitats");
        return $stmt->fetchAll();
    }

    // Méthode pour récupérer un habitat par ID
    public function getHabitatById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM habitats WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Méthode pour supprimer un habitat
    public function deleteHabitat($id) {
        $stmt = $this->pdo->prepare("DELETE FROM habitats WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Méthodes supplémentaires pour ajouter, modifier les habitats, etc.
}
?>