<?php
require_once __DIR__ . '/../models/Commandes.php';

class CommandeController
{
    private PDO $pdo;
    private Commandes $commandeModel;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->commandeModel = new Commandes($this->pdo);
    }
    public function getAll(int $limit) {
        return $this->commandeModel->getAll($limit);
    }
}