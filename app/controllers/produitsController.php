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
    public function SupprimmerProduits(array $produitsIds)
    {
        $success = true;
        foreach ($produitsIds as $produitsId) {
            $produitModel = new Produit($this->pdo, $produitsId);
            $success |= $produitModel->delete();
        }
        return $success;
    }
    public function getProduit(int $id) {
        return $this->produitModel->get($id);
    } 
}