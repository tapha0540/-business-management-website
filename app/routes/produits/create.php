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

function EnregistrerProduitImg($base64Image)
{
    if (!$base64Image) return null;

    // Extraire type + data
    list($type, $data) = explode(';', $base64Image);
    list(, $data) = explode(',', $data);

    // Extraire extension
    preg_match('/data:image\/(.*)/', $type, $matches);
    $ext = $matches[1];

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $data = base64_decode($data);

    $newName = 'produit_' . uniqid('', true) . "." . $ext;

    $destination = __DIR__ . "/../../storage/uploads/images/produits/" . $newName;

    file_put_contents($destination, $data);

    return $newName;
}