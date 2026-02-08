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
                return [
                    ['id' => 1, 'nom' => 'fall']
                ];
            case 'most-sold-products':
                return [
                    ['id' => 1, 'nom' => 'fall']
                ];
            case 'best-customers':
                return [
                    ['id' => 1, 'nom' => 'fall']
                ];
            case 'product-at-risk-of-out-of-stock':
                return [
                    ['id' => 1, 'nom' => 'fall']
                ];
            default:
                // latest-orders
                return [
                    ['id' => 1, 'nom' => 'fall']
                ];
        }
    }
}