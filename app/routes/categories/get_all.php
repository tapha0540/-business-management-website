<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
       

        require_once '../../config/database.php';
        require_once '../../controllers/CategorieController.php';
        $categorieController = new CategorieController($pdo);
        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $categorieController->getAllCategories()
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, $erro_log_path);
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