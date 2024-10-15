<?php
class Service {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer tous les services
    public function getAllServices() {
        $stmt = $this->pdo->query("SELECT * FROM services");
        return $stmt->fetchAll();
    }

    // Méthode pour récupérer un service par ID
    public function getServiceById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Méthode pour ajouter un service (si nécessaire)
    public function addService($title, $subtitle1, $description1, $subtitle2 = null, $description2 = null, $subtitle3 = null, $description3 = null) {
        $stmt = $this->pdo->prepare("INSERT INTO services (title, subtitle1, description1, subtitle2, description2, subtitle3, description3) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle1, $description1, $subtitle2, $description2, $subtitle3, $description3]);
    }

    // Méthode pour modifier un service
    public function updateService($id, $title, $subtitle1, $description1, $subtitle2 = null, $description2 = null, $subtitle3 = null, $description3 = null) {
        $stmt = $this->pdo->prepare("UPDATE services SET title = ?, subtitle1 = ?, description1 = ?, subtitle2 = ?, description2 = ?, subtitle3 = ?, description3 = ? WHERE id = ?");
        $stmt->execute([$title, $subtitle1, $description1, $subtitle2, $description2, $subtitle3, $description3, $id]);
    }

    // Méthode pour supprimer un service
    public function deleteService($id) {
        $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>