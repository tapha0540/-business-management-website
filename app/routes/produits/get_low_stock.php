<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        require_once '../../config/database.php';
        require_once '../../models/Produit.php';

        $produitModel = new Produit($pdo);
        $produitsFaibles = Produit::productsAtRiskOfOutOfStock($pdo, 10);

        echo json_encode([
            'message' => 'Produits en alerte récupérés',
            'success' => true,
            'data' => $produitsFaibles
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
