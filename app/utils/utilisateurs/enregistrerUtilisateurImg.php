<?php

function EnregistrerUtilisateurImg($base64Image)
{
    if (!$base64Image)
        return null;

    if (strpos($base64Image, ';') === false || strpos($base64Image, ',') === false) {
        return null;
    }

    list($type, $data) = explode(';', $base64Image, 2);
    list(, $data) = explode(',', $data, 2);

    // Extraire extension
    preg_match('/data:image\/(.*)/', $type, $matches);
    $ext = strtolower($matches[1] ?? '');

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $data = base64_decode($data, true);

    if ($data === false) {
        return null;
    }

    $newName = 'utilisateur_' . uniqid('', true) . "." . $ext;

    $directory = __DIR__ . "/../../storage/uploads/images/utilisateurs/";

    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    $destination = $directory . $newName;

    file_put_contents($destination, $data);

    return $newName;
}
