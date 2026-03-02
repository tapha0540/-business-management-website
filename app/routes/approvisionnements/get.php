<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $id = $reqData['id'] ?? null;

        if (!$id) {
            throw new Exception('ID requis');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/ApprovisionnementController.php';

        $controller = new ApprovisionnementController($pdo);
        $approv = $controller->get($id);

        if (!$approv) {
            throw new Exception('Approvisionnement non trouvé');
        }

        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $approv
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
