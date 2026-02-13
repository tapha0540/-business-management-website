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
                return [
                    ['id' => 1, 'nom' => 'most-sold-products']
                ];
            case 'best-customers':
                return [
                    ['id' => 1, 'nom' => 'best-customers']
                ];
            case 'product-at-risk-of-out-of-stock':
                return [
                    ['id' => 1, 'nom' => 'product-at-risk-of-out-of-stock']
                ];
            default:
                // latest-orders
                require_once __DIR__ . '/../models/Commandes.php';
                return Commandes::latestOrders($this->pdo, $limit, $from, $to);
        }
    }
}