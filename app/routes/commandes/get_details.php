<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $id = $reqData['commande_id'] ?? null;

        if (!$id) {
            echo json_encode([
                'message' => 'commande_id requis',
                'success' => false
            ]);
            throw new Exception('commande_id requis');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';
        $ctrl = new CommandeController($pdo);
        $data = $ctrl->getDetails($id);
        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $data
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/error_log.log');
        echo json_encode([
            'message' => 'Erreur cote serveur',
            'success' => false
        ]);
    }
} else {
    echo json_encode([
        'message' => 'Mauvaise Method de requete',
        'success' => false
    ]);
}
