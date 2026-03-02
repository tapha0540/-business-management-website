<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        require_once '../../config/database.php';
        require_once '../../controllers/FournisseurController.php';
        $ctrl = new FournisseurController($pdo);
        $success = $ctrl->create($reqData);
        echo json_encode([
            'message' => $success ? 'Fournisseur créé' : 'Erreur lors de la création',
            'success' => (bool) $success
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
