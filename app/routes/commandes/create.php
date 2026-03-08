<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';

        $vendeur_id = $reqData['vendeur_id'] ?? null;
        $client_id = $reqData['client_id'] ?? null;
        $date_commande = $reqData['date_commande'] ?? date('Y-m-d');
        $details = $reqData['details'] ?? [];

        if (!$vendeur_id || !$client_id) {
            echo json_encode([
                'message' => 'vendeur_id et client_id requis',
                'success' => false
            ]);
            exit;
        }

        if (empty($details)) {
            echo json_encode([
                'message' => 'Au moins un produit requis',
                'success' => false
            ]);
            exit;
        }

        $ctrl = new CommandeController($pdo);
        $result = $ctrl->createWithDetails($vendeur_id, $client_id, $date_commande, $details);

        echo json_encode([
            'message' => 'Commande créée avec succès',
            'success' => true,
            'data' => $result
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/error_log.log');
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

