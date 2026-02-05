<?php

require_once '../../config/app.php';
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input') ?? '{}', true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode([
                'message' => "Tous les champs sont obligatoires.",
                'success' => false
            ]);
        }
        require_once '../../config/database.php';
        require_once '../../controllers/AuthController.php';

        $authController = new AuthController($pdo);

        $result = $authController->login($email, $password);
        if ($result['success']) {
            $lifetime = 60 * 60 * 24 * 60; // 60 jours = 2 mois

            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => '/',
                'domain' => '',
                'secure' => false, // true si HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
            $_SESSION['user'] = $result['user'];
        }
        echo json_encode($result);

    } catch (Exception $e) {

        error_log('\n ' . $e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            'message' => 'Erreur cote serveur',
            'success' => false
        ]);
    }
}