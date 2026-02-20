<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        require_once '../../config/database.php';
        require_once '../../controllers/dashboardController.php';
        $dashboardController = new DashboardController($pdo);
        echo json_encode([
            'message' => 'Operation réussie',
            'success' => true,
            'data' => $dashboardController->getMonthlyRevenues()
        ]);
    } catch (Exception $e) {
        error_log("\n " . $e->getFile() . ' -> ' . $e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            'message' => 'Erreur coté serveur.',
            'success' => false
        ]);
    }
}