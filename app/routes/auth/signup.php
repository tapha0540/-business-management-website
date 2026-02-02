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
        $role = $data['role'] ?? '';

        // Example validation and processing
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Tous les champs sont requis.'
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
        


        echo json_encode([
            'success' => true,
            'message' => 'Inscription réussie.'
        ]);
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