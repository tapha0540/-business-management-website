<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $id = $reqData['id'] ?? null;
        require_once '../../config/database.php';
        require_once '../../controllers/FournisseurController.php';
        $ctrl = new FournisseurController($pdo);
        $success = $ctrl->delete($id);
        echo json_encode([
            'message' => $success ? 'Fournisseur supprimé' : 'Erreur lors de la suppression',
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
