<?php

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