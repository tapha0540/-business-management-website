<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $reqBody = json_decode(file_get_contents('php://input'), true);
        $nom = $reqBody['nom'];
        $description = $reqBody['description'];
        $categorie_id = $reqBody['categorie_id'];
        $prix_vente = $reqBody['prix_vente'];
        $quantite = $reqBody['quantite'];
        $seuil_critique = $reqBody['seuil_critique'];
        $base64Image = $reqBody['image'];
        require_once __DIR__ . "/../../utils/produits/enregistrerProduitImg.php";
        $imageName = EnregistrerProduitImg($base64Image);

        if (!$imageName) {
            echo json_encode([
                'message' => "Erreur, l'image n'a pas été uploadé.",
                'success' => false
            ]);
            exit;
        }
        
        require_once '../../config/database.php';
        require_once '../../models/Produit.php';
        $produitModel = new Produit(
            $pdo,
            null,
            $nom,
            $description,
            $imageName,
            $categorie_id,
            $prix_vente,
            $quantite,
            $seuil_critique
        );
        echo json_encode([
            'message' => 'Opération réussie',
            'success' => true,
            'data' => $produitModel->create()
        ]);
    } catch (Exception $e) {
        error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, $erro_log_path);
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

