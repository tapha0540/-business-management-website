<?php

function deleteImage(string $fileName): bool
{
    $baseDir = __DIR__ . "/../../storage/uploads/images/produits/";
    $path = realpath($baseDir . $fileName);

    // Vérifier que le fichier est bien dans le dossier autorisé
    if ($path && strpos($path, realpath($baseDir)) === 0 && file_exists($path)) {
        return unlink($path);
    }

    return false;
}