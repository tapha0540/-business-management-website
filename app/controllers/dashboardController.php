<?php

class DashboardController
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getHomeTableData(int $limit, string $search, string $from, string $to)
    {
        switch ($search) {
            case 'best-orders':
                require_once __DIR__ . '/../models/Commandes.php';
                return Commandes::bestOrdersByPrice($this->pdo, $limit, $from, $to);
            case 'best-sellers':
                require_once __DIR__ . '/../models/Utilisateur.php';
                return Utilisateur::bestSellers($this->pdo, $limit, $from, $to);
            case 'most-sold-products':
                require_once __DIR__ . '/../models/Produit.php';
                return Produit::mostSoldProduct($this->pdo, $limit, $from, $to);
            case 'best-customers':
                require_once __DIR__ . '/../models/Client.php';
                return Client::bestCustomers($this->pdo, $limit, $from, $to);
            case 'product-at-risk-of-out-of-stock':
                require_once __DIR__ . '/../models/Produit.php';
                return Produit::productsAtRiskOfOutOfStock($this->pdo, $limit);
            default:
                // latest-orders
                require_once __DIR__ . '/../models/Commandes.php';
                return Commandes::latestOrders($this->pdo, $limit, $from, $to);
        }
    }
    public function getMonthlyRevenues()
    {
        require_once __DIR__ . '/../models/Facture.php';
        return Facture::getMonthlyRevenues($this->pdo);
    }
}