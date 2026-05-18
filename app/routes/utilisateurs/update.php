<?php
session_start();

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (!isset($_SESSION['user'])) {
            throw new Exception('Utilisateur non connecté');
        }

        $reqData = json_decode(file_get_contents('php://input'), true) ?? [];
        $sessionUserId = (int) $_SESSION['user']['id'];
        $id = isset($reqData['id']) ? (int) $reqData['id'] : $sessionUserId;

        if ($id !== $sessionUserId && ($_SESSION['user']['role'] ?? '') !== 'admin') {
            throw new Exception('Action non autorisée');
        }

        require_once '../../config/database.php';
        require_once '../../controllers/UtilisateurController.php';

        $ctrl = new UtilisateurController($pdo);
        $success = $ctrl->update($id, $reqData);
        $updatedUser = $success ? $ctrl->get($id) : null;

        if ($updatedUser) {
            unset($updatedUser['mot_de_passe']);
        }

        if ($success && $id === $sessionUserId && $updatedUser) {
            $_SESSION['user'] = array_merge($_SESSION['user'], $updatedUser);
        }

        echo json_encode([
            'message' => $success ? 'Utilisateur mis à jour' : 'Erreur lors de la mise à jour',
            'success' => (bool) $success,
            'data' => $updatedUser
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/error_log.log');
        echo json_encode([
            'message' => $e->getMessage(),
            'success' => false
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        'message' => 'Mauvaise Method de requete',
        'success' => false
    ], JSON_UNESCAPED_UNICODE);
}
