<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $reqBody = file_get_contents('php://input');
        $data = json_decode($reqBody, true);

        $first_name = $data['first_name'] ?? 'hello';
        $last_name = $data['last_name'] ?? 'World';
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
        require_once '../../controllers/UserController.php';
        require_once '../../config/database.php';
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        if ($role !== 'admin') {
            $userController = new UserController($pdo);
            echo json_encode($userController->createUser($first_name, $last_name, $email, $passwordHash, $role));

        } else {
            // Pour des raisons de sécurité, empêcher la création directe d'un utilisateur admin via cette route
            echo json_encode([
                'success' => false,
                'message' => 'Création de compte admin non autorisée.'
            ]);
            exit;
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