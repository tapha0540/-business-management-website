<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Example validation and processing
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }


    echo json_encode(['success' => true, 'message' => 'Inscription réussie.']);

} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}