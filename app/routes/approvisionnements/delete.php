<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $approvisionnements_ids = $reqData['approvisionnements_ids'] ?? [];

        if (empty($approvisionnements_ids)) {
            throw new Exception('Aucun ID fourni');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/ApprovisionnementController.php';

        $controller = new ApprovisionnementController($pdo);
        $controller->deleteMultiple($approvisionnements_ids);

        echo json_encode([
            'message' => 'Approvisionnement(s) supprimé(s)',
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
