<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $utilisateurs_ids = $reqData['utilisateurs_ids'] ?? [];
        require_once '../../config/database.php';
        require_once '../../controllers/UtilisateurController.php';
        $ctrl = new UtilisateurController($pdo);
        
        $success = true;
        foreach ($utilisateurs_ids as $id) {
            if (!$ctrl->delete($id)) {
                $success = false;
                error_log('\n ' . __FILE__ . " -> Erreur lors de la suppression de l'utilisateur d'id: $id", 3, __DIR__ . '/../../storage/logs/error_log.log');
            }
        }
        
        echo json_encode([
            'message' => $success ? 'Opération réussie' : 'Erreur lors de la suppression des utilisateurs',
            'success' => $success
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
