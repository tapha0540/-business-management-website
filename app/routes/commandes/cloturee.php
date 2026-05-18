<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = $reqData['id'] ?? null;

        if (!$id) {
            throw new Exception('id requis');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';

        $ctrl = new CommandeController($pdo);
        $result = $ctrl->closeOrder((int) $id);

        echo json_encode([
            'message' => 'Commande clôturée et facture générée',
            'success' => true,
            'data' => $result
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/error_log.log');
        echo json_encode([
            'message' => $e->getMessage(),
            'success' => false
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        'message' => 'Mauvaise Method de requete',
        'success' => false
    ], JSON_UNESCAPED_UNICODE);
}
