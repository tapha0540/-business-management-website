<?php

class CategorieController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCategorie($nom, $description)
    {
        if (empty($nom) || empty($description)) {
            return [
                "message" => "Tous le champs sont obligatoires.",
                "success" => false
            ];
        }
        // Logic to create a new category with provided data
        $stmt = $this->pdo->prepare("INSERT INTO categories(nom, description) VALUES (?, ?)");
        $isCategorieCreated = $stmt->execute([$nom, $description]);

        if ($isCategorieCreated) {
            return [
                "message" => "Catégorie créée avec succès",
                "success" => true
            ];
        } else {
            return [
                "message" => "Échec de la création de la catégorie",
                "success" => false
            ];
        }
    }
    public function updateCategorie($nom, $description)
    {
        if (empty($nom) || empty($description)) {
            throw new Exception("Tous le champs sont obligatoires.");
        }
        $stmt = $this->pdo->prepare("UPDATE categories SET nom=?, description=? WHERE id=?");
        $isCategorieUpdated = $stmt->execute([$nom, $description]);

        if ($isCategorieUpdated) {
            return [
                "message" => "Catégorie mise à jour avec succès",
                "success" => true
            ];
        } else {
            return [
                "message" => "Échec de la mise à jour de la catégorie",
                "success" => false
            ];
        }
    }
    public function deleteCategorie(int $id)
    {
        if (empty($id)) {
            throw new Exception("L'ID de la catégorie est obligatoire.");
        }
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id=?");
        $isCategorieDeleted = $stmt->execute([$id]);

        if ($isCategorieDeleted) {
            return [
                "message" => "Catégorie supprimée avec succès",
                "success" => true
            ];
        } else {
            return [
                "message" => "Échec de la suppression de la catégorie",
                "success" => false
            ];
        }
    }
}