<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $limit = $reqData['limit'] ?? 10;
        $search = $reqData['search'] ?? '';

        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';
        $commandeController = new CommandeController($pdo);
        $commades = $commandeController->getAll($limit, $search);
        echo json_encode([
            "message" => "Opération réussie",
            "success" => true,
            "data" => $commades
        ]);
    } catch (Exception $e) {
        error_log("\n " . $e->getFile() . " -> " . $e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            "message" => "Erreur cote serveur",
            "success" => false
        ]);
    }
} else {
    echo json_encode([
        "message" => "Mauvaise Method de requete",
        "success" => false
    ]);
}
