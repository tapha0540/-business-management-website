<?php
require 'app/config/database.php';
require 'app/controllers/dashboardController.php';
$searches = [
    'latest-orders',
    'best-orders',
    'best-sellers',
    'most-sold-products',
    'best-customers',
    'product-at-risk-of-out-of-stock',
];
$c = new DashboardController($pdo);
foreach ($searches as $s) {
    echo "--- $s ---\n";
    try {
        $r = $c->getHomeTableData(5, $s, date('Y-m-d', strtotime('-1 year')), date('Y-m-d'));
        echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    } catch (Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
