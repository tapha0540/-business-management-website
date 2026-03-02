<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $id = $reqData['id'] ?? null;
        $details = $reqData['details'] ?? [];

        if (!$id) {
            throw new Exception('ID requis');
        }

        if (empty($details)) {
            throw new Exception('Au moins un produit requis');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/ApprovisionnementController.php';

        $controller = new ApprovisionnementController($pdo);
        $controller->update($id, [
            'details' => $details
        ]);

        echo json_encode([
            'message' => 'Approvisionnement mis à jour',
            'success' => true
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, $erro_log_path);
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
