<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $commande_id = $reqData['commande_id'] ?? null;

        if (!$commande_id) {
            echo json_encode([
                'message' => 'commande_id requis',
                'success' => false
            ]);
            exit;
        }

        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';

        $ctrl = new CommandeController($pdo);
        $ctrl->cancelOrder($commande_id);

        echo json_encode([
            'message' => 'Commande annulée et stock restauré',
            'success' => true
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/error_log.log');
        echo json_encode([
            'message' => $e->getMessage(),
            'success' => false
        ]);
    }
} else {
    echo json_encode([
        'message' => 'Mauvaise Method de requete',
        'success' => false
    ]);
}
