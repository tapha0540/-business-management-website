<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $reqBody = file_get_contents('php://input');
        $data = json_decode($reqBody, true);

        $first_name = $data['first_name'] ?? '';
        $last_name = $data['last_name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'vendeur'; // s'il n'est pas spécifié, le rôle par défaut est 'vendeur'

        // Example validation and processing
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => "Tous les champs sont requis. $email $first_name $last_name $password $role"
            ]);
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Adresse e-mail invalide.'
            ]);
            exit;

        }
        require_once '../../controllers/AuthController.php';
        require_once '../../config/database.php';
        if ($role !== 'admin') {
            $authController = new AuthController($pdo);
            echo json_encode($authController->signup($first_name, $last_name, $email, $password, $role));
        }

    } catch (Exception $e) {
        error_log($e->getMessage(), 3, $erro_log_path);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur serveur. Veuillez réessayer plus tard.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);
}