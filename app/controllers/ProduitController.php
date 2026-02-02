<?php

class ProduitController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createProduct(string $name, string $description, int $category_id, float $price, int $quantity, int $critical_level)
    {
        if (empty($name) || empty($description) || empty($category_id) || empty($price) || empty($quantity) || empty($critical_level)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO products (nom, description, category_id, prix_vente, quantite_stock, seuil_critique) VALUES (?, ?, ?, ?, ?, ?)");
        $isProductCreated = $stmt->execute([$name, $description, $category_id, $price, $quantity, $critical_level]);
        return [
            "message" => $isProductCreated ? "Produit créé avec succès" : "Échec de la création du produit",
            "success" => $isProductCreated
        ];
    }
    public function updateProduct(string $name, string $description, int $category_id, float $price, int $quantity, int $critical_level, int $productId)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET nom=?, description=?, category_id=?, prix_vente=?, quantite_stock=?, seuil_critique=? WHERE id=?");
        $isProductUpdated = $stmt->execute([$name, $description, $category_id, $price, $quantity, $critical_level, $productId]);
        return [
            "message" => $isProductUpdated ? "Produit mis à jour avec succès" : "Échec de la mise à jour du produit",
            "success" => $isProductUpdated
        ];
    }
    public function deleteProduct(int $productId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $isProductDeleted = $stmt->execute([$productId]);
        return [
            "message" => $isProductDeleted ? "Produit supprimé avec succès" : "Échec de la suppression du produit",
            "success" => $isProductDeleted
        ];
    }
    public function getProduct(int $productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            "message" => $product ? "Produit récupéré avec succès" : "Échec de la récupération du produit",
            "success" => !empty($product),
            "product" => $product
        ];
    }
}