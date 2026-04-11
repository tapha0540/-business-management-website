<?php


session_start();
header('Location: /html/auth/signin.html');

if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vous n etes pas connecte.'
    ]);
    exit;
}

session_destroy();

echo json_encode([
    'success' => false,
    'message' => 'Deconnexion reussie.'
]);