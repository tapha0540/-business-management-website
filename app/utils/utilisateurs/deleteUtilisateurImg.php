<?php

function deleteUtilisateurImage($imageName)
{
    if (!$imageName)
        return true;

    $filePath = __DIR__ . "/../../storage/uploads/images/utilisateurs/" . basename($imageName);

    if (file_exists($filePath)) {
        return unlink($filePath);
    }

    return true;
}
