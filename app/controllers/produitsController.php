<?php
require_once __DIR__ . '/../models/Produit.php';


class produitsController
{
    private PDO $pdo;
    private Produit $produitModel;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->produitModel = new Produit($this->pdo);
    }

    public function getAll(int $limit, string $search)
    {
        return $this->produitModel::getAll($this->pdo, $limit, $search);
    }

    public function getProduit(int $id)
    {
        return $this->produitModel->get($id);
    }

    public function create(array $data)
    {
        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;
        $categorie_id = $data['categorie_id'] ?? null;
        $prix_vente = $data['prix_vente'] ?? null;
        $quantite = $data['quantite'] ?? null;
        $seuil_critique = $data['seuil_critique'] ?? null;
        $image = $data['image'] ?? null;
        if (!$nom || !$categorie_id) {
            throw new Exception('Nom et catégorie requis');
        }
        require_once __DIR__ . '/../utils/produits/enregistrerProduitImg.php';
        $imgName = null;
        if ($image) {
            $imgName = EnregistrerProduitImg($image);
        }
        $prod = new Produit(
            $this->pdo,
            null,
            $nom,
            $description,
            $imgName,
            $categorie_id,
            $prix_vente,
            $quantite,
            $seuil_critique
        );
        return $prod->create();
    }

    public function update(int $id, array $data)
    {
        $existing = $this->getProduit($id);
        if (!$existing) {
            throw new Exception('Produit introuvable');
        }
        $nom = $data['nom'] ?? $existing['nom'];
        $description = $data['description'] ?? $existing['description'];
        $categorie_id = $data['categorie_id'] ?? $existing['categorie_id'];
        $prix_vente = $data['prix_vente'] ?? $existing['prix_vente'];
        $quantite = $data['quantite'] ?? $existing['quantite'];
        $seuil_critique = $data['seuil_critique'] ?? $existing['seuil_critique'];
        $image = $data['image'] ?? null;

        require_once __DIR__ . '/../utils/produits/enregistrerProduitImg.php';
        require_once __DIR__ . '/../utils/produits/deleteProduitImg.php';
        $newImageName = $existing['imgUrl'];
        if ($image) {
            // delete old
            deleteImage($existing['imgUrl']);
            $newImageName = EnregistrerProduitImg($image);
        }

        $prod = new Produit(
            $this->pdo,
            $id,
            $nom,
            $description,
            $newImageName,
            $categorie_id,
            $prix_vente,
            $quantite,
            $seuil_critique
        );
        return $prod->update(
            $nom,
            $description,
            $newImageName,
            $categorie_id,
            $prix_vente,
            $quantite,
            $seuil_critique
        );
    }

    public function SupprimmerProduits(array $produitsIds)
    {
        $success = true;
        foreach ($produitsIds as $produitsId) {
            $produitModel = new Produit($this->pdo, $produitsId);
            $success |= $produitModel->delete();
        }
        return $success;
    }
}
