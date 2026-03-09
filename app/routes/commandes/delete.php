<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqData = json_decode(file_get_contents('php://input'), true);
        $ids = $reqData['ids'] ?? null;
        $id = $reqData['id'] ?? null;

        if (!is_array($ids)) {
            if ($id !== null) {
                $ids = [(int) $id];
            } else {
                throw new Exception('id requis');
            }
        }

        $ids = array_values(array_filter(array_map('intval', $ids), fn($value) => $value > 0));

        if (count($ids) === 0) {
            throw new Exception('Aucune commande valide à supprimer');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/CommandesController.php';

        $ctrl = new CommandeController($pdo);
        $success = true;

        foreach ($ids as $commandeId) {
            if (!$ctrl->delete($commandeId)) {
                $success = false;
            }
        }

        echo json_encode([
            'message' => $success ? 'Commande(s) supprimée(s)' : 'Erreur lors de la suppression de certaines commandes',
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

