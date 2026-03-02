<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        require_once '../../config/database.php';
        require_once '../../controllers/UtilisateurController.php';
        $ctrl = new UtilisateurController($pdo);
        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $ctrl->getAll()
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
