<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $id = $reqData['id'] ?? null;
        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';
        $ctrl = new CommandeController($pdo);
        $success = $ctrl->update($id, $reqData);
        echo json_encode([
            'message' => $success ? 'Commande mise à jour' : 'Erreur lors de la mise à jour',
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
