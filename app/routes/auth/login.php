<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || $password) {
            echo json_encode([
                'message' => 'Tous les champs sont obligatoires.',
                'success' => false
            ]);
        }
        require_once '../../config/database.php';
        require_once '../../controllers/AuthController.php';

        $user = new AuthController($pdo);
        echo json_encode($user->login($email, $password));

    } catch (Exception $e) {

        error_log('\n ' . $e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            'message' => 'Erreur cote serveur',
            'success' => false
        ]);
    }
}