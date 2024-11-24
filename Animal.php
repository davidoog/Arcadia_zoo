<?php
class Animal {
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour ajouter un animal
    public function addAnimal($name, $race, $habitat, $health, $food, $food_quantity, $food_unit, $last_checkup, $image_path) {
        $stmt = $this->pdo->prepare("INSERT INTO animals (name, race, habitat, health, food, food_quantity, food_unit, last_checkup, image) 
                                     VALUES (:name, :race, :habitat, :health, :food, :food_quantity, :food_unit, :last_checkup, :image)");
        return $stmt->execute([
            'name' => $name,
            'race' => $race,
            'habitat' => $habitat,
            'health' => $health,
            'food' => $food,
            'food_quantity' => $food_quantity,
            'food_unit' => $food_unit,
            'last_checkup' => $last_checkup,
            'image' => $image_path
        ]);
    }

    // Méthode pour récupérer tous les animaux
    public function getAllAnimals() {
        $stmt = $this->pdo->query("SELECT * FROM animals");
        return $stmt->fetchAll();
    }

    // Méthode pour supprimer un animal
    public function deleteAnimal($id) {
        $stmt = $this->pdo->prepare("DELETE FROM animals WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Méthode pour récupérer un seul animal par ID
    public function getAnimalById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM animals WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>