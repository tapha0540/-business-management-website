<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $reqBody = json_decode(file_get_contents('php://input'), true);
        $limit = (int) ($reqBody['limit']) ?? 10;
        $from = $reqBody['from'];
        $to = $reqBody['to'];
        $search = $reqBody['search'];

        if (!$limit || !$from || !$to || !$search) {
            echo json_encode([
                'message' => 'Tous les champs sont obligatoires.',
                'success' => false
            ]);
            exit;
        }
        require_once '../../config/database.php';
        require_once '../../controllers/dashboardController.php';
        $dashboardController = new DashboardController($pdo);

        $data = $dashboardController->getHomeTableData($limit, $search, $from, $to);

        echo json_encode([
            'message' => 'Operation reussie',
            'success' => true,
            'data' => $data
        ]);
    } catch (Exception $e) {
        error_log("\n " . $e->getFile() . ' -> ' . $e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            'message' => 'Erreur coté serveur.',
            'success' => false
        ]);
    }
}