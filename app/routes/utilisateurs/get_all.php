<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        require_once '../../config/database.php';
        require_once '../../controllers/UtilisateurController.php';
        $ctrl = new UtilisateurController($pdo);
        $reqData = json_decode(file_get_contents('php://input'), true);
        $search = $reqData['search'] ?? ""; 
        $limit  = $reqData['limit'] ?? 10; 
        $role = $reqData['role'] ?? "";
        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $ctrl->getAll($search, $limit, $role)
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
