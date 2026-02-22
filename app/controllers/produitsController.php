<?php
require_once __DIR__ . '/../models/Produit.php';


class produitsController {
    private PDO $pdo;
    private Produit $produitModel;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->produitModel = new Produit($this->pdo);
    }
    public function getAll(int $limit) {
        return $this->produitModel::getAll($this->pdo, $limit);
    }


}